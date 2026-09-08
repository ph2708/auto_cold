@extends('layouts.app')

@section('title', 'Movimentações de Estoque')
@section('header_title', 'Kardex & Movimentações de Estoque')

@section('content')
<div class="space-y-6">

    <!-- Barra de Filtros & Ações -->
    <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-2xl">
        <form action="{{ route('stock.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Busca Geral -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="NF, Placa, OS, Peça..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
            </div>

            <!-- Tipo de Movimentação -->
            <div>
                <select name="type" class="w-full bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500">
                    <option value="">Entradas & Saídas</option>
                    <option value="in" {{ request('type') === 'in' ? 'selected' : '' }}>📥 Apenas Entradas (+)</option>
                    <option value="out" {{ request('type') === 'out' ? 'selected' : '' }}>📤 Apenas Saídas (-)</option>
                </select>
            </div>

            <!-- Motivo -->
            <div>
                <select name="reason" class="w-full bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500">
                    <option value="">Todos os Motivos</option>
                    <option value="purchase" {{ request('reason') === 'purchase' ? 'selected' : '' }}>Compra / Fornecedor</option>
                    <option value="service_order" {{ request('reason') === 'service_order' ? 'selected' : '' }}>Aplicação em Veículo / OS</option>
                    <option value="direct_sale" {{ request('reason') === 'direct_sale' ? 'selected' : '' }}>Venda Balcão</option>
                    <option value="adjustment_in" {{ request('reason') === 'adjustment_in' ? 'selected' : '' }}>Ajuste Positivo (+)</option>
                    <option value="adjustment_out" {{ request('reason') === 'adjustment_out' ? 'selected' : '' }}>Ajuste Negativo (-)</option>
                    <option value="internal_use" {{ request('reason') === 'internal_use' ? 'selected' : '' }}>Uso Interno Oficina</option>
                    <option value="loss_damage" {{ request('reason') === 'loss_damage' ? 'selected' : '' }}>Perda / Queima / Avaria</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2 rounded-xl transition">
                    Filtrar Kardex
                </button>
                @if(request()->hasAny(['search', 'type', 'reason']))
                    <a href="{{ route('stock.index') }}" class="text-xs text-slate-400 hover:text-white px-2">Limpar</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tabela Kardex -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/40 text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Data / Hora</th>
                        <th class="py-3.5 px-4">Tipo & Motivo</th>
                        <th class="py-3.5 px-4">Peça / Componente</th>
                        <th class="py-3.5 px-4">Origem / Destino (Placa / NF / Fornecedor)</th>
                        <th class="py-3.5 px-4 text-center">Quantidade</th>
                        <th class="py-3.5 px-4 text-right">Valor Unitário</th>
                        <th class="py-3.5 px-4 text-right">Total</th>
                        <th class="py-3.5 px-4">Usuário</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($movements as $mov)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4 text-slate-400 whitespace-nowrap">
                                <span class="font-bold text-slate-200 block">{{ $mov->created_at->format('d/m/Y') }}</span>
                                <span class="text-[10px] text-slate-500">{{ $mov->created_at->format('H:i:s') }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($mov->type === 'in')
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold bg-emerald-950 text-emerald-300 border border-emerald-800 text-[10px]">
                                        <i class="fa-solid fa-arrow-down text-[8px]"></i> Entrada
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full font-bold bg-rose-950 text-rose-300 border border-rose-800 text-[10px]">
                                        <i class="fa-solid fa-arrow-up text-[8px]"></i> Saída
                                    </span>
                                @endif
                                <span class="block text-[11px] text-slate-300 font-medium mt-1">{{ $mov->reason_label }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-100 block">{{ $mov->product?->name }}</span>
                                <span class="text-[10px] text-cyan-400 font-mono">{{ $mov->product?->sku }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-300">
                                @if($mov->vehicle_plate)
                                    <div class="flex items-center gap-1.5 font-mono text-cyan-400 font-bold">
                                        <i class="fa-solid fa-car text-xs"></i>
                                        <span>Placa: {{ $mov->vehicle_plate }}</span>
                                    </div>
                                @endif

                                @if($mov->service_order_number)
                                    <span class="text-[11px] text-slate-400 block">{{ $mov->service_order_number }}</span>
                                @endif

                                @if($mov->supplier)
                                    <span class="text-[11px] text-slate-400 block">
                                        <i class="fa-solid fa-truck text-[10px] mr-1"></i>{{ $mov->supplier->corporate_name }}
                                    </span>
                                @endif

                                @if($mov->document_number)
                                    <span class="text-[10px] text-slate-500 font-mono block">Doc: {{ $mov->document_number }}</span>
                                @endif

                                @if($mov->notes)
                                    <span class="text-[10px] text-slate-500 italic block mt-0.5">{{ $mov->notes }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-center font-extrabold text-sm whitespace-nowrap {{ $mov->type === 'in' ? 'text-emerald-400' : 'text-rose-400' }}">
                                {{ $mov->type === 'in' ? '+' : '-' }}{{ $mov->quantity }} {{ $mov->product?->unit ?? 'UN' }}
                            </td>

                            <td class="py-3.5 px-4 text-right font-mono text-slate-300">
                                R$ {{ number_format($mov->type === 'in' ? $mov->unit_cost : $mov->unit_price, 2, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-right font-bold text-slate-200 font-mono">
                                R$ {{ number_format($mov->total_amount, 2, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-slate-400 text-[11px]">
                                {{ $mov->user?->name ?? 'Sistema' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-8 text-center text-slate-500">
                                <i class="fa-solid fa-boxes-stacked text-2xl block mb-2"></i>
                                Nenhuma movimentação de estoque registrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movements->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $movements->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
