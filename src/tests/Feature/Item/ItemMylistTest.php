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
        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $seller = User::factory()->create();
        $item1 = Item::factory()->create([
            'title' => 'テスト商品A',
            'user_id' => $seller->id,
        ]);
        $item2 = Item::factory()->create([
            'title' => 'テスト商品B',
            'user_id' => $seller->id,
        ]);

        //item1をいいね登録
        $user->favoriteItems()->attach($item1);

        //マイリスト表示
        $response = $this->get(route('home', ['tab' => 'mylist']));
        $response->assertStatus(200);

        //item1は表示される
        $response->assertSee('テスト商品A');

        //item2は表示されない
        $response->assertDontSee('テスト商品B');
    }

    /**
     * 購入済み商品は「Sold」と表示される
     */
    public function test_sold_label()
    {
        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $seller = User::factory()->create();
        $item = Item::factory()->create([
            'user_id' => $seller->id,
            'status' => 1 // 1=購入済み
        ]);

        // お気に入りに登録
        $user->favoriteItems()->attach($item);

        //マイリスト表示
        $response = $this->get(route('home', ['tab' => 'mylist']));
        $response->assertStatus(200);

        //作成した商品タイトルとSoldラベルが表示されるか確認
        $response->assertSee($item->title);
        $response->assertSee('Sold');
    }

    /**
     * 自分が出品した商品は表示されない
     */
    public function test_my_products_not_shown()
    {
        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //自分が出品した商品
        $item1 = Item::factory()->create([
            'user_id' => $user->id,
            'title' => '自分の商品',
        ]);

        //別のユーザーが出品した商品
        $seller = User::factory()->create();
        $item2 = Item::factory()->create([
            'user_id' => $seller->id,
            'title' => '他人の商品',
            'img_url' => 'sample.jpg',
        ]);

        //item2をいいねする
        $user->favoriteItems()->attach($item2);


        //マイリスト表示
        $response = $this->get(route('home', ['tab' => 'mylist']));
        $response->assertStatus(200);

        //item1は表示されない
        $response->assertDontSee('自分の商品');

        //item2は表示される
        $response->assertSee('他人の商品');
    }

}
