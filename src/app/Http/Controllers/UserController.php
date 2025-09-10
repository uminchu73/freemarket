<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Purchase;


class UserController extends Controller
{

    /**
     * マイページ表示
     */
    public function show(Request $request)
    {
        //現在ログイン中のユーザー取得
        $user = Auth::user();
        $tab = $request->tab ?? 'exhibited';

        //出品商品（Userモデルのリレーションから取得）
        $exhibitedItems = $user->items;

        //購入商品（Purchase経由でItemを取得）
        $purchasedItems = $user->purchases()->with('item')->get();

        return view('mypage.mypage', compact('user', 'exhibitedItems', 'purchasedItems', 'tab'));
    }

    /**
     * プロフィール編集画面表示
     */
    public function edit()
    {
        $user = Auth::user()->load('address');
        $address = $user->address ?? null;

        return view('mypage.edit', compact('user', 'address'));
    }

    /**
     * プロフィール更新処理
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        //ユーザー名を更新
        $user->name = $request->name;

        //プロフィール画像アップロード
        if ($request->hasFile('profile_img')) {
            $path = $request->file('profile_img')->store('profiles', 'public');
            $user->profile_img = $path;
        }

        //DB 保存
        $user->save();

        //住所を更新 or 作成
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only('postal_code', 'address', 'building')
        );

        return redirect('/');
    }


}
