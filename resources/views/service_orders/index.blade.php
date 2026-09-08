@extends('layouts.app')

@section('title', 'Ordens de Serviço')
@section('header_title', 'Controle de Ordens de Serviço (OS)')

@section('content')
<div class="space-y-6">

    <!-- Barra de Filtros & Ações -->
    <div class="bg-slate-900/80 border border-slate-800 p-4 rounded-2xl">
        <form action="{{ route('service_orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Busca Geral -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-500">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nº da OS, Placa, Cliente, Defeito..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
            </div>

            <!-- Status da OS -->
            <div>
                <select name="status" class="w-full bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500">
                    <option value="">Todos os Status</option>
                    <option value="budget" {{ request('status') === 'budget' ? 'selected' : '' }}>📋 Orçamento</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>👍 Aprovada</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>⚡ Em Execução</option>
                    <option value="waiting_parts" {{ request('status') === 'waiting_parts' ? 'selected' : '' }}>⏳ Aguardando Peças (Web/ML)</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>✅ Concluída / Testada</option>
                    <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>🚗 Entregue / Faturada</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>❌ Cancelada</option>
                </select>
            </div>

            <!-- Técnico / Eletricista -->
            <div>
                <select name="user_id" class="w-full bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500">
                    <option value="">Todos os Técnicos</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" {{ request('user_id') == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs font-semibold px-4 py-2 rounded-xl transition">
                    Filtrar
                </button>
                @if(request()->hasAny(['search', 'status', 'user_id']))
                    <a href="{{ route('service_orders.index') }}" class="text-xs text-slate-400 hover:text-white px-2">Limpar</a>
                @endif
            </div>

            <div class="sm:col-span-2 lg:col-span-4 flex items-center justify-between pt-2 border-t border-slate-800/60">
                <p class="text-xs text-slate-400">Total de {{ $serviceOrders->total() }} ordens de serviço localizadas</p>
                <a href="{{ route('service_orders.create') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-cyan-600/20 flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i> Nova Ordem de Serviço
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de OSs -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/40 text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Nº OS / Data</th>
                        <th class="py-3.5 px-4">Cliente & Veículo</th>
                        <th class="py-3.5 px-4">Defeito / Sintoma Relatado</th>
                        <th class="py-3.5 px-4">Eletricista / Técnico</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Valor Total</th>
                        <th class="py-3.5 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($serviceOrders as $os)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-extrabold text-cyan-400 font-mono text-sm block">{{ $os->order_number }}</span>
                                <span class="text-[10px] text-slate-500">{{ $os->created_at->format('d/m/Y H:i') }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-200 block text-sm">{{ $os->customer?->name }}</span>
                                <span class="font-mono text-cyan-300 font-bold text-[11px] inline-block bg-slate-950 px-1.5 py-0.5 rounded border border-slate-800 mt-0.5">
                                    {{ $os->vehicle?->plate }} • {{ $os->vehicle?->brand }} {{ $os->vehicle?->model }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="text-slate-300 text-xs truncate" title="{{ $os->reported_defect }}">
                                    {{ $os->reported_defect }}
                                </p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-400">
                                {{ $os->technician?->name ?? 'Não atribuído' }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-bold
                                    {{ $os->status === 'budget' ? 'bg-slate-800 text-slate-300 border border-slate-700' : '' }}
                                    {{ $os->status === 'approved' ? 'bg-blue-950 text-blue-300 border border-blue-800' : '' }}
                                    {{ $os->status === 'in_progress' ? 'bg-amber-950 text-amber-300 border border-amber-800 animate-pulse' : '' }}
                                    {{ $os->status === 'waiting_parts' ? 'bg-purple-950 text-purple-300 border border-purple-800' : '' }}
                                    {{ $os->status === 'completed' ? 'bg-emerald-950 text-emerald-300 border border-emerald-800' : '' }}
                                    {{ $os->status === 'delivered' ? 'bg-cyan-950 text-cyan-300 border border-cyan-800' : '' }}
                                    {{ $os->status === 'cancelled' ? 'bg-rose-950 text-rose-300 border border-rose-800' : '' }}
                                ">
                                    {{ $os->status_label }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-right font-mono font-bold text-sm text-emerald-400">
                                R$ {{ number_format($os->total_amount, 2, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('service_orders.show', $os) }}" class="p-1.5 rounded-lg bg-cyan-950/80 hover:bg-cyan-900 text-cyan-400 border border-cyan-800 transition" title="Gerenciar OS & Peças">
                                        <i class="fa-solid fa-wrench"></i>
                                    </a>
                                    <a href="{{ route('service_orders.print_budget', $os) }}" target="_blank" class="p-1.5 rounded-lg bg-amber-950/80 hover:bg-amber-900 text-amber-300 border border-amber-800 transition" title="Imprimir Orçamento / Salvar PDF">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </a>
                                    <a href="{{ route('service_orders.print', $os) }}" target="_blank" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition" title="Imprimir OS Completa">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-500">
                                <i class="fa-solid fa-clipboard-list text-2xl block mb-2"></i>
                                Nenhuma ordem de serviço encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($serviceOrders->hasPages())
            <div class="p-4 border-t border-slate-800">
                {{ $serviceOrders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
