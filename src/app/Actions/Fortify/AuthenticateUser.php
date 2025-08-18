<?php

namespace App\Actions\Fortify;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthenticateUser
{
    public function __invoke(Request $request)
    {
        // AppのLoginRequestのバリデーションルールをここで手動適用
        $request->validate(LoginRequest::$rules, LoginRequest::$messages);

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'メールアドレスまたはパスワードが正しくないです。',
            ]);
        }

        $request->session()->regenerate();

        return Auth::user();
    }
}
