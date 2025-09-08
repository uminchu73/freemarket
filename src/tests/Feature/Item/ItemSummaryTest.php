<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Item;
use App\Models\Purchase;

class ItemSummaryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 全商品を取得できる
     */
    public function test_all_item_acquisition()
    {
        //ユーザー作成
        $user = User::factory()->create();

        //商品作成
        $item = Item::factory()->create([
            'user_id' => $user->id,
            'title' => 'テスト商品',
            'img_url' => 'sample.jpg',
            'status' => 0,
        ]);

        //商品一覧ページにアクセス
        $response = $this->get('/')->assertStatus(200);

        //作成した商品のタイトルと画像が表示されているか確認
        $response->assertSee('テスト商品');
        $response->assertSee('sample.jpg');
    }

    /**
     * 購入済み商品は「Sold」と表示される
     */
    public function test_sold_label()
    {
        //ユーザー作成（出品者と購入者）

        $seller = User::factory()->create();
        $buyer  = User::factory()->create();

        //商品作成
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'title' => 'テスト購入商品',
            'status' => 1,
        ]);

        //購入者の住所を作成
        $address = Address::factory()->create([
            'user_id' => $buyer->id,
            'postal_code' => '123-4567',
        ]);


        //商品購入情報作成
        Purchase::create([
            'user_id' => $buyer->id,
            'item_id' => $item->id,
            'address_id' => $address->id,
            'payment_method'=> 1,
            'purchased_at'  => now(),
        ]);

        //商品一覧ページにアクセス
        $response = $this->get('/')->assertStatus(200);

        //作成した商品タイトルとSoldラベルが表示されるか確認
        $response->assertSee('テスト購入商品');
        $response->assertSee('Sold');
    }

    /**
     * 自分が出品した商品は表示されない
     */
    public function test_my_products_not_shown()
    {
        //ユーザー作成
        $user = User::factory()->create();

        //自分が出品した商品作成
        $item1 = Item::factory()->create([
            'user_id' => $user->id,
            'title' => '自分の商品',
        ]);

        //別ユーザーが出品した商品作成
        $seller = User::factory()->create();
        $item2 = Item::factory()->create([
            'user_id' => $seller->id,
            'title' => '他人の商品',
        ]);

        //ログイン状態にする
        $this->actingAs($user);

        //商品一覧ページにアクセス
        $response = $this->get('/')->assertStatus(200);

        //自分の商品は表示されない
        $response->assertDontSee('自分の商品');

        //他人の商品は表示される
        $response->assertSee('他人の商品');
    }
}
