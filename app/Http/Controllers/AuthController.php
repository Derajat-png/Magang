<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Umkm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            if ($user->status !== 'active') {
                Auth::logout();
                return back()->withErrors(['email' => 'Akun Anda telah dinonaktifkan.'])->withInput();
            }

            $request->session()->regenerate();
            return $this->redirectBasedOnRole($user)->with('success', 'Selamat datang kembali, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('success', 'Berhasil logout.');
    }

    public function showRegisterUmkm()
    {
        return view('auth.register-umkm');
    }

    public function registerUmkm(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'umkm_name' => ['required', 'string', 'min:3', 'max:255'],
            'business_type' => ['required', 'string', 'in:kuliner,fashion,kerajinan,jasa,pertanian,perikanan'],
            'phone' => ['required', 'string', 'max:20'],
            'address' => ['required', 'string'],
        ]);

        DB::transaction(function () use ($request) {
            // 1. Create the user as owner
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'owner',
                'status' => 'active',
            ]);

            // 2. Create the UMKM
            $umkm = Umkm::create([
                'owner_id' => $user->id,
                'name' => $request->umkm_name,
                'business_type' => $request->business_type,
                'phone' => $request->phone,
                'address' => $request->address,
                'status' => 'pending', // pending approval
            ]);

            // 3. Link owner back to UMKM
            $user->update(['umkm_id' => $umkm->id]);
        });

        return redirect()->route('login')->with('success', 'Registrasi UMKM berhasil! Silakan login. Status UMKM Anda "Pending" menunggu aktivasi admin.');
    }

    private function redirectBasedOnRole($user)
    {
        if ($user->isSuperAdmin()) {
            return redirect()->intended('/admin/dashboard');
        } elseif ($user->isOwner()) {
            return redirect()->intended('/owner/dashboard');
        } elseif ($user->isStaff()) {
            return redirect()->intended('/staff/dashboard');
        }
        return redirect('/');
    }
}
