<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StockMovementController extends Controller
{
    public function index(Request $request)
    {
        $query = StockMovement::query()->with(['product', 'user', 'supplier']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('reason')) {
            $query->where('reason', $request->reason);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('document_number', 'like', "%{$search}%")
                  ->orWhere('vehicle_plate', 'like', "%{$search}%")
                  ->orWhere('service_order_number', 'like', "%{$search}%")
                  ->orWhereHas('product', function ($pq) use ($search) {
                      $pq->where('name', 'like', "%{$search}%")
                         ->orWhere('sku', 'like', "%{$search}%");
                  });
            });
        }

        $movements = $query->latest()->paginate(15)->withQueryString();
        $products = Product::orderBy('name')->get();

        return view('stock.index', compact('movements', 'products'));
    }

    public function createEntry()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('corporate_name')->get();

        return view('stock.entry', compact('products', 'suppliers'));
    }

    public function storeEntry(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'reason' => 'required|in:purchase,adjustment_in,return_in',
            'quantity' => 'required|numeric|min:0.01',
            'unit_cost' => 'required|numeric|min:0',
            'document_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            $product = Product::lockForUpdate()->findOrFail($validated['product_id']);
            $quantity = (float)$validated['quantity'];
            $unitCost = (float)$validated['unit_cost'];
            $totalAmount = $quantity * $unitCost;

            // Criar movimentação
            StockMovement::create([
                'product_id' => $product->id,
                'user_id' => Auth::id(),
                'supplier_id' => $validated['supplier_id'] ?? null,
                'type' => 'in',
                'reason' => $validated['reason'],
                'quantity' => $quantity,
                'unit_cost' => $unitCost,
                'unit_price' => $product->selling_price,
                'total_amount' => $totalAmount,
                'document_number' => $validated['document_number'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Atualizar estoque e custo do produto
            $product->increment('current_stock', $quantity);
            if ($unitCost > 0) {
                $product->cost_price = $unitCost;
                $product->save();
            }
        });

        return redirect()->route('stock.index')->with('success', 'Entrada de estoque registrada com sucesso!');
    }

    public function createExit()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();

        return view('stock.exit', compact('products'));
    }

    public function storeExit(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'reason' => 'required|in:service_order,direct_sale,internal_use,loss_damage,adjustment_out',
            'quantity' => 'required|numeric|min:0.01',
            'unit_price' => 'nullable|numeric|min:0',
            'service_order_number' => 'nullable|string|max:50',
            'vehicle_plate' => 'nullable|string|max:20',
            'notes' => 'nullable|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);
        $quantity = (float)$validated['quantity'];

        if ($product->current_stock < $quantity) {
            return back()->withErrors([
                'quantity' => "Saldo insuficiente em estoque! Saldo atual: {$product->current_stock} {$product->unit}",
            ])->withInput();
        }

        DB::transaction(function () use ($validated, $product, $quantity) {
            $productLocked = Product::lockForUpdate()->findOrFail($product->id);

            $unitPrice = !empty($validated['unit_price']) ? (float)$validated['unit_price'] : (float)$productLocked->selling_price;
            $totalAmount = $quantity * $unitPrice;

            StockMovement::create([
                'product_id' => $productLocked->id,
                'user_id' => Auth::id(),
                'type' => 'out',
                'reason' => $validated['reason'],
                'quantity' => $quantity,
                'unit_cost' => $productLocked->cost_price,
                'unit_price' => $unitPrice,
                'total_amount' => $totalAmount,
                'service_order_number' => $validated['service_order_number'] ?? null,
                'vehicle_plate' => $validated['vehicle_plate'] ? strtoupper($validated['vehicle_plate']) : null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $productLocked->decrement('current_stock', $quantity);
        });

        return redirect()->route('stock.index')->with('success', 'Saída de estoque registrada com sucesso!');
    }
}
