<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $user = Socialite::driver('google')->user();

        // Логика авторизации и регистрации
        $authUser = $this->findOrCreateUser($user, 'google');
        Auth::login($authUser, true);

        return redirect()->intended('account');
    }

    private function findOrCreateUser($socialUser, $provider)
    {
        $user = User::where('email', $socialUser->email)->first();

        if ($user) {
            return $user;
        }


        $nameParts = explode(' ', $socialUser->name, 2);

        $firstName = $nameParts[0] ?? null;
        $lastName = $nameParts[1] ?? null;

        return User::create([
            'name'      => $firstName,
            'last_name' => $lastName,
            'email'     => $socialUser->email,
            'password'  => bcrypt('social_login'),
        ]);
    }

    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function handleFacebookCallback()
    {
        $user = Socialite::driver('facebook')->user();

        // Логика авторизации и регистрации
        $authUser = $this->findOrCreateUser($user, 'facebook');
        Auth::login($authUser, true);

        return redirect()->intended('account');
    }

    public function redirectToApple()
    {
        return Socialite::driver('apple')->redirect();
    }

    public function handleAppleCallback()
    {
        $user = Socialite::driver('apple')->user();

        // Логика авторизации и регистрации
        $authUser = $this->findOrCreateUser($user, 'apple');
        Auth::login($authUser, true);

        return redirect()->intended('/');
    }
}
