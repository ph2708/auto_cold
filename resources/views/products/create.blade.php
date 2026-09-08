@extends('layouts.app')

@section('title', 'Cadastrar Peça')
@section('header_title', 'Nova Peça / Componente')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8">
        
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-800">
            <div>
                <h3 class="text-base font-bold text-white font-heading">Cadastro de Componente Automotivo</h3>
                <p class="text-xs text-slate-400">Preencha os dados técnicos elétricos, compatibilidade e valores da peça</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form action="{{ route('products.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Identificação Básica -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nome / Descrição da Peça *</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="Ex: Alternador 90A 14V ou Relé Auxiliar 4 Pinos" required
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Código Interno (SKU) *</label>
                    <input type="text" name="sku" value="{{ old('sku') }}" placeholder="Ex: EL-ALT-001" required
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Categoria</label>
                    <select name="category_id" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                        <option value="">Selecione a Categoria</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Marca / Fabricante</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Ex: Bosch, Moura, DNI, Valeo..."
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Fornecedor Padrão</label>
                    <select name="default_supplier_id" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                        <option value="">Selecione o Fornecedor</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('default_supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->corporate_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Código Original / OEM</label>
                    <input type="text" name="oem_code" value="{{ old('oem_code') }}" placeholder="Ex: F000BL0654"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Código de Barras (EAN)</label>
                    <input type="text" name="barcode" value="{{ old('barcode') }}" placeholder="789..."
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Localização Almoxarifado</label>
                    <input type="text" name="location" value="{{ old('location') }}" placeholder="Ex: Gaveta 03, Prateleira B"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <!-- Especificações Específicas de Auto Elétrica -->
            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-bolt"></i> Especificações de Auto Elétrica
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Tensão / Voltagem</label>
                        <select name="voltage" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                            <option value="">N/A ou Geral</option>
                            <option value="12V" {{ old('voltage') === '12V' ? 'selected' : '' }}>12V (Carro Leve / Utilitário)</option>
                            <option value="24V" {{ old('voltage') === '24V' ? 'selected' : '' }}>24V (Caminhão / Pesado)</option>
                            <option value="Bivolt" {{ old('voltage') === 'Bivolt' ? 'selected' : '' }}>Bivolt 12V/24V</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Amperagem / Corrente</label>
                        <input type="text" name="amperage" value="{{ old('amperage') }}" placeholder="Ex: 60Ah, 90A, 40A..."
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Potência / Consumo</label>
                        <input type="text" name="power" value="{{ old('power') }}" placeholder="Ex: 55W, 1.4kW..."
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Pinagem / Conector</label>
                        <input type="text" name="pin_count" value="{{ old('pin_count') }}" placeholder="Ex: 4 pinos, Plug 2 vias"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-4">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Compatibilidade de Veículos</label>
                        <input type="text" name="vehicle_compatibility" value="{{ old('vehicle_compatibility') }}" placeholder="Ex: VW Gol G5/G6 1.0 1.6, Fiat Uno Fire, GM Onix 1.4, Universal..."
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <!-- Estoque e Valores Financeiros -->
            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-coins"></i> Controle de Estoque & Preços
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Unidade</label>
                        <select name="unit" class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                            <option value="UN" {{ old('unit') === 'UN' ? 'selected' : '' }}>UN (Unidade)</option>
                            <option value="PC" {{ old('unit') === 'PC' ? 'selected' : '' }}>PC (Peça)</option>
                            <option value="KT" {{ old('unit') === 'KT' ? 'selected' : '' }}>KT (Kit)</option>
                            <option value="MT" {{ old('unit') === 'MT' ? 'selected' : '' }}>MT (Metro)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Estoque Inicial *</label>
                        <input type="number" step="0.01" name="current_stock" value="{{ old('current_stock', 0) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Estoque Mínimo *</label>
                        <input type="number" step="0.01" name="min_stock" value="{{ old('min_stock', 1) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Preço Custo (R$) *</label>
                        <input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', 0.00) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Preço Venda (R$) *</label>
                        <input type="number" step="0.01" name="selling_price" value="{{ old('selling_price', 0.00) }}" required
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-bold text-emerald-400">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Observações Técnicas / Aplicação Detalhada</label>
                <textarea name="description" rows="3" placeholder="Informações adicionais sobre instalação, esquemas elétricos ou teste de bancada..."
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('description') }}</textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-cyan-500 focus:ring-cyan-500">
                <label for="is_active" class="text-xs font-semibold text-slate-300">Item Ativo para Venda e Aplicação em Serviços</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('products.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-lg shadow-cyan-600/20 transition">
                    Cadastrar Peça
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
