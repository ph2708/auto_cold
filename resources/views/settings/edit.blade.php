@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <!-- Header da Página -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-white font-heading">Identidade Visual & Configurações</h1>
            <p class="text-xs text-slate-400 mt-1">Personalize o logotipo, cores do tema, altura da logo, nome do negócio e dados de contato do site e dashboard.</p>
        </div>
        <div>
            <a href="{{ route('home') }}" target="_blank" class="px-4 py-2 bg-slate-800 hover:bg-slate-700 text-cyan-400 rounded-xl font-bold text-xs border border-slate-700 transition inline-flex items-center gap-2">
                <i class="fa-solid fa-arrow-up-right-from-square"></i> Ver Site ao Vivo
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-lg text-emerald-400"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-300 text-xs space-y-1">
            <div class="font-bold flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation"></i> Verifique os campos abaixo:
            </div>
            <ul class="list-disc list-inside text-[11px] text-rose-400">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            <!-- Coluna 1 & 2: Dados e Cores -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Bloco 1: Informações da Empresa -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 backdrop-blur-sm space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                        <i class="fa-solid fa-building text-cyan-400"></i> Dados da Empresa & Slogan
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nome do Negócio *</label>
                            <input type="text" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" required
                                class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                            <span class="text-[10px] text-slate-500 mt-1 block">Ex: AUTO COLD</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Subtítulo / Especialidade</label>
                            <input type="text" name="company_subtitle" value="{{ old('company_subtitle', $settings['company_subtitle']) }}"
                                class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                            <span class="text-[10px] text-slate-500 mt-1 block">Ex: Elétrica e Ar Condicionado</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Nome do Responsável / Profissional</label>
                            <input type="text" name="owner_name" value="{{ old('owner_name', $settings['owner_name']) }}"
                                class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                            <span class="text-[10px] text-slate-500 mt-1 block">Ex: JULIANO RIBEIRO</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">WhatsApp de Atendimento (DDD + Número)</label>
                            <input type="text" name="whatsapp" value="{{ old('whatsapp', $settings['whatsapp']) }}"
                                class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                            <span class="text-[10px] text-slate-500 mt-1 block">Ex: 64993295596 (apenas números para o link direto)</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Telefone de Contato (Exibição Formatada)</label>
                        <input type="text" name="phone" value="{{ old('phone', $settings['phone']) }}"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                        <span class="text-[10px] text-slate-500 mt-1 block">Ex: (64) 99329-5596</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Slogan / Frase de Efeito da Oficina</label>
                        <textarea name="slogan" rows="2"
                            class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">{{ old('slogan', $settings['slogan']) }}</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Endereço (Rua e Número)</label>
                            <input type="text" name="address" value="{{ old('address', $settings['address']) }}"
                                class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                            <span class="text-[10px] text-slate-500 mt-1 block">Ex: R. Monte Alegre, 271 - Jardim Liberdade</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Cidade, Estado e CEP</label>
                            <input type="text" name="city" value="{{ old('city', $settings['city'] ?? 'Itumbiara - GO, 75510-090') }}"
                                class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500">
                            <span class="text-[10px] text-slate-500 mt-1 block">Ex: Itumbiara - GO, 75510-090</span>
                        </div>
                    </div>

                    <!-- Coordenadas do Google Maps -->
                    <div class="p-4 bg-slate-950 rounded-xl border border-slate-800 space-y-3">
                        <div class="flex items-center justify-between">
                            <h4 class="text-xs font-bold text-cyan-400 uppercase tracking-wider flex items-center gap-1.5">
                                <i class="fa-solid fa-map-location-dot"></i> Coordenadas do Google Maps (Localização Exata)
                            </h4>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Latitude</label>
                                <input type="text" name="latitude" value="{{ old('latitude', $settings['latitude'] ?? '-18.4079971') }}"
                                    class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Longitude</label>
                                <input type="text" name="longitude" value="{{ old('longitude', $settings['longitude'] ?? '-49.2249619') }}"
                                    class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:border-cyan-500 font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-1">Link Completo do Google Maps (para botão "Como Chegar")</label>
                            <input type="text" name="maps_link" value="{{ old('maps_link', $settings['maps_link'] ?? 'https://www.google.com/maps/place/R.+Monte+Alegre,+271+-+Jardim+Liberdade,+Itumbiara+-+GO,+75510-090/@-18.4079971,-49.2256056,238m/data=!3m2!1e3!4b1!4m6!3m5!1s0x94a10d0d1df7616d:0x234bb98b2302d442!8m2!3d-18.4079971!4d-49.2249619!16s%2Fg%2F11wvgpf2r5?entry=ttu') }}"
                                class="w-full px-3 py-2 bg-slate-900 border border-slate-700 rounded-lg text-xs text-white focus:outline-none focus:border-cyan-500">
                        </div>
                    </div>
                </div>

                <!-- Bloco 2: Cores e Temas -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 backdrop-blur-sm space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                        <i class="fa-solid fa-palette text-cyan-400"></i> Cores do Site e Dashboard
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Cor Primária (Destaques & Acentos)</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="primaryColorPicker" value="{{ old('primary_color', $settings['primary_color']) }}"
                                    class="h-10 w-16 bg-slate-950 border border-slate-700 rounded-lg cursor-pointer p-1"
                                    onchange="document.getElementById('primary_color').value = this.value">
                                <input type="text" id="primary_color" name="primary_color" value="{{ old('primary_color', $settings['primary_color']) }}" required
                                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 font-mono"
                                    onchange="document.getElementById('primaryColorPicker').value = this.value">
                            </div>
                            <span class="text-[10px] text-slate-500 mt-1 block">Cor dos ícones, botões de ação e títulos de destaque</span>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Cor Secundária (Gradientes & Botões)</label>
                            <div class="flex items-center gap-3">
                                <input type="color" id="secondaryColorPicker" value="{{ old('secondary_color', $settings['secondary_color']) }}"
                                    class="h-10 w-16 bg-slate-950 border border-slate-700 rounded-lg cursor-pointer p-1"
                                    onchange="document.getElementById('secondary_color').value = this.value">
                                <input type="text" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $settings['secondary_color']) }}" required
                                    class="w-full px-3.5 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white focus:outline-none focus:border-cyan-500 font-mono"
                                    onchange="document.getElementById('secondaryColorPicker').value = this.value">
                            </div>
                            <span class="text-[10px] text-slate-500 mt-1 block">Cor para gradientes e botões secundários</span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Coluna 3: Logotipo & Ajuste de Altura -->
            <div class="space-y-6">

                <!-- Ajuste de Altura da Logo -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 backdrop-blur-sm space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                        <i class="fa-solid fa-arrows-up-down text-cyan-400"></i> Tamanho da Logo
                    </h3>

                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <label class="text-xs font-semibold text-slate-300 uppercase tracking-wider">Altura no Topo/Menu (px)</label>
                            <span id="heightDisplay" class="text-sm font-bold text-cyan-400 font-mono">{{ old('logo_height', $settings['logo_height']) }}px</span>
                        </div>
                        <input type="range" name="logo_height" min="28" max="100" step="2" value="{{ old('logo_height', $settings['logo_height']) }}"
                            class="w-full h-2 bg-slate-950 rounded-lg appearance-none cursor-pointer accent-cyan-500"
                            oninput="document.getElementById('heightDisplay').innerText = this.value + 'px'; document.getElementById('previewDark').style.height = this.value + 'px'; document.getElementById('previewLight').style.height = this.value + 'px';">
                        <div class="flex justify-between text-[10px] text-slate-500 mt-1 font-mono">
                            <span>Compacto (28px)</span>
                            <span>Padrão Topo (48px)</span>
                            <span>Grande (100px)</span>
                        </div>
                        <p class="text-[10px] text-slate-500 mt-2">Ajusta o cabeçalho superior do site e o menu do painel. O banner de contato direto adapta a logo em proporção máxima automaticamente.</p>
                    </div>
                </div>

                <!-- Upload de Logo Escura -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 backdrop-blur-sm space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                        <i class="fa-solid fa-image text-cyan-400"></i> Logo Tema Escuro (Dashboard & Site)
                    </h3>

                    <div class="p-4 bg-slate-950 rounded-xl border border-slate-800 flex flex-col items-center justify-center">
                        <img id="previewDark" src="{{ asset($settings['logo_dark']) }}" alt="Logo Escura Atual" style="height: {{ $settings['logo_height'] }}px" class="w-auto object-contain transition-all duration-200">
                        <span class="text-[10px] text-slate-500 mt-2">Visualização com tamanho ajustado</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Trocar Imagem da Logo Escura</label>
                        <input type="file" name="logo_dark_file" accept="image/*"
                            class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 cursor-pointer">
                        <span class="text-[10px] text-slate-500 mt-1 block">PNG, JPG, SVG ou WEBP até 4MB</span>
                    </div>
                </div>

                <!-- Upload de Logo Clara -->
                <div class="bg-slate-900/80 border border-slate-800 rounded-2xl p-6 backdrop-blur-sm space-y-4">
                    <h3 class="text-base font-bold text-white flex items-center gap-2 border-b border-slate-800 pb-3">
                        <i class="fa-solid fa-file-invoice text-cyan-400"></i> Logo Tema Claro (Impressão de OS)
                    </h3>

                    <div class="p-4 bg-white rounded-xl border border-slate-300 flex flex-col items-center justify-center">
                        <img id="previewLight" src="{{ asset($settings['logo_light']) }}" alt="Logo Clara Atual" style="height: {{ $settings['logo_height'] }}px" class="w-auto object-contain transition-all duration-200">
                        <span class="text-[10px] text-slate-600 mt-2">Visualização em fundo branco</span>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Trocar Imagem da Logo Clara</label>
                        <input type="file" name="logo_light_file" accept="image/*"
                            class="block w-full text-xs text-slate-400 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20 cursor-pointer">
                        <span class="text-[10px] text-slate-500 mt-1 block">Ideal para impressão de orçamentos e relatórios</span>
                    </div>
                </div>

            </div>

        </div>

        <!-- Botão Salvar Fixo / Proeminente -->
        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-800">
            <button type="submit" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold text-sm shadow-xl shadow-cyan-500/25 transition flex items-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Salvar Todas as Configurações
            </button>
        </div>

    </form>

</div>
@endsection
