@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<script src="{{ $midtransIsProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
        data-client-key="{{ $midtransClientKey }}"></script>

<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12 min-h-[60vh]">

    {{-- Header + step indicator --}}
    <div class="mb-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
            <h1 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C]">Checkout</h1>
            <a href="{{ route('cart.index') }}" class="text-sm font-bold transition-colors flex items-center" style="color:#412D15;">
                <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Keranjang
            </a>
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

    {{-- Form Checkout Utama --}}
    <form action="{{ route('checkout') }}" method="POST" 
          x-data="checkoutForm({{ $total ?? $subtotal }}, {{ $totalWeight ?? 1000 }})" 
          @submit.prevent="submit($event)">
        @csrf
        <input type="hidden" name="coupon_code" :value="appliedCode">

        {{-- Hidden Input untuk dikirim ke Backend --}}
        <input type="hidden" name="courier" x-model="selectedCourier.code">
        <input type="hidden" name="courier_service" x-model="selectedCourier.service">
        <input type="hidden" name="shipping_cost" x-model="selectedCourier.cost">

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

            {{-- ============ LEFT: shipping form ============ --}}
            <div class="bg-white p-6 sm:p-7 rounded-2xl border border-black/10 shadow-sm">
                <h3 class="font-display text-lg font-semibold text-[#1F150C] mb-6 flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-full flex items-center justify-center text-white text-sm" style="background:#412D15;">
                        <i class="fa-solid fa-location-dot"></i>
                    </span>
                    Alamat Pengiriman
                </h3>

                <div class="space-y-5">
                    {{-- Pencarian Kota/Kecamatan RajaOngkir --}}
                    <div class="relative">
                    <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">
                            Kecamatan / Kota Tujuan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" x-model="searchKeyword" @input.debounce.500ms="searchDestination()"
                            placeholder="Ketik nama kecamatan atau kota..."
                            class="w-full px-4 py-3 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition-all">
                        
                        {{-- TAMBAHKAN HIDDEN INPUT INI SUPAYA NAMA KOTA IKUT TERKIRIM --}}
                        <input type="hidden" name="destination_name" :value="selectedDestinationName">
                        
                        <div x-show="isSearching" class="absolute right-3 top-9 text-xs text-gray-400">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                        </div>

                        {{-- Dropdown Hasil Pencarian --}}
                        <div x-show="destinations.length > 0" @click.away="destinations = []" class="absolute z-10 w-full mt-1 bg-white border border-black/10 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="dest in destinations" :key="dest.id">
                                <button type="button" @click="selectDestination(dest)"
                                    class="w-full text-left px-4 py-2.5 text-sm hover:bg-[#E1DCC9]/30 transition-colors border-b border-black/5 last:border-0">
                                    <span class="font-semibold text-[#1F150C]" x-text="dest.label || dest.name || dest.city_name || dest.text"></span>
                                </button>
                            </template>
                        </div>
                        
                        <p x-show="selectedDestinationName" class="text-xs text-emerald-600 font-semibold mt-2 flex items-center gap-1">
                            <i class="fa-solid fa-check-circle"></i> Terpilih: <span x-text="selectedDestinationName"></span>
                        </p>
                    </div>

                    {{-- Alamat Lengkap --}}
                    <div>
                        <label for="shipping_address" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">
                            Alamat Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="shipping_address" id="shipping_address" rows="3"
                            placeholder="Nama jalan, nomor rumah, RT/RW, kode pos"
                            class="w-full px-4 py-3 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 transition-all"></textarea>
                    </div>

                    {{-- Nomor HP & Notes --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="shipping_phone" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Nomor HP Aktif <span class="text-rose-500">*</span></label>
                            <input type="text" name="shipping_phone" id="shipping_phone" placeholder="Contoh: 081234567890"
                                class="w-full px-4 py-3 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20">
                        </div>
                        <div>
                            <label for="shipping_notes" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Catatan <span class="font-normal normal-case">(opsional)</span></label>
                            <input type="text" name="shipping_notes" id="shipping_notes" placeholder="Patokan rumah..."
                                class="w-full px-4 py-3 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20">
                        </div>
                    </div>

                    {{-- Pilihan Kurir (Dengan Badge Inisial Modern) --}}
                    <div x-show="shippingOptions.length > 0" class="pt-4 border-t border-black/10">
                        <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-3">
                            Pilih Pengiriman
                        </label>
                        <div class="space-y-3">
                            <template x-for="(option, index) in shippingOptions" :key="index">
                                <label class="flex items-center justify-between p-4 border rounded-xl cursor-pointer transition-all"
                                    :class="selectedCourier.id === option.id ? 'border-[#412D15] bg-[#E1DCC9]/10' : 'border-black/10 hover:border-black/30'">
                                    <div class="flex items-center gap-3.5">
                                        <input type="radio" name="courier_selection" :value="option.id" x-model="selectedCourier.id" @change="setCourier(option)" class="text-[#412D15] focus:ring-[#412D15]">
                                        
                                        {{-- Badge Inisial Kurir Modern --}}
                                        <div class="w-12 h-10 rounded-xl flex items-center justify-center shrink-0 font-bold text-xs uppercase shadow-xs tracking-wider"
                                             :class="{
                                                 'bg-blue-50 text-blue-700 border border-blue-200': option.courier.toLowerCase().includes('jne'),
                                                 'bg-red-50 text-red-600 border border-red-200': option.courier.toLowerCase().includes('jnt') || option.courier.toLowerCase().includes('j&t'),
                                                 'bg-amber-50 text-amber-700 border border-amber-200': option.courier.toLowerCase().includes('sicepat'),
                                                 'bg-orange-50 text-orange-600 border border-orange-200': option.courier.toLowerCase().includes('tiki'),
                                                 'bg-gray-100 text-gray-700 border border-gray-200': !['jne', 'jnt', 'j&t', 'sicepat', 'tiki'].some(k => option.courier.toLowerCase().includes(k))
                                             }"
                                             x-text="option.courier">
                                        </div>

                                        <div>
                                            <p class="font-bold text-[#1F150C] text-sm uppercase" x-text="option.courier + ' - ' + option.service"></p>
                                            <p class="text-xs text-[#1F150C]/50" x-text="'Estimasi: ' + option.etd + ' hari'"></p>
                                        </div>
                                    </div>
                                    <span class="font-bold text-[#1F150C] text-sm" x-text="formatRupiah(option.cost)"></span>
                                </label>
                            </template>
                        </div>
                    </div>

                    <div x-show="shippingLoading" class="p-4 text-center text-sm font-semibold text-[#1F150C]/50 border border-dashed border-black/10 rounded-xl">
                        <i class="fa-solid fa-circle-notch fa-spin mr-2"></i> Menghitung ongkos kirim...
                    </div>
                </div>
            </div>

            {{-- ============ RIGHT: order summary panel ============ --}}
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
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-[#1F150C] text-sm truncate">{{ $item->product->name }}</p>
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
                            <span>Subtotal</span>
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
                        <div class="flex justify-between text-[#1F150C]/60" x-show="selectedCourier.cost > 0">
                            <span>Ongkos Kirim</span>
                            <span class="font-semibold text-[#1F150C]" x-text="formatRupiah(selectedCourier.cost)"></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 pt-4 border-t border-black/5">
                        <span class="font-bold text-[#1F150C]">Total Akhir</span>
                        <span class="font-display text-2xl font-semibold" style="color:#412D15;" x-text="formatRupiah(baseTotal + selectedCourier.cost)"></span>
                    </div>

                    <button type="submit" :disabled="loading || selectedCourier.cost === 0"
                            class="w-full py-3.5 btn-primary text-white font-bold rounded-xl shadow-md transition-all flex items-center justify-center gap-2 hover:-translate-y-0.5 disabled:opacity-60 disabled:pointer-events-none disabled:translate-y-0">
                        <span x-show="!loading">Bayar Sekarang</span>
                        <span x-show="loading" x-cloak>Memproses…</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    function checkoutForm(initialBaseTotal, totalWeightGram) {
        return {
            subtotal: {{ (int) $subtotal }},
            loading: false,
            errorList: [],

            baseTotal: initialBaseTotal,
            totalWeight: totalWeightGram,
            
            searchKeyword: '',
            isSearching: false,
            destinations: [],
            selectedDestinationName: '',
            
            shippingLoading: false,
            shippingOptions: [],
            selectedCourier: {
                id: '', code: '', service: '', cost: 0
            },

            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
            },

            async searchDestination() {
                if (this.searchKeyword.length < 3) return;
                this.isSearching = true;
                try {
                    const res = await fetch(`/checkout/search-destination?keyword=${this.searchKeyword}`);
                    const data = await res.json();
                    this.destinations = data;
                } catch (e) {
                    console.error("Gagal cari destinasi", e);
                }
                this.isSearching = false;
            },

            async selectDestination(dest) {
                this.selectedDestinationName = dest.name || dest.label || dest.city_name || 'Destinasi Terpilih';
                this.searchKeyword = '';
                this.destinations = [];
                this.shippingOptions = [];
                this.selectedCourier = { id: '', code: '', service: '', cost: 0 };
                
                if (dest.id) {
                    await this.calculateCost(dest.id);
                }
            },

            async calculateCost(destinationId) {
                this.shippingLoading = true;
                try {
                    const res = await fetch(`/checkout/shipping-cost`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            destination_id: destinationId,
                            weight: this.totalWeight
                        })
                    });
                    
                    const response = await res.json();
                    
                    if (response.success && response.data) {
                        this.shippingOptions = response.data.map((item, index) => {
                            let price = typeof item.cost === 'number' ? item.cost : (item.cost?.[0]?.value || 0);
                            let estimation = item.etd || item.cost?.[0]?.etd || '-';

                            return {
                                id: index,
                                courier: item.code || item.name || 'KURIR',
                                service: item.service || 'REG',
                                cost: price,
                                etd: estimation
                            };
                        });
                    }
                } catch (e) {
                    console.error("Gagal hitung ongkir", e);
                }
                this.shippingLoading = false;
            },

            setCourier(option) {
                this.selectedCourier.code = option.courier;
                this.selectedCourier.service = option.service;
                this.selectedCourier.cost = option.cost;
            },

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
                if (this.selectedCourier.cost === 0) {
                    this.errorList = ['Silakan pilih alamat dan kurir pengiriman terlebih dahulu.'];
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                    return;
                }

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
                        this.errorList = data.errors ? Object.values(data.errors).flat() : [data.message || 'Error!'];
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                        return;
                    }

                    if (data.snap_token && window.snap) {
                        const verifikasiStatusLaluRedirect = () => {
                            fetch(`/orders/${data.order_id}/verify-status`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? formData.get('_token'),
                                    'Accept': 'application/json',
                                },
                            }).finally(() => { window.location.href = data.redirect_url; });
                        };

                        window.snap.pay(data.snap_token, {
                            onSuccess: verifikasiStatusLaluRedirect,
                            onPending: verifikasiStatusLaluRedirect,
                            onError: () => {
                                this.loading = false;
                                this.errorList = ['Pembayaran gagal.'];
                            },
                            onClose: () => { window.location.href = data.redirect_url; },
                        });
                    }
                } catch (e) {
                    this.loading = false;
                    this.errorList = ['Terjadi kesalahan jaringan.'];
                }
            }
        };
    }
</script>
@endsection