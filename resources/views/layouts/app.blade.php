<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $appSettings['company_name'] ?? 'Auto Cold') - {{ $appSettings['company_subtitle'] ?? 'Elétrica e Ar Condicionado' }}</title>
    
    <!-- Script antecipado para evitar flicker do tema -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        customPrimary: '{{ $appSettings["primary_color"] ?? "#06b6d4" }}',
                        customSecondary: '{{ $appSettings["secondary_color"] ?? "#2563eb" }}',
                        brand: {
                            50: '#ecfeff',
                            100: '#cffafe',
                            500: '{{ $appSettings["primary_color"] ?? "#06b6d4" }}',
                            600: '{{ $appSettings["secondary_color"] ?? "#2563eb" }}',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/theme.css') }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --primary-color: {{ $appSettings['primary_color'] ?? '#06b6d4' }};
            --secondary-color: {{ $appSettings['secondary_color'] ?? '#2563eb' }};
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }
        h1, h2, h3, h4, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full bg-slate-50 dark:bg-[#090d16] text-slate-800 dark:text-slate-100 flex flex-col md:flex-row antialiased transition-colors duration-200">

    <!-- Mobile Top Header com Botão de Menu e Alternador de Tema -->
    <header class="md:hidden h-16 bg-white dark:bg-[#0b1120] border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 sticky top-0 z-40 shadow-xs">
        <div class="flex items-center gap-2.5">
            <img src="{{ asset($appSettings['logo_dark'] ?? 'images/logo-dark.png') }}" alt="{{ $appSettings['company_name'] ?? 'Auto Cold' }}" style="height: {{ min($appSettings['logo_height'] ?? 38, 38) }}px" class="w-auto object-contain rounded-lg">
            <span class="font-extrabold text-sm font-heading tracking-tight text-slate-900 dark:text-white">{{ $appSettings['company_name'] ?? 'AUTO COLD' }}</span>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="toggleDarkMode()" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 hover:text-cyan-500 border border-slate-200 dark:border-slate-700 transition" title="Alternar Modo Escuro / Claro">
                <i class="fa-solid fa-moon hidden dark:inline"></i>
                <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
            </button>
            <button id="mobile_menu_btn" class="p-2 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-200 hover:text-cyan-500 border border-slate-200 dark:border-slate-700 transition">
                <i class="fa-solid fa-bars text-lg"></i>
            </button>
        </div>
    </header>

    <!-- Sidebar de Navegação (Desktop & Mobile Drawer) -->
    <aside id="sidebar" class="w-64 bg-white dark:bg-[#0b1120] border-r border-slate-200 dark:border-slate-800/80 flex-col justify-between shrink-0 fixed md:static inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out flex shadow-sm md:shadow-none">
        <div class="flex-1 overflow-y-auto">
            <!-- Logo & Marca no Desktop -->
            <div class="p-4 border-b border-slate-200/80 dark:border-slate-800/80 bg-slate-50/50 dark:bg-slate-950/40 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset($appSettings['logo_dark'] ?? 'images/logo-dark.png') }}" alt="{{ $appSettings['company_name'] ?? 'Auto Cold' }}" style="height: {{ $appSettings['logo_height'] ?? 42 }}px" class="w-auto object-contain rounded-xl shadow-xs">
                    <div>
                        <h1 class="text-sm font-black tracking-tight text-slate-900 dark:text-white font-heading leading-tight">{{ $appSettings['company_name'] ?? 'AUTO COLD' }}</h1>
                        <p class="text-[10px] text-cyan-600 dark:text-cyan-400 font-semibold uppercase tracking-wider">{{ $appSettings['company_subtitle'] ?? 'Elétrica & Ar Condicionado' }}</p>
                    </div>
                </a>
                <button id="close_menu_btn" class="md:hidden text-slate-400 hover:text-slate-600 dark:hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Menu de Navegação -->
            <nav class="p-3.5 space-y-1 text-xs">
                <p class="px-3 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-1.5">Painel Principal</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('dashboard') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-sm {{ request()->routeIs('dashboard') ? 'text-cyan-600 dark:text-cyan-400' : 'text-slate-400 dark:text-slate-500' }}"></i>
                    Visão Geral & Métricas
                </a>

                <p class="px-3 pt-4 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-1.5">Oficina & Atendimento</p>
                
                <a href="{{ route('service_orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('service_orders.*') && request('status') !== 'budget' ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-list w-5 text-center text-sm text-amber-500"></i>
                    Ordens de Serviço (OS)
                </a>

                <a href="{{ route('service_orders.index', ['status' => 'budget']) }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request('status') === 'budget' ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm text-yellow-500"></i>
                    Orçamentos / Cotações
                </a>

                <a href="{{ route('purchase_orders.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('purchase_orders.*') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-truck-fast w-5 text-center text-sm text-purple-500"></i>
                    Peças a Chegar (ML / Web)
                </a>

                <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('customers.*') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center text-sm text-blue-500"></i>
                    Clientes & Veículos
                </a>

                <p class="px-3 pt-4 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-1.5">Almoxarifado & Estoque</p>
                
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('products.*') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-microchip w-5 text-center text-sm text-cyan-500"></i>
                    Catálogo de Peças
                </a>

                <a href="{{ route('stock.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('stock.*') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm text-emerald-500"></i>
                    Movimentações de Estoque
                </a>

                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('suppliers.*') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-truck-ramp-box w-5 text-center text-sm text-slate-400 dark:text-slate-500"></i>
                    Fornecedores
                </a>

                <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('reports.*') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="fa-solid fa-chart-bar w-5 text-center text-sm text-indigo-500"></i>
                    Relatórios & Análises
                </a>

                @if(auth()->user()->isManager())
                    <p class="px-3 pt-4 text-[10px] font-bold tracking-wider text-slate-400 dark:text-slate-500 uppercase mb-1.5">Gerenciamento</p>
                    
                    <a href="{{ route('settings.edit') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('settings.*') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="fa-solid fa-palette w-5 text-center text-sm text-pink-500"></i>
                        Identidade & Cores
                    </a>

                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2 rounded-xl font-medium transition {{ request()->routeIs('users.*') ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 font-bold border border-cyan-200 dark:border-cyan-500/30' : 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800/60 hover:text-slate-900 dark:hover:text-white' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center text-sm text-slate-400 dark:text-slate-500"></i>
                        Equipe & Acessos
                    </a>
                @endif
                
                <div class="pt-3 px-1">
                    <a href="{{ route('landing') }}" target="_blank" class="flex items-center justify-center gap-2 p-2 rounded-xl bg-slate-100 dark:bg-slate-950/60 border border-slate-200 dark:border-slate-800 text-[11px] font-semibold text-slate-600 dark:text-cyan-300 hover:text-cyan-600 dark:hover:text-cyan-200 hover:border-cyan-400 transition">
                        <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i> Ver Site da Oficina
                    </a>
                </div>
            </nav>
        </div>

        <!-- Perfil do Usuário & Logout -->
        <div class="p-3.5 border-t border-slate-200 dark:border-slate-800 bg-slate-50/60 dark:bg-slate-950/40">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-9 w-9 rounded-full bg-cyan-100 dark:bg-slate-800 border border-cyan-300 dark:border-slate-700 flex items-center justify-center text-cyan-700 dark:text-cyan-400 font-bold text-xs shadow-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden flex-1">
                    <p class="text-xs font-bold text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-block px-1.5 py-0.2 rounded text-[10px] font-semibold bg-slate-200 dark:bg-cyan-950 text-slate-700 dark:text-cyan-300 border border-slate-300 dark:border-cyan-800">
                        {{ auth()->user()->role?->name ?? 'Usuário' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-xl bg-rose-50 dark:bg-rose-950/40 hover:bg-rose-100 dark:hover:bg-rose-900/60 text-rose-600 dark:text-rose-300 text-xs font-semibold border border-rose-200 dark:border-rose-900/50 transition">
                    <i class="fa-solid fa-right-from-bracket"></i> Sair do Sistema
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay escuro para Mobile -->
    <div id="sidebar_backdrop" class="fixed inset-0 bg-black/50 backdrop-blur-xs z-40 hidden md:hidden"></div>

    <!-- Conteúdo Principal Dinâmico -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar Desktop com Breadcrumb, Busca/Ações e Alternador de Tema -->
        <header class="hidden md:flex h-16 bg-white/80 dark:bg-[#0b1120]/80 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800 px-6 items-center justify-between shrink-0 sticky top-0 z-30">
            <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400">
                <span class="font-medium text-slate-600 dark:text-slate-300">Auto Cold</span>
                <i class="fa-solid fa-chevron-right text-[9px] text-slate-400 dark:text-slate-600"></i>
                <span class="text-cyan-600 dark:text-cyan-400 font-bold">@yield('title', 'Visão Geral')</span>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Botão Alternador Dark / Light Mode -->
                <button onclick="toggleDarkMode()" class="h-9 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800/80 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 text-xs font-semibold border border-slate-200 dark:border-slate-700 transition flex items-center gap-2 shadow-2xs" title="Alternar entre Tema Claro e Tema Escuro">
                    <span class="dark:hidden flex items-center gap-1.5"><i class="fa-solid fa-moon text-slate-600"></i> Modo Escuro</span>
                    <span class="hidden dark:flex items-center gap-1.5"><i class="fa-solid fa-sun text-amber-400"></i> Modo Claro</span>
                </button>

                <!-- Atalho Rápido de Abertura de OS -->
                <a href="{{ route('service_orders.create') }}" class="h-9 px-4 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-xs shadow-sm shadow-cyan-600/30 transition flex items-center gap-2">
                    <i class="fa-solid fa-plus text-xs"></i> Nova OS
                </a>
            </div>
        </header>

        <!-- Área de Conteúdo da Página -->
        <div class="p-4 sm:p-6 lg:p-8 flex-1">
            <!-- Flash Messages de Notificação Modernizadas -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 border border-emerald-200 dark:border-emerald-500/40 text-emerald-800 dark:text-emerald-200 text-xs flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5 font-medium">
                        <i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-800 dark:hover:text-white p-1"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-2xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-500/40 text-rose-800 dark:text-rose-200 text-xs flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5 font-medium">
                        <i class="fa-solid fa-circle-exclamation text-rose-500 text-sm"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-800 dark:hover:text-white p-1"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('warning'))
                <div class="mb-6 p-4 rounded-2xl bg-amber-50 dark:bg-amber-950/60 border border-amber-200 dark:border-amber-500/40 text-amber-800 dark:text-amber-200 text-xs flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5 font-medium">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 text-sm"></i>
                        <span>{{ session('warning') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-amber-500 hover:text-amber-800 dark:hover:text-white p-1"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 p-4 rounded-2xl bg-sky-50 dark:bg-sky-950/60 border border-sky-200 dark:border-sky-500/40 text-sky-800 dark:text-sky-200 text-xs flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2.5 font-medium">
                        <i class="fa-solid fa-circle-info text-sky-500 text-sm"></i>
                        <span>{{ session('info') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-sky-500 hover:text-sky-800 dark:hover:text-white p-1"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Script de Gaveta Mobile & Dark Mode Switcher -->
    <script>
        const mobileMenuBtn = document.getElementById('mobile_menu_btn');
        const closeMenuBtn = document.getElementById('close_menu_btn');
        const sidebar = document.getElementById('sidebar');
        const backdrop = document.getElementById('sidebar_backdrop');

        function toggleSidebar() {
            const isClosed = sidebar.classList.contains('-translate-x-full');
            if (isClosed) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        if (mobileMenuBtn) mobileMenuBtn.addEventListener('click', toggleSidebar);
        if (closeMenuBtn) closeMenuBtn.addEventListener('click', toggleSidebar);
        if (backdrop) backdrop.addEventListener('click', toggleSidebar);

        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }
    </script>
</body>
</html>

