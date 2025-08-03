<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ReportAvailability;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string'],
        ]);

        if (!$request->filled('email') && !$request->filled('phone')) {
            $message = __('auth.enter_email_or_phone');

            if ($request->ajax()) {
                return response()->json([
                    'message' => $message,
                    'errors' => ['email' => [$message]],
                ], 422);
            }

            return back()->withErrors(['email' => $message]);
        }

        if ($request->filled('phone')) {
            $user = \App\Models\User::where('phone', $request->input('phone'))->first();

            if (!$user) {
                $message = __('auth.user_not_found_or_no_email');

                if ($request->ajax()) {
                    return response()->json([
                        'message' => $message,
                        'errors' => ['phone' => [$message]],
                    ], 422);
                }

                return back()->withErrors(['phone' => $message]);
            }

            $newPassword = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Обновляем пароль пользователя
            $user->password = Hash::make($newPassword);
            $user->save();


            $this->sendNewPasswordSms($user->phone, $newPassword);

            if ($request->ajax()) {
                return response()->json([
                    'message' => __('auth.sms_password_sent'), // добавь в локализацию
                ], 200);
            }

            return back()->with('status', __('auth.sms_password_sent')); // добавь в сессию

        }

        // Отправка ссылки
        $status = Password::sendResetLink($request->only('email'));

        if ($request->ajax()) {
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json(['message' => __($status)], 200);
            }

            return response()->json([
                'message' => __($status),
                'errors' => ['email' => [__($status)]],
            ], 422);
        }

        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('email'))
                ->withErrors(['email' => __($status)]);
    }

    private function sendNewPasswordSms(string $phone, string $password): void
    {
        $message = "Ваш тимчасовий пароль: {$password}\nЗмініть його після входу в налаштуваннях профілю.";

        app(\App\Services\TurboSMSService::class)->sendSms(
            [$phone],
            $message
        );
    }
}
