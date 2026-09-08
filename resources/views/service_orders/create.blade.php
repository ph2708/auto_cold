@extends('layouts.app')

@section('title', 'Nova Entrada / Orçamento')
@section('header_title', 'Nova Entrada de Veículo ou Orçamento')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 sm:p-8 backdrop-blur-md shadow-2xl">
        
        <!-- Cabeçalho -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-6 mb-6 border-b border-slate-800">
            <div>
                <h3 class="text-lg font-bold text-white font-heading flex items-center gap-2">
                    <span class="px-2.5 py-1 rounded bg-cyan-500/20 text-cyan-400 font-mono text-xs font-bold border border-cyan-500/30">
                        {{ $nextNumber }}
                    </span>
                    Nova Entrada de Veículo na Oficina
                </h3>
                <p class="text-xs text-slate-400 mt-1">Preencha diretamente a placa e o cliente. Se já existirem, os dados são puxados na hora.</p>
            </div>
            <a href="{{ route('service_orders.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5 self-start sm:self-center">
                <i class="fa-solid fa-arrow-left"></i> Voltar à Lista
            </a>
        </div>

        <form action="{{ route('service_orders.store') }}" method="POST" class="space-y-6" id="osForm">
            @csrf
            <input type="hidden" name="order_number" value="{{ $nextNumber }}">

            <!-- TIPO DE ENTRADA (Orçamento x Ordem de Serviço) -->
            <div class="p-4 rounded-xl bg-slate-950 border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-4">
                <div>
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Tipo de Documento:</span>
                    <p class="text-xs text-slate-500">Escolha se é apenas uma cotação/orçamento prévio ou serviço aprovado para execução.</p>
                </div>
                <div class="grid grid-cols-2 gap-2 w-full sm:w-auto">
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="budget" id="type_budget" class="peer sr-only" {{ old('status') === 'budget' ? 'checked' : '' }}>
                        <div class="px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900 text-slate-300 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 peer-checked:text-amber-400 text-xs font-bold flex items-center justify-center gap-2 transition">
                            <i class="fa-solid fa-file-invoice-dollar"></i> Orçamento Prévio
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input type="radio" name="status" value="in_progress" id="type_os" class="peer sr-only" {{ old('status', 'in_progress') === 'in_progress' ? 'checked' : '' }}>
                        <div class="px-4 py-2.5 rounded-xl border border-slate-700 bg-slate-900 text-slate-300 peer-checked:border-cyan-500 peer-checked:bg-cyan-500/10 peer-checked:text-cyan-400 text-xs font-bold flex items-center justify-center gap-2 transition">
                            <i class="fa-solid fa-bolt"></i> Ordem de Serviço (OS)
                        </div>
                    </label>
                </div>
            </div>

            <!-- SEÇÃO 1: VEÍCULO -->
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-6 h-6 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xs font-bold">1</span>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Identificação do Veículo</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                    <div>
                        <label class="block text-xs font-semibold text-cyan-400 uppercase tracking-wider mb-2">
                            Placa do Carro *
                        </label>
                        <div class="relative">
                            <input type="text" name="vehicle_plate" id="vehicle_plate" value="{{ old('vehicle_plate') }}" required
                                placeholder="ABC1D23 ou ABC-1234" maxlength="10"
                                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-sm font-mono uppercase font-bold text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                            <div id="plate_loading" class="hidden absolute right-3 top-3 text-cyan-400 text-xs animate-spin">
                                <i class="fa-solid fa-circle-notch"></i>
                            </div>
                        </div>
                        <span id="plate_status" class="text-[11px] text-slate-500 mt-1 block">Digite a placa para preencher</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Modelo / Versão *
                        </label>
                        <input type="text" name="vehicle_model" id="vehicle_model" value="{{ old('vehicle_model') }}" required
                            placeholder="Ex: Gol G5 1.6, Corolla XEi, Hilux..."
                            class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Marca / Montadora
                        </label>
                        <input type="text" name="vehicle_brand" id="vehicle_brand" value="{{ old('vehicle_brand') }}"
                            placeholder="Ex: VW, Toyota, Chevrolet, Fiat..."
                            class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <!-- SEÇÃO 2: CLIENTE / PROPRIETÁRIO -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-2">
                        <span class="w-6 h-6 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xs font-bold">2</span>
                        <h4 class="text-xs font-bold text-white uppercase tracking-wider">Cliente / Proprietário</h4>
                    </div>
                    <button type="button" onclick="preencherClienteAvulso()" class="text-xs text-amber-400 hover:text-amber-300 font-bold flex items-center gap-1.5 px-3 py-1 rounded-lg bg-amber-500/10 border border-amber-500/20 hover:bg-amber-500/20 transition">
                        <i class="fa-solid fa-bolt text-[11px]"></i> Usar Cliente Avulso / Balcão
                    </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                    <div>
                        <label class="block text-xs font-semibold text-cyan-400 uppercase tracking-wider mb-2">
                            Nome do Cliente *
                        </label>
                        <input type="text" name="customer_name" id="customer_name" value="{{ old('customer_name') }}" required
                            placeholder="Nome do proprietário ou condutor"
                            class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            WhatsApp / Celular
                        </label>
                        <input type="text" name="customer_phone" id="customer_phone" value="{{ old('customer_phone') }}"
                            placeholder="(64) 99999-9999"
                            class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                            CPF (Opcional)
                        </label>
                        <input type="text" name="customer_cpf" id="customer_cpf" value="{{ old('customer_cpf') }}"
                            placeholder="000.000.000-00"
                            class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-mono">
                    </div>
                </div>
            </div>

            <!-- SEÇÃO 3: DETALHES DO SERVIÇO & DEFEITO -->
            <div>
                <div class="flex items-center gap-2 mb-3">
                    <span class="w-6 h-6 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center text-xs font-bold">3</span>
                    <h4 class="text-xs font-bold text-white uppercase tracking-wider">Defeito & Responsável Técnico</h4>
                </div>

                <div class="space-y-4 bg-slate-950/60 p-4 rounded-xl border border-slate-800">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Eletricista / Técnico</label>
                            <select name="user_id"
                                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-slate-300 focus:outline-none focus:border-cyan-500">
                                <option value="">Sem técnico definido</option>
                                @foreach($technicians as $tech)
                                    <option value="{{ $tech->id }}" {{ old('user_id', auth()->id()) == $tech->id ? 'selected' : '' }}>
                                        {{ $tech->name }} ({{ $tech->role?->label }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">KM Atual</label>
                            <input type="number" name="entry_km" value="{{ old('entry_km') }}" placeholder="Ex: 85200"
                                class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Data de Entrada *</label>
                            <input type="date" name="entry_date" value="{{ old('entry_date', date('Y-m-d')) }}" required
                                class="w-full px-3 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white focus:outline-none focus:border-cyan-500">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-cyan-400 uppercase tracking-wider mb-2">
                            Sintoma / Reclamação do Cliente (Defeito Relatado) *
                        </label>
                        <textarea name="reported_defect" rows="2" required 
                            placeholder="Ex: Veículo descarregando bateria durante a noite; motor de arranque estalando mas não vira; ar condicionado parou de gelar..."
                            class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('reported_defect') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">
                            Diagnóstico Preliminar / Laudo Técnico (Opcional)
                        </label>
                        <textarea name="technical_diagnosis" rows="2" 
                            placeholder="Ex: Testado alternador (11.8V abaixo do normal 14V). Suspeita de escova gasta ou regulador de voltagem..."
                            class="w-full px-3.5 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('technical_diagnosis') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Botões Finais -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
                <a href="{{ route('service_orders.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-3 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white text-xs font-bold shadow-lg shadow-cyan-600/20 transition flex items-center gap-2">
                    <i class="fa-solid fa-check"></i> Salvar e Abrir Detalhes
                </button>
            </div>
        </form>

    </div>
</div>

<script>
const plateInput = document.getElementById('vehicle_plate');
const plateStatus = document.getElementById('plate_status');
const plateLoading = document.getElementById('plate_loading');
const modelInput = document.getElementById('vehicle_model');
const brandInput = document.getElementById('vehicle_brand');
const customerNameInput = document.getElementById('customer_name');
const customerPhoneInput = document.getElementById('customer_phone');
const customerCpfInput = document.getElementById('customer_cpf');

let timeout = null;

plateInput.addEventListener('input', function() {
    clearTimeout(timeout);
    const val = this.value.trim().toUpperCase();
    this.value = val;

    if (val.length >= 7) {
        timeout = setTimeout(() => buscarPlaca(val), 300);
    }
});

plateInput.addEventListener('blur', function() {
    const val = this.value.trim();
    if (val.length >= 3) {
        buscarPlaca(val);
    }
});

function buscarPlaca(placa) {
    plateLoading.classList.remove('hidden');
    fetch(`{{ route('service_orders.lookup_vehicle') }}?plate=${encodeURIComponent(placa)}`)
        .then(res => res.json())
        .then(data => {
            plateLoading.classList.add('hidden');
            if (data.found) {
                plateStatus.innerHTML = `<span class="text-emerald-400 font-medium"><i class="fa-solid fa-circle-check"></i> Veículo já cadastrado: ${data.brand || ''} ${data.model || ''}</span>`;
                if (data.model) modelInput.value = data.model;
                if (data.brand) brandInput.value = data.brand;
                if (data.customer_name) customerNameInput.value = data.customer_name;
                if (data.customer_phone) customerPhoneInput.value = data.customer_phone;
                if (data.customer_cpf) customerCpfInput.value = data.customer_cpf;
            } else {
                plateStatus.innerHTML = `<span class="text-slate-400">✨ Novo veículo! Preencha os dados ao lado.</span>`;
            }
        })
        .catch(() => {
            plateLoading.classList.add('hidden');
        });
}

function preencherClienteAvulso() {
    customerNameInput.value = 'CLIENTE AVULSO / BALCÃO';
    customerPhoneInput.value = '(00) 0000-0000';
    customerCpfInput.value = '';
}
</script>
@endsection

