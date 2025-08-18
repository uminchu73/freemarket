<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Actions\Fortify\CreateNewUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function store(Request $request)
    {
        // ユーザー作成（Fortifyのアクションを呼ぶ）
        $user = app(CreateNewUser::class)->create($request->all());

        // 作成したユーザーでログイン
        Auth::login($user);

        // 登録直後にプロフィール編集画面へリダイレクト
        return redirect()->route('profile.edit');
    }
}
