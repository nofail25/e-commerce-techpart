<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;

class GoogleOAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')
            ->stateless()
            ->scopes(['email', 'profile'])
            ->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $googleId = $googleUser->getId();
        $email = $googleUser->getEmail();

        $user = User::where('google_id', $googleId)->first();

        // fallback by email (bind google_id to existing account)
        if (!$user && $email) {
            $user = User::where('email', $email)->first();
        }

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: ($email ? strtok($email, '@') : 'Google User'),
                'email' => $email ?: ($googleId . '@google.local'),
                'password' => Hash::make(\Illuminate\Support\Str::random(32)),
                'role' => 'regular',
                'upgradeRequested' => false,
                'google_id' => $googleId,
            ]);
        } else {
            if (!$user->google_id) {
                $user->google_id = $googleId;
                $user->save();
            }
        }

        Auth::login($user);
        $request->session()->regenerate();

        if (Auth::user()->role === 'admin') {
            return redirect('/admin/dashboard')->with('success', 'Selamat datang kembali, Admin!');
        }

        return redirect('/katalog');
    }
}
