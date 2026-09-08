@extends('layouts.app')

@section('title', 'Novo Fornecedor')
@section('header_title', 'Cadastrar Fornecedor')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 sm:p-8">
        
        <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-800">
            <div>
                <h3 class="text-base font-bold text-white font-heading">Informações da Empresa / Distribuidor</h3>
                <p class="text-xs text-slate-400">Preencha os dados cadastrais e de contato do fornecedor de autopeças</p>
            </div>
            <a href="{{ route('suppliers.index') }}" class="text-xs text-slate-400 hover:text-white flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left"></i> Voltar
            </a>
        </div>

        <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Razão Social *</label>
                    <input type="text" name="corporate_name" value="{{ old('corporate_name') }}" required
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nome Fantasia</label>
                    <input type="text" name="trade_name" value="{{ old('trade_name') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">CNPJ ou CPF</label>
                    <input type="text" name="document_number" value="{{ old('document_number') }}" placeholder="00.000.000/0000-00"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 font-mono">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Inscrição Estadual (IE)</label>
                    <input type="text" name="state_registration" value="{{ old('state_registration') }}"
                        class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4">Contato & Comunicação</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nome do Contato / Vendedor</label>
                        <input type="text" name="contact_person" value="{{ old('contact_person') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Telefone Fixo</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" placeholder="(11) 3000-0000"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">WhatsApp</label>
                        <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" placeholder="(11) 90000-0000"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-3">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">E-mail Comercial / Vendas</label>
                        <input type="email" name="email" value="{{ old('email') }}" placeholder="vendas@fornecedor.com.br"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800">
                <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider mb-4">Localização & Endereço</h4>
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div class="sm:col-span-1">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">CEP</label>
                        <input type="text" name="zipcode" value="{{ old('zipcode') }}" placeholder="00000-000"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Endereço (Rua/Av)</label>
                        <input type="text" name="address" value="{{ old('address') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-1">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Número</label>
                        <input type="text" name="number" value="{{ old('number') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Bairro</label>
                        <input type="text" name="neighborhood" value="{{ old('neighborhood') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-1">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Cidade</label>
                        <input type="text" name="city" value="{{ old('city') }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">
                    </div>

                    <div class="sm:col-span-1">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">UF</label>
                        <input type="text" name="state" value="{{ old('state') }}" maxlength="2" placeholder="SP"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 uppercase focus:outline-none focus:border-cyan-500">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-800">
                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Observações / Peças Fornecidas</label>
                <textarea name="notes" rows="3" placeholder="Ex: Fornecedor de alternadores, motores de partida, chicotes e conectores elétricos..."
                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-xs text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500">{{ old('notes') }}</textarea>
            </div>

            <div class="flex items-center gap-2 pt-2">
                <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                    class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-cyan-500 focus:ring-cyan-500">
                <label for="is_active" class="text-xs font-semibold text-slate-300">Fornecedor Ativo no Sistema</label>
            </div>

            <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-800">
                <a href="{{ route('suppliers.index') }}" class="px-5 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 text-xs font-semibold transition">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold shadow-lg shadow-cyan-600/20 transition">
                    Salvar Fornecedor
                </button>
            </div>
        </form>

    </div>
</div>
@endsection
