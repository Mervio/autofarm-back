<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $remember = $request->has('remember') ? $request->has('remember') : false;
        
        if (Auth::attempt($request->only('email', 'password'), $remember)) {
            Session::regenerate();

            return response()->json([
                'data' => Auth::user()
            ], 200);
        }

        throw ValidationException::withMessages([
            'messages' => ['Email e/ou senha incorreto(s)!']
        ]);
    }

    public function logout()
    {
        try {
            Auth::guard('web')->logout();
            Session::invalidate();
            Session::regenerateToken();

            return response()->json(null, 204);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return response()->json(['message' => __($status)], 202);
        }

        throw ValidationException::withMessages([
            'messages' => [trans($status)],
        ]);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return response([
                'message'=> 'Password reset successfully!'
            ]);
        }

        return response([
            'message'=> __($status)
        ], 500);
    }
}
