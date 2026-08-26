<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->get();

        $stats = [
            'total_user'    => User::count(),
            'total_it'      => User::where('role', 'it')->count(),
            'total_sarpras' => User::where('role', 'sarpras')->count(),
        ];

        return view('user.index', compact('users', 'stats'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => ['required', Rule::in(['it', 'sarpras'])],
        ], [
            'name.required'     => 'Nama pengguna wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min'      => 'Password minimal 6 karakter.',
            'role.required'     => 'Role wajib dipilih.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        return redirect()->route('user.index')->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role'     => ['required', Rule::in(['it', 'sarpras'])],
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required'=> 'Alamat email wajib diisi.',
            'email.unique'  => 'Email sudah digunakan oleh akun lain.',
            'password.min'  => 'Password minimal 6 karakter.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('user.index')->with('success', "Data pengguna {$user->name} berhasil diperbarui!");
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri saat sedang login!');
        }

        if ($user->role === 'it' && User::where('role', 'it')->count() <= 1) {
            return redirect()->back()->with('error', 'Gagal menghapus: Harus ada minimal 1 Admin IT aktif di dalam sistem.');
        }

        $userName = $user->name;
        $user->delete();

        return redirect()->route('user.index')->with('success', "Pengguna {$userName} berhasil dihapus dari sistem.");
    }
}
