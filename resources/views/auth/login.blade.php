<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-slate-950">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Entrar no Sistema - Auto Cold</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-heading { font-family: 'Outfit', sans-serif; }
    </style>
</head>
<body class="h-full flex items-center justify-center p-6 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-900 via-slate-950 to-black">

    <div class="w-full max-w-md">
        <!-- Logo e Cabeçalho -->
        <div class="text-center mb-6">
            <img src="{{ asset('images/logo-dark.png') }}" alt="Auto Cold - Juliano Ribeiro" class="h-20 w-auto mx-auto object-contain rounded-2xl shadow-xl border border-slate-800 mb-3">
            <h1 class="text-2xl font-black text-white tracking-tight font-heading">AUTO <span class="text-cyan-400">COLD</span></h1>
            <p class="text-xs text-cyan-300 font-bold uppercase tracking-wider">Juliano Ribeiro • (64) 99329-5596</p>
            <p class="text-[11px] text-slate-400 italic mt-1">"Confiança e qualidade: a combinação perfeita para o seu veículo."</p>
        </div>

        <!-- Card de Login -->
        <div class="bg-slate-900/90 border border-slate-800 backdrop-blur-xl rounded-2xl p-8 shadow-2xl shadow-black/80">
            <div class="mb-6">
                <h2 class="text-xl font-bold text-white font-heading">Acesse sua conta</h2>
                <p class="text-xs text-slate-400 mt-1">Entre com seu e-mail ou nome de usuário e senha</p>
            </div>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-rose-950/60 border border-rose-500/30 text-rose-300 text-xs">
                    <div class="flex items-center gap-2 font-semibold">
                        <i class="fa-solid fa-triangle-exclamation text-rose-400"></i>
                        <span>Erro de Autenticação</span>
                    </div>
                    <p class="mt-1">{{ $errors->first() }}</p>
                </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label for="login" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Usuário ou E-mail</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-user text-sm"></i>
                        </span>
                        <input type="text" id="login" name="login" value="{{ old('login') }}" placeholder="Digite seu usuário ou e-mail" required autofocus
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 uppercase tracking-wider mb-2">Senha</label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-500">
                            <i class="fa-solid fa-lock text-sm"></i>
                        </span>
                        <input type="password" id="password" name="password" placeholder="••••••••" required
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-950 border border-slate-700 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 transition">
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="flex items-center text-slate-400 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-700 bg-slate-950 text-cyan-500 focus:ring-cyan-500">
                        <span class="ml-2">Lembrar-me</span>
                    </label>
                </div>

                <button type="submit" class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-cyan-500 to-blue-600 hover:from-cyan-400 hover:to-blue-500 text-white font-semibold text-sm shadow-lg shadow-cyan-500/25 transition duration-200">
                    Acessar Sistema
                </button>
            </form>
        </div>
    </div>

</body>
</html>
