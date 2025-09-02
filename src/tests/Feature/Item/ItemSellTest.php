<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use App\Models\User;
use App\Models\Category;
use App\Models\Item;

class ItemSellTest extends TestCase
{
    use RefreshDatabase;

    public function test_item_sell()
    {
        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //カテゴリ作成
        $category1 = Category::factory()->create(['content' => '家具']);
        $category2 = Category::factory()->create(['content' => '家電']);

        //出品リクエスト
        $response = $this->post(route('items.store'), [
            'category_ids' => [$category1->id, $category2->id],
            'condition'   => 2,
            'title'       => 'テスト商品',
            'description' => 'これはテスト用の商品です',
            'price'       => 5000,
            'image' => UploadedFile::fake()->create('test.jpg', 100),
        ]);

        //リダイレクト確認
        $response->assertRedirect('/?tab=all');

        //DBに正しく保存されたか確認
        $this->assertDatabaseHas('items', [
            'user_id' => $user->id,
            'title'   => 'テスト商品',
            'description' => 'これはテスト用の商品です',
            'price'   => 5000,
            'condition' => 2,
        ]);

        //カテゴリが紐づいているか確認
        $item = Item::where('title', 'テスト商品')->first();
        $this->assertTrue($item->categories->contains($category1));
        $this->assertTrue($item->categories->contains($category2));
    }
}
