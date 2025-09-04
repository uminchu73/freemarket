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
        $user = Auth::user();
        $tab = $request->tab ?? 'exhibited';
        $exhibitedItems = Item::where('user_id', $user->id)->get();
        $purchasedItems = $user->purchases()->with('item')->get();

        return view('mypage.mypage', compact('user', 'exhibitedItems', 'purchasedItems', 'tab'));
    }

    /**
     * プロフィール編集
     */
    public function edit()
    {
        $user = Auth::user()->load('address');
        $address = $user->address ?? null;

        return view('mypage.edit', compact('user', 'address'));
    }


    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // ユーザー名を更新
        $user->name = $request->name;

        // プロフィール画像アップロード
        if ($request->hasFile('profile_img')) {
            $path = $request->file('profile_img')->store('profiles', 'public');
            $user->profile_img = $path;
        }

        // DB 保存
        $user->save();

        // 住所を更新 or 作成
        $user->address()->updateOrCreate(
            ['user_id' => $user->id],
            $request->only('postal_code', 'address', 'building')
        );

        return redirect('/');
    }


}
