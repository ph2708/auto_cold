<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\StockMovement;
use App\Models\ServiceOrder;
use App\Models\PurchaseOrder;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Estatísticas Gerais de Estoque & Peças
        $totalProducts = Product::count();
        $totalSuppliers = Supplier::count();
        $totalStockUnits = Product::sum('current_stock');
        $totalStockValue = Product::selectRaw('SUM(current_stock * cost_price) as total_val')->value('total_val') ?? 0;

        // Peças com Estoque Baixo / Crítico
        $lowStockProducts = Product::with('category')
            ->where('current_stock', '<=', DB::raw('min_stock'))
            ->orderBy('current_stock', 'asc')
            ->take(8)
            ->get();

        $outOfStockCount = Product::where('current_stock', '<=', 0)->count();
        $lowStockCount = $lowStockProducts->count();

        // 2. Gastos e Financeiro da Oficina (Mês Atual e Geral)
        // Gastos com compras e reposição de estoque no mês
        $monthStockPurchases = StockMovement::where('type', 'in')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Gastos com encomendas externas de peças (Mercado Livre, Web, etc.) no mês
        $monthPurchaseOrdersExpense = PurchaseOrder::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_cost');

        // Total de Gastos do Mês (Estoque + Pedidos Web)
        $totalMonthExpenses = $monthStockPurchases + $monthPurchaseOrdersExpense;

        // Faturamento com Serviços e Peças Vendidas nas OSs Concluídas/Entregues (Já Realizado)
        $finishedOrders = ServiceOrder::with(['items', 'services'])
            ->whereIn('status', ['completed', 'delivered'])
            ->get();

        $totalRevenue = $finishedOrders->sum('total_amount');
        $totalLaborRevenue = $finishedOrders->sum('services_total');
        $totalPartsRevenue = $finishedOrders->sum('products_total');
        $totalPartsCostInOrders = $finishedOrders->sum(function ($order) {
            return $order->items->sum(function ($item) {
                return $item->unit_cost * $item->quantity;
            });
        });
        
        // Lucro Líquido Real da Oficina = Faturamento - Custo das Peças Aplicadas
        $totalNetProfit = $totalRevenue - $totalPartsCostInOrders;

        // Contas "A Receber" (Carros na Oficina em Execução / Aprovados)
        $pendingOrders = ServiceOrder::with(['items', 'services'])
            ->whereIn('status', ['approved', 'in_progress', 'waiting_parts'])
            ->get();

        $totalReceivable = $pendingOrders->sum('total_amount');
        $totalReceivableLabor = $pendingOrders->sum('services_total');
        $totalReceivableParts = $pendingOrders->sum('products_total');

        // 3. Últimas Movimentações (Entradas e Saídas)
        $recentMovements = StockMovement::with(['product', 'user', 'supplier'])
            ->latest()
            ->take(6)
            ->get();

        // Totais de Entradas e Saídas do Mês Atual
        $monthMovements = StockMovement::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->get();

        $monthInAmount = $monthMovements->where('type', 'in')->sum('total_amount');
        $monthOutAmount = $monthMovements->where('type', 'out')->sum('total_amount');

        // 4. Métricas de Ordens de Serviço (OS)
        $activeOsCount = ServiceOrder::whereNotIn('status', ['delivered', 'cancelled'])->count();
        $waitingPartsOsCount = ServiceOrder::where('status', 'waiting_parts')->count();

        // 5. Métricas de Encomendas / Peças a Chegar (ML / Web)
        $incomingOrdersCount = PurchaseOrder::whereIn('status', ['pending', 'shipped'])->count();
        $incomingOrders = PurchaseOrder::whereIn('status', ['pending', 'shipped'])->take(5)->get();

        // 6. Últimos Gastos / Compras Registradas para visualização detalhada
        $recentExpenses = PurchaseOrder::with(['serviceOrder.vehicle', 'user'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard.index', compact(
            'totalProducts',
            'totalSuppliers',
            'totalStockUnits',
            'totalStockValue',
            'lowStockProducts',
            'outOfStockCount',
            'lowStockCount',
            'recentMovements',
            'monthInAmount',
            'monthOutAmount',
            'activeOsCount',
            'waitingPartsOsCount',
            'incomingOrdersCount',
            'incomingOrders',
            'monthStockPurchases',
            'monthPurchaseOrdersExpense',
            'totalMonthExpenses',
            'totalRevenue',
            'totalLaborRevenue',
            'totalPartsRevenue',
            'totalPartsCostInOrders',
            'totalNetProfit',
            'totalReceivable',
            'totalReceivableLabor',
            'totalReceivableParts',
            'recentExpenses'
        ));
    }
}
