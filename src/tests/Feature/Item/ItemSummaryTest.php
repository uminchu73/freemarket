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
        $item = Item::create([
            'img_url' => 'sample.jpg',
            'user_id' => $user->id,
            'title' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => '商品説明',
            'price' => 1000,
            'condition' => 1,
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
        $item = Item::create([
            'img_url' => 'sample.jpg',
            'user_id' => $seller->id,
            'title' => 'テスト購入商品',
            'brand' => 'テストブランド',
            'description' => '商品説明',
            'price' => 1000,
            'condition' => 1,
            'status' => 1, //１＝購入済み
        ]);

        //購入者の住所を作成
        $address = Address::create([
            'user_id'     => $buyer->id,
            'postal_code' => '123-4567',
            'address'     => '東京都テスト町1-2-3',
            'building'    => 'テストビル101',
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
        $myItem = Item::create([
            'img_url' => 'my_item.jpg',
            'user_id' => $user->id,
            'title' => '自分の商品',
            'brand' => '自ブランド',
            'description' => '自分の商品説明',
            'price' => 200,
            'condition' => 1,
            'status' => 0,
        ]);

        //別ユーザーが出品した商品作成
        $otherUser = User::factory()->create();
        $otherItem = Item::create([
            'img_url' => 'other_item.jpg',
            'user_id' => $otherUser->id,
            'title' => '他人の商品',
            'brand' => '他ブランド',
            'description' => '他人の商品説明',
            'price' => 1500,
            'condition' => 1,
            'status' => 0,
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
