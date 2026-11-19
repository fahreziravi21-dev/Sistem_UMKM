<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle a login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $pengguna = DB::table('penggunas')
            ->where('username', $request->username)
            ->first();

        if (!$pengguna || !Hash::check($request->password, $pengguna->password)) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ])->withInput($request->only('username'));
        }

        // Store all fields except password in session
        session([
            'pengguna' => [
                'id_pengguna'  => $pengguna->id_pengguna,
                'nama_pengguna' => $pengguna->nama_pengguna,
                'username'     => $pengguna->username,
                'no_tlp'       => $pengguna->no_tlp,
                'alamat'       => $pengguna->alamat,
                'level'        => $pengguna->level,
            ],
        ]);

        if ($pengguna->level === 'admin') {
            return redirect()->route('admin.dashboard')->with('success', 'Selamat datang, Admin!');
        }

        return redirect()->route('beranda')->with('success', 'Selamat datang, ' . $pengguna->nama_pengguna . '!');
    }

    /**
     * Handle a registration request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'username'      => 'required|string|max:255|unique:penggunas,username',
            'password'      => 'required|string|min:6|confirmed',
            'no_tlp'        => 'nullable|string|max:20',
            'alamat'        => 'nullable|string|max:500',
        ], [
            'nama_pengguna.required' => 'Nama lengkap wajib diisi.',
            'username.required'      => 'Username wajib diisi.',
            'username.unique'        => 'Username sudah digunakan, pilih username lain.',
            'password.required'      => 'Password wajib diisi.',
            'password.min'           => 'Password minimal 6 karakter.',
            'password.confirmed'     => 'Konfirmasi password tidak cocok.',
        ]);

        $id = DB::table('penggunas')->insertGetId([
            'nama_pengguna' => $request->nama_pengguna,
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'no_tlp'        => $request->no_tlp,
            'alamat'        => $request->alamat,
            'level'         => 'pembeli',
        ]);

        session([
            'pengguna' => [
                'id_pengguna'   => $id,
                'nama_pengguna' => $request->nama_pengguna,
                'username'      => $request->username,
                'no_tlp'        => $request->no_tlp,
                'alamat'        => $request->alamat,
                'level'         => 'pembeli',
            ],
        ]);

        return redirect()->route('beranda')->with('success', 'Registrasi berhasil! Selamat datang, ' . $request->nama_pengguna . '!');
    }

    /**
     * Handle a logout request.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        session()->flush();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
