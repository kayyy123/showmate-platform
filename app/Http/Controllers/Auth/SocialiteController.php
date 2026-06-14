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
        Log::info('Google OAuth: redirecting to Google');
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        Log::info('Google OAuth callback: CALLBACK HIT');

        try {
            $googleUser = Socialite::driver('google')->user();
            Log::info('Google OAuth callback: user received from Google', [
                'id' => $googleUser->getId(),
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        } catch (\Exception $e) {
            Log::error('Google OAuth callback: FAILED to get user from Google', [
                'error' => $e->getMessage(),
                'class' => get_class($e),
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
            Log::info('Google OAuth callback: creating NEW user', [
                'email' => $googleUser->getEmail(),
                'name' => $googleUser->getName(),
            ]);
            $user = User::create([
                'name' => $googleUser->getName() ?? $googleUser->getEmail(),
                'email' => $googleUser->getEmail(),
                'role' => 'merchant',
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => bcrypt(Str::random(24)),
                'email_verified_at' => now(),
            ]);

            $user->assignRole('merchant');

            Log::info('Google OAuth callback: NEW user created & role assigned', [
                'id' => $user->id,
                'email' => $user->email,
                'role' => 'merchant',
            ]);
        } else {
            Log::info('Google OAuth callback: EXISTING user found', [
                'id' => $user->id,
                'email' => $user->email,
                'current_role' => $user->role,
                'current_roles' => $user->getRoleNames()->toArray(),
            ]);

            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);

            if (empty($user->email_verified_at)) {
                $user->update(['email_verified_at' => now()]);
                Log::info('Google OAuth callback: email_verified_at set for existing user');
            }

            // Pastikan existing user punya role merchant (jika belum punya)
            if (! $user->hasRole('merchant')) {
                $user->assignRole('merchant');
                Log::info('Google OAuth callback: merchant role assigned to existing user');
            }

            Log::info('Google OAuth callback: user updated final', [
                'id' => $user->id,
                'google_id' => $user->google_id,
                'has_avatar' => ! empty($user->avatar),
                'roles' => $user->getRoleNames()->toArray(),
            ]);
        }

        Auth::login($user);
        Log::info('Google OAuth callback: AUTH::LOGIN successful', [
            'id' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_merchant' => $user->isMerchant(),
            'auth_check' => Auth::check(),
            'auth_id' => Auth::id(),
        ]);

        // Role-based redirect
        if ($user->isAdmin()) {
            Log::info('Google OAuth callback: redirecting ADMIN to /admin/dashboard');
            return redirect()->intended('/admin/dashboard')->with('success', 'Berhasil masuk dengan Google!');
        }

        Log::info('Google OAuth callback: redirecting MERCHANT to /merchant/manage');
        return redirect()->intended('/merchant/manage')->with('success', 'Berhasil masuk dengan Google!');
    }
}
