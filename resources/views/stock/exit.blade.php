@extends('layouts.app')

@section('title', 'Saída de Estoque')
@section('header_title', 'Registrar Baixa / Aplicação de Peça')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8">
        
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-800">
            <div>
                <h3 class="text-base font-bold text-white font-heading flex items-center gap-2">
                    <span class="h-7 w-7 rounded-lg bg-rose-500/20 text-rose-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-arrow-up"></i>
                    </span>
                    Baixa de Estoque / Aplicação em Veículo
                </h3>
                <p class="text-xs text-slate-400">Diminua o saldo em estoque para ordem de serviço, venda ou descarte</p>
            </div>
            <a href="{{ route('stock.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Voltar ao Kardex
            </a>
        </div>

        <form action="{{ route('stock.exit.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Seleção da Peça -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Peça / Componente *</label>
                <select name="product_id" id="product_select" required
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    <option value="">Selecione a peça...</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" 
                            data-price="{{ $p->selling_price }}" 
                            data-stock="{{ $p->current_stock }}"
                            data-unit="{{ $p->unit }}"
                            {{ old('product_id', request('product_id')) == $p->id ? 'selected' : '' }}>
                            {{ $p->sku }} - {{ $p->name }} [Saldo: {{ $p->current_stock }} {{ $p->unit }} | R$ {{ number_format($p->selling_price, 2, ',', '.') }}]
                        </option>
                    @endforeach
                </select>
                <div id="stock_badge" class="mt-2 text-xs hidden">
                    <span class="text-slate-400">Saldo Disponível:</span>
                    <span id="current_stock_display" class="font-bold text-cyan-400 font-mono">0</span>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Motivo da Saída -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Motivo da Saída *</label>
                    <select name="reason" id="reason_select" required
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                        <option value="service_order" {{ old('reason') === 'service_order' ? 'selected' : '' }}>🚗 Aplicação em Ordem de Serviço / Veículo</option>
                        <option value="direct_sale" {{ old('reason') === 'direct_sale' ? 'selected' : '' }}>🛒 Venda Direta no Balcão</option>
                        <option value="internal_use" {{ old('reason') === 'internal_use' ? 'selected' : '' }}>🔧 Uso Interno / Ferramental na Oficina</option>
                        <option value="loss_damage" {{ old('reason') === 'loss_damage' ? 'selected' : '' }}>⚠️ Peça Avariada / Queimada / Garantia</option>
                        <option value="adjustment_out" {{ old('reason') === 'adjustment_out' ? 'selected' : '' }}>📉 Ajuste Negativo de Inventário</option>
                    </select>
                </div>

                <!-- Quantidade -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Quantidade a Baixar *</label>
                    <input type="number" step="0.01" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" required min="0.01"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>

                <!-- Preço Cobrado Unitário -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Preço Cobrado Unitário (R$)</label>
                    <input type="number" step="0.01" name="unit_price" id="unit_price" value="{{ old('unit_price', '0.00') }}" min="0"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
                </div>

                <!-- Placa do Veículo -->
                <div id="plate_container">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Placa do Veículo Atendido</label>
                    <input type="text" name="vehicle_plate" value="{{ old('vehicle_plate') }}" placeholder="Ex: BRA2E19 ou ABC-1234" maxlength="8"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-cyan-400 font-mono uppercase placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <!-- Número da Ordem de Serviço -->
                <div id="os_container">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nº Ordem de Serviço (OS)</label>
                    <input type="text" name="service_order_number" value="{{ old('service_order_number') }}" placeholder="Ex: OS-2026-0045"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Observações / Motivo Detalhado</label>
                <textarea name="notes" rows="2" placeholder="Ex: Cliente relatou falha na partida pela manhã. Peça instalada e testada na bancada..."
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('stock.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-500 text-white text-xs font-bold shadow-lg shadow-rose-600/20 transition">
                    Confirmar Saída / Baixa
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const prodSelect = document.getElementById('product_select');
        const priceInput = document.getElementById('unit_price');
        const badge = document.getElementById('stock_badge');
        const currentStockDisplay = document.getElementById('current_stock_display');

        function updateFields() {
            const selected = prodSelect.options[prodSelect.selectedIndex];
            if (selected && selected.value) {
                const price = selected.getAttribute('data-price');
                const stock = selected.getAttribute('data-stock');
                const unit = selected.getAttribute('data-unit');
                
                if (price && (!priceInput.value || priceInput.value === '0.00' || priceInput.value === '0')) {
                    priceInput.value = price;
                }
                if (stock !== null) {
                    badge.classList.remove('hidden');
                    currentStockDisplay.textContent = `${stock} ${unit}`;
                }
            } else {
                badge.classList.add('hidden');
            }
        }

        prodSelect.addEventListener('change', updateFields);
        updateFields();
    });
</script>
@endsection
