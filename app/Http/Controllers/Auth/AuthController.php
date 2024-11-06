<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Notifications\SendSmsNotification;
use App\Services\TurboSMSService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected TurboSMSService $turboSMS;

    public function __construct(TurboSMSService $turboSMS)
    {
        $this->turboSMS = $turboSMS;
    }

    public function sendVerificationCode(Request $request)
    {
//        $validator = Validator::make($request->all(), [
//            'phone' => 'required|string|phone:AUTO',
//        ]);

//        if ($validator->fails()) {
//            return response()->json($validator->errors(), 422);
//        }

        $phone = preg_replace('/[\s+]/', '', $request->input('phone'));

        $user = User::query()->first();


        if (!$user) {
            return response()->json([
                'errors' => [
                    'phone' => ['Пользователь с таким номером телефона не найден.']
                ]
            ], 422);
        }

        $verificationCode = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

        $expiresAt = now()->addMinutes(10);

        $user->update([
            'verification_code' => $verificationCode,
            'verification_code_expires_at' => $expiresAt,
        ]);


        $message = "Ваш код авторизації: $verificationCode";

        $user->notify(new SendSmsNotification($message));

        return response('Success', 200);
    }

    public function verifyCode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string|phone:AUTO',
            'verification_code' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $phone = preg_replace('/[\s+]/', '', $request->input('phone'));
        $code = $request->input('verification_code');

        $user = User::where('phone', $phone)
            ->where('verification_code', $code)
            ->where('verification_code_expires_at', '>', now())
            ->first();

        if (!$user) {
            return response()->json(['message' => 'Неверный код или код истек.'], 400);
        }

        $user->update([
            'verification_code' => null,
            'verification_code_expires_at' => null,
        ]);

        Auth::login($user);

        return response()->json([
            'redirect_url' => route('account')
        ]);
    }
}
