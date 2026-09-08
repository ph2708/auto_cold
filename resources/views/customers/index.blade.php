@extends('layouts.app')

@section('title', 'Clientes & Veículos')
@section('header_title', 'Clientes & Veículos')

@section('content')
<div class="space-y-6">

    <!-- Barra de Filtros & Ações -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-slate-900/80 border border-slate-800 p-4 rounded-2xl">
        <form action="{{ route('customers.index') }}" method="GET" class="flex flex-wrap items-center gap-3 w-full sm:w-auto flex-1">
            <div class="relative flex-1 min-w-[260px]">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nome, Placa do Carro, Telefone, CPF..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
            </div>

            <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2 rounded-xl transition">
                Buscar
            </button>
            @if(request()->has('search'))
                <a href="{{ route('customers.index') }}" class="text-xs text-slate-400 hover:text-white">Limpar</a>
            @endif
        </form>

        <div class="flex items-center gap-2">
            <form action="{{ route('customers.generic') }}" method="POST">
                @csrf
                <button type="submit" class="bg-slate-800 hover:bg-slate-700 text-cyan-300 text-xs font-bold px-3.5 py-2.5 rounded-xl border border-slate-700 flex items-center gap-1.5 transition shrink-0" title="Atendimento rápido sem preencher dados">
                    <i class="fa-solid fa-user-clock"></i> Cliente Avulso
                </button>
            </form>
            <a href="{{ route('customers.create') }}" class="bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-cyan-500/20 flex items-center gap-2 transition shrink-0">
                <i class="fa-solid fa-user-plus"></i> Novo Cliente
            </a>
        </div>
    </div>

    <!-- Tabela de Clientes -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/40 text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Cliente / Contato</th>
                        <th class="py-3.5 px-4">Veículo(s) Cadastrado(s)</th>
                        <th class="py-3.5 px-4">Telefone / WhatsApp</th>
                        <th class="py-3.5 px-4">Cidade / UF</th>
                        <th class="py-3.5 px-4 text-center">Histórico OS</th>
                        <th class="py-3.5 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($customers as $c)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-200 block text-sm">{{ $c->name }}</span>
                                @if($c->document_number)
                                    <span class="text-[10px] text-slate-500 font-mono">Doc: {{ $c->document_number }}</span>
                                @endif
                            </td>

                            <td class="py-3.5 px-4">
                                <div class="flex flex-wrap gap-1.5">
                                    @forelse($c->vehicles as $v)
                                        <span class="px-2 py-0.5 rounded bg-slate-950 border border-slate-700 text-cyan-400 font-mono font-bold text-[11px]">
                                            <i class="fa-solid fa-car text-[9px] mr-1 text-slate-400"></i>
                                            {{ $v->plate }} ({{ $v->model }})
                                        </span>
                                    @empty
                                        <span class="text-slate-500 italic text-[11px]">Nenhum veículo cadastrado</span>
                                    @endforelse
                                </div>
                            </td>

                            <td class="py-3.5 px-4">
                                @if($c->phone)
                                     <div class="text-slate-300">
                                         <a href="tel:{{ preg_replace('/\D/', '', $c->phone) }}" class="hover:text-cyan-400 transition" title="Ligar">
                                             <i class="fa-solid fa-phone text-[10px] mr-1 text-slate-500"></i>{{ $c->phone }}
                                         </a>
                                     </div>
                                 @endif
                                 @if($c->whatsapp)
                                     @php
                                         $cleanZap = preg_replace('/\D/', '', $c->whatsapp);
                                         if(strlen($cleanZap) <= 11) $cleanZap = '55' . $cleanZap;
                                     @endphp
                                     <div class="mt-0.5">
                                         <a href="https://wa.me/{{ $cleanZap }}?text=Ol%C3%A1+{{ urlencode($c->name) }}%2C+tudo+bem%3F+Aqui+%C3%A9+da+oficina+Auto+Cold." target="_blank"
                                             class="inline-flex items-center gap-1 text-emerald-400 hover:text-emerald-300 font-bold bg-emerald-950/60 hover:bg-emerald-900/80 px-2 py-0.5 rounded-lg border border-emerald-800 transition text-[11px]" title="Abrir conversa no WhatsApp">
                                             <i class="fa-brands fa-whatsapp text-xs"></i>
                                             <span>{{ $c->whatsapp }}</span>
                                         </a>
                                     </div>
                                 @endif
                            </td>

                            <td class="py-3.5 px-4 text-slate-300">
                                {{ $c->city ?? '-' }} / {{ $c->state ?? '-' }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full bg-cyan-950/80 text-cyan-300 font-semibold text-[11px] border border-cyan-800/50">
                                    {{ $c->service_orders_count }} OS
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('service_orders.create') }}?customer_id={{ $c->id }}" class="p-1.5 rounded-lg bg-cyan-950/80 hover:bg-cyan-900 text-cyan-400 border border-cyan-800 transition" title="Abrir Nova OS">
                                        <i class="fa-solid fa-file-circle-plus"></i>
                                    </a>
                                    <a href="{{ route('customers.edit', $c) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition" title="Editar / Adicionar Veículo">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                <i class="fa-solid fa-users text-2xl block mb-2"></i>
                                Nenhum cliente encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($customers->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $customers->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
