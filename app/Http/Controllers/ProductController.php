<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()->with(['category', 'defaultSupplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%")
                  ->orWhere('oem_code', 'like', "%{$search}%")
                  ->orWhere('brand', 'like', "%{$search}%")
                  ->orWhere('vehicle_compatibility', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('voltage')) {
            $query->where('voltage', $request->voltage);
        }

        if ($request->filled('stock_status')) {
            if ($request->stock_status === 'low') {
                $query->whereColumn('current_stock', '<=', 'min_stock')->where('current_stock', '>', 0);
            } elseif ($request->stock_status === 'out') {
                $query->where('current_stock', '<=', 0);
            } elseif ($request->stock_status === 'normal') {
                $query->whereColumn('current_stock', '>', 'min_stock');
            }
        }

        $products = $query->orderBy('name')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('corporate_name')->get();

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku',
            'barcode' => 'nullable|string|max:50|unique:products,barcode',
            'oem_code' => 'nullable|string|max:50',
            'manufacturer_code' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'default_supplier_id' => 'nullable|exists:suppliers,id',
            'brand' => 'nullable|string|max:100',
            'voltage' => 'nullable|string|max:20',
            'amperage' => 'nullable|string|max:50',
            'power' => 'nullable|string|max:50',
            'pin_count' => 'nullable|string|max:50',
            'vehicle_compatibility' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:100',
            'unit' => 'required|string|max:10',
            'current_stock' => 'required|numeric|min:0',
            'min_stock' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Peça / Componente cadastrado com sucesso!');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $suppliers = Supplier::where('is_active', true)->orderBy('corporate_name')->get();

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'barcode' => 'nullable|string|max:50|unique:products,barcode,' . $product->id,
            'oem_code' => 'nullable|string|max:50',
            'manufacturer_code' => 'nullable|string|max:50',
            'category_id' => 'nullable|exists:categories,id',
            'default_supplier_id' => 'nullable|exists:suppliers,id',
            'brand' => 'nullable|string|max:100',
            'voltage' => 'nullable|string|max:20',
            'amperage' => 'nullable|string|max:50',
            'power' => 'nullable|string|max:50',
            'pin_count' => 'nullable|string|max:50',
            'vehicle_compatibility' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:100',
            'unit' => 'required|string|max:10',
            'min_stock' => 'required|numeric|min:0',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Peça / Componente atualizado com sucesso!');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto desativado com sucesso.');
    }
}
