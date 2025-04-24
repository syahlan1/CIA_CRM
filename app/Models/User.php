<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'location',
        'is_active',
        'is_login',
        'role',
        'identifier',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_login' => 'boolean',
    ];

    protected static function generateUsername()
    {
        // Cari username terakhir yang memiliki format 'userXXXX'
        $lastUser = DB::table('users')
            ->where('username', 'like', 'user%')
            ->orderBy('id', 'desc')
            ->value('username');

        // Jika belum ada user, mulai dari user0001
        if (!$lastUser) {
            return 'user0001';
        }

        // Ambil angka dari username terakhir (misal: user0005 → 5)
        $number = (int) substr($lastUser, 4);

        // Tambah 1 angka ke username terakhir
        $newNumber = $number + 1;

        // Format ulang username dengan 4 digit angka
        return 'user' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}
