<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{
    public function index()
    {
        //ログインユーザーのマイリスト取得
        $items = auth()->user()->mylist()->with('purchase')->get();
        //タブ情報も渡す
        $tab = 'mylist';

        return view('index', compact('items', 'tab'));
    }


    public function toggle(Item $item)
    {
        $user = Auth::user();

        if ($user->favoriteItems()->where('item_id' , $item->id)->exists()) {
            $user->favoriteItems()->detach($item);
        } else {
            $user->favoriteItems()->attach($item);
        }

        // Ajax リクエストなら JSON で返す
        if (request()->ajax()) {
            return response()->json([
                'favorites_count' => $item->favoritedByUsers()->count()
            ]);
        }

        return back();
    }
}
