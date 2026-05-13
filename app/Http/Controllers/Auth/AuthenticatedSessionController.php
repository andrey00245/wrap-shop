<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AuthenticatedSessionController extends Controller
{
    /**
     * Редирект на головну з відкриттям модалки входу.
     */
    public function create(): RedirectResponse
    {
        return redirect()->to(route('index').'?modal=login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        return response()->json([
            'redirect_url' => route('account'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function checkUser(Request $request)
    {
        $phoneEmail = $request->input('phone_email');

        $user = User::where('email', $phoneEmail)->orWhere('phone', $phoneEmail)->first();

        if ($user) {
            return response()->json(['exists' => true]);
        }

        return response()->json(['exists' => false]);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $theme = Session::get('theme') ?? 'dark';
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Session::put('theme', $theme);

        return redirect('/');
    }
}
