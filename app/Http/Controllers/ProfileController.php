<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * /profile — Tampilkan halaman profil user yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        return view('profile', compact('user'));
    }

    /**
     * PUT /profile — Update nama, email, dan foto profil.
     */
public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|max:255|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|max:2048',
        ]);

        // Ambil instance fresh dari database untuk memastikan update berhasil
        $freshUser = \App\Models\User::find($user->id);

        if ($request->hasFile('avatar')) {
            // Hapus file avatar lama supaya tidak numpuk jadi sampah di storage
            if ($freshUser->avatar) {
                Storage::disk('public')->delete($freshUser->avatar);
            }
            $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
        } else {
            unset($validated['avatar']);
        }

        // Lakukan update pada instance yang fresh
        $freshUser->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * PUT /profile/password — Ganti password.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = Auth::user();

        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')->with('success', 'Password berhasil diubah.');
    }
}