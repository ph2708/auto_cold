<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', $appSettings['company_name'] ?? 'Auto Cold') - {{ $appSettings['company_subtitle'] ?? 'Elétrica e Ar Condicionado' }}</title>
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
                            500: '{{ $appSettings["primary_color"] ?? "#06b6d4" }}',
                            600: '{{ $appSettings["secondary_color"] ?? "#2563eb" }}',
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
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --primary-color: {{ $appSettings['primary_color'] ?? '#06b6d4' }};
            --secondary-color: {{ $appSettings['secondary_color'] ?? '#2563eb' }};
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        h1, h2, h3, .font-heading {
            font-family: 'Outfit', sans-serif;
        }
        .text-custom-primary { color: var(--primary-color); }
        .bg-custom-primary { background-color: var(--primary-color); }
        .border-custom-primary { border-color: var(--primary-color); }
    </style>
</head>
<body class="h-full text-slate-100 bg-slate-950 flex flex-col md:flex-row antialiased">

    <!-- Mobile Top Header com Botão de Menu -->
    <header class="md:hidden h-16 bg-slate-900 border-b border-slate-800 flex items-center justify-between px-4 sticky top-0 z-40">
        <div class="flex items-center gap-3">
            <img src="{{ asset($appSettings['logo_dark'] ?? 'images/logo-dark.png') }}" alt="{{ $appSettings['company_name'] ?? 'Auto Cold' }}" style="height: {{ min($appSettings['logo_height'] ?? 40, 42) }}px" class="w-auto object-contain rounded-lg">
            <span class="font-extrabold text-sm font-heading tracking-wide text-white">{{ $appSettings['company_name'] ?? 'AUTO COLD' }}</span>
        </div>
        <button id="mobile_menu_btn" class="p-2 rounded-xl bg-slate-800 text-slate-200 hover:text-white border border-slate-700">
            <i class="fa-solid fa-bars text-lg"></i>
        </button>
    </header>

    <!-- Sidebar de Navegação (Desktop & Gaveta Mobile) -->
    <aside id="sidebar" class="w-64 bg-slate-900 border-r border-slate-800 flex-col justify-between shrink-0 fixed md:static inset-y-0 left-0 z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-200 ease-in-out flex bg-slate-900/95 md:bg-slate-900 backdrop-blur-md md:backdrop-blur-none">
        <div>
            <!-- Logo & Marca no Desktop -->
            <div class="p-4 border-b border-slate-800/80 bg-slate-950/60 flex items-center justify-between">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                    <img src="{{ asset($appSettings['logo_dark'] ?? 'images/logo-dark.png') }}" alt="{{ $appSettings['company_name'] ?? 'Auto Cold' }}" style="height: {{ $appSettings['logo_height'] ?? 44 }}px" class="w-auto object-contain rounded-lg shadow">
                    <div>
                        <h1 class="text-sm font-black tracking-wider text-white font-heading leading-tight">{{ $appSettings['company_name'] ?? 'AUTO COLD' }}</h1>
                        <p class="text-[10px] text-cyan-300 font-semibold uppercase tracking-wider">{{ $appSettings['company_subtitle'] ?? 'Elétrica & Ar Condicionado' }}</p>
                    </div>
                </a>
                <button id="close_menu_btn" class="md:hidden text-slate-400 hover:text-white p-1">
                    <i class="fa-solid fa-xmark text-lg"></i>
                </button>
            </div>

            <!-- Menu de Navegação com Nomes Simples e Diretos -->
            <nav class="p-4 space-y-1 text-xs">
                <p class="px-3 text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-2">Início</p>
                
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-chart-pie w-5 text-center text-sm text-cyan-400"></i>
                    Visão Geral
                </a>

                <p class="px-3 pt-4 text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-2">Oficina no Dia a Dia</p>
                
                <a href="{{ route('service_orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('service_orders.*') && request('status') !== 'budget' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-clipboard-list w-5 text-center text-sm text-amber-400"></i>
                    Ordens de Serviço (OS)
                </a>

                <a href="{{ route('service_orders.index', ['status' => 'budget']) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request('status') === 'budget' ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-file-invoice-dollar w-5 text-center text-sm text-yellow-400"></i>
                    Orçamentos / Cotações
                </a>

                <a href="{{ route('purchase_orders.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('purchase_orders.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-truck-fast w-5 text-center text-sm text-purple-400"></i>
                    Peças a Chegar (ML / Web)
                </a>

                <a href="{{ route('customers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('customers.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-users w-5 text-center text-sm text-blue-400"></i>
                    Clientes & Veículos
                </a>

                <p class="px-3 pt-4 text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-2">Peças & Fornecedores</p>
                
                <a href="{{ route('products.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('products.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-microchip w-5 text-center text-sm text-cyan-400"></i>
                    Catálogo de Peças
                </a>

                <a href="{{ route('stock.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('stock.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center text-sm text-emerald-400"></i>
                    Entradas e Saídas (Estoque)
                </a>

                <a href="{{ route('suppliers.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('suppliers.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <i class="fa-solid fa-truck-ramp-box w-5 text-center text-sm text-slate-400"></i>
                    Fornecedores
                </a>

                @if(auth()->user()->isManager())
                    <p class="px-3 pt-4 text-[11px] font-bold tracking-wider text-slate-400 uppercase mb-2">Administração</p>
                    
                    <a href="{{ route('settings.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('settings.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-sliders w-5 text-center text-sm text-pink-400"></i>
                        Personalizar Site & Cores
                    </a>

                    <a href="{{ route('users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl font-semibold transition {{ request()->routeIs('users.*') ? 'bg-cyan-500/10 text-cyan-400 border border-cyan-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                        <i class="fa-solid fa-users-gear w-5 text-center text-sm text-slate-400"></i>
                        Usuários & Acessos
                    </a>
                @endif
                
                <div class="pt-4 px-2">
                    <a href="{{ route('landing') }}" target="_blank" class="flex items-center justify-center gap-2 p-2 rounded-xl bg-slate-950 border border-slate-800 text-[11px] text-cyan-300 hover:border-cyan-500 transition">
                        <i class="fa-solid fa-globe"></i> Ver Site da Oficina
                    </a>
                </div>
            </nav>
        </div>

        <!-- Perfil do Usuário & Logout -->
        <div class="p-4 border-t border-slate-800 bg-slate-950/60">
            <div class="flex items-center gap-3 mb-3">
                <div class="h-9 w-9 rounded-full bg-slate-800 border border-slate-700 flex items-center justify-center text-cyan-400 font-bold text-xs">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-white truncate">{{ auth()->user()->name }}</p>
                    <span class="inline-block px-1.5 py-0.5 rounded text-[10px] font-semibold bg-cyan-950 text-cyan-300 border border-cyan-800">
                        {{ auth()->user()->role?->name ?? 'Usuário' }}
                    </span>
                </div>
            </div>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-xl bg-rose-950/40 hover:bg-rose-900/60 text-rose-300 text-xs font-semibold border border-rose-900/50 transition">
                    <i class="fa-solid fa-right-from-bracket"></i> Sair do Sistema
                </button>
            </form>
        </div>
    </aside>

    <!-- Overlay escuro para Mobile -->
    <div id="sidebar_backdrop" class="fixed inset-0 bg-black/60 z-40 hidden md:hidden"></div>

    <!-- Conteúdo Principal Dinâmico -->
    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <!-- Topbar Desktop -->
        <header class="hidden md:flex h-16 bg-slate-900/50 border-b border-slate-800 px-6 items-center justify-between shrink-0">
            <div class="flex items-center gap-3 text-xs text-slate-400">
                <span>Painel de Controle</span>
                <i class="fa-solid fa-chevron-right text-[10px] text-slate-600"></i>
                <span class="text-cyan-400 font-semibold">@yield('title', 'Visão Geral')</span>
            </div>
            
            <div class="flex items-center gap-4">
                <a href="{{ route('service_orders.create') }}" class="px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-bold text-xs shadow-md shadow-cyan-500/20 transition flex items-center gap-1.5">
                    <i class="fa-solid fa-plus text-xs"></i> Nova OS
                </a>
            </div>
        </header>

        <!-- Área de Conteúdo da Página -->
        <div class="p-4 sm:p-6 lg:p-8">
            <!-- Flash Messages de Notificação -->
            @if(session('success'))
                <div class="mb-6 p-4 rounded-xl bg-emerald-950/80 border border-emerald-500/50 text-emerald-300 text-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-check text-emerald-400 text-sm"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-emerald-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 p-4 rounded-xl bg-rose-950/80 border border-rose-500/50 text-rose-300 text-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation text-rose-400 text-sm"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button onclick="this.parentElement.remove()" class="text-rose-400 hover:text-white"><i class="fa-solid fa-xmark"></i></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Script de Gaveta Mobile -->
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
    </script>
</body>
</html>
