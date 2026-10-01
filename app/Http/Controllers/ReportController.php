<?php

namespace App\Http\Controllers;

use App\Models\ServiceOrder;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\PurchaseOrder;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $period    = $request->get('period', 'month');
        $startDate = $this->getStartDate($period, $request);
        $endDate   = Carbon::now()->endOfDay();

        // ──────────────────────────────────────────────
        // FINANCEIRO — OSs Concluídas no período
        // ──────────────────────────────────────────────
        $finishedOs = ServiceOrder::whereIn('status', ['completed', 'delivered'])
            ->whereBetween('updated_at', [$startDate, $endDate]);

        $totalRevenue      = $finishedOs->clone()->sum('total_amount');
        $totalLaborRevenue = $finishedOs->clone()->sum('services_total');
        $totalPartsRevenue = $finishedOs->clone()->sum('products_total');

        $totalPartsCost = DB::table('service_order_items')
            ->join('service_orders', 'service_orders.id', '=', 'service_order_items.service_order_id')
            ->whereIn('service_orders.status', ['completed', 'delivered'])
            ->whereBetween('service_orders.updated_at', [$startDate, $endDate])
            ->selectRaw('SUM(service_order_items.unit_cost * service_order_items.quantity) as total')
            ->value('total') ?? 0;

        $totalNetProfit = $totalRevenue - $totalPartsCost;
        $profitMargin   = $totalRevenue > 0 ? round(($totalNetProfit / $totalRevenue) * 100, 1) : 0;

        // Ticket médio
        $osCount       = $finishedOs->clone()->count();
        $averageTicket = $osCount > 0 ? $totalRevenue / $osCount : 0;

        // ──────────────────────────────────────────────
        // OSs por Status (totais gerais, não filtradas por período)
        // ──────────────────────────────────────────────
        $osByStatus = ServiceOrder::selectRaw('status, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // ──────────────────────────────────────────────
        // Faturamento mensal dos últimos 6 meses (para gráfico)
        // ──────────────────────────────────────────────
        $monthlyRevenue = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $rev   = ServiceOrder::whereIn('status', ['completed', 'delivered'])
                ->whereMonth('updated_at', $month->month)
                ->whereYear('updated_at', $month->year)
                ->sum('total_amount');
            $monthlyRevenue[] = [
                'month'   => $month->locale('pt_BR')->isoFormat('MMM/YY'),
                'revenue' => (float) $rev,
            ];
        }

        // ──────────────────────────────────────────────
        // GIRO DE ESTOQUE — peças mais usadas nas OSs
        // ──────────────────────────────────────────────
        $topParts = DB::table('service_order_items')
            ->join('service_orders', 'service_orders.id', '=', 'service_order_items.service_order_id')
            ->join('products', 'products.id', '=', 'service_order_items.product_id')
            ->whereBetween('service_orders.updated_at', [$startDate, $endDate])
            ->whereNotNull('service_order_items.product_id')
            ->selectRaw('
                products.id,
                products.name,
                products.sku,
                products.current_stock,
                SUM(service_order_items.quantity) as total_used,
                SUM(service_order_items.total_amount) as total_revenue,
                COUNT(DISTINCT service_orders.id) as os_count
            ')
            ->groupBy('products.id', 'products.name', 'products.sku', 'products.current_stock')
            ->orderByDesc('total_used')
            ->take(10)
            ->get();

        // ──────────────────────────────────────────────
        // CLIENTES — maiores valores em OSs concluídas
        // ──────────────────────────────────────────────
        $topCustomers = DB::table('service_orders')
            ->join('customers', 'customers.id', '=', 'service_orders.customer_id')
            ->whereIn('service_orders.status', ['completed', 'delivered'])
            ->whereBetween('service_orders.updated_at', [$startDate, $endDate])
            ->selectRaw('
                customers.id,
                customers.name,
                COUNT(service_orders.id) as os_count,
                SUM(service_orders.total_amount) as total_spent
            ')
            ->groupBy('customers.id', 'customers.name')
            ->orderByDesc('total_spent')
            ->take(8)
            ->get();

        // ──────────────────────────────────────────────
        // ESTOQUE — alertas de peças abaixo do mínimo
        // ──────────────────────────────────────────────
        $lowStockProducts = Product::with('category')
            ->where('current_stock', '<=', DB::raw('min_stock'))
            ->orderBy('current_stock', 'asc')
            ->get();

        // ──────────────────────────────────────────────
        // OSs mais recentes do período para listagem detalhada
        // ──────────────────────────────────────────────
        $recentFinishedOs = ServiceOrder::with(['customer', 'vehicle', 'technician'])
            ->whereIn('status', ['completed', 'delivered'])
            ->whereBetween('updated_at', [$startDate, $endDate])
            ->latest('updated_at')
            ->take(20)
            ->get();

        return view('reports.index', compact(
            'period',
            'startDate',
            'endDate',
            'totalRevenue',
            'totalLaborRevenue',
            'totalPartsRevenue',
            'totalPartsCost',
            'totalNetProfit',
            'profitMargin',
            'osCount',
            'averageTicket',
            'osByStatus',
            'monthlyRevenue',
            'topParts',
            'topCustomers',
            'lowStockProducts',
            'recentFinishedOs'
        ));
    }

    private function getStartDate(string $period, Request $request): Carbon
    {
        return match ($period) {
            'week'        => Carbon::now()->startOfWeek(),
            'month'       => Carbon::now()->startOfMonth(),
            'quarter'     => Carbon::now()->startOfQuarter(),
            'year'        => Carbon::now()->startOfYear(),
            'custom'      => Carbon::parse($request->get('start_date', now()->startOfMonth())),
            default       => Carbon::now()->startOfMonth(),
        };
    }
}
