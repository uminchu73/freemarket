<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;

class PurchaseController extends Controller
{
    public function show(Item $item)
    {
        $user = Auth::user()->load('address');

        return view('purchase', compact('item', 'user'));
    }

    public function store(Request $request, Item $item)
    {
        if ($item->status == 1) {
            return redirect()->back()->with('error', 'この商品はすでに購入済みです');
        }

        $request->validate([
            'payment_method' => 'required|in:1,2',
        ]);

        // モデルに委譲
        $item->purchaseBy(Auth::user(), (int) $request->payment_method);

        return redirect()->route('home');
    }

}
