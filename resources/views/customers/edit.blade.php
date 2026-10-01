@extends('layouts.app')

@section('title', 'Editar Cliente')
@section('header_title', 'Editar Cliente & Veículos')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    
    <!-- Dados do Cliente -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm">
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100 dark:border-slate-800">
            <div>
                <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading">Editar: {{ $customer->name }}</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400">Atualize os contatos e dados do proprietário</p>
            </div>
            <a href="{{ route('customers.index') }}" class="text-xs text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-white flex items-center gap-1.5 font-medium">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form action="{{ route('customers.update', $customer) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Nome Completo *</label>
                    <input type="text" name="name" value="{{ old('name', $customer->name) }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 font-medium">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">CPF ou CNPJ</label>
                    <input type="text" name="document_number" value="{{ old('document_number', $customer->document_number) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">WhatsApp / Celular *</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $customer->whatsapp) }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">Telefone Fixo</label>
                    <input type="text" name="phone" value="{{ old('phone', $customer->phone) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-600 dark:text-slate-300 uppercase tracking-wider mb-2">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $customer->email) }}"
                        class="w-full px-3.5 py-2.5 bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                </div>
            </div>

            <div class="flex items-center justify-end pt-4 border-t border-slate-100 dark:border-slate-800">
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-md shadow-cyan-600/20 transition">
                    Atualizar Dados
                </button>
            </div>
        </form>
    </div>

    <!-- Veículos do Cliente & Adicionar Novo Veículo -->
    <div class="bg-white dark:bg-[#0f172a] border border-slate-200/80 dark:border-slate-800 rounded-2xl p-6 sm:p-8 shadow-sm">
        <h3 class="text-base font-bold text-slate-900 dark:text-white font-heading mb-4 flex items-center gap-2">
            <i class="fa-solid fa-car text-cyan-600 dark:text-cyan-400"></i> Veículos deste Cliente
        </h3>

        <!-- Lista de Veículos Atuais -->
        <div class="space-y-3 mb-6">
            @forelse($customer->vehicles as $veh)
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800">
                    <div class="flex items-center gap-3">
                        <div class="h-10 w-10 rounded-xl bg-cyan-50 dark:bg-cyan-950 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-800 flex items-center justify-center font-bold text-sm">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 dark:text-white font-mono text-sm block">{{ $veh->plate }}</span>
                            <span class="text-xs text-slate-500 dark:text-slate-400">{{ $veh->brand }} {{ $veh->model }} • Ano: {{ $veh->year ?? '-' }} • Cor: {{ $veh->color ?? '-' }}</span>
                        </div>
                    </div>
                    <a href="{{ route('service_orders.create') }}?customer_id={{ $customer->id }}&vehicle_id={{ $veh->id }}" class="px-3 py-1.5 rounded-lg bg-cyan-50 hover:bg-cyan-100 dark:bg-cyan-950/80 dark:hover:bg-cyan-900 text-cyan-700 dark:text-cyan-300 border border-cyan-200 dark:border-cyan-800 font-semibold text-xs flex items-center gap-1.5 transition">
                        <i class="fa-solid fa-file-circle-plus"></i> Abrir OS
                    </a>
                </div>
            @empty
                <p class="text-xs text-slate-400 dark:text-slate-500 py-4 text-center">Nenhum veículo vinculado a este cliente.</p>
            @endforelse
        </div>

        <!-- Formulário para Adicionar Mais um Veículo -->
        <div class="pt-6 border-t border-slate-100 dark:border-slate-800">
            <h4 class="text-xs font-bold text-cyan-600 dark:text-cyan-400 uppercase tracking-wider mb-4">Adicionar Outro Veículo</h4>
            
            <form action="{{ route('customers.vehicles.store', $customer) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                @csrf
                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 uppercase mb-1">Placa *</label>
                    <input type="text" name="plate" placeholder="ABC1D23" required maxlength="8"
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-cyan-600 dark:text-cyan-400 font-mono font-bold uppercase focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 uppercase mb-1">Marca *</label>
                    <input type="text" name="brand" placeholder="VW, Fiat, Ford..." required
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 uppercase mb-1">Modelo *</label>
                    <input type="text" name="model" placeholder="Ex: Gol 1.6 MSI" required
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 uppercase mb-1">Ano</label>
                    <input type="text" name="year" placeholder="2020"
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-[11px] font-semibold text-slate-600 dark:text-slate-300 uppercase mb-1">Cor</label>
                    <input type="text" name="color" placeholder="Branco, Prata..."
                        class="w-full px-3 py-2 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-xs text-slate-800 dark:text-white focus:outline-none focus:ring-2 focus:ring-cyan-500/20 focus:border-cyan-500">
                </div>

                <div class="sm:col-span-2 flex items-end">
                    <button type="submit" class="w-full py-2 px-4 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-cyan-700 dark:text-cyan-400 border border-slate-200 dark:border-slate-700 font-bold text-xs transition">
                        <i class="fa-solid fa-plus mr-1"></i> Adicionar Veículo
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>
@endsection
