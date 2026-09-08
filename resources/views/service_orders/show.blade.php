@extends('layouts.app')

@section('title', 'Gerenciar OS ' . $serviceOrder->order_number)
@section('header_title', 'Ordem de Serviço ' . $serviceOrder->order_number)

@section('content')
<div class="space-y-6">

    <!-- Topo da OS: Cabeçalho com Status e Impressão -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                <span class="text-2xl font-black text-cyan-400 font-mono tracking-tight">{{ $serviceOrder->order_number }}</span>
                <span class="px-3 py-1 rounded-full text-xs font-bold
                    {{ $serviceOrder->status === 'budget' ? 'bg-slate-800 text-slate-300 border border-slate-700' : '' }}
                    {{ $serviceOrder->status === 'approved' ? 'bg-blue-950 text-blue-300 border border-blue-800' : '' }}
                    {{ $serviceOrder->status === 'in_progress' ? 'bg-amber-950 text-amber-300 border border-amber-800' : '' }}
                    {{ $serviceOrder->status === 'waiting_parts' ? 'bg-purple-950 text-purple-300 border border-purple-800' : '' }}
                    {{ $serviceOrder->status === 'completed' ? 'bg-emerald-950 text-emerald-300 border border-emerald-800' : '' }}
                    {{ $serviceOrder->status === 'delivered' ? 'bg-cyan-950 text-cyan-300 border border-cyan-800' : '' }}
                    {{ $serviceOrder->status === 'cancelled' ? 'bg-rose-950 text-rose-300 border border-rose-800' : '' }}
                ">
                    {{ $serviceOrder->status_label }}
                </span>
            </div>
            <p class="text-xs text-slate-400 mt-1">
                Entrada em {{ $serviceOrder->entry_date?->format('d/m/Y') }} • Técnico: <span class="text-slate-200 font-semibold">{{ $serviceOrder->technician?->name ?? 'Não atribuído' }}</span>
            </p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- Botões de Impressão e PDF -->
            <a href="{{ route('service_orders.print_budget', $serviceOrder) }}" target="_blank" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-500 text-white text-xs font-bold flex items-center gap-2 shadow-lg shadow-amber-600/20 transition">
                <i class="fa-solid fa-file-invoice-dollar"></i> Orçamento (PDF / Imprimir)
            </a>

            <a href="{{ route('service_orders.print', $serviceOrder) }}" target="_blank" class="px-4 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white text-xs font-bold flex items-center gap-2 border border-slate-700 transition">
                <i class="fa-solid fa-print"></i> Ordem de Serviço Completa
            </a>

            @if($serviceOrder->status === 'budget')
                <form action="{{ route('service_orders.budget.approve', $serviceOrder) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center gap-2 shadow-lg shadow-emerald-600/20 transition">
                        <i class="fa-solid fa-circle-check"></i> Aprovar Orçamento
                    </button>
                </form>
            @endif

            @if(in_array($serviceOrder->status, ['completed', 'delivered']) && $serviceOrder->customer?->whatsapp)
                @php
                    $cleanZap = preg_replace('/\D/', '', $serviceOrder->customer->whatsapp);
                    if(strlen($cleanZap) <= 11) $cleanZap = '55' . $cleanZap;
                    $msgPronto = "🚗 Olá {$serviceOrder->customer->name}, o seu veículo {$serviceOrder->vehicle?->brand} {$serviceOrder->vehicle?->model} (Placa: {$serviceOrder->vehicle?->plate}) já está com o serviço CONCLUÍDO e pronto para retirada na Auto Cold!\n\n💰 Valor Total: R$ " . number_format($serviceOrder->total_amount, 2, ',', '.') . "\n\nFicamos à disposição para entrega. Obrigado pela preferência!";
                @endphp
                <a href="https://wa.me/{{ $cleanZap }}?text={{ urlencode($msgPronto) }}" target="_blank"
                    class="px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold flex items-center gap-2 shadow-lg shadow-emerald-600/30 transition animate-bounce">
                    <i class="fa-brands fa-whatsapp text-sm"></i> Avisar Cliente que o Carro Está Pronto
                </a>
            @endif

            <a href="{{ route('purchase_orders.create') }}?service_order_id={{ $serviceOrder->id }}" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-500 text-white text-xs font-bold flex items-center gap-2 shadow-lg shadow-purple-600/20 transition">
                <i class="fa-solid fa-cart-shopping"></i> Comprar Peça (ML/Web)
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Coluna Esquerda: Dados do Cliente, Veículo e Diagnóstico -->
        <div class="space-y-6">
            <!-- Card Cliente & Veículo -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 space-y-4">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-user"></i> Cliente & Veículo
                </h4>

                <div class="space-y-2 text-xs">
                    <div>
                        <span class="text-slate-400 block">Cliente:</span>
                        <span class="text-white font-bold text-sm">{{ $serviceOrder->customer?->name }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">WhatsApp / Telefone:</span>
                        @if($serviceOrder->customer?->whatsapp)
                            @php
                                $cleanZap = preg_replace('/\D/', '', $serviceOrder->customer->whatsapp);
                                if(strlen($cleanZap) <= 11) $cleanZap = '55' . $cleanZap;
                                $msgOs = "Olá {$serviceOrder->customer->name}, estamos trabalhando na Ordem de Serviço {$serviceOrder->order_number} do seu veículo ({$serviceOrder->vehicle?->plate}). Status atual: {$serviceOrder->status_label}.";
                            @endphp
                            <a href="https://wa.me/{{ $cleanZap }}?text={{ urlencode($msgOs) }}" target="_blank"
                                class="inline-flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-bold bg-emerald-950/80 hover:bg-emerald-900 px-2.5 py-1 rounded-lg border border-emerald-800 transition mt-1" title="Enviar atualização pelo WhatsApp">
                                <i class="fa-brands fa-whatsapp text-sm"></i>
                                <span>{{ $serviceOrder->customer->whatsapp }} (Enviar Mensagem)</span>
                            </a>
                        @elseif($serviceOrder->customer?->phone)
                            <a href="tel:{{ preg_replace('/\D/', '', $serviceOrder->customer->phone) }}" class="text-cyan-400 font-semibold hover:underline">
                                <i class="fa-solid fa-phone text-[10px] mr-1"></i>{{ $serviceOrder->customer->phone }}
                            </a>
                        @else
                            <span class="text-slate-500">Não informado</span>
                        @endif
                    </div>
                    <div class="pt-2 border-t border-slate-800">
                        <span class="text-slate-400 block">Veículo Atendido:</span>
                        <span class="text-white font-bold">{{ $serviceOrder->vehicle?->brand }} {{ $serviceOrder->vehicle?->model }}</span>
                        <span class="block text-cyan-400 font-mono font-bold mt-0.5">Placa: {{ $serviceOrder->vehicle?->plate }}</span>
                        @if($serviceOrder->entry_km)
                            <span class="text-slate-500 text-[11px] block">KM Entrada: {{ number_format($serviceOrder->entry_km, 0, ',', '.') }} km</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Card Diagnóstico e Defeito -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-5 space-y-4">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider flex items-center gap-1.5">
                    <i class="fa-solid fa-stethoscope"></i> Sintomas & Diagnóstico
                </h4>

                <div class="space-y-3 text-xs">
                    <div class="bg-slate-950 p-3 rounded-xl border border-slate-800">
                        <span class="text-slate-400 font-semibold block text-[11px] uppercase mb-1">Defeito Relatado:</span>
                        <p class="text-slate-200">{{ $serviceOrder->reported_defect }}</p>
                    </div>

                    <form action="{{ route('service_orders.status.update', $serviceOrder) }}" method="POST" class="space-y-3">
                        @csrf
                        @method('PATCH')

                        <div>
                            <label class="text-slate-400 font-semibold block text-[11px] uppercase mb-1">Status Atual da OS:</label>
                            <select name="status" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                                <option value="budget" {{ $serviceOrder->status === 'budget' ? 'selected' : '' }}>📋 Orçamento</option>
                                <option value="approved" {{ $serviceOrder->status === 'approved' ? 'selected' : '' }}>👍 Aprovada pelo Cliente</option>
                                <option value="in_progress" {{ $serviceOrder->status === 'in_progress' ? 'selected' : '' }}>⚡ Em Execução</option>
                                <option value="waiting_parts" {{ $serviceOrder->status === 'waiting_parts' ? 'selected' : '' }}>⏳ Aguardando Peças</option>
                                <option value="completed" {{ $serviceOrder->status === 'completed' ? 'selected' : '' }}>✅ Concluída / Testada</option>
                                <option value="delivered" {{ $serviceOrder->status === 'delivered' ? 'selected' : '' }}>🚗 Entregue / Faturada</option>
                                <option value="cancelled" {{ $serviceOrder->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelada</option>
                            </select>
                        </div>

                        <div>
                            <label class="text-slate-400 font-semibold block text-[11px] uppercase mb-1">Diagnóstico do Eletricista:</label>
                            <textarea name="technical_diagnosis" rows="2" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('technical_diagnosis', $serviceOrder->technical_diagnosis) }}</textarea>
                        </div>

                        <div>
                            <label class="text-slate-400 font-semibold block text-[11px] uppercase mb-1">Solução / Teste de Bancada:</label>
                            <textarea name="solution_applied" rows="2" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('solution_applied', $serviceOrder->solution_applied) }}</textarea>
                        </div>

                        <div>
                            <label class="text-slate-400 font-semibold block text-[11px] uppercase mb-1">Desconto (R$):</label>
                            <input type="number" step="0.01" name="discount" value="{{ old('discount', $serviceOrder->discount) }}"
                                class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                        </div>

                        <button type="submit" class="w-full py-2 bg-slate-800 hover:bg-slate-700 text-cyan-400 font-bold text-xs rounded-xl border border-slate-700 transition">
                            Atualizar Diagnóstico / Status
                        </button>
                    </form>
                </div>
            </div>

            <!-- Card Encomendas Vinculadas a esta OS -->
            @if($serviceOrder->purchaseOrders->count() > 0)
                <div class="bg-slate-900/80 border border-purple-900/50 rounded-2xl p-5 space-y-3">
                    <h4 class="text-xs font-bold text-purple-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-truck-ramp-box"></i> Peças Compradas para esta OS
                    </h4>
                    <div class="space-y-2">
                        @foreach($serviceOrder->purchaseOrders as $po)
                            <div class="p-2.5 rounded-xl bg-slate-950 border border-slate-800 text-xs">
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-white">{{ $po->item_name }}</span>
                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $po->status === 'delivered' ? 'bg-emerald-950 text-emerald-300' : 'bg-purple-950 text-purple-300' }}">
                                        {{ $po->status_label }}
                                    </span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-1 flex items-center justify-between">
                                    <span>{{ $po->origin }}</span>
                                    @if($po->tracking_code)
                                        <span class="font-mono text-cyan-400">{{ $po->tracking_code }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Coluna Direita: Peças Utilizadas (Estoque) + Serviços de Mão de Obra -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- 1. Peças / Componentes Elétricos Utilizados -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-bold text-white font-heading flex items-center gap-2">
                            <i class="fa-solid fa-microchip text-cyan-400"></i> Peças & Materiais Aplicados
                        </h4>
                        <p class="text-xs text-slate-400">Ao adicionar uma peça, o saldo é baixado automaticamente do estoque</p>
                    </div>
                    <span class="text-xs font-bold text-cyan-400">Total Peças: R$ {{ number_format($serviceOrder->products_total, 2, ',', '.') }}</span>
                </div>

                <!-- Lista de Peças na OS / Orçamento -->
                <div class="overflow-x-auto mb-4">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 uppercase text-[10px]">
                                <th class="py-2.5">Peça / Origem</th>
                                <th class="py-2.5 text-center">Qtd</th>
                                <th class="py-2.5 text-right">Valor Unit.</th>
                                <th class="py-2.5 text-right">Subtotal</th>
                                <th class="py-2.5 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($serviceOrder->items as $item)
                                <tr>
                                    <td class="py-2.5">
                                        <span class="font-bold text-slate-200 block">{{ $item->item_name ?? $item->product?->name }}</span>
                                        @if($item->product_id)
                                            <span class="text-[10px] text-cyan-400 font-mono"><i class="fa-solid fa-boxes-stacked mr-1"></i>Estoque: {{ $item->product?->sku }}</span>
                                        @else
                                            <span class="text-[10px] text-amber-400"><i class="fa-solid fa-globe mr-1"></i>Cotação Externa / Peça Cotada</span>
                                        @endif
                                    </td>
                                    <td class="py-2.5 text-center font-bold text-slate-300">
                                        {{ $item->quantity }} {{ $item->unit ?? $item->product?->unit ?? 'UN' }}
                                    </td>
                                    <td class="py-2.5 text-right font-mono text-slate-300">
                                        R$ {{ number_format($item->unit_price, 2, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 text-right font-mono font-bold text-emerald-400">
                                        R$ {{ number_format($item->total_amount, 2, ',', '.') }}
                                    </td>
                                    <td class="py-2.5 text-right">
                                        <form action="{{ route('service_orders.items.destroy', [$serviceOrder, $item]) }}" method="POST" onsubmit="return confirm('Deseja remover este item do {{ $serviceOrder->status === 'budget' ? 'orçamento' : 'serviço' }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-rose-400 hover:bg-rose-500/10" title="Remover item">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-slate-500 italic">
                                        Nenhuma peça ou cotação adicionada ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Formulário para Inserir Peça: Abas de Cotação Externa vs Peça do Estoque -->
                <div class="pt-3 border-t border-slate-800 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Adicionar Peça ao Orçamento / OS:</span>
                        <div class="flex items-center gap-1 text-[11px] bg-slate-950 p-1 rounded-xl border border-slate-800">
                            <button type="button" onclick="setMode('external')" id="btn_tab_external" class="px-3 py-1 rounded-lg font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 transition">
                                🛒 Cotar Peça de Fora (Sem Estoque)
                            </button>
                            <button type="button" onclick="setMode('inventory')" id="btn_tab_inventory" class="px-3 py-1 rounded-lg font-bold text-slate-400 hover:text-white transition">
                                📦 Usar Peça do Estoque Físico
                            </button>
                        </div>
                    </div>

                    <form action="{{ route('service_orders.items.store', $serviceOrder) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-12 gap-2">
                        @csrf
                        <input type="hidden" name="source_type" id="item_source_type" value="external">

                        <!-- Campo 1: Nome da Peça Cotada (Modo Externo) -->
                        <div class="sm:col-span-6" id="field_external_name">
                            <label class="block text-[10px] font-semibold text-amber-400 uppercase mb-1">Nome da Peça Cotada (Mercado Livre / Auto Peças) *</label>
                            <input type="text" name="item_name" id="input_external_name" placeholder="Ex: Bobina de Ignição Gol G5, Regulador Bosch..."
                                class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-amber-500 font-medium">
                        </div>

                        <!-- Campo 1: Selecionar do Estoque (Modo Estoque) -->
                        <div class="sm:col-span-6 hidden" id="field_inventory_select">
                            <label class="block text-[10px] font-semibold text-cyan-400 uppercase mb-1">Selecionar Peça do Estoque Físico *</label>
                            <select name="product_id" id="modal_product_select" class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                                <option value="">Selecione a peça...</option>
                                @foreach($products as $prod)
                                    <option value="{{ $prod->id }}" data-price="{{ $prod->selling_price }}" data-cost="{{ $prod->cost_price }}">
                                        {{ $prod->sku }} - {{ $prod->name }} [Saldo: {{ $prod->current_stock }} {{ $prod->unit }} | R$ {{ number_format($prod->selling_price, 2, ',', '.') }}]
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Quantidade *</label>
                            <input type="number" step="0.01" name="quantity" value="1" placeholder="Qtd" min="0.01" required
                                class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[10px] font-semibold text-emerald-400 uppercase mb-1">Preço Venda (R$) *</label>
                            <input type="number" step="0.01" name="unit_price" id="modal_unit_price" placeholder="0.00" required min="0"
                                class="w-full px-3 py-2 bg-slate-950 border border-emerald-500/50 rounded-xl text-xs text-emerald-400 font-bold focus:outline-none focus:border-emerald-400 font-mono">
                        </div>

                        <div class="sm:col-span-2 flex items-end">
                            <button type="submit" class="w-full py-2 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-white font-bold text-xs rounded-xl shadow transition" id="btn_submit_item">
                                <i class="fa-solid fa-plus mr-1"></i> Inserir
                            </button>
                        </div>
                    </form>
                </div>

                <script>
                function setMode(mode) {
                    const sourceType = document.getElementById('item_source_type');
                    const fieldExternal = document.getElementById('field_external_name');
                    const fieldInventory = document.getElementById('field_inventory_select');
                    const inputExternal = document.getElementById('input_external_name');
                    const selectInventory = document.getElementById('modal_product_select');
                    const btnTabExternal = document.getElementById('btn_tab_external');
                    const btnTabInventory = document.getElementById('btn_tab_inventory');
                    const btnSubmit = document.getElementById('btn_submit_item');

                    sourceType.value = mode;

                    if (mode === 'external') {
                        fieldExternal.classList.remove('hidden');
                        fieldInventory.classList.add('hidden');
                        inputExternal.required = true;
                        selectInventory.required = false;
                        selectInventory.value = '';

                        btnTabExternal.className = 'px-3 py-1 rounded-lg font-bold bg-amber-500/20 text-amber-400 border border-amber-500/30 transition';
                        btnTabInventory.className = 'px-3 py-1 rounded-lg font-bold text-slate-400 hover:text-white transition';
                        btnSubmit.className = 'w-full py-2 bg-gradient-to-r from-amber-600 to-amber-500 hover:from-amber-500 hover:to-amber-400 text-white font-bold text-xs rounded-xl shadow transition';
                    } else {
                        fieldExternal.classList.add('hidden');
                        fieldInventory.classList.remove('hidden');
                        inputExternal.required = false;
                        selectInventory.required = true;

                        btnTabInventory.className = 'px-3 py-1 rounded-lg font-bold bg-cyan-500/20 text-cyan-400 border border-cyan-500/30 transition';
                        btnTabExternal.className = 'px-3 py-1 rounded-lg font-bold text-slate-400 hover:text-white transition';
                        btnSubmit.className = 'w-full py-2 bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-xs rounded-xl shadow transition';
                    }
                }
                </script>
            </div>

            <!-- Resumo Financeiro & Cálculo de Ganho Real da Oficina -->
            <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6 space-y-4">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pb-4 border-b border-slate-800">
                    <div class="flex items-center gap-6 text-xs text-slate-400">
                        <div>
                            <span>Total Peças:</span>
                            <span class="font-bold text-white block text-sm">R$ {{ number_format($serviceOrder->products_total, 2, ',', '.') }}</span>
                        </div>
                        <div>
                            <span>Mão de Obra:</span>
                            <span class="font-bold text-white block text-sm">R$ {{ number_format($serviceOrder->services_total, 2, ',', '.') }}</span>
                        </div>
                        <div>
                            <span>Desconto:</span>
                            <span class="font-bold text-rose-400 block text-sm">- R$ {{ number_format($serviceOrder->discount, 2, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="text-right">
                        <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Valor Total Cobrado do Cliente:</span>
                        <h3 class="text-2xl font-black text-white font-mono">
                            R$ {{ number_format($serviceOrder->total_amount, 2, ',', '.') }}
                        </h3>
                    </div>
                </div>

                <!-- Painel de Lucro / Ganho Líquido da Oficina (DRE Interno) -->
                <div class="p-4 rounded-xl bg-gradient-to-r from-emerald-950/40 via-cyan-950/30 to-slate-900 border border-emerald-500/30 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-xs font-bold text-emerald-400 uppercase tracking-wider flex items-center gap-1.5">
                            <i class="fa-solid fa-calculator"></i> Demonstração de Ganho Líquido da Oficina
                        </span>
                        <p class="text-[11px] text-slate-400 mt-1">
                            Mão de Obra (100% ganho: <strong class="text-slate-200">R$ {{ number_format($serviceOrder->services_total, 2, ',', '.') }}</strong>) + 
                            Margem nas Peças (<strong class="text-slate-200">R$ {{ number_format($serviceOrder->products_profit, 2, ',', '.') }}</strong>) 
                            <span class="text-slate-500">[Custo Real Peças: R$ {{ number_format($serviceOrder->products_cost, 2, ',', '.') }}]</span>
                        </p>
                    </div>

                    <div class="text-right">
                        <span class="text-[10px] text-emerald-400 font-bold uppercase block">Lucro Líquido Real:</span>
                        <span class="text-xl font-black text-emerald-400 font-mono">
                            R$ {{ number_format($serviceOrder->net_profit, 2, ',', '.') }}
                        </span>
                        <span class="text-[10px] text-slate-400 block">Margem: {{ $serviceOrder->profit_margin_percent }}%</span>
                    </div>
                </div>
            </div>

            <!-- 2. Serviços de Mão de Obra -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-sm font-bold text-white font-heading flex items-center gap-2">
                            <i class="fa-solid fa-screwdriver-wrench text-cyan-400"></i> Mão de Obra & Serviços Elétricos
                        </h4>
                        <p class="text-xs text-slate-400">Testes de bancada, reparos de chicote, revisão de alternador/arranque</p>
                    </div>
                    <span class="text-xs font-bold text-cyan-400">Total Serviços: R$ {{ number_format($serviceOrder->services_total, 2, ',', '.') }}</span>
                </div>

                <!-- Lista de Serviços -->
                <div class="overflow-x-auto mb-4">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="border-b border-slate-800 text-slate-400 uppercase text-[10px]">
                                <th class="py-2.5">Descrição do Serviço</th>
                                <th class="py-2.5 text-center">Horas / Qtd</th>
                                <th class="py-2.5 text-right">Valor</th>
                                <th class="py-2.5 text-right">Subtotal</th>
                                <th class="py-2.5 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/60">
                            @forelse($serviceOrder->services as $serv)
                                <tr>
                                    <td class="py-2.5 font-bold text-slate-200">{{ $serv->description }}</td>
                                    <td class="py-2.5 text-center text-slate-300">{{ $serv->quantity }}</td>
                                    <td class="py-2.5 text-right font-mono text-slate-300">R$ {{ number_format($serv->unit_price, 2, ',', '.') }}</td>
                                    <td class="py-2.5 text-right font-mono font-bold text-emerald-400">R$ {{ number_format($serv->total_amount, 2, ',', '.') }}</td>
                                    <td class="py-2.5 text-right">
                                        <form action="{{ route('service_orders.services.destroy', [$serviceOrder, $serv]) }}" method="POST" onsubmit="return confirm('Deseja remover este serviço?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 rounded text-rose-400 hover:bg-rose-500/10">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-4 text-center text-slate-500 italic">
                                        Nenhum serviço de mão de obra lançado.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Formulário para Adicionar Serviço -->
                <form action="{{ route('service_orders.services.store', $serviceOrder) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-5 gap-2 pt-3 border-t border-slate-800">
                    @csrf
                    <div class="sm:col-span-2">
                        <input type="text" name="description" placeholder="Ex: Revisão de Alternador na Bancada..." required
                            class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <input type="number" step="0.5" name="quantity" value="1" placeholder="Horas/Qtd" min="0.5" required
                            class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <input type="number" step="0.01" name="unit_price" placeholder="Valor (R$)" required min="0"
                            class="w-full px-3 py-2 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <button type="submit" class="w-full py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs rounded-xl shadow transition">
                            <i class="fa-solid fa-plus mr-1"></i> Incluir Mão de Obra
                        </button>
                    </div>
                </form>
            </div>

            <!-- Resumo Financeiro da OS -->
            <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-6 text-xs text-slate-400">
                    <div>
                        <span>Peças:</span>
                        <span class="font-bold text-white block">R$ {{ number_format($serviceOrder->products_total, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <span>Mão de Obra:</span>
                        <span class="font-bold text-white block">R$ {{ number_format($serviceOrder->services_total, 2, ',', '.') }}</span>
                    </div>
                    <div>
                        <span>Desconto:</span>
                        <span class="font-bold text-rose-400 block">- R$ {{ number_format($serviceOrder->discount, 2, ',', '.') }}</span>
                    </div>
                </div>

                <div class="text-right">
                    <span class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Valor Total da OS:</span>
                    <h3 class="text-2xl font-black text-emerald-400 font-mono">
                        R$ {{ number_format($serviceOrder->total_amount, 2, ',', '.') }}
                    </h3>
                </div>
            </div>

            <!-- 3. Registro Fotográfico de Resguardo do Mecânico / Eletricista (Antes & Depois) -->
            <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div>
                        <h4 class="text-sm font-bold text-white font-heading flex items-center gap-2">
                            <i class="fa-solid fa-camera-retro text-cyan-400"></i> Fotos do Veículo & Checklist Visual (Antes / Diagnóstico / Depois)
                        </h4>
                        <p class="text-xs text-slate-400">Resguardo técnico: registre avarias prévias de lataria, fiação queimada, luzes acesas no painel e peças testadas</p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-800 text-slate-300 border border-slate-700 w-fit">
                        {{ $serviceOrder->photos->count() }} fotos anexadas
                    </span>
                </div>

                <!-- Formulário de Upload de Foto -->
                <form action="{{ route('service_orders.photos.store', $serviceOrder) }}" method="POST" enctype="multipart/form-data"
                    class="bg-slate-950 p-4 rounded-xl border border-slate-800 space-y-3">
                    @csrf
                    <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-300 uppercase mb-1">Etapa da Foto *</label>
                            <select name="stage" required
                                class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-200 focus:outline-none focus:border-cyan-500">
                                <option value="before">📸 ANTES (Entrada / Estado do Carro / Painel)</option>
                                <option value="diagnostic">🔬 DIAGNÓSTICO (Peça Danificada / Fiação Queimada)</option>
                                <option value="after">✨ DEPOIS (Serviço Concluído / Peça Nova Instalada)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] font-semibold text-slate-300 uppercase mb-1">Título / Identificação</label>
                            <input type="text" name="title" placeholder="Ex: Arranhão porta, Painel 12V..."
                                class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-[11px] font-semibold text-slate-300 uppercase mb-1">Selecionar Imagem(ns) do Celular / PC *</label>
                            <input type="file" name="photos[]" multiple required accept="image/*"
                                class="w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-cyan-600 file:text-white hover:file:bg-cyan-500 cursor-pointer">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <input type="text" name="description" placeholder="Observação adicional da foto (ex: cliente ciente de trinco no farol esquerdo)..."
                            class="flex-1 mr-3 px-3 py-1.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                        <button type="submit" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs rounded-xl shadow transition shrink-0 flex items-center gap-1.5">
                            <i class="fa-solid fa-cloud-arrow-up"></i> Anexar Foto(s)
                        </button>
                    </div>
                </form>

                <!-- Galeria de Fotos Agrupada por Etapa -->
                <div class="space-y-6">
                    
                    <!-- 1. ANTES (Entrada / Avarias Iniciais) -->
                    <div>
                        <h5 class="text-xs font-bold text-amber-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-camera"></i> 1. Antes da Manutenção (Entrada / Lataria / Painel)
                        </h5>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @forelse($serviceOrder->beforePhotos as $photo)
                                <div class="bg-slate-950 border border-slate-800 rounded-xl overflow-hidden group relative">
                                    <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank" class="block aspect-video bg-slate-900 overflow-hidden">
                                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </a>
                                    <div class="p-2">
                                        <span class="font-bold text-slate-200 block text-xs truncate">{{ $photo->title ?? 'Foto de Entrada' }}</span>
                                        @if($photo->description)
                                            <p class="text-[10px] text-slate-400 truncate">{{ $photo->description }}</p>
                                        @endif
                                        <span class="text-[9px] text-slate-500 block mt-0.5">{{ $photo->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    <form action="{{ route('service_orders.photos.destroy', [$serviceOrder, $photo]) }}" method="POST" onsubmit="return confirm('Deseja excluir esta foto?');" class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded bg-rose-600/90 text-white text-[10px]" title="Excluir Foto">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="col-span-4 p-3 rounded-xl bg-slate-950/40 border border-slate-800/60 text-center text-slate-500 text-xs italic">
                                    Nenhuma foto de entrada anexada.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 2. DIAGNÓSTICO (Durante / Peças Queimadas) -->
                    <div>
                        <h5 class="text-xs font-bold text-purple-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-microchip"></i> 2. Diagnóstico Técnico (Componente Danificado / Testes)
                        </h5>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @forelse($serviceOrder->diagnosticPhotos as $photo)
                                <div class="bg-slate-950 border border-slate-800 rounded-xl overflow-hidden group relative">
                                    <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank" class="block aspect-video bg-slate-900 overflow-hidden">
                                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </a>
                                    <div class="p-2">
                                        <span class="font-bold text-slate-200 block text-xs truncate">{{ $photo->title ?? 'Foto Diagnóstico' }}</span>
                                        @if($photo->description)
                                            <p class="text-[10px] text-slate-400 truncate">{{ $photo->description }}</p>
                                        @endif
                                        <span class="text-[9px] text-slate-500 block mt-0.5">{{ $photo->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    <form action="{{ route('service_orders.photos.destroy', [$serviceOrder, $photo]) }}" method="POST" onsubmit="return confirm('Deseja excluir esta foto?');" class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded bg-rose-600/90 text-white text-[10px]">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="col-span-4 p-3 rounded-xl bg-slate-950/40 border border-slate-800/60 text-center text-slate-500 text-xs italic">
                                    Nenhuma foto de diagnóstico anexada.
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- 3. DEPOIS (Serviço Finalizado / Peça Nova) -->
                    <div>
                        <h5 class="text-xs font-bold text-emerald-400 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check"></i> 3. Depois do Reparo (Peça Nova Instalada / Teste Aprovado)
                        </h5>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @forelse($serviceOrder->afterPhotos as $photo)
                                <div class="bg-slate-950 border border-slate-800 rounded-xl overflow-hidden group relative">
                                    <a href="{{ asset('storage/' . $photo->file_path) }}" target="_blank" class="block aspect-video bg-slate-900 overflow-hidden">
                                        <img src="{{ asset('storage/' . $photo->file_path) }}" alt="{{ $photo->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </a>
                                    <div class="p-2">
                                        <span class="font-bold text-slate-200 block text-xs truncate">{{ $photo->title ?? 'Foto Concluído' }}</span>
                                        @if($photo->description)
                                            <p class="text-[10px] text-slate-400 truncate">{{ $photo->description }}</p>
                                        @endif
                                        <span class="text-[9px] text-slate-500 block mt-0.5">{{ $photo->created_at->format('d/m H:i') }}</span>
                                    </div>
                                    <form action="{{ route('service_orders.photos.destroy', [$serviceOrder, $photo]) }}" method="POST" onsubmit="return confirm('Deseja excluir esta foto?');" class="absolute top-1.5 right-1.5 opacity-0 group-hover:opacity-100 transition">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1 rounded bg-rose-600/90 text-white text-[10px]">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            @empty
                                <div class="col-span-4 p-3 rounded-xl bg-slate-950/40 border border-slate-800/60 text-center text-slate-500 text-xs italic">
                                    Nenhuma foto de conclusão anexada.
                                </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>

        </div>

    </div>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const prodSelect = document.getElementById('modal_product_select');
        const priceInput = document.getElementById('modal_unit_price');

        if (prodSelect && priceInput) {
            prodSelect.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (selected && selected.value) {
                    const price = selected.getAttribute('data-price');
                    if (price) {
                        priceInput.value = price;
                    }
                }
            });
        }
    });
</script>
@endsection
