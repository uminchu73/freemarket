<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Purchase;

class ItemAddressTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 送付先住所変更画面にて登録した住所が商品購入画面に反映されている
     */
    public function test_address_show()
    {
        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $item = Item::factory()->create();

        //住所登録
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区テスト1-2-3',
        ]);

        //商品購入画面を開く
        $response = $this->get(route('purchase.show', $item->id));

        //購入画面に住所が表示されることを確認
        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区テスト1-2-3');
    }

    /**
     * 購入した商品に送付先住所が紐づいて登録される
     */
    public function test_address_correct()
    {
        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $item = Item::factory()->create();

        //住所登録
        $address = Address::factory()->create([
            'user_id' => $user->id,
            'postal_code' => '123-4567',
            'address' => '東京都新宿区テスト1-2-3',
        ]);

        //購入リクエスト
        $response = $this->post(route('purchase.store', $item), [
            'payment_method' => 2,
            'address_id' => $address->id,
        ]);

        //DBの購入商品に正しい住所IDが紐づいているか
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'address_id' => $address->id,
        ]);

        //トップページににリダイレクト
        $response->assertRedirect('/');
    }

}

