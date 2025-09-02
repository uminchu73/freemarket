<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Address;
use App\Models\Favorite;
use App\Models\Purchase;

class ItemMylistTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいねした商品だけが表示される
     */
    public function test_only_favorited_sown()
    {
        //ユーザー作成（出品者とログインユーザー）
        $seller = User::factory()->create();
        $user = User::factory()->create();

        //商品作成
        $item1 = Item::create([
            'img_url' => 'sample.jpg',
            'user_id' => $seller->id,
            'title' => 'いいね商品',
            'brand' => 'テストブランド',
            'description' => '商品説明',
            'price' => 500,
            'condition' => 1,
            'status' => 0,
        ]);

        $item2 = Item::create([
            'img_url' => 'sample2.jpg',
            'user_id' => $seller->id,
            'title' => 'テスト商品',
            'brand' => 'テストブランド',
            'description' => '商品説明',
            'price' => 1200,
            'condition' => 3,
            'status' => 0,
        ]);

        //item1をいいね登録
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        //マイリスト取得
        $response = $this->actingAs($user)->get(route('mylist'));

        $response->assertStatus(200);

        //item1は表示される
        $response->assertSee($item1->title);
        $response->assertSee($item1->img_url);

        //item2は表示されない
        $response->assertDontSee($item2->title);
        $response->assertDontSee($item2->img_url);
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

        //マイリスト表示
        $response = $this->actingAs($buyer)->get(route('mylist'));
        $response->assertStatus(200);

        //作成した商品タイトルとSoldラベルが表示されるか確認
        $response->assertSee('テスト購入商品');
        $response->assertSee('Sold');
    }

    /**
     * 自分が出品した商品は表示されない
     */
    public function test_my_products_not_shown()
    {
        //ユーザー作成（出品者とログインユーザー）
        $seller = User::factory()->create();
        $user = User::factory()->create();

        //商品作成
        $item1 = Item::create([
            'img_url' => 'my_item.jpg',
            'user_id' => $seller->id,
            'title' => 'テスト商品１',
            'brand' => 'テストブランド',
            'description' => 'テスト商品説明',
            'price' => 200,
            'condition' => 1,
            'status' => 0,
        ]);

        $item2 = Item::create([
            'img_url' => 'other_item.jpg',
            'user_id' => $user->id,
            'title' => 'テスト商品２',
            'brand' => 'テストブランド',
            'description' => 'テスト商品説明',
            'price' => 1500,
            'condition' => 1,
            'status' => 0,
        ]);

        //item1をいいねする
        Favorite::create([
            'user_id' => $user->id,
            'item_id' => $item1->id,
        ]);

        //マイリスト表示
        $response = $this->actingAs($user)->get(route('home'));

        $response->assertStatus(200);

        //item1は表示されない
        $response->assertDontSee('テスト商品１');

        //item2は表示される
        $response->assertSee('テスト商品２');
    }

}
