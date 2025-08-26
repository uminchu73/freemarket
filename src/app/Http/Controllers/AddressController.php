<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Address;
use App\Http\Requests\AddressRequest;

class AddressController extends Controller
{
    /**
     * 住所編集画面表示
     */
    public function edit(Item $item)
    {
        $user = Auth::user();
        $address = $user->address;

        return view('address', compact('item', 'address'));
    }

    /**
     * 住所更新処理
     */
    public function update(AddressRequest $request, Item $item)
    {
        $user = Auth::user();
        $data = $request->validated(); // バリデーション済みデータを取得

        $user->address()->updateOrCreate(['user_id' => $user->id], $data);

        return redirect()->route('purchase.show', ['item' => $item->id]);
    }

}
