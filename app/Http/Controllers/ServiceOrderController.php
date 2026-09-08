<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\ServiceOrderItem;
use App\Models\ServiceOrderService;
use App\Models\Customer;
use App\Models\Vehicle;
use App\Models\Product;
use App\Models\User;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ServiceOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = ServiceOrder::query()->with(['customer', 'vehicle', 'technician']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('reported_defect', 'like', "%{$search}%")
                  ->orWhereHas('customer', fn($cq) => $cq->where('name', 'like', "%{$search}%"))
                  ->orWhereHas('vehicle', fn($vq) => $vq->where('plate', 'like', "%{$search}%")->orWhere('model', 'like', "%{$search}%"));
            });
        }

        $serviceOrders = $query->latest()->paginate(15)->withQueryString();
        $technicians = User::where('is_active', true)->get();

        return view('service_orders.index', compact('serviceOrders', 'technicians'));
    }

    public function lookupVehicle(Request $request)
    {
        $plate = strtoupper(trim(preg_replace('/[^A-Za-z0-9]/', '', $request->get('plate', ''))));
        if (strlen($plate) < 3) {
            return response()->json(['found' => false]);
        }

        $vehicle = Vehicle::with('customer')
            ->where('plate', 'like', "%{$plate}%")
            ->orWhereRaw("REPLACE(plate, '-', '') LIKE ?", ["%{$plate}%"])
            ->first();

        if ($vehicle) {
            return response()->json([
                'found' => true,
                'vehicle_id' => $vehicle->id,
                'plate' => $vehicle->plate,
                'brand' => $vehicle->brand,
                'model' => $vehicle->model,
                'year' => $vehicle->year,
                'color' => $vehicle->color,
                'customer_id' => $vehicle->customer_id,
                'customer_name' => $vehicle->customer?->name,
                'customer_phone' => $vehicle->customer?->whatsapp ?? $vehicle->customer?->phone,
                'customer_cpf' => $vehicle->customer?->document_number,
            ]);
        }

        return response()->json(['found' => false]);
    }

    public function create()
    {
        $customers = Customer::with('vehicles')->orderBy('name')->get();
        $vehicles = Vehicle::with('customer')->orderBy('plate')->get();
        $technicians = User::where('is_active', true)->get();

        // Próximo número da OS
        $year = date('Y');
        $lastOs = ServiceOrder::whereYear('created_at', $year)->count();
        $nextNumber = 'OS-' . $year . '-' . str_pad($lastOs + 1, 4, '0', STR_PAD_LEFT);

        return view('service_orders.create', compact('customers', 'vehicles', 'technicians', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_number' => 'required|string|unique:service_orders,order_number',
            // Dados diretos do veículo (sem precisar de select prévio)
            'vehicle_plate' => 'required|string|max:15',
            'vehicle_model' => 'nullable|string|max:100',
            'vehicle_brand' => 'nullable|string|max:50',
            // Dados diretos do cliente
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:30',
            'customer_cpf' => 'nullable|string|max:30',
            // Dados da OS
            'user_id' => 'nullable|exists:users,id',
            'status' => 'required|in:budget,approved,in_progress,waiting_parts,completed,delivered,cancelled',
            'reported_defect' => 'required|string',
            'technical_diagnosis' => 'nullable|string',
            'entry_km' => 'nullable|integer',
            'entry_date' => 'required|date',
            'expected_date' => 'nullable|date',
            'internal_notes' => 'nullable|string',
        ]);

        $cleanPlate = strtoupper(trim($validated['vehicle_plate']));
        $customerName = trim($validated['customer_name']);

        // 1. Encontrar ou criar o Cliente
        $customer = null;
        if (!empty($validated['customer_cpf'])) {
            $cleanCpf = preg_replace('/\D/', '', $validated['customer_cpf']);
            if (!empty($cleanCpf)) {
                $customer = Customer::whereRaw("REPLACE(REPLACE(REPLACE(document_number, '.', ''), '-', ''), '/', '') = ?", [$cleanCpf])->first();
            }
        }

        if (!$customer) {
            $customer = Customer::firstOrCreate(
                ['name' => $customerName],
                [
                    'whatsapp' => $validated['customer_phone'] ?? null,
                    'phone' => $validated['customer_phone'] ?? null,
                    'document_number' => $validated['customer_cpf'] ?? null,
                ]
            );
        }

        // 2. Encontrar ou criar o Veículo
        $vehicle = Vehicle::where('plate', $cleanPlate)->first();
        if (!$vehicle) {
            $vehicle = Vehicle::create([
                'customer_id' => $customer->id,
                'plate' => $cleanPlate,
                'brand' => !empty($validated['vehicle_brand']) ? $validated['vehicle_brand'] : 'Geral',
                'model' => !empty($validated['vehicle_model']) ? $validated['vehicle_model'] : 'Veículo',
                'current_km' => $validated['entry_km'] ?? null,
            ]);
        } else {
            // Se o veículo já existia mas não tinha dono ou dono mudou
            if ($vehicle->customer_id !== $customer->id && $customer->name !== 'CLIENTE AVULSO / BALCÃO') {
                $vehicle->customer_id = $customer->id;
                $vehicle->save();
            }
        }

        // 3. Criar a OS / Orçamento
        $osData = [
            'order_number' => $validated['order_number'],
            'customer_id' => $customer->id,
            'vehicle_id' => $vehicle->id,
            'user_id' => $validated['user_id'] ?? null,
            'status' => $validated['status'],
            'reported_defect' => $validated['reported_defect'],
            'technical_diagnosis' => $validated['technical_diagnosis'] ?? null,
            'entry_km' => $validated['entry_km'] ?? null,
            'entry_date' => $validated['entry_date'],
            'expected_date' => $validated['expected_date'] ?? null,
            'internal_notes' => $validated['internal_notes'] ?? null,
        ];

        $os = ServiceOrder::create($osData);

        $msg = $os->status === 'budget' 
            ? 'Orçamento criado com sucesso! Adicione as peças e serviços para imprimir ou enviar.' 
            : 'Ordem de Serviço criada com sucesso!';

        return redirect()->route('service_orders.show', $os)->with('success', $msg);
    }

    public function show(ServiceOrder $serviceOrder)
    {
        $serviceOrder->load(['customer', 'vehicle', 'technician', 'items.product', 'services', 'purchaseOrders', 'photos.user']);
        $products = Product::where('is_active', true)->where('current_stock', '>', 0)->orderBy('name')->get();
        $technicians = User::where('is_active', true)->get();

        return view('service_orders.show', compact('serviceOrder', 'products', 'technicians'));
    }

    public function uploadPhotos(Request $request, ServiceOrder $serviceOrder)
    {
        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg,webp,heic|max:10240', // até 10MB por foto
            'stage' => 'required|in:before,diagnostic,after',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $stage = $request->stage;
        $title = $request->title;
        $description = $request->description;

        foreach ($request->file('photos') as $photoFile) {
            $path = $photoFile->store('os_photos/' . $serviceOrder->id, 'public');

            \App\Models\ServiceOrderPhoto::create([
                'service_order_id' => $serviceOrder->id,
                'user_id' => Auth::id(),
                'stage' => $stage,
                'file_path' => $path,
                'title' => $title,
                'description' => $description,
            ]);
        }

        return back()->with('success', 'Foto(s) do estado do veículo anexada(s) à OS com sucesso!');
    }

    public function deletePhoto(ServiceOrder $serviceOrder, \App\Models\ServiceOrderPhoto $photo)
    {
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($photo->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($photo->file_path);
        }

        $photo->delete();

        return back()->with('info', 'Foto removida do histórico da OS.');
    }

    public function updateStatus(Request $request, ServiceOrder $serviceOrder)
    {
        $validated = $request->validate([
            'status' => 'required|in:budget,approved,in_progress,waiting_parts,completed,delivered,cancelled',
            'technical_diagnosis' => 'nullable|string',
            'solution_applied' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
        ]);

        if ($validated['status'] === 'completed' && empty($serviceOrder->completion_date)) {
            $serviceOrder->completion_date = now();
        }

        $serviceOrder->update($validated);
        $serviceOrder->recalculateTotals();

        return back()->with('success', 'Status da OS atualizado com sucesso!');
    }

    public function addItem(Request $request, ServiceOrder $serviceOrder)
    {
        $validated = $request->validate([
            'source_type' => 'nullable|in:inventory,external',
            'product_id' => 'nullable|exists:products,id',
            'item_name' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'required|numeric|min:0',
            'unit_cost' => 'nullable|numeric|min:0',
        ]);

        $quantity = (float)$validated['quantity'];
        $unitPrice = (float)$validated['unit_price'];
        $totalAmount = $quantity * $unitPrice;
        $sourceType = $validated['source_type'] ?? (!empty($validated['product_id']) ? 'inventory' : 'external');

        // Se for orçamento ou cotação externa (não baixa estoque)
        $isBudgetOrExternal = ($serviceOrder->status === 'budget') || ($sourceType === 'external') || empty($validated['product_id']);

        if ($isBudgetOrExternal) {
            $product = !empty($validated['product_id']) ? Product::find($validated['product_id']) : null;
            $itemName = !empty($validated['item_name']) ? $validated['item_name'] : ($product ? $product->name : 'Peça/Item');
            $unitCost = !empty($validated['unit_cost']) ? (float)$validated['unit_cost'] : ($product ? (float)$product->cost_price : 0.00);

            ServiceOrderItem::create([
                'service_order_id' => $serviceOrder->id,
                'product_id' => $product?->id,
                'item_name' => $itemName,
                'unit' => $product?->unit ?? 'UN',
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
            ]);

            $serviceOrder->recalculateTotals();

            return back()->with('success', "Item \"{$itemName}\" inserido no orçamento/OS com sucesso!");
        }

        // Se for peça do estoque físico em OS em execução (baixa do estoque)
        $product = Product::findOrFail($validated['product_id']);

        if ($product->current_stock < $quantity) {
            return back()->withErrors(['product_id' => "Estoque insuficiente para a peça {$product->name}. Saldo: {$product->current_stock} {$product->unit}"]);
        }

        DB::transaction(function () use ($serviceOrder, $product, $quantity, $unitPrice, $totalAmount) {
            $productLocked = Product::lockForUpdate()->find($product->id);
            $unitCost = (float)$productLocked->cost_price;

            // 1. Cria o item na OS
            ServiceOrderItem::create([
                'service_order_id' => $serviceOrder->id,
                'product_id' => $productLocked->id,
                'item_name' => $productLocked->name,
                'unit' => $productLocked->unit ?? 'UN',
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
            ]);

            // 2. Baixa automática no estoque (Kardex)
            StockMovement::create([
                'product_id' => $productLocked->id,
                'user_id' => Auth::id(),
                'type' => 'out',
                'reason' => 'service_order',
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'service_order_number' => $serviceOrder->order_number,
                'vehicle_plate' => $serviceOrder->vehicle?->plate,
                'notes' => 'Peça aplicada na ' . $serviceOrder->order_number,
            ]);

            $productLocked->decrement('current_stock', $quantity);
            $serviceOrder->recalculateTotals();
        });

        return back()->with('success', "Peça {$product->name} adicionada à OS e baixada do estoque!");
    }

    public function removeItem(ServiceOrder $serviceOrder, ServiceOrderItem $item)
    {
        DB::transaction(function () use ($serviceOrder, $item) {
            // Se a OS for orçamento ou se não tinha produto vinculado, apenas remove da lista sem mexer no estoque
            if ($serviceOrder->status !== 'budget' && $item->product_id) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if ($product) {
                    // Estorna saldo de estoque apenas se foi deduzido
                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'type' => 'in',
                        'reason' => 'return_in',
                        'quantity' => $item->quantity,
                        'unit_cost' => $item->unit_cost,
                        'unit_price' => $item->unit_price,
                        'total_amount' => $item->total_amount,
                        'service_order_number' => $serviceOrder->order_number,
                        'vehicle_plate' => $serviceOrder->vehicle?->plate,
                        'notes' => 'Estorno de peça removida da ' . $serviceOrder->order_number,
                    ]);

                    $product->increment('current_stock', $item->quantity);
                }
            }

            $item->delete();
            $serviceOrder->recalculateTotals();
        });

        $msg = $serviceOrder->status === 'budget' 
            ? 'Item removido do orçamento.' 
            : 'Peça removida da OS com sucesso.';

        return back()->with('info', $msg);
    }

    public function addService(Request $request, ServiceOrder $serviceOrder)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.5',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $quantity = (float)$validated['quantity'];
        $unitPrice = (float)$validated['unit_price'];

        ServiceOrderService::create([
            'service_order_id' => $serviceOrder->id,
            'description' => $validated['description'],
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'total_amount' => $quantity * $unitPrice,
        ]);

        $serviceOrder->recalculateTotals();

        return back()->with('success', 'Serviço / Mão de Obra adicionado à OS!');
    }

    public function removeService(ServiceOrder $serviceOrder, ServiceOrderService $service)
    {
        $service->delete();
        $serviceOrder->recalculateTotals();

        return back()->with('info', 'Serviço removido da OS.');
    }

    public function approveBudget(ServiceOrder $serviceOrder)
    {
        $serviceOrder->update(['status' => 'in_progress']);
        return back()->with('success', 'Orçamento aprovado pelo cliente! A OS entrou em execução.');
    }

    public function print(ServiceOrder $serviceOrder)
    {
        $serviceOrder->load(['customer', 'vehicle', 'technician', 'items.product', 'services']);
        return view('service_orders.print', compact('serviceOrder'));
    }

    public function printBudget(ServiceOrder $serviceOrder)
    {
        $serviceOrder->load(['customer', 'vehicle', 'technician', 'items.product', 'services']);
        return view('service_orders.print_budget', compact('serviceOrder'));
    }
}
