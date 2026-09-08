@extends('layouts.app')

@section('title', 'Usuários & Acessos')
@section('header_title', 'Controle de Acessos & Usuários')

@section('content')
<div class="space-y-6">

    <!-- Barra de Ações -->
    <div class="flex items-center justify-between bg-slate-900/80 border border-slate-800 p-4 rounded-2xl">
        <div>
            <h3 class="text-sm font-bold text-white">Equipe da Oficina & Níveis de Acesso</h3>
            <p class="text-xs text-slate-400">Gerencie usuários, funções (Administrador, Gerente, Eletricista, Estoquista) e senhas</p>
        </div>

        <a href="{{ route('users.create') }}" class="bg-cyan-600 hover:bg-cyan-500 text-white text-xs font-bold px-4 py-2.5 rounded-xl shadow-lg shadow-cyan-600/20 flex items-center gap-2 transition">
            <i class="fa-solid fa-user-plus"></i> Novo Usuário
        </a>
    </div>

    <!-- Tabela de Usuários -->
    <div class="bg-slate-900/80 border border-slate-800 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead>
                    <tr class="border-b border-slate-800 bg-slate-950/40 text-slate-400 uppercase text-[10px] tracking-wider">
                        <th class="py-3.5 px-4">Nome do Profissional</th>
                        <th class="py-3.5 px-4">Usuário de Acesso</th>
                        <th class="py-3.5 px-4">E-mail & Telefone</th>
                        <th class="py-3.5 px-4">Papel / Função (ACL)</th>
                        <th class="py-3.5 px-4 text-center">Status</th>
                        <th class="py-3.5 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/60">
                    @forelse($users as $u)
                        <tr class="hover:bg-slate-800/30 transition">
                            <td class="py-3.5 px-4">
                                <span class="font-bold text-slate-200 block text-sm">{{ $u->name }}</span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-cyan-400">
                                {{ $u->username ?? '-' }}
                            </td>
                            <td class="py-3.5 px-4 text-slate-300">
                                <div>{{ $u->email }}</div>
                                @if($u->phone)
                                    <div class="text-[11px] text-slate-500">{{ $u->phone }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-block px-2.5 py-1 rounded-full text-xs font-semibold 
                                    {{ $u->role?->name === 'admin' ? 'bg-purple-950 text-purple-300 border border-purple-800' : '' }}
                                    {{ $u->role?->name === 'manager' ? 'bg-blue-950 text-blue-300 border border-blue-800' : '' }}
                                    {{ $u->role?->name === 'electrician' ? 'bg-amber-950 text-amber-300 border border-amber-800' : '' }}
                                    {{ $u->role?->name === 'stockist' ? 'bg-emerald-950 text-emerald-300 border border-emerald-800' : '' }}
                                ">
                                    {{ $u->role?->label ?? 'Sem Perfil' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($u->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-emerald-950 text-emerald-300 border border-emerald-800">
                                        Ativo
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-medium bg-rose-950 text-rose-300 border border-rose-800">
                                        Inativo
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('users.edit', $u) }}" class="p-1.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-300 hover:text-white transition" title="Editar Usuário / Senha">
                                    <i class="fa-solid fa-user-pen"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-500">
                                Nenhum usuário cadastrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
