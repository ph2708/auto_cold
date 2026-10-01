<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('role')->paginate(15);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:30',
            'role_id'  => 'required|exists:roles,id',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $validated['password']  = Hash::make($validated['password']);
        $validated['is_active'] = $request->has('is_active');

        User::create($validated);

        return redirect()->route('users.index')->with('success', 'Usuário criado com sucesso!');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:users,username,' . $user->id,
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone'    => 'nullable|string|max:30',
            'role_id'  => 'required|exists:roles,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $willBeInactive  = !$request->has('is_active');
        $adminRole       = Role::where('slug', 'admin')->first();
        $isCurrentlyAdmin = $adminRole && $user->role_id === $adminRole->id;

        // Proteção: impede desativar o ÚLTIMO administrador ativo
        if ($isCurrentlyAdmin && $willBeInactive) {
            $activeAdminCount = User::where('role_id', $adminRole->id)
                ->where('is_active', true)
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeAdminCount === 0) {
                return back()->withInput()->withErrors([
                    'is_active' => 'Não é possível desativar este usuário pois ele é o único Administrador ativo do sistema. Ative outro administrador primeiro.',
                ]);
            }
        }

        // Proteção: impede rebaixar o ÚLTIMO administrador ativo para outro papel
        $newRole = Role::find($validated['role_id']);
        if ($isCurrentlyAdmin && $newRole && $newRole->slug !== 'admin') {
            $activeAdminCount = User::where('role_id', $adminRole->id)
                ->where('is_active', true)
                ->where('id', '!=', $user->id)
                ->count();

            if ($activeAdminCount === 0) {
                return back()->withInput()->withErrors([
                    'role_id' => 'Não é possível alterar o papel deste usuário pois ele é o único Administrador ativo. Promova outro usuário a Administrador primeiro.',
                ]);
            }
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_active'] = $request->has('is_active');

        $user->update($validated);

        return redirect()->route('users.index')->with('success', 'Usuário atualizado com sucesso!');
    }
}
