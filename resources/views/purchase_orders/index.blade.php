@extends('layouts.app')

@section('title', 'Peças a Chegar & Rastreio')
@section('header_title', 'Peças a Chegar (Mercado Livre / Shopee / Web / Locais)')

@section('content')
<div class="space-y-6">

    <!-- Cards de Resumo de Encomendas -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Aguardando Envio</p>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1 font-heading">{{ $pendingCount }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">pedidos</span></h3>
            </div>
            <div class="h-11 w-11 rounded-xl bg-amber-50 dark:bg-amber-500/10 border border-amber-200 dark:border-amber-500/20 text-amber-600 dark:text-amber-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wider">Em Trânsito / A Caminho</p>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1 font-heading">{{ $shippedCount }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">encomendas</span></h3>
            </div>
            <div class="h-11 w-11 rounded-xl bg-purple-50 dark:bg-purple-500/10 border border-purple-200 dark:border-purple-500/20 text-purple-600 dark:text-purple-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
        </div>

        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-5 rounded-2xl flex items-center justify-between shadow-xs">
            <div>
                <p class="text-xs font-semibold text-emerald-700 dark:text-emerald-400 uppercase tracking-wider">Recebidos no Mês</p>
                <h3 class="text-2xl font-bold text-slate-900 dark:text-white mt-1 font-heading">{{ $deliveredMonthCount }} <span class="text-xs font-normal text-slate-500 dark:text-slate-400">entregues</span></h3>
            </div>
            <div class="h-11 w-11 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-lg">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        </div>
    </div>

    <!-- Barra de Filtros & Ações -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-xs">
        <form action="{{ route('purchase_orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Busca Geral -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Código Rastreio, Peça, Pedido..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500 transition">
            </div>

            <!-- Origem da Compra -->
            <div>
                <select name="origin" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500 transition">
                    <option value="">Todas as Origens</option>
                    <option value="Mercado Livre" {{ request('origin') === 'Mercado Livre' ? 'selected' : '' }}>🟡 Mercado Livre</option>
                    <option value="Shopee" {{ request('origin') === 'Shopee' ? 'selected' : '' }}>🟠 Shopee</option>
                    <option value="Loja Online / Internet" {{ request('origin') === 'Loja Online / Internet' ? 'selected' : '' }}>🌐 Loja Online / Internet</option>
                    <option value="Fornecedor Local" {{ request('origin') === 'Fornecedor Local' ? 'selected' : '' }}>🏬 Fornecedor Local</option>
                    <option value="Distribuidora de Autopeças" {{ request('origin') === 'Distribuidora de Autopeças' ? 'selected' : '' }}>🏭 Distribuidora</option>
                </select>
            </div>

            <!-- Status -->
            <div>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500 transition">
                    <option value="">Todos os Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>⏳ Aguardando Envio</option>
                    <option value="shipped" {{ request('status') === 'shipped' ? 'selected' : '' }}>🚚 Em Trânsito / A Caminho</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>✅ Recebido na Oficina</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Cancelado</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                    Filtrar Encomendas
                </button>
                @if(request()->hasAny(['search', 'origin', 'status']))
                    <a href="{{ route('purchase_orders.index') }}" class="text-xs text-slate-500 hover:text-slate-800 dark:hover:text-white px-2">Limpar</a>
                @endif
            </div>

            <div class="sm:col-span-2 lg:col-span-4 flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800/80">
                <p class="text-xs text-slate-500 dark:text-slate-400">Total de <strong class="text-slate-800 dark:text-white">{{ $purchaseOrders->total() }}</strong> pedidos acompanhados</p>
                <a href="{{ route('purchase_orders.create') }}" class="bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-xs shadow-purple-600/20 flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i> Novo Pedido a Chegar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de Encomendas -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-950/40 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Pedido / Origem</th>
                        <th class="py-3 px-4">Peça / Descrição Comprada</th>
                        <th class="py-3 px-4">Rastreamento / Destino</th>
                        <th class="py-3 px-4">Previsão Chegada</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Custo Total</th>
                        <th class="py-3 px-4 text-right">Ações & Entrada</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($purchaseOrders as $po)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-bold text-slate-800 dark:text-white block">{{ $po->order_code }}</span>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold px-2 py-0.5 rounded-full mt-1
                                    {{ str_contains($po->origin, 'Mercado') ? 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-800' : '' }}
                                    {{ str_contains($po->origin, 'Shopee') ? 'bg-orange-50 text-orange-700 border border-orange-200 dark:bg-orange-950 dark:text-orange-300 dark:border-orange-800' : '' }}
                                    {{ str_contains($po->origin, 'Online') ? 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950 dark:text-blue-300 dark:border-blue-800' : '' }}
                                    {{ str_contains($po->origin, 'Local') ? 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700' : '' }}
                                ">
                                    {{ $po->origin }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 dark:text-slate-100 text-sm block">{{ $po->item_name }}</span>
                                <span class="text-[11px] text-slate-500 dark:text-slate-400">
                                    Qtd: <strong class="text-slate-800 dark:text-white">{{ $po->quantity }}</strong>
                                    @if($po->product)
                                        • SKU: <span class="text-cyan-700 dark:text-cyan-400 font-mono">{{ $po->product->sku }}</span>
                                    @endif
                                </span>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($po->tracking_code)
                                    <div class="flex items-center gap-1.5 font-mono text-cyan-700 dark:text-cyan-400 font-bold">
                                        <i class="fa-solid fa-barcode text-xs"></i>
                                        <span>{{ $po->tracking_code }}</span>
                                        @if($po->tracking_url)
                                            <a href="{{ $po->tracking_url }}" target="_blank" class="text-[10px] text-slate-400 hover:text-cyan-600 dark:hover:text-white" title="Rastrear Link">
                                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                            </a>
                                        @endif
                                    </div>
                                @endif

                                @if($po->serviceOrder)
                                    <div class="text-[11px] text-purple-700 dark:text-purple-300 mt-1 flex items-center gap-1">
                                        <i class="fa-solid fa-car text-[10px]"></i>
                                        <span>Para OS: <strong>{{ $po->serviceOrder->order_number }}</strong> ({{ $po->serviceOrder->vehicle?->plate }})</span>
                                    </div>
                                @else
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500 block mt-0.5">Reposição de Estoque</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 whitespace-nowrap">
                                @if($po->expected_delivery_date)
                                    <span class="font-semibold block">{{ $po->expected_delivery_date->format('d/m/Y') }}</span>
                                    <span class="text-[10px] text-slate-400 dark:text-slate-500">Comprado: {{ $po->purchase_date?->format('d/m/Y') }}</span>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $po->status === 'pending' ? 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-800' : '' }}
                                    {{ $po->status === 'shipped' ? 'bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950 dark:text-purple-300 dark:border-purple-800' : '' }}
                                    {{ $po->status === 'delivered' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800' : '' }}
                                    {{ $po->status === 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950 dark:text-rose-300 dark:border-rose-800' : '' }}
                                ">
                                    {{ $po->status_label }}
                                </span>
                                @if($po->received_at)
                                    <span class="text-[9px] text-slate-400 block mt-0.5">{{ $po->received_at->format('d/m H:i') }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400">
                                R$ {{ number_format($po->total_cost, 2, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    @if($po->status !== 'delivered')
                                        <form action="{{ route('purchase_orders.receive', $po) }}" method="POST" onsubmit="return confirm('Confirmar o recebimento desta peça na oficina? (Será gerada entrada no estoque automaticamente)');">
                                            @csrf
                                            <button type="submit" class="px-2.5 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 dark:bg-emerald-600/20 dark:hover:bg-emerald-600/30 dark:text-emerald-400 dark:border-emerald-500/30 font-bold text-xs transition flex items-center gap-1 shadow-2xs" title="Chegou na Oficina">
                                                <i class="fa-solid fa-box-check text-[11px]"></i> Receber
                                            </button>
                                        </form>
                                    @endif

                                    <a href="{{ route('purchase_orders.edit', $po) }}" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 dark:border-slate-700 transition" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-box-open text-2xl block mb-2"></i>
                                Nenhum pedido ou encomenda cadastrada no momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($purchaseOrders->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $purchaseOrders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
