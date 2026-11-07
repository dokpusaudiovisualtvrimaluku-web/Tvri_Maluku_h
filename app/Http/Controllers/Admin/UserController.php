<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $roleFilter = $request->input('role');
        $statusFilter = $request->input('status');

        $query = User::with('roles')->latest();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($roleFilter) {
            $query->whereHas('roles', function ($q) use ($roleFilter) {
                $q->where('name', $roleFilter);
            });
        }

        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles', 'search', 'roleFilter', 'statusFilter'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'status' => ['required', 'in:ACTIVE,INACTIVE'],
        ], [
            'username.unique' => 'Username tersebut sudah terdaftar.',
            'email.unique' => 'Alamat Email sudah terdaftar.',
            'password.min' => 'Password minimal harus 6 karakter.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
        ]);

        $user->syncRoles([$validated['role']]);

        ActivityLogger::log('USER_CREATED', "Admin menambahkan pengguna baru: {$user->name} ({$user->username}) dengan role {$validated['role']}.");

        return redirect()->route('admin.users.index')->with('success', "Pengguna {$user->name} berhasil ditambahkan ke sistem.");
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:50', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role' => ['required', 'string', 'exists:roles,name'],
            'status' => ['required', 'in:ACTIVE,INACTIVE'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->syncRoles([$validated['role']]);

        ActivityLogger::log('USER_UPDATED', "Admin memperbarui data pengguna: {$user->name} ({$user->username}).");

        return redirect()->route('admin.users.index')->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak dapat menonaktifkan akun Anda sendiri.');
        }

        $newStatus = ($user->status === 'ACTIVE') ? 'INACTIVE' : 'ACTIVE';
        $user->update(['status' => $newStatus]);

        $actionText = ($newStatus === 'ACTIVE') ? 'diaktifkan kembali' : 'dinonaktifkan';
        $logType = ($newStatus === 'ACTIVE') ? 'USER_UPDATED' : 'USER_DISABLED';

        ActivityLogger::log($logType, "Admin mengubah status pengguna {$user->name} ({$user->username}) menjadi {$newStatus}.");

        return redirect()->route('admin.users.index')->with('success', "Status pengguna {$user->name} berhasil {$actionText}.");
    }
}
