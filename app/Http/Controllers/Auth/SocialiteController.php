<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            Log::info('Google OAuth callback: user received', [
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        } catch (\Exception $e) {
            Log::error('Google OAuth callback: failed to get user', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->route('login')->withErrors([
                'email' => 'Gagal masuk dengan Google. Silakan coba lagi.',
            ]);
        }

        if (empty($googleUser->getEmail())) {
            Log::error('Google OAuth callback: email is empty');
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Google Anda tidak memiliki email. Silakan coba metode lain.',
            ]);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (! $user) {
            Log::info('Google OAuth callback: creating new user', [
                'email' => $googleUser->getEmail(),
            ]);
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getEmail(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
            ]);
            Log::info('Google OAuth callback: user created', [
                'id' => $user->id,
                'email' => $user->email,
            ]);
        } else {
            Log::info('Google OAuth callback: updating existing user', [
                'id' => $user->id,
                'email' => $user->email,
            ]);
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
            if (empty($user->email_verified_at)) {
                $user->update(['email_verified_at' => now()]);
            }
            Log::info('Google OAuth callback: user updated', [
                'id' => $user->id,
                'google_id' => $user->google_id,
                'has_avatar' => ! empty($user->avatar),
            ]);
        }

        Auth::login($user);
        Log::info('Google OAuth callback: user logged in', [
            'id' => $user->id,
            'redirect' => RouteServiceProvider::HOME,
        ]);

        return redirect()->intended(RouteServiceProvider::HOME);
    }
}
