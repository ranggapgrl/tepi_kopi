@extends('layouts.admin')

@section('title', 'Manajemen User - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-amber-950">Manajemen User</h1>
                <p class="text-amber-700/80 text-sm">Kelola akun admin dan pelanggan Tepi Kopi.</p>
            </div>
            <a href="{{ route('users.create') }}"
            class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-800 hover:bg-amber-900 text-white font-bold rounded-xl text-sm shadow-md transition-colors whitespace-nowrap">
                <i class="fa-solid fa-user-plus"></i> Tambah Akun
            </a>
        </div>

        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-check"></i>
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm font-medium flex items-center gap-2">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ session('error') }}
        </div>
        @endif

        {{-- Ringkasan --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                    <i class="fa-solid fa-users text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Akun</p>
                    <p class="text-xl font-bold text-amber-950">{{ $totalUsers }} Akun</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                    <i class="fa-solid fa-user-shield text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Admin</p>
                    <p class="text-xl font-bold text-amber-950">{{ $totalAdmins }} Admin</p>
                </div>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center text-amber-700">
                    <i class="fa-solid fa-user text-lg"></i>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Customer</p>
                    <p class="text-xl font-bold text-amber-950">{{ $totalCustomers }} Customer</p>
                </div>
            </div>
        </div>

        {{-- Filter & Pencarian --}}
        <form action="{{ route('users.index') }}" method="GET" class="flex flex-wrap items-center gap-2">
            <a href="{{ route('users.index') }}"
               class="text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors {{ !request('role') ? 'bg-amber-800 text-white' : 'bg-white border border-amber-100 text-amber-800 hover:bg-amber-50' }}">
                Semua Role
            </a>
            <a href="{{ route('users.index', ['role' => 'admin', 'search' => request('search')]) }}"
               class="text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors {{ request('role') === 'admin' ? 'bg-amber-800 text-white' : 'bg-white border border-amber-100 text-amber-800 hover:bg-amber-50' }}">
                Admin
            </a>
            <a href="{{ route('users.index', ['role' => 'customer', 'search' => request('search')]) }}"
               class="text-xs font-semibold px-3.5 py-2 rounded-lg transition-colors {{ request('role') === 'customer' ? 'bg-amber-800 text-white' : 'bg-white border border-amber-100 text-amber-800 hover:bg-amber-50' }}">
                Customer
            </a>

            <div class="flex-grow"></div>

            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama atau email..."
                       class="text-sm border border-amber-100 rounded-lg pl-9 pr-3 py-2 w-64 focus:outline-none focus:ring-2 focus:ring-amber-200">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-amber-300 text-xs"></i>
            </div>
        </form>

        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-amber-50 bg-amber-950 text-white flex justify-between items-center">
                <h3 class="font-bold">Daftar Akun</h3>
                <span class="text-xs text-amber-200">{{ $users->total() }} akun</span>
            </div>

            @if($users->isEmpty())
                <div class="p-12 text-center">
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-600 mx-auto mb-4">
                        <i class="fa-solid fa-user-slash text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-amber-950 mb-1">Tidak ada akun ditemukan</h3>
                    <p class="text-sm text-gray-500">Coba ubah kata kunci pencarian atau filter role.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Nama</th>
                                <th class="px-6 py-3 font-semibold">Email</th>
                                <th class="px-6 py-3 font-semibold">Role</th>
                                <th class="px-6 py-3 font-semibold">Pesanan</th>
                                <th class="px-6 py-3 font-semibold">Bergabung</th>
                                <th class="px-6 py-3 font-semibold text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                            <tr class="border-b border-gray-100 hover:bg-gray-50 align-middle">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-amber-100 text-amber-800 flex items-center justify-center font-bold flex-shrink-0 text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-semibold text-amber-950">{{ $user->name }}</p>
                                            @if($user->id === auth()->id())
                                                <span class="text-[10px] font-bold text-amber-600 bg-amber-50 border border-amber-100 px-1.5 py-0.5 rounded">Kamu</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if($user->role === 'admin')
                                        <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-2.5 py-1 rounded-md">
                                            <i class="fa-solid fa-user-shield text-[10px] mr-1"></i>Admin
                                        </span>
                                    @else
                                        <span class="bg-amber-100 text-amber-800 text-xs font-semibold px-2.5 py-1 rounded-md">
                                            Customer
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $user->orders_count }} pesanan</td>
                                <td class="px-6 py-4 text-gray-400 text-xs">{{ $user->created_at->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('users.edit', $user) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg border border-amber-100 text-amber-700 hover:bg-amber-50 transition-colors"
                                        title="Edit">
                                            <i class="fa-solid fa-pen text-xs"></i>
                                        </a>
                                        <form action="{{ route('users.destroy', $user) }}" method="POST"
                                            onsubmit="return confirm('Hapus akun &quot;{{ $user->name }}&quot;?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-9 h-9 flex items-center justify-center rounded-lg border border-rose-100 text-rose-600 hover:bg-rose-50 transition-colors disabled:opacity-40 disabled:pointer-events-none"
                                                    title="Hapus"
                                                    {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                                                <i class="fa-solid fa-trash text-xs"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="p-6 border-t border-amber-50">
                    {{ $users->onEachSide(1)->links('pagination.tepikopi', ['itemLabel' => 'akun']) }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection