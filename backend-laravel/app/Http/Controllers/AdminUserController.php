<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AdminUserController extends Controller
{
    // GET /api/users
    public function index()
    {
        return response()->json(User::all(), 200);
    }

    // GET /api/users/{id}
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        return response()->json($user, 200);
    }

    // PUT /api/users/{id}/verify
    public function verify($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $user->is_verified = true;
        $user->save();

        return response()->json(['message' => 'User berhasil diverifikasi.'], 200);
    }

    // PUT /api/users/{id}/activate
    public function activate($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => 'Status user berhasil diubah.',
            'is_active' => $user->is_active
        ], 200);
    }

    // DELETE /api/users/{id}
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User berhasil dihapus.'], 200);
    }
}
