<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    /** -------- GOOGLE ---------- **/
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();
        $authUser = $this->findOrCreateUser($user, 'google');
        Auth::login($authUser, true);

        return redirect()->intended('account');
    }

    /** -------- FACEBOOK ---------- **/
    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();
        $authUser = $this->findOrCreateUser($user, 'facebook');
        Auth::login($authUser, true);

        return redirect()->intended('account');
    }

    /** -------- APPLE ---------- **/
    public function redirectToApple()
    {
        return Socialite::driver('apple')->redirect();
    }

    public function handleAppleCallback()
    {
        $user = Socialite::driver('apple')->user();
        $authUser = $this->findOrCreateUser($user, 'apple');
        Auth::login($authUser, true);

        if (empty($authUser->name)) {
            return redirect()->route('personal-data.edit');
        }

        // Иначе — на аккаунт
        return redirect()->intended('account');
    }

    /** -------- Общий метод ---------- **/
    private function findOrCreateUser($socialUser, $provider)
    {
        $user = User::where('email', $socialUser->getEmail())->first();

        if ($user) {
            $user->update([
                'provider'    => $provider,
                'provider_id' => $socialUser->getId(),
            ]);

            return $user;
        }

        $fullName = $socialUser->getName();
        if (!$fullName && isset($socialUser->user['name'])) {
            $fullName = $socialUser->user['name'];
        }

        $nameParts = $fullName ? explode(' ', $fullName, 2) : [null, null];
        $firstName = $nameParts[0] ?? 'Користувач';
        $lastName = $nameParts[1] ?? '';

        return User::create([
            'name'         => $firstName,
            'last_name'    => $lastName,
            'email'        => $socialUser->getEmail(),
            'password'     => bcrypt(Str::random(32)),
            'provider'     => $provider,
            'provider_id'  => $socialUser->getId(),
        ]);
    }
}
