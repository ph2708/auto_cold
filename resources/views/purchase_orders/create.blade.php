@extends('layouts.app')

@section('title', 'Novo Pedido a Chegar')
@section('header_title', 'Registrar Peça Comprada / Encomenda')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Card Principal -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8 backdrop-blur-sm">
        
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-800">
            <div>
                <h3 class="text-base font-bold text-white font-heading flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded bg-purple-500/20 text-purple-400 font-mono text-xs font-bold border border-purple-500/30">
                        {{ $nextCode }}
                    </span>
                    Registrar Compra / Peça a Chegar
                </h3>
                <p class="text-xs text-slate-400">Apenas digite o nome da peça e o valor para rastrear a chegada.</p>
            </div>
            <a href="{{ route('purchase_orders.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form action="{{ route('purchase_orders.store') }}" method="POST" class="space-y-6">
            @csrf
            <input type="hidden" name="order_code" value="{{ $nextCode }}">

            <!-- 1. Nome da Peça e Onde Comprou -->
            <div class="space-y-4">
                <div>
                    <label class="block text-xs font-bold text-cyan-400 uppercase tracking-wider mb-2">
                        <i class="fa-solid fa-box-open mr-1"></i> O Que Foi Comprado? (Nome / Descrição da Peça) *
                    </label>
                    <input type="text" name="item_name" value="{{ old('item_name') }}" required 
                        placeholder="Ex: Módulo de Injeção Bosch ME7.5, Alternador 90A, Compressor Denso..."
                        class="w-full px-4 py-3 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-medium">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Onde Comprou? (Origem) *</label>
                        <select name="origin" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                            <option value="Mercado Livre" {{ old('origin') === 'Mercado Livre' ? 'selected' : '' }}>🟡 Mercado Livre</option>
                            <option value="Shopee" {{ old('origin') === 'Shopee' ? 'selected' : '' }}>🟠 Shopee</option>
                            <option value="Distribuidora Local" {{ old('origin') === 'Distribuidora Local' ? 'selected' : '' }}>🏬 Distribuidora Local / Balcão</option>
                            <option value="Loja Online / Internet" {{ old('origin') === 'Loja Online / Internet' ? 'selected' : '' }}>🌐 Loja Online / Internet</option>
                            <option value="Outro" {{ old('origin') === 'Outro' ? 'selected' : '' }}>Outro Canal</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">É Para Algum Carro / OS Específico?</label>
                        <select name="service_order_id"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                            <option value="">Uso Geral / Reposição de Estoque</option>
                            @foreach($serviceOrders as $os)
                                <option value="{{ $os->id }}" {{ old('service_order_id', request('service_order_id')) == $os->id ? 'selected' : '' }}>
                                    {{ $os->order_number }} • {{ $os->vehicle?->plate }} ({{ $os->vehicle?->model }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- 2. Valores e Quantidade -->
            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-brazilian-real-sign"></i> Valores & Quantidade
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Quantidade *</label>
                        <input type="number" step="0.01" name="quantity" value="{{ old('quantity', 1) }}" required min="0.01"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Valor Pago na Peça (R$) *</label>
                        <input type="number" step="0.01" name="unit_cost" value="{{ old('unit_cost', 0.00) }}" required min="0" placeholder="0.00"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Frete (R$) (Se houver)</label>
                        <input type="number" step="0.01" name="shipping_cost" value="{{ old('shipping_cost', 0.00) }}" min="0" placeholder="0.00"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
                    </div>
                </div>
            </div>

            <!-- 3. Rastreamento e Previsão -->
            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-truck-fast"></i> Rastreamento & Entrega
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Código de Rastreio (Opcional)</label>
                        <input type="text" name="tracking_code" value="{{ old('tracking_code') }}" placeholder="Ex: NL123456789BR"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-cyan-400 font-mono uppercase focus:outline-none focus:border-cyan-500 font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Status do Pedido *</label>
                        <select name="status" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                            <option value="shipped" {{ old('status', 'shipped') === 'shipped' ? 'selected' : '' }}>🚚 A Caminho / Em Trânsito</option>
                            <option value="pending" {{ old('status') === 'pending' ? 'selected' : '' }}>⏳ Comprado (Aguardando Envio)</option>
                            <option value="delivered" {{ old('status') === 'delivered' ? 'selected' : '' }}>✅ Já Chegou na Oficina</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Previsão de Chegada</label>
                        <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Data da Compra *</label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date', date('Y-m-d')) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('purchase_orders.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-500 hover:to-indigo-500 text-white text-xs font-bold shadow-lg shadow-purple-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-truck-ramp-box"></i> Registrar Peça a Chegar
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
