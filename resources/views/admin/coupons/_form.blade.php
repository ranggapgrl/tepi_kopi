@php
    $coupon = $coupon ?? null;
@endphp

<div class="grid sm:grid-cols-2 gap-5">
    <div class="sm:col-span-2">
        <label for="code" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
            Kode Kupon
        </label>
        <input type="text" name="code" id="code" value="{{ old('code', $coupon->code ?? '') }}"
            placeholder="Contoh: TEPIKOPI10" style="text-transform:uppercase"
            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('code') ? 'border-rose-300 focus:ring-rose-200' : 'border-amber-100 focus:ring-amber-300' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:border-amber-300 transition-all">
        @error('code')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
        <p class="text-amber-700/60 text-xs mt-1.5">Ini kode yang dimasukkan customer saat checkout. Otomatis disimpan huruf besar.</p>
    </div>

    <div>
        <label for="type" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
            Tipe Potongan
        </label>
        <select name="type" id="type" x-data
            @change="document.getElementById('value-suffix').textContent = $event.target.value === 'percentage' ? '%' : 'Rp'"
            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('type') ? 'border-rose-300' : 'border-amber-100' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
            <option value="percentage" {{ old('type', $coupon->type ?? '') === 'percentage' ? 'selected' : '' }}>Persentase (%)</option>
            <option value="fixed" {{ old('type', $coupon->type ?? '') === 'fixed' ? 'selected' : '' }}>Nominal Tetap (Rp)</option>
        </select>
        @error('type')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="value" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
            Nilai Potongan (<span id="value-suffix">{{ old('type', $coupon->type ?? 'percentage') === 'fixed' ? 'Rp' : '%' }}</span>)
        </label>
        <input type="number" name="value" id="value" min="1" value="{{ old('value', $coupon->value ?? '') }}"
            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('value') ? 'border-rose-300' : 'border-amber-100' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
        @error('value')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="min_purchase" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
            Minimal Belanja (Rp) <span class="text-amber-700/50 font-normal normal-case">— opsional</span>
        </label>
        <input type="number" name="min_purchase" id="min_purchase" min="0" value="{{ old('min_purchase', $coupon->min_purchase ?? 0) }}"
            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('min_purchase') ? 'border-rose-300' : 'border-amber-100' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
        @error('min_purchase')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="max_discount" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
            Maks. Potongan (Rp) <span class="text-amber-700/50 font-normal normal-case">— khusus tipe persentase</span>
        </label>
        <input type="number" name="max_discount" id="max_discount" min="1" value="{{ old('max_discount', $coupon->max_discount ?? '') }}"
            placeholder="Kosongkan = tidak dibatasi"
            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('max_discount') ? 'border-rose-300' : 'border-amber-100' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
        @error('max_discount')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="usage_limit" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
            Batas Pemakaian <span class="text-amber-700/50 font-normal normal-case">— opsional</span>
        </label>
        <input type="number" name="usage_limit" id="usage_limit" min="1" value="{{ old('usage_limit', $coupon->usage_limit ?? '') }}"
            placeholder="Kosongkan = tidak terbatas"
            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('usage_limit') ? 'border-rose-300' : 'border-amber-100' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
        @error('usage_limit')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="expires_at" class="block text-xs font-bold text-amber-900 uppercase tracking-wide mb-2">
            Berlaku Sampai <span class="text-amber-700/50 font-normal normal-case">— opsional</span>
        </label>
        <input type="date" name="expires_at" id="expires_at"
            value="{{ old('expires_at', isset($coupon->expires_at) ? $coupon->expires_at->format('Y-m-d') : '') }}"
            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('expires_at') ? 'border-rose-300' : 'border-amber-100' }} bg-amber-50/40 text-sm text-amber-950 outline-none focus:ring-2 focus:ring-amber-300 focus:border-amber-300 transition-all">
        @error('expires_at')
            <p class="text-rose-600 text-xs font-medium mt-1.5">{{ $message }}</p>
        @enderror
    </div>

    <div class="sm:col-span-2 flex items-center gap-3 pt-2">
        <input type="checkbox" name="is_active" id="is_active" value="1"
            {{ old('is_active', $coupon->is_active ?? true) ? 'checked' : '' }}
            class="w-5 h-5 rounded border-amber-200 text-amber-800 focus:ring-amber-300">
        <label for="is_active" class="text-sm font-semibold text-amber-950">Kupon aktif (bisa langsung dipakai customer)</label>
    </div>
</div>
