<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    // Menampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Validasi input registrasi
        $request->validate([
            'name'     => 'required',
            'username' => 'required|unique:users',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        // Generate identifier 7 digit yang unik
        $identifier = $this->generateIdentifier();

        // Buat user dengan identifier yang unik
        User::create([
            'name'       => $request->name,
            'username'   => $request->username,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
            'identifier' => $identifier,  // Simpan identifier yang sudah di-generate
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil, silakan login!');
    }

    /**
     * Menghasilkan identifier acak 7 digit yang unik.
     *
     * Fungsi ini akan menghasilkan angka acak antara 0 dan 9,999,999, kemudian
     * melakukan padding (leading zeros) agar selalu 7 digit, misalnya "0001234".
     * Fungsi akan mengecek ke database agar identifier yang dihasilkan tidak duplikat.
     *
     * @return string
     */
    private function generateIdentifier()
    {
        do {
            // Generate angka acak antara 0 dan 9,999,999, kemudian pad menjadi 7 digit.
            $code = str_pad(mt_rand(0, 9999999), 7, '0', STR_PAD_LEFT);
        } while (User::where('identifier', $code)->exists()); // Ulang jika sudah ada

        return $code;
    }

    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('home');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Logout berhasil.');
    }
}
