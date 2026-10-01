@extends('layouts.app')

@section('title', $customer->name)
@section('header_title', 'Perfil do Cliente')

@section('content')
<div class="space-y-6">

    {{-- Header do Perfil --}}
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl p-6 shadow-xs">
        <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
            <div class="h-16 w-16 rounded-2xl bg-gradient-to-br from-cyan-500 to-blue-600 flex items-center justify-center text-white font-black text-2xl font-heading shadow-sm shadow-cyan-600/20 shrink-0">
                {{ strtoupper(substr($customer->name, 0, 1)) }}
            </div>
            <div class="flex-1">
                <h2 class="text-xl font-black text-slate-900 dark:text-white font-heading">{{ $customer->name }}</h2>
                <div class="flex flex-wrap gap-3 mt-2 text-xs text-slate-500 dark:text-slate-400">
                    @if($customer->whatsapp)
                        <span><i class="fa-brands fa-whatsapp text-emerald-500 mr-1"></i>{{ $customer->whatsapp }}</span>
                    @endif
                    @if($customer->phone)
                        <span><i class="fa-solid fa-phone text-slate-400 mr-1"></i>{{ $customer->phone }}</span>
                    @endif
                    @if($customer->email)
                        <span><i class="fa-solid fa-envelope text-blue-400 mr-1"></i>{{ $customer->email }}</span>
                    @endif
                    @if($customer->document_number)
                        <span><i class="fa-solid fa-id-card text-slate-400 mr-1"></i>{{ $customer->document_number }}</span>
                    @endif
                    @if($customer->city)
                        <span><i class="fa-solid fa-location-dot text-rose-400 mr-1"></i>{{ $customer->city }}{{ $customer->state ? ', ' . $customer->state : '' }}</span>
                    @endif
                </div>
            </div>
            <div class="flex gap-2 shrink-0">
                <a href="{{ route('customers.edit', $customer) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold border border-slate-200 dark:border-slate-700 transition flex items-center gap-2">
                    <i class="fa-solid fa-pen-to-square"></i> Editar
                </a>
                <a href="{{ route('service_orders.create') }}" class="px-4 py-2 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-sm shadow-cyan-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i> Nova OS
                </a>
            </div>
        </div>

        {{-- KPIs do Cliente --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mt-5 pt-5 border-t border-slate-100 dark:border-slate-800">
            <div class="text-center">
                <p class="text-2xl font-black text-emerald-600 dark:text-emerald-400 font-heading">R$ {{ number_format($totalSpent, 2, ',', '.') }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Total Gasto (Faturado)</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-black text-cyan-600 dark:text-cyan-400 font-heading">{{ $totalOs }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Ordens de Serviço</p>
            </div>
            <div class="text-center">
                <p class="text-2xl font-black text-blue-600 dark:text-blue-400 font-heading">{{ $customer->vehicles->count() }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Veículos Cadastrados</p>
            </div>
            <div class="text-center">
                <p class="text-sm font-black text-slate-800 dark:text-slate-200 font-heading">{{ $lastVisit ? $lastVisit->format('d/m/Y') : '—' }}</p>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">Última Visita</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        {{-- Veículos --}}
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-car text-cyan-500"></i>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white font-heading">Veículos</h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                @forelse($customer->vehicles as $vehicle)
                    <div class="px-4 py-3 flex items-center gap-3">
                        <div class="h-9 w-9 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center">
                            <i class="fa-solid fa-car text-slate-400 text-sm"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ $vehicle->brand }} {{ $vehicle->model }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span class="font-mono text-[11px] font-bold text-cyan-700 dark:text-cyan-300 bg-slate-100 dark:bg-slate-900 px-2 py-0.5 rounded-lg border border-slate-200 dark:border-slate-700">
                                    {{ $vehicle->plate }}
                                </span>
                                @if($vehicle->year)
                                    <span class="text-[10px] text-slate-400">{{ $vehicle->year }}</span>
                                @endif
                                @if($vehicle->color)
                                    <span class="text-[10px] text-slate-400">{{ $vehicle->color }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400 text-xs">
                        <i class="fa-solid fa-car text-2xl block mb-2 text-slate-300 dark:text-slate-700"></i>
                        Nenhum veículo cadastrado.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Histórico de OSs --}}
        <div class="lg:col-span-2 bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-amber-500"></i>
                <h3 class="text-sm font-bold text-slate-800 dark:text-white font-heading">Histórico de Ordens de Serviço</h3>
            </div>
            <div class="divide-y divide-slate-100 dark:divide-slate-800/60">
                @forelse($customer->serviceOrders as $os)
                    <div class="px-4 py-3 flex items-center gap-3 hover:bg-slate-50 dark:hover:bg-slate-800/30 transition">
                        <div class="shrink-0">
                            <span class="inline-block px-2 py-0.5 rounded-lg text-[10px] font-bold
                                {{ $os->status === 'budget' ? 'bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300' : '' }}
                                {{ $os->status === 'in_progress' ? 'bg-amber-50 text-amber-700 dark:bg-amber-950 dark:text-amber-300' : '' }}
                                {{ $os->status === 'waiting_parts' ? 'bg-purple-50 text-purple-700 dark:bg-purple-950 dark:text-purple-300' : '' }}
                                {{ $os->status === 'completed' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950 dark:text-emerald-300' : '' }}
                                {{ $os->status === 'delivered' ? 'bg-cyan-50 text-cyan-700 dark:bg-cyan-950 dark:text-cyan-300' : '' }}
                                {{ $os->status === 'cancelled' ? 'bg-rose-50 text-rose-700 dark:bg-rose-950 dark:text-rose-300' : '' }}
                            ">{{ $os->status_label }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('service_orders.show', $os) }}" class="font-mono font-bold text-cyan-600 dark:text-cyan-400 hover:underline text-xs">
                                    {{ $os->order_number }}
                                </a>
                                <span class="text-[10px] text-slate-400">{{ $os->created_at->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ $os->reported_defect }}</p>
                        </div>
                        <div class="text-right shrink-0">
                            <p class="text-sm font-black font-mono {{ in_array($os->status, ['completed','delivered']) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-500 dark:text-slate-400' }}">
                                R$ {{ number_format($os->total_amount, 2, ',', '.') }}
                            </p>
                            @if($os->technician)
                                <p class="text-[10px] text-slate-400">{{ $os->technician->name }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-10 text-center text-slate-400 dark:text-slate-500 text-xs">
                        <i class="fa-solid fa-clipboard text-2xl block mb-2 text-slate-300 dark:text-slate-700"></i>
                        Nenhuma OS registrada para este cliente.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection
