 <!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $appSettings['company_name'] ?? 'Auto Cold' }} - {{ $appSettings['company_subtitle'] ?? 'Elétrica e Ar Condicionado' }} | {{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</title>
    <meta name="description" content="{{ $appSettings['company_name'] ?? 'Auto Cold' }} - {{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}. Especialistas em manutenção de ar condicionado automotivo, alternadores, motores de partida, baterias, injeção eletrônica e diagnósticos elétricos. {{ $appSettings['slogan'] ?? 'Confiança e qualidade: a combinação perfeita para o seu veículo.' }}">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        themePrimary: '{{ $appSettings["primary_color"] ?? "#06b6d4" }}',
                        themeSecondary: '{{ $appSettings["secondary_color"] ?? "#2563eb" }}',
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: {{ $appSettings['primary_color'] ?? '#06b6d4' }};
            --secondary-color: {{ $appSettings['secondary_color'] ?? '#2563eb' }};
        }
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
        .bg-grid {
            background-size: 40px 40px;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                              linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 antialiased selection:bg-cyan-500 selection:text-white">

    <!-- Top Bar Contato Rápido -->
    <div class="bg-slate-900 border-b border-slate-800 text-xs py-2 px-4">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-2 text-slate-400">
            <div class="flex items-center gap-4">
                <span class="flex items-center gap-1.5"><i class="fa-solid fa-wrench text-cyan-400"></i> <strong>{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</strong> - {{ $appSettings['company_subtitle'] ?? 'Auto Elétrica & Ar Condicionado' }}</span>
                <span class="hidden md:flex items-center gap-1.5"><i class="fa-solid fa-clock text-cyan-400"></i> Seg a Sex: 08:00 às 18:00 | Sáb: 08:00 às 12:00</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="https://wa.me/55{{ $appSettings['whatsapp'] ?? '64993295596' }}?text={{ urlencode('Olá, gostaria de solicitar um orçamento.') }}" target="_blank" class="text-emerald-400 hover:text-emerald-300 font-bold flex items-center gap-1.5">
                    <i class="fa-brands fa-whatsapp text-sm"></i> {{ $appSettings['phone'] ?? '(64) 99329-5596' }}
                </a>
                <span class="text-slate-600">|</span>
                <a href="{{ route('login') }}" class="text-cyan-400 hover:underline font-semibold flex items-center gap-1">
                    <i class="fa-solid fa-lock text-[10px]"></i> Acesso ao Sistema
                </a>
            </div>
        </div>
    </div>

    <!-- Header / Navbar Principal -->
    <header class="sticky top-0 z-50 bg-slate-950/90 backdrop-blur-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <img src="{{ asset($appSettings['logo_dark'] ?? 'images/logo-dark.png') }}" alt="{{ $appSettings['company_name'] ?? 'Auto Cold' }}" style="height: {{ $appSettings['logo_height'] ?? 48 }}px" class="w-auto object-contain rounded-xl shadow-lg border border-slate-800">
                <div>
                    <span class="text-xl font-black text-white font-heading tracking-tight block">{{ $appSettings['company_name'] ?? 'AUTO COLD' }}</span>
                    <span class="text-[10px] text-cyan-300 uppercase tracking-widest font-extrabold block">{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }} • {{ $appSettings['company_subtitle'] ?? 'Elétrica e Ar Condicionado' }}</span>
                </div>
            </div>

            <!-- Navegação Desktop -->
            <nav class="hidden md:flex items-center gap-8 text-sm font-semibold text-slate-300">
                <a href="#servicos" class="hover:text-cyan-400 transition">Serviços</a>
                <a href="#ar-condicionado" class="hover:text-cyan-400 transition">Ar Condicionado</a>
                <a href="#eletrica" class="hover:text-cyan-400 transition">Auto Elétrica</a>
                <a href="#localizacao" class="hover:text-cyan-400 transition">Localização</a>
                <a href="#diferenciais" class="hover:text-cyan-400 transition">Diferenciais</a>
                <a href="#contato" class="hover:text-cyan-400 transition">Contato</a>
            </nav>

            <!-- Botão CTA -->
            <div class="flex items-center gap-3">
                <a href="https://wa.me/55{{ $appSettings['whatsapp'] ?? '64993295596' }}?text={{ urlencode('Olá, preciso de um orçamento para meu carro.') }}" target="_blank"
                    class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-500/25 transition flex items-center gap-2">
                    <i class="fa-brands fa-whatsapp text-sm"></i> Falar no WhatsApp
                </a>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-20 lg:py-24 overflow-hidden bg-grid">
        <div class="absolute inset-0 bg-gradient-to-b from-slate-950/80 via-slate-950/95 to-slate-950 pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <div class="lg:col-span-7 space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-cyan-950/80 border border-cyan-800/80 text-cyan-300 text-xs font-bold shadow-sm">
                        <i class="fa-solid fa-bolt text-amber-400"></i> Especialista: {{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}
                    </div>

                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-black text-white font-heading tracking-tight leading-tight">
                        Ar Gelando e Elétrica de Precisão para o seu <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Veículo</span>.
                    </h1>

                    <!-- Slogan Oficial em Destaque -->
                    <div class="p-4 rounded-2xl bg-slate-900/90 border-l-4 border-cyan-400 border border-slate-800 text-slate-200 shadow-xl">
                        <p class="text-base sm:text-lg font-bold tracking-wide text-cyan-300 uppercase font-heading">
                            “{{ $appSettings['slogan'] ?? 'CONFIANÇA E QUALIDADE: A COMBINAÇÃO PERFEITA PARA O SEU VEÍCULO.' }}”
                        </p>
                    </div>

                    <p class="text-sm sm:text-base text-slate-300 max-w-2xl mx-auto lg:mx-0">
                        Diagnósticos elétricos computadorizados, alternadores, motores de partida, recarga ecológica de gás e higienização completa para manter seu carro sempre novo.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4 pt-2">
                        <a href="https://wa.me/55{{ $appSettings['whatsapp'] ?? '64993295596' }}?text={{ urlencode('Olá, gostaria de solicitar um orçamento para meu carro.') }}" target="_blank"
                            class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-black text-sm shadow-xl shadow-emerald-500/25 flex items-center justify-center gap-3 transition transform hover:-translate-y-0.5">
                            <i class="fa-brands fa-whatsapp text-xl"></i> {{ $appSettings['phone'] ?? '(64) 99329-5596' }} • Chamar no WhatsApp
                        </a>
                        <a href="#servicos" class="w-full sm:w-auto px-8 py-4 rounded-2xl bg-slate-900 hover:bg-slate-800 text-slate-200 border border-slate-700 font-bold text-sm transition flex items-center justify-center gap-2">
                            Ver Serviços <i class="fa-solid fa-arrow-down text-xs"></i>
                        </a>
                    </div>

                    <!-- Badges de Confiança -->
                    <div class="grid grid-cols-3 gap-4 pt-6 border-t border-slate-800/80 text-left">
                        <div>
                            <span class="text-2xl font-black text-cyan-400 font-heading block">Garantia</span>
                            <span class="text-xs text-slate-400">Total em peças e serviços</span>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-cyan-400 font-heading block">Qualidade</span>
                            <span class="text-xs text-slate-400">Peças com procedência</span>
                        </div>
                        <div>
                            <span class="text-2xl font-black text-cyan-400 font-heading block">Confiança</span>
                            <span class="text-xs text-slate-400">Atendimento {{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card de Contato Oficial / Banner -->
                <div class="lg:col-span-5 flex justify-center">
                    <div class="relative w-full max-w-md p-6 bg-slate-900/90 rounded-3xl border border-slate-800/90 shadow-2xl shadow-cyan-500/10 backdrop-blur-xl space-y-6">
                        
                        <!-- Logo Auto Cold em destaque proporcional -->
                        <div class="p-4 bg-slate-950/80 rounded-2xl border border-slate-800 flex items-center justify-center">
                            <img src="{{ asset($appSettings['logo_dark'] ?? 'images/logo-dark.png') }}" alt="{{ $appSettings['company_name'] ?? 'Auto Cold' }}" class="w-full max-h-44 object-contain rounded-xl">
                        </div>
                        
                        <!-- Bloco de Contato Direto -->
                        <div class="p-6 rounded-2xl bg-gradient-to-b from-slate-950 to-slate-900/90 border border-slate-800 text-center space-y-3.5">
                            <div class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-cyan-400 font-extrabold">
                                <i class="fa-solid fa-phone-volume"></i> Contato Direto
                            </div>
                            <div class="text-3xl sm:text-4xl font-black text-emerald-400 font-heading tracking-wider">
                                {{ $appSettings['phone'] ?? '(64) 99329-5596' }}
                            </div>
                            <div class="text-sm font-extrabold text-slate-200 uppercase tracking-wider">
                                {{ $appSettings['owner_name'] ?? 'JULIANO RIBEIRO' }}
                            </div>
                            <p class="text-xs text-slate-400 italic pt-1 border-t border-slate-800/80">
                                "{{ $appSettings['slogan'] ?? 'Confiança e qualidade: a combinação perfeita para o seu veículo.' }}"
                            </p>
                            <a href="https://wa.me/55{{ $appSettings['whatsapp'] ?? '64993295596' }}?text={{ urlencode('Olá Juliano, vi o site e gostaria de solicitar um atendimento.') }}" target="_blank"
                                class="w-full py-3.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 text-white font-bold text-sm shadow-lg shadow-emerald-500/25 flex items-center justify-center gap-2 transition">
                                <i class="fa-brands fa-whatsapp text-lg"></i> Iniciar Conversa no WhatsApp
                            </a>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- Seção de Serviços Especializados -->
    <section id="servicos" class="py-20 bg-slate-900/50 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-16 space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-cyan-400">Nossa Especialidade</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-heading">Serviços Especializados na {{ $appSettings['company_name'] ?? 'Auto Cold' }}</h2>
                <p class="text-sm text-slate-400">Cuidamos da parte elétrica e do sistema de climatização do seu carro com equipamentos modernos e atendimento personalizado de {{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                
                <!-- Card 1: Ar Condicionado -->
                <div id="ar-condicionado" class="bg-slate-950 border border-slate-800 hover:border-cyan-500/50 p-8 rounded-3xl transition duration-300 hover:shadow-xl hover:shadow-cyan-500/10 group">
                    <div class="h-14 w-14 rounded-2xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-snowflake"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white font-heading mb-3">Ar Condicionado Automotivo</h3>
                    <ul class="text-xs text-slate-300 space-y-2.5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-cyan-400"></i> Recarga de gás com contraste ecológico</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-cyan-400"></i> Troca do filtro de cabine / pólen</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-cyan-400"></i> Higienização por ozônio e bactericida</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-cyan-400"></i> Reparo e troca de compressor e condensador</li>
                    </ul>
                </div>

                <!-- Card 2: Alternador e Partida -->
                <div id="eletrica" class="bg-slate-950 border border-slate-800 hover:border-amber-500/50 p-8 rounded-3xl transition duration-300 hover:shadow-xl hover:shadow-amber-500/10 group">
                    <div class="h-14 w-14 rounded-2xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-bolt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white font-heading mb-3">Alternador & Motor de Partida</h3>
                    <ul class="text-xs text-slate-300 space-y-2.5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-amber-400"></i> Teste de bancada e análise de carga 12V/24V</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-amber-400"></i> Troca de induzido, escovas e rolamentos</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-amber-400"></i> Substituição de regulador de voltagem</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-amber-400"></i> Reparo em motores de arranque que não giram</li>
                    </ul>
                </div>

                <!-- Card 3: Baterias Automotivas -->
                <div class="bg-slate-950 border border-slate-800 hover:border-blue-500/50 p-8 rounded-3xl transition duration-300 hover:shadow-xl hover:shadow-blue-500/10 group">
                    <div class="h-14 w-14 rounded-2xl bg-blue-500/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-car-battery"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white font-heading mb-3">Baterias & Fuga de Corrente</h3>
                    <ul class="text-xs text-slate-300 space-y-2.5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-blue-400"></i> Venda e instalação de baterias de alta performance</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-blue-400"></i> Teste de condutância e CCA na hora</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-blue-400"></i> Localização e eliminação de fuga de corrente</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-blue-400"></i> Limpeza e proteção dos polos da bateria</li>
                    </ul>
                </div>

                <!-- Card 4: Injeção Eletrônica & Scanner -->
                <div class="bg-slate-950 border border-slate-800 hover:border-purple-500/50 p-8 rounded-3xl transition duration-300 hover:shadow-xl hover:shadow-purple-500/10 group">
                    <div class="h-14 w-14 rounded-2xl bg-purple-500/10 border border-purple-500/20 text-purple-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white font-heading mb-3">Diagnóstico com Scanner</h3>
                    <ul class="text-xs text-slate-300 space-y-2.5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-purple-400"></i> Leitura de falhas da injeção eletrônica</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-purple-400"></i> Apagamento de luzes de alerta do painel</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-purple-400"></i> Teste de sensores, atuadores e sonda lambda</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-purple-400"></i> Reset de parâmetros de adaptação</li>
                    </ul>
                </div>

                <!-- Card 5: Iluminação & Faróis -->
                <div class="bg-slate-950 border border-slate-800 hover:border-yellow-500/50 p-8 rounded-3xl transition duration-300 hover:shadow-xl hover:shadow-yellow-500/10 group">
                    <div class="h-14 w-14 rounded-2xl bg-yellow-500/10 border border-yellow-500/20 text-yellow-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-lightbulb"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white font-heading mb-3">Iluminação & LED</h3>
                    <ul class="text-xs text-slate-300 space-y-2.5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-yellow-400"></i> Instalação de lâmpadas Super LED e Halógenas</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-yellow-400"></i> Alinhamento e regulagem de faróis</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-yellow-400"></i> Faróis de milha / neblina e lanternas</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-yellow-400"></i> Reparo de soquetes, fiação e relés</li>
                    </ul>
                </div>

                <!-- Card 6: Chicotes & Acessórios -->
                <div class="bg-slate-950 border border-slate-800 hover:border-emerald-500/50 p-8 rounded-3xl transition duration-300 hover:shadow-xl hover:shadow-emerald-500/10 group">
                    <div class="h-14 w-14 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-2xl mb-6 group-hover:scale-110 transition duration-300">
                        <i class="fa-solid fa-plug"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white font-heading mb-3">Chicotes & Acessórios</h3>
                    <ul class="text-xs text-slate-300 space-y-2.5">
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Reparação de chicotes elétricos rompidos</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Vidros elétricos e travas automáticas</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Alarmes, bloqueadores e travas</li>
                        <li class="flex items-center gap-2"><i class="fa-solid fa-check text-emerald-400"></i> Troca de botões e comandos do volante</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <!-- Por que Escolher a Auto Cold -->
    <section id="diferenciais" class="py-20 bg-slate-950">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="space-y-6">
                    <span class="text-xs font-extrabold uppercase tracking-widest text-cyan-400">Qualidade Garantida</span>
                    <h2 class="text-3xl sm:text-4xl font-black text-white font-heading">Transparência e Excelência em Cada Diagnóstico</h2>
                    <p class="text-sm text-slate-300 leading-relaxed">
                        Na {{ $appSettings['company_name'] ?? 'Auto Cold' }} você tem a tranquilidade de contar com o trabalho sério de <strong>{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</strong>. Realizamos testes minuciosos com multímetro, osciloscópio e scanner automotivo para solucionar com precisão qualquer defeito elétrico ou de ar condicionado.
                    </p>

                    <div class="space-y-4 pt-2">
                        <div class="flex items-start gap-3">
                            <div class="h-8 w-8 rounded-lg bg-cyan-500/10 text-cyan-400 flex items-center justify-center shrink-0 mt-1">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Fotos e Laudos de Entrada</h4>
                                <p class="text-xs text-slate-400">Registramos fotos antes e depois do serviço para seu total resguardo e confiança.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="h-8 w-8 rounded-lg bg-emerald-500/10 text-emerald-400 flex items-center justify-center shrink-0 mt-1">
                                <i class="fa-brands fa-whatsapp"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Avisos Direto no WhatsApp</h4>
                                <p class="text-xs text-slate-400">Você recebe notificações sobre o orçamento e é avisado no mesmo instante em que seu veículo fica pronto.</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="h-8 w-8 rounded-lg bg-purple-500/10 text-purple-400 flex items-center justify-center shrink-0 mt-1">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div>
                                <h4 class="text-sm font-bold text-white">Garantia e Procedência</h4>
                                <p class="text-xs text-slate-400">Ordem de serviço formal com detalhamento de todas as peças e serviços executados.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-tr from-cyan-950/60 to-slate-900 border border-slate-800 p-8 rounded-3xl text-center space-y-6">
                    <img src="{{ asset($appSettings['logo_light'] ?? 'images/logo-light.png') }}" alt="{{ $appSettings['company_name'] ?? 'Auto Cold' }}" class="max-w-xs mx-auto rounded-2xl shadow-2xl bg-white p-2">
                    <div class="space-y-2">
                        <h3 class="text-2xl font-black text-white font-heading">{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</h3>
                        <p class="text-sm font-extrabold text-cyan-400 uppercase tracking-wide">
                            “{{ $appSettings['slogan'] ?? 'CONFIANÇA E QUALIDADE: A COMBINAÇÃO PERFEITA PARA O SEU VEÍCULO.' }}”
                        </p>
                    </div>
                    <p class="text-xs text-slate-300 max-w-md mx-auto">Precisa de orçamento para elétrica ou ar condicionado? Clique abaixo e fale diretamente no WhatsApp.</p>
                    <a href="https://wa.me/55{{ $appSettings['whatsapp'] ?? '64993295596' }}?text={{ urlencode('Olá, preciso de um diagnóstico para o meu carro.') }}" target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 text-white font-black text-sm shadow-xl shadow-emerald-500/20 transition">
                        <i class="fa-brands fa-whatsapp text-xl"></i> {{ $appSettings['phone'] ?? '(64) 99329-5596' }} • Chamar no WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Localização & Mapa Interativo -->
    <section id="localizacao" class="py-20 bg-slate-900/40 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-12 space-y-3">
                <span class="text-xs font-extrabold uppercase tracking-widest text-cyan-400">Nossa Localização</span>
                <h2 class="text-3xl sm:text-4xl font-black text-white font-heading">Fácil Acesso para Você e seu Carro</h2>
                <p class="text-sm text-slate-400">Venha nos visitar e traga seu veículo para um diagnóstico elétrico ou de climatização completo.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                
                <!-- Card com Endereço e Ações -->
                <div class="lg:col-span-4 bg-slate-950 border border-slate-800 p-8 rounded-3xl flex flex-col justify-between space-y-6">
                    <div class="space-y-4">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-cyan-950/80 border border-cyan-800 text-cyan-300 text-xs font-bold">
                            <i class="fa-solid fa-location-dot text-cyan-400"></i> Oficina Física
                        </div>
                        <h3 class="text-xl font-bold text-white font-heading">{{ $appSettings['company_name'] ?? 'Auto Cold' }}</h3>
                        
                        <div class="space-y-2 text-xs text-slate-300">
                            <p class="font-bold text-white text-sm"><i class="fa-solid fa-map-pin text-cyan-400 mr-2"></i> {{ $appSettings['address'] ?? 'R. Monte Alegre, 271 - Jardim Liberdade' }}</p>
                            <p class="text-slate-400 ml-6">{{ $appSettings['city'] ?? 'Itumbiara - GO, 75510-090' }}</p>
                        </div>

                        <div class="pt-2 border-t border-slate-800/80 space-y-2 text-xs text-slate-300">
                            <p><i class="fa-solid fa-user-check text-cyan-400 mr-2"></i> Responsável: <strong>{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</strong></p>
                            <p><i class="fa-brands fa-whatsapp text-emerald-400 mr-2"></i> WhatsApp: <strong>{{ $appSettings['phone'] ?? '(64) 99329-5596' }}</strong></p>
                            <p><i class="fa-solid fa-clock text-cyan-400 mr-2"></i> Seg a Sex: 08:00 - 18:00 | Sáb: 08:00 - 12:00</p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-slate-800">
                        <a href="{{ $appSettings['maps_link'] ?? 'https://maps.google.com/?q=' . ($appSettings['latitude'] ?? '-18.4079971') . ',' . ($appSettings['longitude'] ?? '-49.2249619') }}" target="_blank"
                            class="w-full py-3.5 px-4 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold text-xs shadow-lg shadow-cyan-500/25 flex items-center justify-center gap-2 transition">
                            <i class="fa-solid fa-diamond-turn-right"></i> Abrir no Google Maps / Como Chegar
                        </a>
                        <a href="https://wa.me/55{{ $appSettings['whatsapp'] ?? '64993295596' }}?text={{ urlencode('Olá, estou a caminho da oficina!') }}" target="_blank"
                            class="w-full py-3 px-4 rounded-xl bg-emerald-500/10 hover:bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 font-bold text-xs flex items-center justify-center gap-2 transition">
                            <i class="fa-brands fa-whatsapp"></i> Avisar que Estou a Caminho
                        </a>
                    </div>
                </div>

                <!-- Iframe do Mapa do Google -->
                <div class="lg:col-span-8 bg-slate-950 border border-slate-800 rounded-3xl overflow-hidden shadow-2xl min-h-[380px] flex items-center justify-center relative">
                    <iframe 
                        width="100%" 
                        height="100%" 
                        style="border:0; min-height: 420px;" 
                        loading="lazy" 
                        allowfullscreen 
                        referrerpolicy="no-referrer-when-downgrade" 
                        src="https://maps.google.com/maps?q={{ $appSettings['latitude'] ?? '-18.4079971' }},{{ $appSettings['longitude'] ?? '-49.2249619' }}&hl=pt-BR&z=17&output=embed">
                    </iframe>
                </div>

            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer id="contato" class="bg-slate-950 border-t border-slate-800/80 py-12 text-xs text-slate-400">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset($appSettings['logo_dark'] ?? 'images/logo-dark.png') }}" alt="{{ $appSettings['company_name'] ?? 'Auto Cold' }}" style="height: {{ min($appSettings['logo_height'] ?? 40, 48) }}px" class="w-auto rounded-lg">
                    <div>
                        <span class="text-base font-black text-white font-heading block">{{ $appSettings['company_name'] ?? 'AUTO COLD' }}</span>
                        <span class="text-[10px] text-slate-400 font-bold block">{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</span>
                    </div>
                </div>
                <p class="text-slate-400 text-xs">Oficina especializada em auto elétrica de precisão e manutenção de ar condicionado automotivo.</p>
                <p class="text-cyan-400 font-semibold text-xs italic">"{{ $appSettings['slogan'] ?? 'Confiança e qualidade: a combinação perfeita para o seu veículo.' }}"</p>
            </div>

            <div class="space-y-2">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider">Contato & Atendimento</h4>
                <p><i class="fa-solid fa-user-gear text-cyan-400 mr-1.5"></i> Responsável: <strong>{{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}</strong></p>
                <p><i class="fa-brands fa-whatsapp text-emerald-400 mr-1.5"></i> <a href="https://wa.me/55{{ $appSettings['whatsapp'] ?? '64993295596' }}" target="_blank" class="text-emerald-400 hover:underline font-bold">{{ $appSettings['phone'] ?? '(64) 99329-5596' }}</a></p>
                <p><i class="fa-solid fa-location-dot text-cyan-400 mr-1.5"></i> {{ $appSettings['address'] ?? 'Rua Automotiva, 120 - Centro' }}</p>
            </div>

            <div class="space-y-2">
                <h4 class="text-sm font-bold text-white uppercase tracking-wider">Horários da Oficina</h4>
                <p>Segunda a Sexta: 08:00 às 18:00</p>
                <p>Sábado: 08:00 às 12:00</p>
                <p class="text-cyan-400 font-semibold pt-2">© {{ date('Y') }} {{ $appSettings['company_name'] ?? 'Auto Cold' }} - {{ $appSettings['owner_name'] ?? 'Juliano Ribeiro' }}. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

</body>
</html>
