@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<script src="{{ $midtransIsProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $midtransClientKey }}"></script>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 min-h-[60vh]">

    {{-- Header + step indicator — step 2 active, consistent with cart page --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h1 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C]">Checkout</h1>
            <a href="{{ route('cart.index') }}" class="text-sm font-bold transition-colors flex items-center" style="color:#412D15;">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Keranjang
            </a>
        </div>

        <div class="flex items-center gap-2 sm:gap-3 text-xs sm:text-sm font-semibold">
            <div class="flex items-center gap-2 text-[#1F150C]/40">
                <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center text-white text-[11px]" style="background:#412D15;">
                    <i class="fa-solid fa-check text-[9px]"></i>
                </span>
                <span>Keranjang</span>
            </div>
            <div class="flex-1 h-px max-w-16 sm:max-w-24" style="background:#412D15;"></div>
            <div class="flex items-center gap-2">
                <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full flex items-center justify-center text-white text-[11px]" style="background:#412D15;">2</span>
                <span class="text-[#1F150C]">Checkout</span>
            </div>
            <div class="flex-1 h-px bg-black/10 max-w-16 sm:max-w-24"></div>
            <div class="flex items-center gap-2 text-[#1F150C]/35">
                <span class="w-6 h-6 sm:w-7 sm:h-7 rounded-full border-2 border-black/10 flex items-center justify-center text-[11px]">3</span>
                <span>Selesai</span>
            </div>
        </div>
    </div>

    {{-- Server-rendered errors fallback --}}
    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl">
        <p class="text-sm font-bold mb-1">Cek lagi isian di bawah:</p>
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('checkout') }}" method="POST" x-data="checkoutForm({{ (int) $subtotal }})" @submit.prevent="submit($event)">
        @csrf
        <input type="hidden" name="coupon_code" :value="appliedCode">

        {{-- Client-side errors --}}
        <div x-show="errorList.length > 0" x-cloak x-transition class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl">
            <p class="text-sm font-bold mb-1">Cek lagi isian di bawah:</p>
            <ul class="text-sm list-disc list-inside space-y-0.5">
                <template x-for="err in errorList" :key="err">
                    <li x-text="err"></li>
                </template>
            </ul>
        </div>

        <div class="grid lg:grid-cols-[1fr_360px] gap-8 items-start">

            {{-- ============ LEFT: shipping form only — items live in the summary panel ============ --}}
            <div class="bg-white p-6 sm:p-7 rounded-2xl border border-black/10 shadow-sm">
                <h3 class="font-display text-lg font-semibold text-[#1F150C] mb-6 flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm" style="background:#412D15;">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    Alamat Pengiriman
                </h3>

                <div class="space-y-5">
                    @if($addresses->isNotEmpty())
                    <div>
                        <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Alamat Tersimpan</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach($addresses as $address)
                            <button type="button"
                                onclick="fillFromSavedAddress({{ $address->id }})"
                                class="text-xs font-semibold px-3 py-2 rounded-lg border border-black/10 hover:border-[#412D15]/40 hover:bg-[#E1DCC9]/30 transition text-[#1F150C]">
                                <i class="fa-solid fa-location-dot mr-1" style="color:#412D15;"></i>
                                {{ $address->label }}{{ $address->is_default ? ' (Utama)' : '' }}
                            </button>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-[#1F150C]/40 mt-2">Klik salah satu untuk isi otomatis, atau tulis manual di bawah.</p>
                    </div>
                    @endif

                    <div>
                        <label for="shipping_address" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">
                            Alamat Lengkap
                        </label>
                        <textarea name="shipping_address" id="shipping_address" rows="3"
                            placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota, kode pos"
                            class="w-full px-4 py-3 rounded-xl border {{ $errors->has('shipping_address') ? 'border-rose-300' : 'border-black/10' }} bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition-all">{{ old('shipping_address', $lastOrder->shipping_address ?? '') }}</textarea>
                    </div>

                    <div>
                        <label for="shipping_phone" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">
                            Nomor HP Aktif
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#1F150C]/30">
                                <i class="fa-solid fa-phone text-xs"></i>
                            </div>
                            <input type="text" name="shipping_phone" id="shipping_phone"
                                value="{{ old('shipping_phone', $lastOrder->shipping_phone ?? '') }}"
                                placeholder="Contoh: 081234567890"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border {{ $errors->has('shipping_phone') ? 'border-rose-300' : 'border-black/10' }} bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="shipping_notes" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">
                            Catatan Pengiriman <span class="text-[#1F150C]/35 font-normal normal-case">(opsional)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-[#1F150C]/30">
                                <i class="fa-solid fa-note-sticky text-xs"></i>
                            </div>
                            <input type="text" name="shipping_notes" id="shipping_notes"
                                value="{{ old('shipping_notes') }}"
                                placeholder="Contoh: Rumah cat hijau, sebelah warung"
                                class="w-full pl-10 pr-4 py-3 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition-all">
                        </div>
                    </div>
                </div>
            </div>

            {{-- ============ RIGHT: order summary panel, items collapsible ============ --}}
            <div class="bg-white rounded-2xl border border-black/10 shadow-sm lg:sticky lg:top-24 overflow-hidden" x-data="{ open: true }">

                <button type="button" @click="open = !open" class="w-full flex items-center justify-between gap-3 p-6 text-left">
                    <span class="font-display text-lg font-semibold text-[#1F150C]">Ringkasan Pesanan</span>
                    <span class="flex items-center gap-2 text-xs font-bold" style="color:#412D15;">
                        {{ $cartItems->sum('quantity') }} Barang
                        <i class="fa-solid fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </span>
                </button>

                <div x-show="open" x-transition class="px-6 pb-2 space-y-3">
                    @foreach($cartItems as $item)
                    @php $itemPrice = $item->variant ? $item->variant->price : $item->product->price; @endphp
                    <div class="flex items-center gap-3 pb-3 border-b border-black/5 last:border-0">
                        <div class="w-12 h-12 shrink-0 rounded-lg overflow-hidden border border-black/10" style="background:#E1DCC9;">
                            @if($item->product->image)
                                <img src="{{ asset('storage/' . $item->product->image) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="color:#412D15;"><i class="fa-solid fa-mug-hot text-sm opacity-50"></i></div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-[#1F150C] text-sm truncate">
                                {{ $item->product->name }}
                                @if($item->variant)
                                    <span class="text-[#1F150C]/40 font-normal">— {{ $item->variant->name }}</span>
                                @endif
                            </p>
                            <p class="text-[#1F150C]/40 text-xs">{{ $item->quantity }} x Rp {{ number_format($itemPrice, 0, ',', '.') }}</p>
                        </div>
                        <p class="font-bold text-[#1F150C] text-sm shrink-0">Rp {{ number_format($itemPrice * $item->quantity, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>

                <div class="border-t border-dashed border-black/15 mx-6 mt-2"></div>

                {{-- Kupon diskon --}}
                <div class="px-6 pt-4">
                    <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Kode Kupon</label>
                    <div class="flex gap-2" x-show="!appliedCode">
                        <input type="text" x-model="couponInput" @keyup.enter.prevent="applyCoupon()"
                            placeholder="Contoh: TEPIKOPI10"
                            class="flex-1 min-w-0 px-4 py-2.5 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] uppercase outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition-all">
                        <button type="button" @click="applyCoupon()" :disabled="couponLoading || !couponInput"
                            class="px-4 py-2.5 rounded-xl border border-[#412D15]/30 text-[#412D15] font-bold text-xs hover:bg-[#412D15]/5 transition-colors disabled:opacity-40 disabled:pointer-events-none whitespace-nowrap">
                            <span x-show="!couponLoading">Terapkan</span>
                            <i x-show="couponLoading" x-cloak class="fa-solid fa-circle-notch fa-spin"></i>
                        </button>
                    </div>
                    <div x-show="appliedCode" x-cloak class="flex items-center justify-between gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-200">
                        <span class="text-xs font-bold text-emerald-700 flex items-center gap-1.5">
                            <i class="fa-solid fa-circle-check"></i> <span x-text="appliedCode"></span> terpasang
                        </span>
                        <button type="button" @click="removeCoupon()" class="text-emerald-700/60 hover:text-emerald-800 text-xs font-semibold">Hapus</button>
                    </div>
                    <p x-show="couponMessage && !appliedCode" x-cloak x-text="couponMessage" class="text-rose-600 text-xs font-medium mt-1.5"></p>
                </div>

                <div class="p-6">
                    <div class="space-y-3 mb-5 text-sm">
                        <div class="flex justify-between text-[#1F150C]/60">
                            <span>Subtotal ({{ $cartItems->sum('quantity') }} Barang)</span>
                            <span class="font-semibold text-[#1F150C]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-emerald-700" x-show="discountAmount > 0" x-cloak>
                            <span>Diskon Kupon</span>
                            <span class="font-semibold" x-text="'- Rp ' + formatRupiah(discountAmount)"></span>
                        </div>
                        <div class="flex justify-between text-[#1F150C]/60">
                            <span>Pajak (11%)</span>
                            <span class="font-semibold text-[#1F150C]" x-text="'Rp ' + formatRupiah(displayTax)"></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 pt-4 border-t border-black/5">
                        <span class="font-bold text-[#1F150C]">Total Akhir</span>
                        <span class="font-display text-2xl font-semibold" style="color:#412D15;" x-text="'Rp ' + formatRupiah(displayTotal)"></span>
                    </div>

                    <button type="submit" :disabled="loading"
                            class="w-full py-3.5 btn-primary text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 disabled:opacity-60 disabled:pointer-events-none disabled:translate-y-0">
                        <span x-show="!loading">Buat Pesanan</span>
                        <span x-show="loading" x-cloak>Menyiapkan pembayaran…</span>
                        <i class="fa-solid" :class="loading ? 'fa-circle-notch fa-spin' : 'fa-check'"></i>
                    </button>
                    <p class="flex items-center justify-center gap-1.5 text-[10px] text-[#1F150C]/35 uppercase tracking-wider mt-4">
                        <i class="fa-solid fa-shield-halved"></i> Stok akan divalidasi ulang saat pesanan dibuat
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    // Data alamat tersimpan, dipakai untuk isi otomatis form saat salah satu
    // chip "Alamat Tersimpan" diklik.
    const savedAddresses = {{ Illuminate\Support\Js::from($addresses->keyBy('id')->map(fn($a) => [
        'address' => $a->address,
        'phone' => $a->phone,
    ])) }};

    function fillFromSavedAddress(id) {
        const data = savedAddresses[id];
        if (!data) return;
        document.getElementById('shipping_address').value = data.address;
        document.getElementById('shipping_phone').value = data.phone;
    }

    function checkoutForm(subtotal) {
        return {
            subtotal: subtotal,
            loading: false,
            errorList: [],
            couponInput: '',
            couponLoading: false,
            couponMessage: '',
            appliedCode: '',
            discountAmount: 0,

            get displayTax() {
                return Math.round((this.subtotal - this.discountAmount) * 0.11);
            },
            get displayTotal() {
                return (this.subtotal - this.discountAmount) + this.displayTax;
            },
            formatRupiah(value) {
                return new Intl.NumberFormat('id-ID').format(value);
            },
            async applyCoupon() {
                if (!this.couponInput) return;
                this.couponLoading = true;
                this.couponMessage = '';

                try {
                    const response = await fetch(`{{ route('checkout.applyCoupon') }}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                ?? document.querySelector('input[name="_token"]')?.value,
                        },
                        body: JSON.stringify({ code: this.couponInput, subtotal: this.subtotal }),
                    });
                    const data = await response.json().catch(() => ({}));

                    this.couponLoading = false;

                    if (!response.ok || !data.valid) {
                        this.couponMessage = data.message || 'Kupon tidak valid.';
                        return;
                    }

                    this.appliedCode = data.code;
                    this.discountAmount = data.discount;
                    this.couponMessage = '';
                } catch (e) {
                    this.couponLoading = false;
                    this.couponMessage = 'Gagal menghubungi server, coba lagi.';
                }
            },
            removeCoupon() {
                this.appliedCode = '';
                this.discountAmount = 0;
                this.couponInput = '';
                this.couponMessage = '';
            },
            async submit(event) {
                this.loading = true;
                this.errorList = [];
                const formEl = event.target;

                try {
                    const formData = new FormData(formEl);
                    const response = await fetch(formEl.action, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json' },
                        body: formData,
                    });

                    const data = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        this.loading = false;
                        this.errorList = data.errors
                            ? Object.values(data.errors).flat()
                            : [data.message || 'Terjadi kesalahan, silakan coba lagi.'];
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }

                    if (data.snap_token && window.snap) {
                        // BUGFIX: sebelumnya status order 100% bergantung webhook Midtrans
                        // yang tidak bisa menjangkau localhost saat development, dan bisa
                        // telat/gagal walau di production. Sekarang begitu Snap.js melapor
                        // sukses/pending, kita minta server cek status transaksi langsung
                        // ke Midtrans (endpoint verify-status) sebelum redirect.
                        const verifikasiStatusLaluRedirect = () => {
                            fetch(`/orders/${data.order_id}/verify-status`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content
                                        ?? formData.get('_token'),
                                    'Accept': 'application/json',
                                },
                            }).finally(() => {
                                window.location.href = data.redirect_url;
                            });
                        };

                        window.snap.pay(data.snap_token, {
                            onSuccess: verifikasiStatusLaluRedirect,
                            onPending: verifikasiStatusLaluRedirect,
                            onError: () => {
                                this.loading = false;
                                this.errorList = ['Pembayaran gagal, silakan coba lagi.'];
                            },
                            onClose: () => {
                                window.location.href = data.redirect_url;
                            },
                        });
                    } else {
                        this.loading = false;
                        this.errorList = ['Gagal memuat metode pembayaran. Silakan coba lagi.'];
                    }
                } catch (e) {
                    this.loading = false;
                    this.errorList = ['Terjadi kesalahan jaringan, silakan coba lagi.'];
                }
            },
        };
    }
</script>
@endsection