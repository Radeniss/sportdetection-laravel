<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    // Arahkan user ke halaman login Google
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // Tangani callback dari Google
public function handleGoogleCallback()
{
    try {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('google_id', $googleUser->getId())->first();

        if (!$user) {
            // Buat user baru kalau belum ada
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(Str::random(16)), // password random
            ]);
        }

        // ✅ Login user ke Laravel session
        Auth::login($user);

        // ✅ Redirect ke halaman utama
        return redirect()->intended('/');

    } catch (\Exception $e) {
        dd($e->getMessage());
    }
}

}
