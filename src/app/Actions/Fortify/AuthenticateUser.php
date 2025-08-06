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
    $request->validate((new LoginRequest)->rules(), (new LoginRequest)->messages());

    // 認証処理
    $credentials = $request->only('email', 'password');

    if (!Auth::attempt($credentials, $request->boolean('remember'))) {
        return null;
    }

    $request->session()->regenerate();

    return Auth::user();
}
}
