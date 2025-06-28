<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TarifConfig;

class TarifController extends Controller
{
    /**
     * ✅ API: Tampilkan tarif saat ini (JSON)
     */
    public function index()
    {
        $tarif = TarifConfig::first();
        return response()->json($tarif);
    }

    /**
     * ✅ API: Update tarif (JSON)
     */
    public function update(Request $request)
    {
        $request->validate([
            'price_per_km' => 'required|numeric|min:0',
        ]);

        $tarif = TarifConfig::first();
        if (!$tarif) {
            $tarif = new TarifConfig();
        }

        $tarif->price_per_km = $request->price_per_km;
        $tarif->save();

        return response()->json([
            'message' => '✅ Tarif berhasil diperbarui',
            'data' => $tarif
        ]);
    }

    /**
     * ✅ WEB: Tampilkan form edit tarif di Blade view
     */
    public function edit()
    {
        $tarif = TarifConfig::first();
        return view('admin.tarif.edit', compact('tarif'));
    }

    /**
     * ✅ WEB: Simpan tarif dari form Blade
     */
    public function updateWeb(Request $request)
    {
        $request->validate([
            'price_per_km' => 'required|numeric|min:0',
        ]);

        $tarif = TarifConfig::first();
        if (!$tarif) {
            $tarif = new TarifConfig();
        }

        $tarif->price_per_km = $request->price_per_km;
        $tarif->save();

        return redirect()->back()->with('success', '✅ Tarif berhasil diperbarui');
    }
}
