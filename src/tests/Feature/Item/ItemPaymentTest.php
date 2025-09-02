<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;


class ItemPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_select_payment_method()
    {
        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $user->address()->create([
            'postal_code' => '123-4567',
            'address'     => '東京都テスト町1-2-3',
            'building'    => 'テストビル101',
        ]);
        $this->actingAs($user);

        //商品作成
        $item = Item::factory()->create();

        //支払い方法選択画面にアクセス
        $response = $this->get(route('purchase.show', $item));
        $response->assertStatus(200);
        $response->assertSee('支払い方法');
        $response->assertSee('未選択');

        //支払い方法を選択して送信
        $response = $this->post(route('purchase.store', $item), [
            'payment_method' => 2,
            'address_id' => $user->address->id,
        ]);

        //DB に購入レコードがあるか確認
        $this->assertDatabaseHas('purchases', [
            'user_id' => $user->id,
            'item_id' => $item->id,
            'payment_method' => 2,
        ]);

        //購入後トップにリダイレクト
        $response->assertRedirect('/');

        //購入画面で反映されるかも確認
        $topResponse = $this->get('/');
        $topResponse->assertSee('Sold');

    }
}
