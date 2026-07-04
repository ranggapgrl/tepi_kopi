<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public const ROLES = ['admin', 'customer'];

    /**
     * ADMIN ONLY — /users
     * Daftar semua akun (admin & customer), bisa dicari dan difilter per role.
     */
    public function index(Request $request)
    {
        $query = User::withCount('orders')->latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(10)->withQueryString();

        $totalUsers = User::count();
        $totalAdmins = User::where('role', 'admin')->count();
        $totalCustomers = User::where('role', 'customer')->count();

        return view('admin.users.index', compact('users', 'totalUsers', 'totalAdmins', 'totalCustomers'));
    }

    /**
     * ADMIN ONLY — /users/create
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * ADMIN ONLY — POST /users
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Password::min(8)],
            'role'     => ['required', Rule::in(self::ROLES)],
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Role di-set terpisah, bukan mass assignment, konsisten dengan alur register.
        $user->role = $validated['role'];
        $user->save();

        return redirect()->route('users.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * ADMIN ONLY — /users/{user}/edit
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * ADMIN ONLY — PUT /users/{user}
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email,' . $user->id,
            'role'     => ['required', Rule::in(self::ROLES)],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ]);

        // Jangan sampai admin menurunkan role dirinya sendiri — bisa bikin
        // dia sendiri langsung ke-lock dari halaman admin.
        if ($user->id === Auth::id() && $validated['role'] !== 'admin') {
            return back()->with('error', 'Kamu tidak bisa mengubah role akunmu sendiri.');
        }

        // Jangan sampai admin terakhir diturunkan jadi customer.
        if ($user->role === 'admin' && $validated['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa mengubah role. Sistem harus punya minimal satu akun admin.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];
        $user->role = $validated['role'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * ADMIN ONLY — DELETE /users/{user}
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus. Sistem harus punya minimal satu akun admin.');
        }

        if ($user->orders()->exists()) {
            return back()->with('error', 'Akun "' . $user->name . '" tidak bisa dihapus karena masih punya riwayat pesanan.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Akun berhasil dihapus.');
    }
}