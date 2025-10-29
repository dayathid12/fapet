<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Hash;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        try {
            $googleUser = Socialite::driver('google')->withoutVerifying()->user();

            $user = User::where('email', $googleUser->email)->first();

            if (!$user) {
                return redirect('/login')->with('error', 'Email Anda tidak terdaftar. Silakan hubungi administrator.');
            }

            // Update google_id if it's null
            if (is_null($user->google_id)) {
                $user->google_id = $googleUser->id;
                $user->save();
            }

            // Assign 'admin' role to specific user
            if ($user->email === 'dayat.hidayat@unpad.ac.id') {
                $user->assignRole('admin');
            }

            Auth::login($user);

            $request->session()->regenerate();

            return redirect('/app'); // Redirect to dashboard or home

        } catch (\Throwable $e) {
            Log::error('Google Login Error: ' . $e->getMessage());
            return redirect('/app')->with('error', 'Terjadi kesalahan saat login atau Anda menolak aplikasi.');
        }
    }
}
