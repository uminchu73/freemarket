<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Requests\PurchaseRequest;

class PurchaseController extends Controller
{
    /**
     * 購入ページ表示
     */
    public function show(Item $item)
    {
        //ユーザー情報に住所をロード
        $user = Auth::user()->load('address');

        return view('purchase', compact('item', 'user'));
    }

    /**
     * 購入処理（Stripe 決済 or テスト環境用の即時購入）
     * - 商品がすでに購入済みかをチェック
     * - テスト環境なら決済を通さず直接購入処理
     * - 本番環境では Stripe セッションを作成して決済ページへ遷移

     */
    public function store(PurchaseRequest $request, Item $item)
    {
        //すでに購入済みの商品は買えないようにする
        if ($item->status == 1) {
            return back()->with('error', 'この商品はすでに購入済みです');
        }

        //テスト環境では Stripe を経由せず即購入
        if (app()->environment('testing')) {
            $user = Auth::user();
            $item->purchaseBy($user, $request->payment_method ?? 2);

            return redirect()->route('home');
        }

        //本番環境では Stripe セッションを作成して決済ページにリダイレクト
        try {
            $session = $item->createStripeSession(
                (int) $request->payment_method,
                route('purchase.success', $item),
                route('purchase.cancel', $item)
            );
        } catch (\Exception $e) {
            return back()->with('error', 'Stripe セッション作成エラー: ' . $e->getMessage());
        }

        return redirect($session->url);
    }

    /**
     * 決済成功処理
     */
    public function success(Request $request, Item $item)
    {
        $user = Auth::user();

        // まだ購入されていなければ購入処理
        if ($item->status == 0) {
            $item->purchaseBy($user, $request->payment_method ?? 2);
        }

        return redirect()->route('home');
    }

    /**
     * 決済キャンセル処理
     */
    public function cancel(Item $item)
    {
        return redirect()->route('purchase.show', $item);
    }
}
