<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses;

        return view('addresses.index', compact('addresses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'is_default'     => 'nullable|boolean',
        ]);

        $address = Auth::user()->addresses()->create($validated);

        $this->applyDefault($address, $request->boolean('is_default'));

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function update(Request $request, Address $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $validated = $request->validate([
            'label'          => 'required|string|max:50',
            'recipient_name' => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address'        => 'required|string|max:500',
            'is_default'     => 'nullable|boolean',
        ]);

        $address->update($validated);

        $this->applyDefault($address, $request->boolean('is_default'));

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    public function destroy(Address $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $address->delete();

        return back()->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault(Address $address)
    {
        abort_unless($address->user_id === Auth::id(), 403);

        $this->applyDefault($address, true);

        return back()->with('success', 'Alamat utama diperbarui.');
    }

    /**
     * Pastikan cuma ada satu alamat default per user.
     */
    private function applyDefault(Address $address, bool $makeDefault): void
    {
        if (! $makeDefault) {
            return;
        }

        Address::where('user_id', $address->user_id)
            ->where('id', '!=', $address->id)
            ->update(['is_default' => false]);

        $address->update(['is_default' => true]);
    }
}