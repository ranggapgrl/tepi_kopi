@extends('layouts.app')

@section('title', 'Alamat Tersimpan')

@section('content')
<style>[x-cloak]{display:none!important;}</style>

<div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12" x-data="{ formOpen: false, editingId: null }">

    <div class="mb-8 flex items-center justify-between gap-3">
        <div>
            <p class="text-xs text-[#1F150C]/40 mb-2"><a href="/profile" class="hover:text-[#412D15]">Profil</a> <i class="fa-solid fa-chevron-right text-[8px] mx-1.5"></i> Alamat Tersimpan</p>
            <h1 class="font-display text-2xl sm:text-3xl font-semibold text-[#1F150C]">Alamat Tersimpan</h1>
        </div>
        <button @click="formOpen = true; editingId = null; $nextTick(() => resetForm())"
                class="px-4 py-2.5 btn-primary text-sm font-bold rounded-lg shrink-0">
            <i class="fa-solid fa-plus mr-1.5"></i> Tambah
        </button>
    </div>

    @if(session('success'))
    <div class="mb-6 px-4 py-3 rounded-xl flex items-center gap-3" style="background:#f3f8f1; border:1px solid #cfe6c9; color:#2f5e29;">
        <i class="fa-solid fa-circle-check"></i>
        <span class="text-sm font-medium">{{ session('success') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl">
        <ul class="text-sm list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ============ LIST ALAMAT ============ --}}
    @if($addresses->isEmpty())
    <div class="bg-white rounded-3xl p-8 sm:p-12 text-center border border-black/10 shadow-sm">
        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full flex items-center justify-center mx-auto mb-6" style="background:#E1DCC9; color:#412D15;">
            <i class="fa-solid fa-location-dot text-2xl sm:text-3xl"></i>
        </div>
        <h3 class="text-lg font-bold text-[#1F150C] mb-2">Belum ada alamat tersimpan</h3>
        <p class="text-[#1F150C]/50 text-sm">Tambah alamat supaya checkout jadi lebih cepat.</p>
    </div>
    @else
    <div class="space-y-4">
        @foreach($addresses as $address)
        <div class="bg-white rounded-2xl border border-black/10 shadow-sm p-5 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
            <div class="min-w-0">
                <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                    <span class="inline-flex items-center gap-1.5 bg-[#E1DCC9] text-[#1F150C] text-xs font-bold px-2.5 py-1 rounded-full">
                        <i class="fa-solid fa-tag text-[10px]"></i> {{ $address->label }}
                    </span>
                    @if($address->is_default)
                    <span class="inline-flex items-center gap-1.5 text-white text-xs font-bold px-2.5 py-1 rounded-full" style="background:var(--brown);">
                        <i class="fa-solid fa-check text-[10px]"></i> Utama
                    </span>
                    @endif
                </div>
                <p class="font-bold text-[#1F150C]">{{ $address->recipient_name }}</p>
                <p class="text-sm text-[#1F150C]/60">{{ $address->phone }}</p>
                <p class="text-sm text-[#1F150C]/60 mt-1">{{ $address->address }}</p>
            </div>

            <div class="flex sm:flex-col items-start gap-2 shrink-0">
                <button
                    @click="formOpen = true; editingId = {{ $address->id }}; $nextTick(() => fillForm({{ $address->id }}))"
                    class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-black/10 hover:bg-black/5 transition text-[#1F150C]">
                    <i class="fa-solid fa-pen mr-1"></i> Ubah
                </button>
                @unless($address->is_default)
                <form method="POST" action="{{ route('addresses.setDefault', $address) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-black/10 hover:bg-black/5 transition text-[#1F150C]">
                        <i class="fa-solid fa-star mr-1"></i> Jadikan Utama
                    </button>
                </form>
                @endunless
                <form method="POST" action="{{ route('addresses.destroy', $address) }}" onsubmit="return confirm('Hapus alamat ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg border border-rose-200 text-rose-600 hover:bg-rose-50 transition">
                        <i class="fa-solid fa-trash mr-1"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
    @endif

    {{-- ============ MODAL TAMBAH/UBAH ALAMAT ============ --}}
    <div x-show="formOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div @click="formOpen = false" x-show="formOpen" x-transition.opacity class="absolute inset-0 bg-black/40"></div>

        <div x-show="formOpen" x-transition class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-6 max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between mb-5">
                <h3 class="font-display text-lg font-semibold text-[#1F150C]" x-text="editingId ? 'Ubah Alamat' : 'Tambah Alamat'"></h3>
                <button @click="formOpen = false" class="w-8 h-8 rounded-full bg-black/5 flex items-center justify-center"><i class="fa-solid fa-xmark text-sm"></i></button>
            </div>

            <form :action="editingId ? `/addresses/${editingId}` : '{{ route('addresses.store') }}'" method="POST" id="addressForm">
                @csrf
                <template x-if="editingId">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Label</label>
                        <input type="text" name="label" id="field_label" placeholder="Rumah / Kantor / dll" required
                               class="w-full px-4 py-2.5 rounded-xl border border-black/10 bg-black/[0.02] text-sm outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Nama Penerima</label>
                        <input type="text" name="recipient_name" id="field_recipient_name" required
                               class="w-full px-4 py-2.5 rounded-xl border border-black/10 bg-black/[0.02] text-sm outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Nomor HP</label>
                        <input type="text" name="phone" id="field_phone" required
                               class="w-full px-4 py-2.5 rounded-xl border border-black/10 bg-black/[0.02] text-sm outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-[#1F150C]/60 uppercase tracking-wide mb-2">Alamat Lengkap</label>
                        <textarea name="address" id="field_address" rows="3" required
                                  class="w-full px-4 py-2.5 rounded-xl border border-black/10 bg-black/[0.02] text-sm outline-none focus:ring-2 focus:ring-[#412D15]/20 focus:border-[#412D15]/40 transition"></textarea>
                    </div>
                    <label class="flex items-center gap-2.5 cursor-pointer">
                        <input type="checkbox" name="is_default" id="field_is_default" value="1" class="accent-[#412D15]">
                        <span class="text-sm text-[#1F150C]/75">Jadikan alamat utama</span>
                    </label>
                </div>

                <button type="submit" class="w-full mt-6 py-3 btn-primary rounded-lg text-sm font-bold transition">Simpan Alamat</button>
            </form>
        </div>
    </div>
</div>

<script>
    // Data alamat dari server, dipakai buat isi ulang form modal saat "Ubah" diklik.
    const addressData = {!! $addresses->keyBy('id')->map(fn($a) => [
        'label' => $a->label,
        'recipient_name' => $a->recipient_name,
        'phone' => $a->phone,
        'address' => $a->address,
        'is_default' => $a->is_default,
    ])->toJson() !!};

    function resetForm() {
        document.getElementById('addressForm').reset();
    }

    function fillForm(id) {
        const data = addressData[id];
        if (!data) return;
        document.getElementById('field_label').value = data.label;
        document.getElementById('field_recipient_name').value = data.recipient_name;
        document.getElementById('field_phone').value = data.phone;
        document.getElementById('field_address').value = data.address;
        document.getElementById('field_is_default').checked = !!data.is_default;
    }
</script>
@endsection