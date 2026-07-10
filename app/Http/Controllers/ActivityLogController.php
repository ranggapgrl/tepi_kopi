<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * ADMIN ONLY — /log-aktivitas
     * Menampilkan riwayat aksi yang dilakukan admin/staff di dalam sistem.
     */
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest('created_at');

        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $logs = $query->paginate(20)->withQueryString();

        // Buat dropdown filter
        $modules = ActivityLog::select('module')->distinct()->orderBy('module')->pluck('module');
        $admins = User::where('role', 'admin')->orderBy('name')->get(['id', 'name']);

        return view('admin.activity-logs.index', compact('logs', 'modules', 'admins'));
    }
}