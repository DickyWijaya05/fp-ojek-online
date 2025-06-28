<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class LaporanAktivitasController extends Controller
{
    public function index()
    {
        // Dummy data aktivitas
        $aktivitas = [
            [
                'waktu' => '2025-06-23 08:32:11',
                'nama' => 'Budi',
                'jenis' => 'Login',
                'ip' => '192.168.1.10',
                'device' => 'Chrome (Windows)'
            ],
            [
                'waktu' => '2025-06-23 10:12:45',
                'nama' => 'Dedi',
                'jenis' => 'Logout',
                'ip' => '192.168.1.11',
                'device' => 'Firefox (Linux)'
            ],
            [
                'waktu' => '2025-06-23 11:20:03',
                'nama' => 'Bayu',
                'jenis' => 'Login',
                'ip' => '192.168.1.12',
                'device' => 'Safari (MacOS)'
            ]
        ];

        // Statistik dummy
        $totalAktivitas = count($aktivitas);
        $loginDriver = collect($aktivitas)->where('jenis', 'Login')->count();
        $logoutDriver = collect($aktivitas)->where('jenis', 'Logout')->count();

        return view('admin.laporan_aktivitas.index', compact(
            'aktivitas',
            'totalAktivitas',
            'loginDriver',
            'logoutDriver'
        ));
    }
}
