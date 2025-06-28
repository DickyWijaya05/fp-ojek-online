<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class LaporanTransaksiController extends Controller
{
    public function index()
    {
        // Data dummy
        $laporan = [
            [
                'id' => 'TRX001',
                'pelanggan' => 'Andi',
                'driver' => 'Budi',
                'tanggal' => '2025-06-22 10:30',
                'jarak' => 5.4,
                'tarif' => 15000,
                'metode' => 'QRIS',
                'status' => 'Lunas'
            ],
            [
                'id' => 'TRX002',
                'pelanggan' => 'Sari',
                'driver' => 'Dedi',
                'tanggal' => '2025-06-22 11:15',
                'jarak' => 3.1,
                'tarif' => 12000,
                'metode' => 'Tunai',
                'status' => 'Belum'
            ],
            [
                'id' => 'TRX003',
                'pelanggan' => 'Rina',
                'driver' => 'Bayu',
                'tanggal' => '2025-06-21 15:45',
                'jarak' => 8.9,
                'tarif' => 25000,
                'metode' => 'Transfer',
                'status' => 'Lunas'
            ]
        ];

        // Statistik Dummy
        $totalTransaksi = count($laporan);
        $totalPenghasilan = array_sum(array_column($laporan, 'tarif'));

        return view('admin.laporan_transaksi.index', compact('laporan', 'totalTransaksi', 'totalPenghasilan'));
    }

    public function cetakPDF()
    {
        // Data dummy yang sama
        $laporan = [
            [
                'id' => 'TRX001',
                'pelanggan' => 'Andi',
                'driver' => 'Budi',
                'tanggal' => '2025-06-22 10:30',
                'jarak' => 5.4,
                'tarif' => 15000,
                'metode' => 'QRIS',
                'status' => 'Lunas'
            ],
            [
                'id' => 'TRX002',
                'pelanggan' => 'Sari',
                'driver' => 'Dedi',
                'tanggal' => '2025-06-22 11:15',
                'jarak' => 3.1,
                'tarif' => 12000,
                'metode' => 'Tunai',
                'status' => 'Belum'
            ],
            [
                'id' => 'TRX003',
                'pelanggan' => 'Rina',
                'driver' => 'Bayu',
                'tanggal' => '2025-06-21 15:45',
                'jarak' => 8.9,
                'tarif' => 25000,
                'metode' => 'Transfer',
                'status' => 'Lunas'
            ]
        ];

        $pdf = Pdf::loadView('admin.laporan_transaksi.laporan-transaksi-pdf', compact('laporan'))
                  ->setPaper('a4', 'landscape');

        return $pdf->stream('laporan_transaksi_' . Carbon::now()->format('d-m-Y_H-i') . '.pdf');
    }
}
