<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use Carbon\Carbon;

class LaporanAktivitasController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->filter;
        $query = ActivityLog::with('user')
            ->where('level_id', 2); // hanya driver

        // Filter waktu
        if ($filter == 'hari') {
            $query->whereDate('created_at', today());
        } elseif ($filter == 'minggu') {
            $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($filter == 'bulan') {
            $query->whereMonth('created_at', now()->month);
        }

        $logs = $query->latest()->get();

        // Data untuk tabel
        $aktivitas = $logs->map(function ($log) {
            return [
                'waktu' => $log->created_at->format('d M Y H:i'),
                'nama' => optional($log->user)->name ?? 'Tidak diketahui',
                'jenis' => $log->activity,
                'ip' => $log->ip_address ?? '-',
                'device' => $log->device ?? '-',
            ];
        });

        // Statistik dashboard
        $todayLogs = ActivityLog::whereDate('created_at', today())->where('level_id', 2);
        $totalAktivitas = $todayLogs->count();
        $loginDriver = $todayLogs->where('activity', 'Login')->count();
        $logoutDriver = $todayLogs->where('activity', 'Logout')->count();

        return view('admin.laporan_aktivitas.index', compact(
            'aktivitas',
            'totalAktivitas',
            'loginDriver',
            'logoutDriver'
        ));
    }
}
