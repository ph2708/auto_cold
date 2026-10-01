@extends('layouts.app')

@section('title', 'Visão Geral & Finanças')

@section('content')
<div class="space-y-8">
    
    <!-- 1. Destaque Financeiro da Oficina (Faturamento, A Receber, Gastos, Lucro e Estoque) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Card Faturamento Real (Concluído) -->
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-emerald-500/20 p-4 rounded-2xl shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-wider">Faturado (OSs)</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1 font-heading">R$ {{ number_format($totalRevenue, 2, ',', '.') }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-arrow-trend-up"></i>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800">
                <span>Serviços: <strong class="text-slate-800 dark:text-slate-200">R$ {{ number_format($totalLaborRevenue, 2, ',', '.') }}</strong></span>
                <span>Peças: <strong class="text-slate-800 dark:text-slate-200">R$ {{ number_format($totalPartsRevenue, 2, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Card A Receber (Carros em Andamento na Oficina) -->
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-blue-500/20 p-4 rounded-2xl shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wider">A Receber (Na Oficina)</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1 font-heading">R$ {{ number_format($totalReceivable, 2, ',', '.') }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-blue-50 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800">
                <span>{{ $activeOsCount }} OSs ativas</span>
                <span class="text-blue-600 dark:text-blue-300 font-semibold">Previsão</span>
            </div>
        </div>

        <!-- Card Gastos / Compras do Mês -->
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-rose-500/20 p-4 rounded-2xl shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Gastos no Mês</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1 font-heading">R$ {{ number_format($totalMonthExpenses, 2, ',', '.') }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-rose-50 dark:bg-rose-500/20 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-500/30 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-arrow-trend-down"></i>
                </div>
            </div>
            <div class="flex items-center justify-between text-[11px] text-slate-500 dark:text-slate-400 mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800">
                <span>Estoque: <strong class="text-slate-800 dark:text-slate-200">R$ {{ number_format($monthStockPurchases, 2, ',', '.') }}</strong></span>
                <span>Web: <strong class="text-slate-800 dark:text-slate-200">R$ {{ number_format($monthPurchaseOrdersExpense, 2, ',', '.') }}</strong></span>
            </div>
        </div>

        <!-- Card Lucro Líquido Real -->
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-cyan-500/20 p-4 rounded-2xl shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-cyan-700 dark:text-cyan-400 uppercase tracking-wider">Lucro Líquido</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1 font-heading">R$ {{ number_format($totalNetProfit, 2, ',', '.') }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-cyan-50 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/30 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-wallet"></i>
                </div>
            </div>
            <p class="text-[11px] text-cyan-700 dark:text-cyan-300 mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800 flex items-center gap-1.5 font-medium">
                <i class="fa-solid fa-check-double text-cyan-600 dark:text-cyan-400"></i>
                <span>100% Serviços + Margem Peças</span>
            </p>
        </div>

        <!-- Card Capital Imobilizado em Estoque -->
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-xs hover:shadow-md transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-[11px] font-bold text-amber-600 dark:text-amber-400 uppercase tracking-wider">Capital em Peças</p>
                    <h3 class="text-xl sm:text-2xl font-black text-slate-900 dark:text-white mt-1 font-heading">R$ {{ number_format($totalStockValue, 2, ',', '.') }}</h3>
                </div>
                <div class="h-10 w-10 rounded-xl bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-3 pt-2.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <span>{{ number_format($totalStockUnits, 0, ',', '.') }} un no estoque</span>
                <span class="text-amber-600 dark:text-amber-400 font-bold">{{ $totalProducts }} itens</span>
            </p>
        </div>

    </div>

    <!-- 2. Cards Operacionais da Oficina (OS, Encomendas e Alertas) -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <!-- OSs Ativas -->
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="space-y-1">
                <span class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Ordens de Serviço Ativas</span>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white font-heading">{{ $activeOsCount }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">em andamento</span></h4>
                <p class="text-xs text-purple-600 dark:text-purple-400 font-medium"><i class="fa-solid fa-hourglass-half mr-1"></i> {{ $waitingPartsOsCount }} aguardando peças</p>
            </div>
            <a href="{{ route('service_orders.index') }}" class="p-3 bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 rounded-xl hover:bg-cyan-100 dark:hover:bg-cyan-500/20 transition">
                <i class="fa-solid fa-clipboard-list text-xl"></i>
            </a>
        </div>

        <!-- Encomendas Web a Chegar -->
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="space-y-1">
                <span class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wider font-semibold">Peças a Chegar (ML / Web)</span>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white font-heading">{{ $incomingOrdersCount }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">encomendas</span></h4>
                <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium"><i class="fa-solid fa-truck-fast mr-1"></i> Em rota de entrega</p>
            </div>
            <a href="{{ route('purchase_orders.index') }}" class="p-3 bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-500/20 transition">
                <i class="fa-solid fa-box-open text-xl"></i>
            </a>
        </div>

        <!-- Alertas de Reposição -->
        <div class="bg-white dark:bg-[#0f172a] border {{ $outOfStockCount > 0 ? 'border-rose-300 dark:border-rose-500/40 bg-rose-50/50 dark:bg-rose-950/10' : 'border-amber-300 dark:border-amber-500/40 bg-amber-50/50 dark:bg-amber-950/10' }} p-5 rounded-2xl flex items-center justify-between shadow-xs">
            <div class="space-y-1">
                <span class="text-xs {{ $outOfStockCount > 0 ? 'text-rose-600 dark:text-rose-400' : 'text-amber-600 dark:text-amber-400' }} uppercase tracking-wider font-semibold">Alerta de Reposição</span>
                <h4 class="text-2xl font-black text-slate-900 dark:text-white font-heading">{{ $lowStockCount }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">peças no limite</span></h4>
                <p class="text-xs {{ $outOfStockCount > 0 ? 'text-rose-600 dark:text-rose-400 font-bold' : 'text-amber-600 dark:text-amber-400 font-medium' }}"><i class="fa-solid fa-triangle-exclamation mr-1"></i> {{ $outOfStockCount }} totalmente zeradas</p>
            </div>
            <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="p-3 bg-amber-100 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 rounded-xl hover:bg-amber-200 dark:hover:bg-amber-500/20 transition">
                <i class="fa-solid fa-bell text-xl"></i>
            </a>
        </div>
    </div>

    <!-- 3. Grid com Detalhamento de Gastos / Compras e Peças Críticas -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        <!-- Tabela: Últimos Gastos e Compras de Peças Registradas -->
        <div class="lg:col-span-6 bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-4 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading flex items-center gap-2">
                        <i class="fa-solid fa-receipt text-rose-500"></i> Últimos Gastos com Peças & Encomendas
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Gastos com Mercado Livre, fornecedores e lojas online</p>
                </div>
                <a href="{{ route('purchase_orders.index') }}" class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 hover:underline">Ver todas</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-400 uppercase text-[10px] tracking-wider">
                            <th class="py-2.5 px-2">Origem / Peça</th>
                            <th class="py-2.5 px-2">Veículo Vinculado</th>
                            <th class="py-2.5 px-2 text-right">Valor Gasto</th>
                            <th class="py-2.5 px-2 text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($recentExpenses as $expense)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3 px-2">
                                    <div class="font-bold text-slate-800 dark:text-white">{{ $expense->item_name }}</div>
                                    <span class="text-[10px] text-purple-700 dark:text-purple-300 font-semibold bg-purple-50 dark:bg-purple-950/60 px-1.5 py-0.5 rounded border border-purple-200 dark:border-purple-800/60">{{ $expense->origin }}</span>
                                </td>
                                <td class="py-3 px-2 text-slate-600 dark:text-slate-300">
                                    @if($expense->serviceOrder)
                                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $expense->serviceOrder->vehicle?->plate }}</div>
                                        <div class="text-[10px] text-slate-500 dark:text-slate-400">{{ $expense->serviceOrder->vehicle?->model }}</div>
                                    @else
                                        <span class="text-slate-400">Uso Geral / Estoque</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-right font-mono font-bold text-rose-600 dark:text-rose-400">
                                    R$ {{ number_format($expense->total_cost, 2, ',', '.') }}
                                </td>
                                <td class="py-3 px-2 text-center">
                                    @if($expense->status === 'received')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">Entregue</span>
                                    @elseif($expense->status === 'shipped')
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-cyan-50 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800">A Caminho</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">Pendente</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500 text-xs">Nenhum gasto com encomenda registrado recentemente.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tabela: Peças com Estoque Crítico que Precisam de Compra -->
        <div class="lg:col-span-6 bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 space-y-4 shadow-xs">
            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                <div>
                    <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading flex items-center gap-2">
                        <i class="fa-solid fa-bolt text-amber-500"></i> Peças Elétricas que Precisam de Reposição
                    </h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Peças com saldo em estoque abaixo ou igual ao mínimo</p>
                </div>
                <a href="{{ route('products.index', ['stock_status' => 'low']) }}" class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 hover:underline">Ver catálogo</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead>
                        <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-400 uppercase text-[10px] tracking-wider">
                            <th class="py-2.5 px-2">Peça / Componente</th>
                            <th class="py-2.5 px-2">Voltagem / Aplicação</th>
                            <th class="py-2.5 px-2 text-center">Saldo Atual</th>
                            <th class="py-2.5 px-2 text-right">Custo Estimado</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                        @forelse($lowStockProducts as $product)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3 px-2">
                                    <div class="font-bold text-slate-800 dark:text-white">{{ $product->name }}</div>
                                    <div class="text-[10px] text-slate-500">Cód: {{ $product->internal_code }}</div>
                                </td>
                                <td class="py-3 px-2">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $product->voltage == '24V' ? 'bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800' : 'bg-cyan-50 dark:bg-cyan-950 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800' }}">
                                        {{ $product->voltage ?? '12V' }}
                                    </span>
                                </td>
                                <td class="py-3 px-2 text-center">
                                    @if($product->current_stock <= 0)
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-300 dark:border-rose-800">0 UN (ZERADO)</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">{{ $product->current_stock }} UN (MÍN {{ $product->min_stock }})</span>
                                    @endif
                                </td>
                                <td class="py-3 px-2 text-right font-mono font-semibold text-slate-700 dark:text-slate-300">
                                    R$ {{ number_format($product->cost_price, 2, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400 dark:text-slate-500 text-xs">Nenhuma peça em estado crítico no momento.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- 4. Histórico Recente de Entradas e Saídas (Estoque) -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="flex items-center justify-between mb-4 border-b border-slate-100 dark:border-slate-800 pb-3">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading flex items-center gap-2">
                    <i class="fa-solid fa-arrows-rotate text-cyan-600 dark:text-cyan-400"></i> Últimas Entradas e Saídas do Estoque
                </h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Acompanhamento transparente das movimentações diárias</p>
            </div>
            <a href="{{ route('stock.index') }}" class="text-xs font-semibold text-cyan-600 dark:text-cyan-400 hover:underline">Ver histórico completo</a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 text-slate-400 dark:text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-2.5 px-3">Data/Hora</th>
                        <th class="py-2.5 px-3">Tipo</th>
                        <th class="py-2.5 px-3">Peça / Produto</th>
                        <th class="py-2.5 px-3">Quantidade</th>
                        <th class="py-2.5 px-3">Motivo / Fornecedor / OS</th>
                        <th class="py-2.5 px-3">Responsável</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($recentMovements as $mov)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition">
                            <td class="py-3 px-3 text-slate-500 dark:text-slate-400 font-mono">{{ $mov->created_at->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-3">
                                @if($mov->type === 'in')
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 flex items-center gap-1 w-fit">
                                        <i class="fa-solid fa-arrow-down text-[9px]"></i> ENTRADA
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 flex items-center gap-1 w-fit">
                                        <i class="fa-solid fa-arrow-up text-[9px]"></i> SAÍDA
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-slate-800 dark:text-white">{{ $mov->product?->name }}</div>
                                <div class="text-[10px] text-slate-500">Cód: {{ $mov->product?->internal_code }}</div>
                            </td>
                            <td class="py-3 px-3 font-mono font-bold {{ $mov->type === 'in' ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                                {{ $mov->type === 'in' ? '+' : '-' }}{{ $mov->quantity }} {{ $mov->product?->unit_measure ?? 'UN' }}
                            </td>
                            <td class="py-3 px-3 text-slate-600 dark:text-slate-300">
                                @if($mov->supplier)
                                    <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $mov->supplier->trade_name }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400">NF: {{ $mov->invoice_number ?? '-' }}</div>
                                @elseif($mov->serviceOrder)
                                    <div class="font-semibold text-slate-800 dark:text-slate-200">OS: {{ $mov->serviceOrder->order_number }}</div>
                                    <div class="text-[10px] text-slate-500 dark:text-slate-400">{{ $mov->serviceOrder->vehicle?->plate }}</div>
                                @else
                                    <div>{{ $mov->reason ?? '-' }}</div>
                                @endif
                            </td>
                            <td class="py-3 px-3 text-slate-500 dark:text-slate-400">{{ $mov->user?->name ?? 'Sistema' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400 dark:text-slate-500 text-xs">Nenhuma movimentação de estoque registrada até o momento.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

