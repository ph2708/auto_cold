@extends('layouts.app')

@section('title', 'Editar Pedido a Chegar')
@section('header_title', 'Editar Encomenda ' . $purchaseOrder->order_code)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8">
        
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-800">
            <div>
                <h3 class="text-base font-bold text-white font-heading">Editar: {{ $purchaseOrder->order_code }}</h3>
                <p class="text-xs text-slate-400">Atualize o código de rastreamento, status ou valores da encomenda</p>
            </div>
            <a href="{{ route('purchase_orders.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form action="{{ route('purchase_orders.update', $purchaseOrder) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Origem e Descrição da Peça -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Origem / Canal *</label>
                    <select name="origin" required
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                        <option value="Mercado Livre" {{ old('origin', $purchaseOrder->origin) === 'Mercado Livre' ? 'selected' : '' }}>🟡 Mercado Livre</option>
                        <option value="Shopee" {{ old('origin', $purchaseOrder->origin) === 'Shopee' ? 'selected' : '' }}>🟠 Shopee</option>
                        <option value="Loja Online / Internet" {{ old('origin', $purchaseOrder->origin) === 'Loja Online / Internet' ? 'selected' : '' }}>🌐 Loja Online / Internet</option>
                        <option value="Fornecedor Local" {{ old('origin', $purchaseOrder->origin) === 'Fornecedor Local' ? 'selected' : '' }}>🏬 Fornecedor Local</option>
                        <option value="Distribuidora de Autopeças" {{ old('origin', $purchaseOrder->origin) === 'Distribuidora de Autopeças' ? 'selected' : '' }}>🏭 Distribuidora</option>
                        <option value="Outro" {{ old('origin', $purchaseOrder->origin) === 'Outro' ? 'selected' : '' }}>Outro Canal</option>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nome / Descrição da Peça *</label>
                    <input type="text" name="item_name" value="{{ old('item_name', $purchaseOrder->item_name) }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Vincular a Peça do Catálogo</label>
                    <select name="product_id"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                        <option value="">Não vincular a SKU existente</option>
                        @foreach($products as $prod)
                            <option value="{{ $prod->id }}" {{ old('product_id', $purchaseOrder->product_id) == $prod->id ? 'selected' : '' }}>
                                {{ $prod->sku }} - {{ $prod->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Destinado para a OS</label>
                    <select name="service_order_id"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                        <option value="">Reposição Geral de Estoque</option>
                        @foreach($serviceOrders as $os)
                            <option value="{{ $os->id }}" {{ old('service_order_id', $purchaseOrder->service_order_id) == $os->id ? 'selected' : '' }}>
                                {{ $os->order_number }} ({{ $os->vehicle?->plate }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Rastreamento & Links -->
            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-barcode"></i> Rastreamento & Dados do Pedido
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Código de Rastreamento</label>
                        <input type="text" name="tracking_code" value="{{ old('tracking_code', $purchaseOrder->tracking_code) }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-cyan-400 font-mono font-bold uppercase focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nº Pedido / NF</label>
                        <input type="text" name="external_order_number" value="{{ old('external_order_number', $purchaseOrder->external_order_number) }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white font-mono focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Link Direto de Rastreio</label>
                        <input type="url" name="tracking_url" value="{{ old('tracking_url', $purchaseOrder->tracking_url) }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <!-- Custos, Quantidade e Prazos -->
            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-coins"></i> Valores & Prazos
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Quantidade *</label>
                        <input type="number" step="0.01" name="quantity" value="{{ old('quantity', $purchaseOrder->quantity) }}" required min="0.01"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Custo Unitário (R$) *</label>
                        <input type="number" step="0.01" name="unit_cost" value="{{ old('unit_cost', $purchaseOrder->unit_cost) }}" required min="0"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Valor Frete (R$)</label>
                        <input type="number" step="0.01" name="shipping_cost" value="{{ old('shipping_cost', $purchaseOrder->shipping_cost) }}" min="0"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Status *</label>
                        <select name="status" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                            <option value="pending" {{ old('status', $purchaseOrder->status) === 'pending' ? 'selected' : '' }}>⏳ Aguardando Envio</option>
                            <option value="shipped" {{ old('status', $purchaseOrder->status) === 'shipped' ? 'selected' : '' }}>🚚 Em Trânsito / A Caminho</option>
                            <option value="delivered" {{ old('status', $purchaseOrder->status) === 'delivered' ? 'selected' : '' }}>✅ Recebido na Oficina</option>
                            <option value="cancelled" {{ old('status', $purchaseOrder->status) === 'cancelled' ? 'selected' : '' }}>❌ Cancelado</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Data da Compra *</label>
                        <input type="date" name="purchase_date" value="{{ old('purchase_date', $purchaseOrder->purchase_date?->format('Y-m-d')) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Previsão de Entrega</label>
                        <input type="date" name="expected_delivery_date" value="{{ old('expected_delivery_date', $purchaseOrder->expected_delivery_date?->format('Y-m-d')) }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Observações / Vendedor</label>
                <textarea name="notes" rows="2"
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('notes', $purchaseOrder->notes) }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('purchase_orders.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold shadow-lg shadow-purple-600/20 transition">
                    Salvar Alterações
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
