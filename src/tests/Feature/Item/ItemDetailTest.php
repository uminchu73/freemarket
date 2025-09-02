<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;
use App\Models\Comment;

class ItemDetailTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 必要な情報が表示される（商品画像、商品名、ブランド名、価格、いいね数、コメント数、商品説明、商品情報（複数のカテゴリ、商品の状態）、コメント数、コメントしたユーザー情報、コメント内容）
     */
    public function test_item_detail()
    {
        //ユーザー作成
        $user = User::factory()->create();

        //商品作成
        $item = Item::factory()
            ->hasAttached(Category::factory()->count(2))
            ->create([
                'user_id' => $user->id,
                'title' => 'テスト商品',
                'brand' => 'テストブランド',
                'price' => 5000,
                'description' => 'これは説明文です。',
                'condition' => 3,
            ]);

        //コメント作成
        Comment::factory()->create([
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'テストコメント',
        ]);

        //お気に入り登録
        $item->favoritedByUsers()->attach($user);

        //詳細ページアクセス
        $response = $this->get(route('items.show', $item->id));

        $response->assertStatus(200);

        //詳細が表示されるか確認
        $response->assertSee($item->title);
        $response->assertSee($item->brand);
        $response->assertSee(number_format($item->price));
        $response->assertSee($item->description);
        $response->assertSee($item->condition_label);

        //コメントが表示されるか確認
        $response->assertSee('テストコメント');
        $response->assertSee($user->name);

        //複数のカテゴリが表示されるか確認
        foreach ($item->categories as $category) {
            $response->assertSee($category->content);
        }
    }
}

