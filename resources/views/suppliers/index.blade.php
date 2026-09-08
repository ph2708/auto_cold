@extends('layouts.app')

@section('title', 'Fornecedores')
@section('header_title', 'Gestão de Fornecedores')

@section('content')
<div class="space-y-6">

    <!-- Barra de Ações & Filtros -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-900/80 border border-slate-800 p-4 rounded-2xl">
        <form action="{{ route('suppliers.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto flex-1">
            <div class="relative flex-1 min-w-[240px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Buscar por Razão Social, CNPJ, Contato..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
            </div>

            <select name="status" class="bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500">
                <option value="">Todos os Status</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Ativos</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inativos</option>
            </select>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2 rounded-xl transition">
                Filtrar
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('suppliers.index') }}" class="text-xs text-slate-400 hover:text-white">Limpar</a>
            @endif
        </form>

        <a href="{{ route('suppliers.create') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-cyan-600/20 flex items-center gap-2 transition shrink-0">
            <i class="fa-solid fa-plus"></i> Novo Fornecedor
        </a>
    </div>

    <!-- Tabela de Fornecedores -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/40 text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Fornecedor / Razão Social</th>
                        <th class="py-3.5 px-4">CNPJ / CPF</th>
                        <th class="py-3.5 px-4">Contato & Telefones</th>
                        <th class="py-3.5 px-4">Cidade / UF</th>
                        <th class="py-3.5 px-4 text-center">Peças Fornecidas</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($suppliers as $s)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-200 block text-sm">{{ $s->corporate_name }}</span>
                                @if($s->trade_name)
                                    <span class="text-[11px] text-cyan-400">{{ $s->trade_name }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-300">
                                {{ $s->document_number ?? 'Não informado' }}
                            </td>
                            <td class="py-3.5 px-4">
                                @if($s->contact_person)
                                    <span class="font-semibold text-slate-300 block">{{ $s->contact_person }}</span>
                                @endif
                                <div class="flex items-center gap-2 text-[11px] text-slate-400 mt-0.5">
                                    @if($s->phone)
                                        <a href="tel:{{ preg_replace('/\D/', '', $s->phone) }}" class="hover:text-cyan-400 transition" title="Ligar">
                                            <i class="fa-solid fa-phone text-[10px] mr-1"></i>{{ $s->phone }}
                                        </a>
                                    @endif
                                    @if($s->whatsapp)
                                        @php
                                            $cleanZap = preg_replace('/\D/', '', $s->whatsapp);
                                            if(strlen($cleanZap) <= 11) $cleanZap = '55' . $cleanZap;
                                        @endphp
                                        <a href="https://wa.me/{{ $cleanZap }}?text=Ol%C3%A1%2C+sou+da+Auto+Cold.+Gostaria+de+or%C3%A7ar+algumas+pe%C3%A7as." target="_blank"
                                            class="inline-flex items-center gap-1 text-emerald-400 hover:text-emerald-300 font-bold bg-emerald-950/60 hover:bg-emerald-900/80 px-2 py-0.5 rounded-lg border border-emerald-800 transition" title="Abrir WhatsApp">
                                            <i class="fa-brands fa-whatsapp text-xs"></i>
                                            <span>{{ $s->whatsapp }}</span>
                                        </a>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-slate-300">
                                {{ $s->city ?? '-' }} / {{ $s->state ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 font-semibold text-[11px] border border-slate-700">
                                    {{ $s->products_count }} catálogo
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($s->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-950 text-emerald-300 border border-emerald-800">
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-slate-800 text-slate-400 border border-slate-700">
                                        Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('suppliers.edit', $s) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition" title="Editar">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                <i class="fa-solid fa-truck-ramp-box text-2xl block mb-2"></i>
                                Nenhum fornecedor encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($suppliers->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $suppliers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
