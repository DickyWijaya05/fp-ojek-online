<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Customer;
use App\Models\Driver;

class LaporanTransaksiController extends Controller
{
    public function index()
    {
        // Ambil semua transaksi dengan relasi customer dan driver
        $transaksi = Transaction::with(['customer', 'driver'])->latest()->get();

        // Mapping data laporan
        $laporan = $transaksi->map(function ($trx) {
            return [
                'id' => $trx->id,
                'pelanggan' => optional($trx->customer)->name ?? '-',  // ✅ Perbaikan di sini
                'driver' => optional($trx->driver)->name ?? '-',        // ✅ Perbaikan di sini
                'tanggal' => $trx->created_at->format('d M Y H:i'),
                'jarak' => $trx->distance ?? 0,
                'tarif' => $trx->total_price ?? 0,
                'metode' => ucfirst($trx->metode ?? 'Tunai'),
                'status' => 'Lunas', // Ganti jika punya kolom status pembayaran
            ];
        });

        // Hitung total transaksi dan penghasilan
        $totalTransaksi = $laporan->count();
        $totalPenghasilan = $laporan->sum('tarif');

        // Kirim ke view
       return view('admin.laporan_transaksi.index', compact('laporan', 'totalTransaksi', 'totalPenghasilan'));
    }
}
