@extends('layouts.app')

@section('title', 'Relatórios & Análises')
@section('header_title', 'Relatórios & Análises Financeiras')

@section('content')
<div class="space-y-6">

    {{-- Filtro de Período --}}
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-xs">
        <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-bold text-slate-600 dark:text-slate-400 uppercase tracking-wider">Período:</span>

            @foreach(['week' => 'Esta Semana', 'month' => 'Este Mês', 'quarter' => 'Este Trimestre', 'year' => 'Este Ano'] as $key => $label)
                <a href="{{ route('reports.index', ['period' => $key]) }}"
                   class="px-3.5 py-1.5 rounded-xl text-xs font-semibold border transition
                       {{ $period === $key
                           ? 'bg-cyan-600 text-white border-cyan-600 shadow-sm shadow-cyan-600/20'
                           : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border-slate-200 dark:border-slate-700 hover:border-cyan-400' }}">
                    {{ $label }}
                </a>
            @endforeach

            <span class="text-slate-300 dark:text-slate-700">|</span>
            <span class="text-xs text-slate-500 dark:text-slate-400">
                <i class="fa-solid fa-calendar-days mr-1"></i>
                {{ $startDate->format('d/m/Y') }} → {{ $endDate->format('d/m/Y') }}
            </span>
        </form>
    </div>

    {{-- KPIs Financeiros --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Faturamento Total</p>
            <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-heading">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $osCount }} OS{{ $osCount !== 1 ? 's' : '' }} concluída{{ $osCount !== 1 ? 's' : '' }}</p>
        </div>

        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Lucro Líquido</p>
            <p class="text-2xl font-black text-cyan-600 dark:text-cyan-400 font-heading">R$ {{ number_format($totalNetProfit, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Margem: <span class="font-bold {{ $profitMargin > 30 ? 'text-emerald-500' : ($profitMargin > 15 ? 'text-amber-500' : 'text-rose-500') }}">{{ $profitMargin }}%</span></p>
        </div>

        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Mão de Obra</p>
            <p class="text-2xl font-black text-blue-600 dark:text-blue-400 font-heading">R$ {{ number_format($totalLaborRevenue, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                {{ $totalRevenue > 0 ? round(($totalLaborRevenue / $totalRevenue) * 100, 1) : 0 }}% do faturamento
            </p>
        </div>

        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-wider mb-1">Ticket Médio</p>
            <p class="text-2xl font-black text-purple-600 dark:text-purple-400 font-heading">R$ {{ number_format($averageTicket, 2, ',', '.') }}</p>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">por OS concluída</p>
        </div>
    </div>

    {{-- Faturamento Mensal (últimos 6 meses) + Status das OSs --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Gráfico de Barras Mensal --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4 font-heading flex items-center gap-2">
                <i class="fa-solid fa-chart-bar text-indigo-500"></i> Faturamento — Últimos 6 Meses
            </h3>
            @php
                $maxRevenue = collect($monthlyRevenue)->max('revenue') ?: 1;
            @endphp
            <div class="flex items-end gap-2 h-40">
                @foreach($monthlyRevenue as $mData)
                    @php $barHeight = $maxRevenue > 0 ? ($mData['revenue'] / $maxRevenue) * 100 : 0; @endphp
                    <div class="flex-1 flex flex-col items-center gap-1 group">
                        <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 opacity-0 group-hover:opacity-100 transition">
                            R$ {{ number_format($mData['revenue'], 0, ',', '.') }}
                        </span>
                        <div class="w-full rounded-t-lg bg-gradient-to-t from-cyan-500 to-blue-500 dark:from-cyan-600 dark:to-blue-600 transition-all duration-500 hover:from-cyan-400 hover:to-blue-400 relative"
                             style="height: {{ max($barHeight, 2) }}%"
                             title="R$ {{ number_format($mData['revenue'], 2, ',', '.') }}">
                        </div>
                        <span class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">{{ $mData['month'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- OSs por Status --}}
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-5 shadow-xs">
            <h3 class="text-sm font-bold text-slate-800 dark:text-white mb-4 font-heading flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-amber-500"></i> OSs por Status
            </h3>
            @php
                $statusInfo = [
                    'budget'       => ['label' => 'Orçamento',        'color' => 'bg-slate-400'],
                    'approved'     => ['label' => 'Aprovada',          'color' => 'bg-blue-500'],
                    'in_progress'  => ['label' => 'Em Execução',       'color' => 'bg-amber-500'],
                    'waiting_parts'=> ['label' => 'Aguard. Peças',     'color' => 'bg-purple-500'],
                    'completed'    => ['label' => 'Concluída',         'color' => 'bg-emerald-500'],
                    'delivered'    => ['label' => 'Entregue',          'color' => 'bg-cyan-500'],
                    'cancelled'    => ['label' => 'Cancelada',         'color' => 'bg-rose-500'],
                ];
                $totalOs = $osByStatus->sum('count') ?: 1;
            @endphp
            <div class="space-y-2.5">
                @foreach($statusInfo as $sKey => $sData)
                    @if(isset($osByStatus[$sKey]))
                        @php $osRow = $osByStatus[$sKey]; $pct = round(($osRow->count / $totalOs) * 100); @endphp
                        <div>
                            <div class="flex justify-between text-[11px] mb-1">
                                <span class="text-slate-600 dark:text-slate-300 font-medium">{{ $sData['label'] }}</span>
                                <span class="font-bold text-slate-800 dark:text-white">{{ $osRow->count }} <span class="text-slate-400 font-normal">({{ $pct }}%)</span></span>
                            </div>
                            <div class="h-1.5 bg-slate-100 dark:bg-slate-800 rounded-full overflow-hidden">
                                <div class="{{ $sData['color'] }} h-full rounded-full transition-all" style="width: {{ $pct }}%"></div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Peças Mais Usadas + Clientes Top --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        {{-- Giro de Estoque — Top 10 Peças --}}
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-microchip text-blue-500"></i>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white font-heading">Peças Mais Utilizadas</h3>
                <span class="ml-auto text-[11px] text-slate-400">no período</span>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                @forelse($topParts as $i => $part)
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                        <span class="text-xs font-black w-5 text-center {{ $i === 0 ? 'text-amber-500' : ($i === 1 ? 'text-slate-400' : ($i === 2 ? 'text-orange-400' : 'text-slate-300 dark:text-slate-600')) }}">
                            #{{ $i + 1 }}
                        </span>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200 truncate">{{ $part->name }}</p>
                            <p class="text-[10px] text-slate-400">SKU: {{ $part->sku ?? '—' }} | Estoque atual: {{ $part->current_stock }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-xs font-bold text-cyan-600 dark:text-cyan-400">{{ number_format($part->total_used, 0, ',', '.') }} un.</p>
                            <p class="text-[10px] text-slate-400">{{ $part->os_count }} OSs</p>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs">
                        <i class="fa-solid fa-box-open text-2xl block mb-2"></i>
                        Nenhuma peça utilizada no período.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Top Clientes --}}
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-trophy text-amber-500"></i>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white font-heading">Melhores Clientes</h3>
                <span class="ml-auto text-[11px] text-slate-400">por faturamento</span>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                @forelse($topCustomers as $i => $cust)
                    <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                        <div class="h-8 w-8 rounded-full bg-cyan-100 dark:bg-slate-800 flex items-center justify-center text-cyan-700 dark:text-cyan-400 font-bold text-xs shrink-0">
                            {{ strtoupper(substr($cust->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <a href="{{ route('customers.show', $cust->id) }}" class="text-xs font-bold text-slate-800 dark:text-slate-200 hover:text-cyan-600 dark:hover:text-cyan-400 truncate block transition">
                                {{ $cust->name }}
                            </a>
                            <p class="text-[10px] text-slate-400">{{ $cust->os_count }} OS{{ $cust->os_count !== 1 ? 's' : '' }}</p>
                        </div>
                        <span class="text-sm font-black text-emerald-600 dark:text-emerald-400 shrink-0">
                            R$ {{ number_format($cust->total_spent, 2, ',', '.') }}
                        </span>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 dark:text-slate-500 text-xs">
                        <i class="fa-solid fa-users text-2xl block mb-2"></i>
                        Nenhuma OS concluída no período.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Alertas de Estoque Crítico --}}
    @if($lowStockProducts->count() > 0)
    <div class="bg-white dark:bg-[#0f172a] border border-amber-200 dark:border-amber-800/60 rounded-2xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-amber-100 dark:border-amber-900/40 bg-amber-50 dark:bg-amber-950/30 flex items-center gap-2">
            <i class="fa-solid fa-triangle-exclamation text-amber-500"></i>
            <h3 class="text-sm font-bold text-amber-800 dark:text-amber-200 font-heading">
                Estoque Crítico — {{ $lowStockProducts->count() }} peça{{ $lowStockProducts->count() !== 1 ? 's' : '' }} abaixo do mínimo
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-950/30 text-slate-500 dark:text-slate-400 text-[10px] uppercase tracking-wider">
                        <th class="py-2.5 px-4 text-left">Peça</th>
                        <th class="py-2.5 px-4 text-center">Estoque Atual</th>
                        <th class="py-2.5 px-4 text-center">Mínimo</th>
                        <th class="py-2.5 px-4 text-center">Déficit</th>
                        <th class="py-2.5 px-4 text-right">Ação</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @foreach($lowStockProducts as $prod)
                        <tr class="hover:bg-amber-50/50 dark:hover:bg-amber-950/10 transition">
                            <td class="py-3 px-4">
                                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $prod->name }}</p>
                                <p class="text-[10px] text-slate-400">{{ $prod->sku ?? '—' }} | {{ $prod->category?->name }}</p>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-black {{ $prod->current_stock <= 0 ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }}">
                                    {{ $prod->current_stock }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center text-slate-500 dark:text-slate-400">{{ $prod->min_stock }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="font-bold text-rose-600 dark:text-rose-400">
                                    {{ max(0, $prod->min_stock - $prod->current_stock) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a href="{{ route('stock.entry') }}?product_id={{ $prod->id }}"
                                   class="inline-flex items-center gap-1 px-3 py-1 rounded-lg bg-amber-100 hover:bg-amber-200 dark:bg-amber-950/60 dark:hover:bg-amber-900/80 text-amber-700 dark:text-amber-300 text-[11px] font-semibold border border-amber-200 dark:border-amber-800 transition">
                                    <i class="fa-solid fa-plus text-[10px]"></i> Entrada
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    {{-- OSs Concluídas no Período --}}
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
        <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
            <i class="fa-solid fa-list-check text-emerald-500"></i>
            <h3 class="text-sm font-bold text-slate-800 dark:text-white font-heading">OSs Concluídas no Período</h3>
            <span class="ml-auto text-[11px] text-slate-400">{{ $recentFinishedOs->count() }} mais recentes</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-950/30 text-slate-500 dark:text-slate-400 text-[10px] uppercase tracking-wider">
                        <th class="py-2.5 px-4 text-left">Nº OS</th>
                        <th class="py-2.5 px-4 text-left">Cliente / Veículo</th>
                        <th class="py-2.5 px-4 text-left">Técnico</th>
                        <th class="py-2.5 px-4 text-center">Status</th>
                        <th class="py-2.5 px-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($recentFinishedOs as $os)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                            <td class="py-3 px-4 whitespace-nowrap">
                                <a href="{{ route('service_orders.show', $os) }}" class="font-mono font-bold text-cyan-600 dark:text-cyan-400 hover:underline">
                                    {{ $os->order_number }}
                                </a>
                            </td>
                            <td class="py-3 px-4">
                                <p class="font-bold text-slate-800 dark:text-slate-200">{{ $os->customer?->name }}</p>
                                <span class="font-mono text-[10px] text-slate-500 dark:text-slate-400">{{ $os->vehicle?->plate }}</span>
                            </td>
                            <td class="py-3 px-4 text-slate-500 dark:text-slate-400">{{ $os->technician?->name ?? '—' }}</td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-bold
                                    {{ $os->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800' : 'bg-cyan-50 text-cyan-700 border border-cyan-200 dark:bg-cyan-950 dark:text-cyan-300 dark:border-cyan-800' }}">
                                    {{ $os->status_label }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                R$ {{ number_format($os->total_amount, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-chart-line text-2xl block mb-2"></i>
                                Nenhuma OS concluída no período selecionado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
