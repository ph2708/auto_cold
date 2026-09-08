@extends('layouts.app')

@section('title', 'Entrada de Estoque')
@section('header_title', 'Registrar Entrada de Peças')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8">
        
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-800">
            <div>
                <h3 class="text-base font-bold text-white font-heading flex items-center gap-2">
                    <span class="h-7 w-7 rounded-lg bg-emerald-500/20 text-emerald-400 flex items-center justify-center text-xs">
                        <i class="fa-solid fa-arrow-down"></i>
                    </span>
                    Entrada de Mercadoria / Compra
                </h3>
                <p class="text-xs text-slate-400">Aumente o saldo em estoque e atualize o custo da peça</p>
            </div>
            <a href="{{ route('stock.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Voltar ao Kardex
            </a>
        </div>

        <form action="{{ route('stock.entry.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Seleção da Peça -->
            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Peça / Componente Elétrico *</label>
                <select name="product_id" id="product_select" required
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    <option value="">Selecione a peça...</option>
                    @foreach($products as $p)
                        <option value="{{ $p->id }}" 
                            data-cost="{{ $p->cost_price }}" 
                            data-stock="{{ $p->current_stock }}"
                            data-unit="{{ $p->unit }}"
                            data-supplier="{{ $p->default_supplier_id }}"
                            {{ old('product_id', request('product_id')) == $p->id ? 'selected' : '' }}>
                            {{ $p->sku }} - {{ $p->name }} (Saldo atual: {{ $p->current_stock }} {{ $p->unit }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <!-- Fornecedor -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Fornecedor / Distribuidor</label>
                    <select name="supplier_id" id="supplier_select"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                        <option value="">Selecione o fornecedor...</option>
                        @foreach($suppliers as $s)
                            <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->corporate_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Motivo da Entrada -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Motivo da Entrada *</label>
                    <select name="reason" required
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                        <option value="purchase" {{ old('reason') === 'purchase' ? 'selected' : '' }}>Compra de Fornecedor</option>
                        <option value="adjustment_in" {{ old('reason') === 'adjustment_in' ? 'selected' : '' }}>Ajuste Positivo de Inventário</option>
                        <option value="return_in" {{ old('reason') === 'return_in' ? 'selected' : '' }}>Devolução de Peça Não Utilizada</option>
                    </select>
                </div>

                <!-- Quantidade -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Quantidade de Entrada *</label>
                    <input type="number" step="0.01" name="quantity" id="quantity" value="{{ old('quantity', 1) }}" required min="0.01"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>

                <!-- Custo Unitário -->
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Custo Unitário (R$) *</label>
                    <input type="number" step="0.01" name="unit_cost" id="unit_cost" value="{{ old('unit_cost', '0.00') }}" required min="0"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                </div>

                <!-- Número da Nota Fiscal ou Pedido -->
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nº Nota Fiscal / Pedido / Comprovante</label>
                    <input type="text" name="document_number" value="{{ old('document_number') }}" placeholder="Ex: NF-104928"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-mono">
                </div>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Observações / Lote</label>
                <textarea name="notes" rows="2" placeholder="Informações do lote, garantia do fornecedor, etc."
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('stock.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-600/20 transition">
                    Confirmar Entrada
                </button>
            </div>
        </form>

    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const prodSelect = document.getElementById('product_select');
        const costInput = document.getElementById('unit_cost');
        const supplierSelect = document.getElementById('supplier_select');

        function updateFields() {
            const selected = prodSelect.options[prodSelect.selectedIndex];
            if (selected && selected.value) {
                const cost = selected.getAttribute('data-cost');
                const supplierId = selected.getAttribute('data-supplier');
                if (cost && (!costInput.value || costInput.value === '0.00' || costInput.value === '0')) {
                    costInput.value = cost;
                }
                if (supplierId && !supplierSelect.value) {
                    supplierSelect.value = supplierId;
                }
            }
        }

        prodSelect.addEventListener('change', updateFields);
        updateFields();
    });
</script>
@endsection
