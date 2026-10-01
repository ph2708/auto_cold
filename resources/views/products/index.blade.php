@extends('layouts.app')

@section('title', 'Catálogo de Peças')
@section('header_title', 'Catálogo de Peças & Elétrica')

@section('content')
<div class="space-y-6">

    <!-- Barra de Ações & Filtros -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200/80 dark:border-slate-800 p-4 rounded-2xl shadow-sm">
        <form action="{{ route('products.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Busca Geral -->
            <div class="relative lg:col-span-2">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, SKU, OEM, Marca, Aplicação..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
            </div>

            <!-- Categoria -->
            <div>
                <select name="category_id" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                    <option value="">Todas as Categorias</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Voltagem Elétrica -->
            <div>
                <select name="voltage" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                    <option value="">Todas Voltagens</option>
                    <option value="12V" {{ request('voltage') === '12V' ? 'selected' : '' }}>12V (Leve / Utilitário)</option>
                    <option value="24V" {{ request('voltage') === '24V' ? 'selected' : '' }}>24V (Pesado / Caminhão)</option>
                    <option value="Bivolt" {{ request('voltage') === 'Bivolt' ? 'selected' : '' }}>Bivolt / Universal</option>
                </select>
            </div>

            <!-- Alerta de Estoque -->
            <div>
                <select name="stock_status" class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 py-2 px-3 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                    <option value="">Status do Estoque</option>
                    <option value="low" {{ request('stock_status') === 'low' ? 'selected' : '' }}>⚠️ Estoque Baixo / Crítico</option>
                    <option value="out" {{ request('stock_status') === 'out' ? 'selected' : '' }}>🚨 Totalmente Zerado (0)</option>
                    <option value="normal" {{ request('stock_status') === 'normal' ? 'selected' : '' }}>✅ Estoque Normal</option>
                </select>
            </div>

            <div class="sm:col-span-2 lg:col-span-5 flex items-center justify-between pt-2 border-t border-slate-100 dark:border-slate-800/60">
                <div class="flex items-center gap-3">
                    <button type="submit" class="bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold px-4 py-2 rounded-xl transition">
                        <i class="fa-solid fa-filter text-[10px] mr-1"></i> Aplicar Filtros
                    </button>
                    @if(request()->hasAny(['search', 'category_id', 'voltage', 'stock_status']))
                        <a href="{{ route('products.index') }}" class="text-xs text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white">Limpar Filtros</a>
                    @endif
                </div>

                <a href="{{ route('products.create') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-md shadow-cyan-600/20 flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i> Nova Peça / Componente
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de Produtos / Peças -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200/80 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950/40 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-4 font-semibold">Código / SKU</th>
                        <th class="py-3.5 px-4 font-semibold">Peça / Especificações Elétricas</th>
                        <th class="py-3.5 px-4 font-semibold">Categoria / Marca</th>
                        <th class="py-3.5 px-4 font-semibold">Localização</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Preço Venda</th>
                        <th class="py-3.5 px-4 text-center font-semibold">Saldo em Estoque</th>
                        <th class="py-3.5 px-4 text-right font-semibold">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($products as $p)
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4 font-mono">
                                <span class="font-bold text-cyan-600 dark:text-cyan-400 block">{{ $p->sku }}</span>
                                @if($p->oem_code)
                                    <span class="text-[10px] text-slate-400">OEM: {{ $p->oem_code }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-900 dark:text-slate-100 text-sm block">{{ $p->name }}</span>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                    @if($p->voltage)
                                        <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-950/80 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 font-semibold text-[10px]">
                                            <i class="fa-solid fa-bolt text-[8px] mr-0.5"></i> {{ $p->voltage }}
                                        </span>
                                    @endif
                                    @if($p->amperage)
                                        <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px]">
                                            {{ $p->amperage }}
                                        </span>
                                    @endif
                                    @if($p->pin_count)
                                        <span class="px-2 py-0.5 rounded bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 text-[10px]">
                                            {{ $p->pin_count }}
                                        </span>
                                    @endif
                                    @if($p->vehicle_compatibility)
                                        <span class="text-[11px] text-slate-500 dark:text-slate-400 italic truncate max-w-xs">
                                            • Compat: {{ $p->vehicle_compatibility }}
                                        </span>
                                    @endif
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-medium text-slate-800 dark:text-slate-300 block">{{ $p->category?->name ?? 'Sem categoria' }}</span>
                                <span class="text-[11px] text-slate-400">{{ $p->brand ?? 'Marca não especificada' }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-slate-600 dark:text-slate-300 font-mono text-[11px]">
                                <i class="fa-solid fa-location-dot text-slate-400 dark:text-slate-500 mr-1"></i>
                                {{ $p->location ?? 'Geral' }}
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <span class="font-bold text-emerald-600 dark:text-emerald-400 block text-sm">R$ {{ number_format($p->selling_price, 2, ',', '.') }}</span>
                                <span class="text-[10px] text-slate-400 dark:text-slate-500">Custo: R$ {{ number_format($p->cost_price, 2, ',', '.') }}</span>
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                @if($p->current_stock <= 0)
                                    <span class="inline-block px-2.5 py-1 rounded-full font-bold bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800 text-xs">
                                        0 {{ $p->unit }} (Zerado)
                                    </span>
                                @elseif($p->isLowStock())
                                    <span class="inline-block px-2.5 py-1 rounded-full font-bold bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 text-xs">
                                        ⚠️ {{ $p->current_stock }} {{ $p->unit }} (Mín: {{ $p->min_stock }})
                                    </span>
                                @else
                                    <span class="inline-block px-2.5 py-1 rounded-full font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 text-xs">
                                        {{ $p->current_stock }} {{ $p->unit }}
                                    </span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('stock.entry') }}?product_id={{ $p->id }}" class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-100 dark:bg-emerald-950/80 dark:hover:bg-emerald-900 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800 transition" title="Dar Entrada">
                                        <i class="fa-solid fa-plus"></i>
                                    </a>
                                    <a href="{{ route('stock.exit') }}?product_id={{ $p->id }}" class="p-1.5 rounded-lg bg-amber-50 hover:bg-amber-100 dark:bg-amber-950/80 dark:hover:bg-amber-900 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-800 transition" title="Dar Saída / Aplicar em Carro">
                                        <i class="fa-solid fa-minus"></i>
                                    </a>
                                    <a href="{{ route('products.edit', $p) }}" class="p-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-microchip text-2xl block mb-2"></i>
                                Nenhuma peça ou componente encontrado no catálogo.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
