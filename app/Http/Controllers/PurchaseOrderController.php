<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\ServiceOrder;
use App\Models\Supplier;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = PurchaseOrder::query()->with(['supplier', 'serviceOrder.vehicle', 'product', 'buyer']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('origin')) {
            $query->where('origin', $request->origin);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhere('item_name', 'like', "%{$search}%")
                  ->orWhere('tracking_code', 'like', "%{$search}%")
                  ->orWhere('external_order_number', 'like', "%{$search}%");
            });
        }

        $purchaseOrders = $query->latest()->paginate(15)->withQueryString();

        // Estatísticas rápidas de encomendas
        $pendingCount = PurchaseOrder::where('status', 'pending')->count();
        $shippedCount = PurchaseOrder::where('status', 'shipped')->count();
        $deliveredMonthCount = PurchaseOrder::where('status', 'delivered')->whereMonth('received_at', now()->month)->count();

        return view('purchase_orders.index', compact('purchaseOrders', 'pendingCount', 'shippedCount', 'deliveredMonthCount'));
    }

    public function create()
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('corporate_name')->get();
        $serviceOrders = ServiceOrder::whereNotIn('status', ['delivered', 'cancelled'])->with('vehicle')->orderBy('order_number')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        $year = date('Y');
        $lastPed = PurchaseOrder::whereYear('created_at', $year)->count();
        $nextCode = 'PED-' . $year . '-' . str_pad($lastPed + 1, 4, '0', STR_PAD_LEFT);

        return view('purchase_orders.create', compact('suppliers', 'serviceOrders', 'products', 'nextCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_code' => 'required|string|unique:purchase_orders,order_code',
            'origin' => 'required|string',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'service_order_id' => 'nullable|exists:service_orders,id',
            'product_id' => 'nullable|exists:products,id',
            'external_order_number' => 'nullable|string|max:100',
            'tracking_code' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:255',
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'purchase_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $shipping = (float)($validated['shipping_cost'] ?? 0);
        $validated['total_cost'] = ((float)$validated['quantity'] * (float)$validated['unit_cost']) + $shipping;
        $validated['user_id'] = Auth::id();

        PurchaseOrder::create($validated);

        return redirect()->route('purchase_orders.index')->with('success', 'Pedido de peça registrado com sucesso!');
    }

    public function edit(PurchaseOrder $purchaseOrder)
    {
        $suppliers = Supplier::where('is_active', true)->orderBy('corporate_name')->get();
        $serviceOrders = ServiceOrder::whereNotIn('status', ['delivered', 'cancelled'])->with('vehicle')->orderBy('order_number')->get();
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('purchase_orders.edit', compact('purchaseOrder', 'suppliers', 'serviceOrders', 'products'));
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder)
    {
        $validated = $request->validate([
            'origin' => 'required|string',
            'item_name' => 'required|string|max:255',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0',
            'shipping_cost' => 'nullable|numeric|min:0',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'service_order_id' => 'nullable|exists:service_orders,id',
            'product_id' => 'nullable|exists:products,id',
            'external_order_number' => 'nullable|string|max:100',
            'tracking_code' => 'nullable|string|max:100',
            'tracking_url' => 'nullable|url|max:255',
            'status' => 'required|in:pending,shipped,delivered,cancelled',
            'purchase_date' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $shipping = (float)($validated['shipping_cost'] ?? 0);
        $validated['total_cost'] = ((float)$validated['quantity'] * (float)$validated['unit_cost']) + $shipping;

        $purchaseOrder->update($validated);

        return redirect()->route('purchase_orders.index')->with('success', 'Pedido atualizado!');
    }

    /**
     * Receber peça na oficina e dar entrada automática no estoque
     */
    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status === 'delivered') {
            return back()->with('info', 'Este pedido já foi marcado como recebido anteriormente.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            $purchaseOrder->status = 'delivered';
            $purchaseOrder->received_at = now();
            $purchaseOrder->save();

            // Se o pedido tiver um produto cadastrado no catálogo, dá entrada no estoque
            if ($purchaseOrder->product_id) {
                $product = Product::lockForUpdate()->find($purchaseOrder->product_id);
                if ($product) {
                    $product->increment('current_stock', $purchaseOrder->quantity);
                    if ($purchaseOrder->unit_cost > 0) {
                        $product->cost_price = $purchaseOrder->unit_cost;
                        $product->save();
                    }

                    StockMovement::create([
                        'product_id' => $product->id,
                        'user_id' => Auth::id(),
                        'supplier_id' => $purchaseOrder->supplier_id,
                        'type' => 'in',
                        'reason' => 'purchase',
                        'quantity' => $purchaseOrder->quantity,
                        'unit_cost' => $purchaseOrder->unit_cost,
                        'unit_price' => $product->selling_price,
                        'total_amount' => $purchaseOrder->total_cost,
                        'document_number' => $purchaseOrder->external_order_number ?? $purchaseOrder->order_code,
                        'notes' => "Chegada de encomenda [{$purchaseOrder->origin}] Rastreio: {$purchaseOrder->tracking_code}",
                    ]);
                }
            }

            // Se a OS estiver aguardando peças, avisa no histórico
            if ($purchaseOrder->serviceOrder && $purchaseOrder->serviceOrder->status === 'waiting_parts') {
                $purchaseOrder->serviceOrder->update(['status' => 'in_progress']);
            }
        });

        return back()->with('success', 'Peça conferida e recebida na oficina com sucesso! Entrada gerada no estoque.');
    }
}
