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
        $totalProducts    = Product::count();
        $totalSuppliers   = Supplier::count();
        $totalStockUnits  = Product::sum('current_stock');
        $totalStockValue  = Product::selectRaw('SUM(current_stock * cost_price) as total_val')->value('total_val') ?? 0;

        // Peças com Estoque Baixo / Crítico
        $lowStockProducts = Product::with('category')
            ->where('current_stock', '<=', DB::raw('min_stock'))
            ->orderBy('current_stock', 'asc')
            ->take(8)
            ->get();

        $outOfStockCount = Product::where('current_stock', '<=', 0)->count();
        $lowStockCount   = $lowStockProducts->count();

        // 2. Financeiro — usando DB::sum() em vez de carregar coleções em memória
        $monthStockPurchases = StockMovement::where('type', 'in')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $monthPurchaseOrdersExpense = PurchaseOrder::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_cost');

        $totalMonthExpenses = $monthStockPurchases + $monthPurchaseOrdersExpense;

        // Faturamento OSs concluídas — aggregate via DB (sem carregar coleção em memória)
        $finishedStatuses = ['completed', 'delivered'];

        $totalRevenue      = ServiceOrder::whereIn('status', $finishedStatuses)->sum('total_amount');
        $totalLaborRevenue = ServiceOrder::whereIn('status', $finishedStatuses)->sum('services_total');
        $totalPartsRevenue = ServiceOrder::whereIn('status', $finishedStatuses)->sum('products_total');

        // Custo real das peças nas OSs finalizadas (via join — sem N+1)
        $totalPartsCostInOrders = DB::table('service_order_items')
            ->join('service_orders', 'service_orders.id', '=', 'service_order_items.service_order_id')
            ->whereIn('service_orders.status', $finishedStatuses)
            ->selectRaw('SUM(service_order_items.unit_cost * service_order_items.quantity) as total')
            ->value('total') ?? 0;

        $totalNetProfit = $totalRevenue - $totalPartsCostInOrders;

        // Contas "A Receber" — aggregate via DB
        $pendingStatuses     = ['approved', 'in_progress', 'waiting_parts'];
        $totalReceivable      = ServiceOrder::whereIn('status', $pendingStatuses)->sum('total_amount');
        $totalReceivableLabor = ServiceOrder::whereIn('status', $pendingStatuses)->sum('services_total');
        $totalReceivableParts = ServiceOrder::whereIn('status', $pendingStatuses)->sum('products_total');

        // 3. Últimas Movimentações (Entradas e Saídas)
        $recentMovements = StockMovement::with(['product', 'user', 'supplier'])
            ->latest()
            ->take(6)
            ->get();

        // Totais de Entradas e Saídas do Mês Atual — com DB (não coleção)
        $monthInAmount  = StockMovement::where('type', 'in')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        $monthOutAmount = StockMovement::where('type', 'out')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // 4. Métricas de Ordens de Serviço (OS)
        $activeOsCount       = ServiceOrder::whereNotIn('status', ['delivered', 'cancelled'])->count();
        $waitingPartsOsCount = ServiceOrder::where('status', 'waiting_parts')->count();

        // OSs paradas há mais de 3 dias sem movimentação (alerta)
        $stalledOs = ServiceOrder::whereIn('status', ['in_progress', 'approved'])
            ->where('updated_at', '<', now()->subDays(3))
            ->with(['vehicle', 'technician'])
            ->take(5)
            ->get();

        // 5. Métricas de Encomendas / Peças a Chegar
        $incomingOrdersCount = PurchaseOrder::whereIn('status', ['pending', 'shipped'])->count();
        $incomingOrders      = PurchaseOrder::whereIn('status', ['pending', 'shipped'])
            ->with(['serviceOrder.vehicle'])
            ->take(5)
            ->get();

        // Pedidos de compra atrasados (passou a data prevista e ainda não chegou)
        $overdueOrders = PurchaseOrder::whereIn('status', ['pending', 'shipped'])
            ->whereNotNull('expected_delivery_date')
            ->where('expected_delivery_date', '<', now()->toDateString())
            ->count();

        // 6. Últimos Gastos / Compras para visualização
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
            'recentExpenses',
            'stalledOs',
            'overdueOrders'
        ));
    }
}
