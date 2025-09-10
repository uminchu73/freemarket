<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;


class FavoriteController extends Controller
{
    public function toggle(Item $item)
    {
        $user = Auth::user();

        $user->favoriteItems()->toggle($item->id);

        // Ajax リクエストなら JSON で返す
        if (request()->ajax()) {
            return response()->json([
                'favorites_count' => $item->favoritedByUsers()->count()
            ]);
        }

        return back();
    }
}
