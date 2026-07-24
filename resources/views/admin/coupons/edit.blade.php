@extends('layouts.admin')

@section('title', 'Edit Kupon - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex items-center gap-3">
            <a href="{{ route('coupons.index') }}" class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Edit Kupon</h1>
                <p class="text-amber-700/80 text-sm">Perbarui kupon "{{ $coupon->code }}".</p>
            </div>
        </div>

        @if($coupon->used_count > 0)
        <div class="max-w-3xl bg-amber-50 border border-amber-200 text-amber-800 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-info"></i>
            Kupon ini sudah dipakai {{ $coupon->used_count }} kali. Mengubah nilai potongan tidak akan mengubah pesanan yang sudah dibuat sebelumnya.
        </div>
        @endif

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm p-6 sm:p-8 max-w-3xl">
            <form action="{{ route('coupons.update', $coupon) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                @include('admin.coupons._form')

                <div class="flex flex-col sm:flex-row gap-3 pt-2">
                    <button type="submit"
                            class="flex-1 sm:flex-none px-8 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors flex items-center justify-center gap-2">
                        <i class="fa-solid fa-check"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('coupons.index') }}"
                    class="flex-1 sm:flex-none px-8 py-3 border border-amber-200 text-amber-800 hover:bg-amber-50 font-bold rounded-xl text-sm transition-colors text-center">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
