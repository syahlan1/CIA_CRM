<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\KanbanController;
use App\Models\User;


Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Email atau password salah'], 401);
    }

    // Hapus token lama untuk user yang sudah login sebelumnya
    $user->tokens()->delete();

    // Buat token baru
    $token = $user->createToken('auth_token')->plainTextToken;

    return response()->json([
        'user' => $user,
        'token' => $token
    ]);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->tokens()->delete();
    return response()->json(['message' => 'Logout berhasil']);
});

Route::middleware('auth:sanctum')->get('/kanbans', [KanbanController::class, 'index']);