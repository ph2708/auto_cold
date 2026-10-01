@extends('layouts.app')

@section('title', 'Ordens de Serviço')
@section('header_title', 'Controle de Ordens de Serviço (OS)')

@section('content')
<div class="space-y-6">

    <!-- Barra de Filtros & Ações -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 p-4 rounded-2xl shadow-xs">
        <form action="{{ route('service_orders.index') }}" method="GET" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <!-- Busca Geral -->
            <div class="relative">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Nº da OS, Placa, Cliente, Defeito..."
                    class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500 transition">
            </div>

            <!-- Status da OS -->
            <div>
                <select name="status" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500 transition">
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
                <select name="user_id" class="w-full bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-700 dark:text-slate-300 py-2 px-3 focus:outline-none focus:border-cyan-500 transition">
                    <option value="">Todos os Técnicos</option>
                    @foreach($technicians as $tech)
                        <option value="{{ $tech->id }}" {{ request('user_id') == $tech->id ? 'selected' : '' }}>
                            {{ $tech->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full bg-slate-800 hover:bg-slate-700 dark:bg-slate-800 dark:hover:bg-slate-700 text-white text-xs font-semibold px-4 py-2 rounded-xl transition">
                    Filtrar
                </button>
                @if(request()->hasAny(['search', 'status', 'user_id']))
                    <a href="{{ route('service_orders.index') }}" class="text-xs text-slate-500 hover:text-slate-800 dark:hover:text-white px-2">Limpar</a>
                @endif
            </div>

            <div class="sm:col-span-2 lg:col-span-4 flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800/80">
                <p class="text-xs text-slate-500 dark:text-slate-400">Total de <strong class="text-slate-800 dark:text-white">{{ $serviceOrders->total() }}</strong> ordens de serviço</p>
                <a href="{{ route('service_orders.create') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold px-4 py-2 rounded-xl shadow-sm shadow-cyan-600/20 flex items-center gap-2 transition">
                    <i class="fa-solid fa-plus"></i> Nova Ordem de Serviço
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de OSs -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-800 bg-slate-50/75 dark:bg-slate-950/40 text-slate-500 dark:text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-3 px-4">Nº OS / Data</th>
                        <th class="py-3 px-4">Cliente & Veículo</th>
                        <th class="py-3 px-4">Defeito / Sintoma Relatado</th>
                        <th class="py-3 px-4">Eletricista / Técnico</th>
                        <th class="py-3 px-4 text-center">Status</th>
                        <th class="py-3 px-4 text-right">Valor Total</th>
                        <th class="py-3 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800/60">
                    @forelse($serviceOrders as $os)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4 whitespace-nowrap">
                                <span class="font-extrabold text-cyan-600 dark:text-cyan-400 font-mono text-sm block">{{ $os->order_number }}</span>
                                <span class="text-[10px] text-slate-400">{{ $os->created_at->format('d/m/Y H:i') }}</span>
                            </td>

                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-800 dark:text-slate-200 block text-sm">{{ $os->customer?->name }}</span>
                                <span class="font-mono text-cyan-700 dark:text-cyan-300 font-bold text-[11px] inline-block bg-slate-100 dark:bg-slate-950 px-2 py-0.5 rounded-lg border border-slate-200 dark:border-slate-800 mt-1">
                                    {{ $os->vehicle?->plate }} • {{ $os->vehicle?->brand }} {{ $os->vehicle?->model }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 max-w-xs">
                                <p class="text-slate-600 dark:text-slate-300 text-xs truncate" title="{{ $os->reported_defect }}">
                                    {{ $os->reported_defect }}
                                </p>
                            </td>

                            <td class="py-3.5 px-4 text-slate-500 dark:text-slate-400">
                                {{ $os->technician?->name ?? 'Não atribuído' }}
                            </td>

                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-1 rounded-full text-[11px] font-bold
                                    {{ $os->status === 'budget' ? 'bg-slate-100 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:text-slate-300 dark:border-slate-700' : '' }}
                                    {{ $os->status === 'approved' ? 'bg-blue-50 text-blue-700 border border-blue-200 dark:bg-blue-950 dark:text-blue-300 dark:border-blue-800' : '' }}
                                    {{ $os->status === 'in_progress' ? 'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-950 dark:text-amber-300 dark:border-amber-800' : '' }}
                                    {{ $os->status === 'waiting_parts' ? 'bg-purple-50 text-purple-700 border border-purple-200 dark:bg-purple-950 dark:text-purple-300 dark:border-purple-800' : '' }}
                                    {{ $os->status === 'completed' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-950 dark:text-emerald-300 dark:border-emerald-800' : '' }}
                                    {{ $os->status === 'delivered' ? 'bg-cyan-50 text-cyan-700 border border-cyan-200 dark:bg-cyan-950 dark:text-cyan-300 dark:border-cyan-800' : '' }}
                                    {{ $os->status === 'cancelled' ? 'bg-rose-50 text-rose-700 border border-rose-200 dark:bg-rose-950 dark:text-rose-300 dark:border-rose-800' : '' }}
                                ">
                                    {{ $os->status_label }}
                                </span>
                            </td>

                            <td class="py-3.5 px-4 text-right font-mono font-bold text-sm text-emerald-600 dark:text-emerald-400">
                                R$ {{ number_format($os->total_amount, 2, ',', '.') }}
                            </td>

                            <td class="py-3.5 px-4 text-right">
                                <div class="flex items-center justify-end gap-1.5">
                                    <a href="{{ route('service_orders.show', $os) }}" class="p-2 rounded-xl bg-cyan-50 hover:bg-cyan-100 text-cyan-700 border border-cyan-200 dark:bg-cyan-950/80 dark:hover:bg-cyan-900 dark:text-cyan-400 dark:border-cyan-800 transition" title="Gerenciar OS & Peças">
                                        <i class="fa-solid fa-wrench"></i>
                                    </a>
                                    <a href="{{ route('service_orders.print_budget', $os) }}" target="_blank" class="p-2 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-700 border border-amber-200 dark:bg-amber-950/80 dark:hover:bg-amber-900 dark:text-amber-300 dark:border-amber-800 transition" title="Imprimir Orçamento / Salvar PDF">
                                        <i class="fa-solid fa-file-invoice-dollar"></i>
                                    </a>
                                    <a href="{{ route('service_orders.print', $os) }}" target="_blank" class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-slate-300 dark:border-slate-700 transition" title="Imprimir OS Completa">
                                        <i class="fa-solid fa-print"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400 dark:text-slate-500">
                                <i class="fa-solid fa-clipboard-list text-2xl block mb-2"></i>
                                Nenhuma ordem de serviço encontrada.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($serviceOrders->hasPages())
            <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                {{ $serviceOrders->links() }}
            </div>
        @endif
    </div>

</div>
@endsection

