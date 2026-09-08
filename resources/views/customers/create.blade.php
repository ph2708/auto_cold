@extends('layouts.app')

@section('title', 'Novo Cliente')
@section('header_title', 'Cadastrar Cliente & Veículo')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Card de Atalho para Cliente Avulso -->
    <div class="bg-gradient-to-r from-slate-900 via-slate-900/90 to-blue-950/40 border border-slate-800 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-lg">
        <div class="flex items-center gap-3">
            <div class="h-11 w-11 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-xl shrink-0">
                <i class="fa-solid fa-user-clock"></i>
            </div>
            <div>
                <h4 class="text-sm font-bold text-white">Atendimento Rápido sem Cadastro?</h4>
                <p class="text-xs text-slate-400">Use a opção de Cliente Avulso para vendas rápidas ou serviços sem documentação.</p>
            </div>
        </div>
        <form action="{{ route('customers.generic') }}" method="POST">
            @csrf
            <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-cyan-300 font-bold text-xs border border-slate-700 transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-arrow-right"></i> Usar Cliente Avulso
            </button>
        </form>
    </div>

    <!-- Formulário Principal -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8 backdrop-blur-sm">
        
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-800">
            <div>
                <h3 class="text-base font-bold text-white font-heading">Ficha de Cadastro do Cliente</h3>
                <p class="text-xs text-slate-400">Preencha os dados abaixo. O CPF é opcional caso o cliente não queira informar.</p>
            </div>
            <a href="{{ route('customers.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-300 text-xs space-y-1">
                <div class="font-bold flex items-center gap-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-400 text-sm"></i>
                    <span>Atenção:</span>
                </div>
                <ul class="list-disc list-inside text-[11px] text-rose-400">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('customers.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Aba / Bloco de CPF com Validação Instantânea -->
            <div class="p-4 bg-slate-950/80 rounded-2xl border border-slate-800 space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-cyan-400 uppercase tracking-wider flex items-center gap-1.5">
                        <i class="fa-solid fa-id-card"></i> CPF ou CNPJ do Cliente (Opcional)
                    </label>
                    <span class="text-[10px] text-slate-500 font-semibold">Deixe em branco se o cliente não quiser informar</span>
                </div>
                
                <div class="relative">
                    <input type="text" id="document_number_input" name="document_number" value="{{ old('document_number') }}" placeholder="000.000.000-00"
                        class="w-full pl-3.5 pr-10 py-2.5 bg-slate-900 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-mono text-sm"
                        onblur="verificarCpf(this.value)">
                    
                    <!-- Indicador de Status do CPF -->
                    <div id="cpf_status_spinner" class="absolute inset-y-0 right-0 pr-3.5 flex items-center hidden pointer-events-none">
                        <i class="fa-solid fa-circle-notch fa-spin text-cyan-400 text-sm"></i>
                    </div>
                </div>

                <!-- Box de Alerta em tempo real caso o CPF já exista -->
                <div id="cpf_alert_box" class="hidden p-3 rounded-xl bg-amber-950/70 border border-amber-500/40 text-amber-300 text-xs flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-triangle-exclamation text-amber-400 text-sm"></i>
                        <span id="cpf_alert_text">Este CPF já está cadastrado.</span>
                    </div>
                    <a id="cpf_edit_link" href="#" class="px-3 py-1 bg-amber-500/20 hover:bg-amber-500/30 text-amber-300 rounded-lg text-[11px] font-bold border border-amber-500/40 text-center">
                        Abrir Cadastro Existente <i class="fa-solid fa-arrow-up-right-from-square text-[9px]"></i>
                    </a>
                </div>
            </div>

            <!-- Dados de Contato do Cliente -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nome Completo do Cliente / Razão Social *</label>
                    <input type="text" id="customer_name_input" name="name" value="{{ old('name') }}" required placeholder="Ex: Roberto Fernandes da Silva"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">WhatsApp / Celular</label>
                    <input type="text" id="customer_whatsapp_input" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="(64) 99888-7777"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Telefone Secundário / Fixo</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="(64) 3431-2222"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Cidade / UF</label>
                    <input type="text" name="city" value="{{ old('city', 'Itumbiara') }}" placeholder="Itumbiara"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Endereço Residencial / Comercial</label>
                    <input type="text" name="address" value="{{ old('address') }}" placeholder="Rua, Número, Bairro"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <!-- Veículo Inicial (Opcional) -->
            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4 flex items-center gap-1.5">
                    <i class="fa-solid fa-car"></i> Veículo do Cliente (Opcional no primeiro cadastro)
                </h4>
                
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Placa</label>
                        <input type="text" name="plate" value="{{ old('plate') }}" placeholder="BRA2E19" maxlength="8"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-cyan-400 font-mono uppercase focus:outline-none focus:border-cyan-500 font-bold">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Marca</label>
                        <input type="text" name="brand" value="{{ old('brand') }}" placeholder="Ex: VW, Fiat, GM"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Modelo & Versão</label>
                        <input type="text" name="model" value="{{ old('model') }}" placeholder="Ex: Gol 1.6 MSI Flex"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('customers.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white text-xs font-bold shadow-lg shadow-cyan-500/20 transition">
                    Salvar Cadastro
                </button>
            </div>
        </form>

    </div>
</div>

<!-- Script de Verificação Instantânea de CPF -->
<script>
function verificarCpf(cpf) {
    const clean = cpf.replace(/\D/g, '');
    const alertBox = document.getElementById('cpf_alert_box');
    const alertText = document.getElementById('cpf_alert_text');
    const editLink = document.getElementById('cpf_edit_link');
    const spinner = document.getElementById('cpf_status_spinner');

    if (clean.length < 11) {
        alertBox.classList.add('hidden');
        return;
    }

    spinner.classList.remove('hidden');

    fetch(`{{ route('customers.check_cpf') }}?cpf=${encodeURIComponent(cpf)}`)
        .then(res => res.json())
        .then(data => {
            spinner.classList.add('hidden');
            if (data.exists) {
                alertText.innerText = data.message;
                editLink.href = data.edit_url;
                alertBox.classList.remove('hidden');
            } else {
                alertBox.classList.add('hidden');
            }
        })
        .catch(err => {
            spinner.classList.add('hidden');
            console.error(err);
        });
}
</script>
@endsection
