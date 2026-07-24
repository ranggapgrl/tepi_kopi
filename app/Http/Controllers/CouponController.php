<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    /**
     * ADMIN ONLY — /coupons
     */
    public function index()
    {
        $coupons = Coupon::withCount('orders')->latest()->paginate(10);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * ADMIN ONLY — /coupons/create
     */
    public function create()
    {
        return view('admin.coupons.create');
    }

    /**
     * ADMIN ONLY — POST /coupons
     */
    public function store(Request $request)
    {
        $validated = $this->validateCoupon($request);

        $validated['code'] = strtoupper($validated['code']);
        $validated['used_count'] = 0;
        $validated['is_active'] = $request->boolean('is_active');

        $coupon = Coupon::create($validated);

        ActivityLog::record('Kupon', 'create', 'Menambahkan kupon "' . $coupon->code . '".');

        return redirect()->route('coupons.index')->with('success', 'Kupon berhasil ditambahkan.');
    }

    /**
     * ADMIN ONLY — /coupons/{coupon}/edit
     */
    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    /**
     * ADMIN ONLY — PUT /coupons/{coupon}
     */
    public function update(Request $request, Coupon $coupon)
    {
        $validated = $this->validateCoupon($request, $coupon->id);

        $validated['code'] = strtoupper($validated['code']);
        $validated['is_active'] = $request->boolean('is_active');

        $coupon->update($validated);

        ActivityLog::record('Kupon', 'update', 'Memperbarui kupon "' . $coupon->code . '".');

        return redirect()->route('coupons.index')->with('success', 'Kupon berhasil diperbarui.');
    }

    /**
     * ADMIN ONLY — DELETE /coupons/{coupon}
     * Aman dihapus kapan pun karena kode & nominal diskon di pesanan lama
     * sudah di-snapshot ke kolom coupon_code/discount_amount pada tabel orders.
     */
    public function destroy(Coupon $coupon)
    {
        $code = $coupon->code;
        $coupon->delete();

        ActivityLog::record('Kupon', 'delete', 'Menghapus kupon "' . $code . '".');

        return redirect()->route('coupons.index')->with('success', 'Kupon "' . $code . '" berhasil dihapus.');
    }

    /**
     * CUSTOMER — POST /checkout/apply-coupon
     * Dipanggil lewat AJAX dari halaman checkout untuk mengecek & preview
     * potongan sebelum pesanan benar-benar dibuat. Validasi final tetap
     * dilakukan ulang di OrderController::checkout() saat submit sungguhan,
     * supaya endpoint ini tidak bisa dipakai untuk memanipulasi total di sisi client.
     */
    public function apply(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|integer|min:0',
        ]);

        $coupon = Coupon::where('code', strtoupper($request->code))->first();

        if (! $coupon) {
            return response()->json(['valid' => false, 'message' => 'Kode kupon tidak ditemukan.'], 404);
        }

        $error = $coupon->errorForSubtotal((int) $request->subtotal);

        if ($error) {
            return response()->json(['valid' => false, 'message' => $error], 422);
        }

        $discount = $coupon->calculateDiscount((int) $request->subtotal);

        return response()->json([
            'valid' => true,
            'code' => $coupon->code,
            'discount' => $discount,
            'message' => 'Kupon "' . $coupon->code . '" berhasil dipakai.',
        ]);
    }

    private function validateCoupon(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'code' => 'required|string|max:30|alpha_dash|unique:coupons,code' . ($ignoreId ? ',' . $ignoreId : ''),
            'type' => 'required|in:percentage,fixed',
            'value' => 'required|integer|min:1' . ($request->type === 'percentage' ? '|max:100' : ''),
            'min_purchase' => 'nullable|integer|min:0',
            'max_discount' => 'nullable|integer|min:1',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
        ], [
            'code.unique' => 'Kode kupon ini sudah dipakai.',
            'code.alpha_dash' => 'Kode kupon cuma boleh huruf, angka, strip, dan underscore.',
            'value.max' => 'Potongan persen maksimal 100%.',
        ]);
    }
}
