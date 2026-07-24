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
        {{-- ... (Step indicator sama seperti sebelumnya) ... --}}
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

    {{-- Inisialisasi Alpine dengan melempar baseTotal dan totalWeight --}}
    <form action="{{ route('checkout') }}" method="POST" 
          x-data="checkoutForm({{ $total }}, {{ $totalWeight ?? 1000 }})" 
          @submit.prevent="submit($event)">
        @csrf

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

                    {{-- Alamat Lengkap (Textarea bawaanmu) --}}
                    <div>
                        <label for="shipping_address" class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">
                            Alamat Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="shipping_address" id="shipping_address" rows="3"
                            placeholder="Nama jalan, nomor rumah, RT/RW, kode pos"
                            class="w-full px-4 py-3 rounded-xl border border-black/10 bg-black/[0.02] text-sm text-[#1F150C] outline-none focus:ring-2 focus:ring-[#412D15]/20 transition-all"></textarea>
                    </div>

                    {{-- Nomor HP & Notes (Bawaanmu) --}}
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

{{-- Pilihan Kurir (Dengan Logo) --}}
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
                                        
                                        {{-- Kotak Logo Kurir --}}
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
                
                {{-- Toggle Summary Items --}}
                <button type="button" @click="open = !open" class="w-full flex items-center justify-between gap-3 p-6 text-left">
                    <span class="font-display text-lg font-semibold text-[#1F150C]">Ringkasan Pesanan</span>
                    <span class="flex items-center gap-2 text-xs font-bold" style="color:#412D15;">
                        {{ $cartItems->sum('quantity') }} Barang
                        <i class="fa-solid fa-chevron-down transition-transform" :class="open ? 'rotate-180' : ''"></i>
                    </span>
                </button>

                <div x-show="open" x-transition class="px-6 pb-2 space-y-3">
                    {{-- Loop item keranjang bawaanmu --}}
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

                <div class="p-6">
                    <div class="space-y-3 mb-5 text-sm">
                        <div class="flex justify-between text-[#1F150C]/60">
                            <span>Subtotal</span>
                            <span class="font-semibold text-[#1F150C]">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between text-[#1F150C]/60">
                            <span>Pajak (11%)</span>
                            <span class="font-semibold text-[#1F150C]">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                        </div>
                        {{-- Ongkir Dinamis --}}
                        <div class="flex justify-between text-[#1F150C]/60" x-show="selectedCourier.cost > 0">
                            <span>Ongkos Kirim</span>
                            <span class="font-semibold text-[#1F150C]" x-text="formatRupiah(selectedCourier.cost)"></span>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 pt-4 border-t border-black/5">
                        <span class="font-bold text-[#1F150C]">Total Akhir</span>
                        {{-- Kalkulasi Total Akhir (Base Total + Ongkir) --}}
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
            loading: false,
            errorList: [],
            
            // Variabel RajaOngkir
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

            // Format Rupiah Helper
            formatRupiah(angka) {
                return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(angka);
            },

            // Hit Endpoint Search
            async searchDestination() {
                if (this.searchKeyword.length < 3) return;
                this.isSearching = true;
                try {
                    const res = await fetch(`/checkout/search-destination?keyword=${this.searchKeyword}`);
                    const data = await res.json();
                    
                    // DEBUGGING: Cek struktur aslinya di Console!
                    console.log("🔍 CEK DATA DESTINASI:", data);
                    
                    this.destinations = data;
                } catch (e) {
                    console.error("Gagal cari destinasi", e);
                }
                this.isSearching = false;
            },

            // Saat Destinasi Dipilih
            async selectDestination(dest) {
                // Coba gunakan nama properti umum Komerce jika 'name' kosong
                this.selectedDestinationName = dest.name || dest.label || dest.city_name || 'Destinasi Terpilih';
                this.searchKeyword = '';
                this.destinations = [];
                this.shippingOptions = [];
                this.selectedCourier = { id: '', code: '', service: '', cost: 0 };
                
                // Pastikan ID tidak kosong sebelum menghitung ongkir
                if (dest.id) {
                    await this.calculateCost(dest.id);
                } else {
                    console.error("ID Destinasi tidak ditemukan!");
                }
            },

            // Hit Endpoint Ongkir
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
                    
                    // DEBUGGING: Cek struktur respon ongkir di Console!
                    console.log("📦 CEK DATA ONGKIR:", response);
                    
                        if (response.success && response.data) {
                        this.shippingOptions = response.data.map((item, index) => {
                            // API Komerce biasanya mengirim cost dan etd secara langsung
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

            // Saat Radio Button Kurir Diklik
            setCourier(option) {
                this.selectedCourier.code = option.courier;
                this.selectedCourier.service = option.service;
                this.selectedCourier.cost = option.cost;
            },

            // Submit Form Midtrans Bawaanmu
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