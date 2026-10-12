<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Search by name/email
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($role = $request->input('role')) {
            $query->role($role);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::pluck('name');

        return view('admin.users.index', compact('users', 'roles'));
    }

    public function show(User $user)
    {
        $user->load([
            'roles',
            'reservations.vehicle',
            'invoices',
            'clientContracts',
        ]);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::pluck('name');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'phone'     => 'nullable|string|max:30',
            'address'   => 'nullable|string|max:255',
            'city'      => 'nullable|string|max:100',
            'country'   => 'nullable|string|max:100',
            'is_active' => 'boolean',
        ]);

        $user->update($validated);

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', 'Utilisateur mis à jour.');
    }

    /**
     * Update user's role (replaces existing roles).
     */
    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        // Prevent removing your own admin role (safety)
        if ($user->id === Auth::user()->id && $validated['role'] !== 'admin') {
            return back()->with('error', 'Vous ne pouvez pas retirer votre propre rôle admin.');
        }

        $user->syncRoles([$validated['role']]);

        return back()->with('success', "Rôle mis à jour : {$validated['role']}");
    }

    /**
     * Toggle user active status.
     */
    public function toggleActive(User $user)
    {
        if ($user->id === Auth::user()->id) {
            return back()->with('error', 'Vous ne pouvez pas désactiver votre propre compte.');
        }

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', 'Statut mis à jour.');
    }

    public function destroy(User $user)
    {
        if ($user->id === Auth::user()->id) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé.');
    }
}