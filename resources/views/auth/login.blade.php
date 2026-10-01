<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar no Sistema - Auto Cold</title>
    
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            500: '#06b6d4',
                            600: '#2563eb',
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full flex flex-col items-center justify-center p-6 bg-slate-50 dark:bg-[#090d16] text-slate-800 dark:text-slate-100 transition-colors duration-200 relative">

    <!-- Alternador Dark/Light no topo direito -->
    <div class="absolute top-6 right-6">
        <button onclick="toggleDarkMode()" class="p-2.5 rounded-xl bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 text-slate-600 dark:text-slate-300 hover:text-cyan-500 shadow-xs transition" title="Alternar Modo Escuro / Claro">
            <i class="fa-solid fa-moon hidden dark:inline"></i>
            <i class="fa-solid fa-sun inline dark:hidden text-amber-500"></i>
        </button>
    </div>

    <div class="w-full max-w-md">
        <!-- Logo e Cabeçalho -->
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo-dark.png') }}" alt="Auto Cold - Juliano Ribeiro" class="h-20 w-auto mx-auto object-contain rounded-2xl shadow-md border border-slate-200 dark:border-slate-800 mb-3 bg-white dark:bg-transparent p-1">
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight font-heading">AUTO <span class="text-cyan-600 dark:text-cyan-400">COLD</span></h1>
            <p class="text-xs text-cyan-700 dark:text-cyan-300 font-bold uppercase tracking-wider">Juliano Ribeiro • (64) 99329-5596</p>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 italic mt-1">"Confiança e qualidade: a combinação perfeita para o seu veículo."</p>
        </div>

        <!-- Card de Login -->
        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-slate-800 rounded-3xl p-8 shadow-sm">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-slate-900 dark:text-white font-heading">Acesse sua conta</h2>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Entre com seu e-mail ou nome de usuário e senha</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-50 dark:bg-rose-950/60 border border-rose-200 dark:border-rose-500/30 text-rose-700 dark:text-rose-300 text-xs">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
                        <span>Erro de Autenticação</span>
                    </div>
                    <p class="mt-1">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="login" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Usuário ou E-mail</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Digite seu usuário ou e-mail" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">Senha</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" id="password" name="password" placeholder="••••••••" required
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-white placeholder-slate-400 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center text-slate-600 dark:text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-950 text-cyan-600 focus:ring-cyan-500">
                        <span class="ml-2">Lembrar-me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-semibold text-sm shadow-sm shadow-cyan-600/25 transition duration-200">
                    Acessar Sistema
                </button>
            </form>
        </div>
        
        <div class="text-center mt-6">
            <a href="{{ route('landing') }}" class="text-xs font-medium text-slate-500 dark:text-slate-400 hover:text-cyan-600 dark:hover:text-cyan-400 transition inline-flex items-center gap-1.5">
                <i class="fa-solid fa-arrow-left text-[10px]"></i> Voltar para a Página Pública da Oficina
            </a>
        </div>
    </div>

    <script>
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

