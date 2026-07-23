@extends('layouts.admin')

@section('title', 'Log Aktivitas - Admin')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 flex flex-col md:flex-row gap-8">

    @include('admin.partials.sidebar')

    <div class="flex-grow space-y-6 min-w-0">
        <div>
            <h1 class="text-2xl font-bold text-amber-950">Log Aktivitas</h1>
            <p class="text-amber-700/80 text-sm">Riwayat aksi yang dilakukan admin di dalam sistem.</p>
        </div>

        {{-- Filter --}} 
        <form method="GET" action="{{ route('activity-logs.index') }}"
            class="bg-white p-5 rounded-2xl border border-amber-100 shadow-sm flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Modul</label>
                <select name="module" class="border border-amber-200 rounded-lg px-3 py-2 text-sm text-amber-950 focus:outline-none focus:ring-2 focus:ring-amber-500 min-w-[10rem]">
                    <option value="">Semua Modul</option>
                    @foreach($modules as $module)
                        <option value="{{ $module }}" {{ request('module') === $module ? 'selected' : '' }}>{{ $module }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-xs font-bold text-gray-500 uppercase mb-1.5">Admin</label>
                <select name="user_id" class="border border-amber-200 rounded-lg px-3 py-2 text-sm text-amber-950 focus:outline-none focus:ring-2 focus:ring-amber-500 min-w-[10rem]">
                    <option value="">Semua Admin</option>
                    @foreach($admins as $admin)
                        <option value="{{ $admin->id }}" {{ (string) request('user_id') === (string) $admin->id ? 'selected' : '' }}>{{ $admin->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit"
                    class="px-5 py-2.5 bg-amber-800 hover:bg-amber-900 text-white text-sm font-bold rounded-lg shadow-sm transition-colors">
                <i class="fa-solid fa-filter mr-1"></i> Terapkan
            </button>
            @if(request('module') || request('user_id'))
            <a href="{{ route('activity-logs.index') }}"
            class="px-5 py-2.5 border border-amber-200 text-amber-800 hover:bg-amber-50 text-sm font-bold rounded-lg transition-colors">
                Reset
            </a>
            @endif
        </form>

        {{-- Tabel Log --}}
        <div class="bg-white rounded-2xl border border-amber-100 shadow-sm overflow-hidden">
            @if($logs->isEmpty())
                <div class="p-10 text-center text-sm text-gray-400">
                    Belum ada aktivitas yang tercatat.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-amber-50/50 text-amber-900 border-b border-amber-100">
                            <tr>
                                <th class="px-6 py-3 font-semibold">Waktu</th>
                                <th class="px-6 py-3 font-semibold">Admin</th>
                                <th class="px-6 py-3 font-semibold">Modul</th>
                                <th class="px-6 py-3 font-semibold">Aksi</th>
                                <th class="px-6 py-3 font-semibold">Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                            @php
                                $actionColor = match($log->action) {
                                    'create' => 'text-emerald-700 bg-emerald-100',
                                    'update' => 'text-blue-700 bg-blue-100',
                                    'delete' => 'text-rose-700 bg-rose-100',
                                    default  => 'text-gray-700 bg-gray-100',
                                };
                                $actionLabel = match($log->action) {
                                    'create' => 'Tambah',
                                    'update' => 'Ubah',
                                    'delete' => 'Hapus',
                                    default  => ucfirst($log->action),
                                };
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-xs text-gray-500">
                                    {{ $log->created_at->translatedFormat('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 font-medium">
                                    {{ $log->user->name ?? 'Sistem / User Dihapus' }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-xs font-bold text-amber-800 bg-amber-100 px-2 py-1 rounded-md whitespace-nowrap">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="{{ $actionColor }} px-2 py-1 rounded-md text-xs font-bold whitespace-nowrap">
                                        {{ $actionLabel }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    {{ $log->description }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-amber-50">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection