<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            // Mengambil data user dari Google [Tanpa withoutVerifying]
            $googleUser = Socialite::driver('google')->user();

            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                return redirect('/app/login')->with('error', 'Email Anda tidak terdaftar.');
            }

            // Simpan google_id jika masih kosong
            if (is_null($user->google_id)) {
                $user->google_id = $googleUser->id;
                $user->save();
            }

            // Memberikan role admin jika email adalah admin utama
            if ($user->email === 'dayat.hidayat@unpad.ac.id') {
                if (method_exists($user, 'assignRole')) {
                    $user->assignRole('admin');
                }
            }

            // Login dan regenerasi session
            Auth::login($user);
            $request->session()->regenerate();

            // Redirect langsung ke dashboard admin
            return redirect()->to('https://sarpras.unpad.ac.id/app');

        } catch (\Throwable $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/app/login')->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
