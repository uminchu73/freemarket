<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemSearchTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 「商品名」で部分一致検索ができる
     */
    public function test_search_items()
    {
        //商品作成
        $item1 = Item::factory()->create(['title' => 'テスト商品A']);
        $item2 = Item::factory()->create(['title' => 'サンプル商品B']);

        //「テスト」で検索する
        $response = $this->get('/search?keyword=テスト');

        //検索結果に商品Aが含まれることを確認
        $response->assertSee($item1->title);

        // 検索結果に商品Bが含まれないことを確認
        $response->assertDontSee($item2->title);
    }

    /**
     * 検索状態がマイリストでも保持されている
     */
    public function test_search_items_save_mylist()
    {
        //ユーザー作成
        $seller = User::factory()->create();
        $user  = User::factory()->create();


        //商品作成
        $item1 = Item::factory()->create([
            'title' => 'テスト商品A',
            'user_id' => $seller->id,
        ]);
        $item2 = Item::factory()->create([
            'title' => 'サンプル商品B',
            'user_id' => $seller->id,
        ]);

        //いいね登録
        $user->favoriteItems()->attach($item1->id);

        //ログイン状態で「マイリスト」タブ、キーワード検索
        $response = $this->actingAs($user)->get('/search?tab=mylist&keyword=テスト');

        //検索結果に商品Aが含まれることを確認
        $response->assertSee($item1->title);

        // 検索結果に商品Bが含まれないことを確認
        $response->assertDontSee($item2->title);

        // tabがマイリストで保持されていることを確認
        $this->assertEquals('mylist', $response->viewData('tab'));

    }
}
