<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class UserProfileTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 必要な情報が取得できる（プロフィール画像、ユーザー名、出品した商品一覧、購入した商品一覧）
     */
    public function test_user_profile_show()
    {
        //ユーザー作成・ログイン
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'profile_img' => 'profile.jpg',
        ]);
        $this->actingAs($user);

        //出品商品作成
        $item1 = Item::factory()->create([
            'user_id' => $user->id,
            'title' => '出品商品1'
        ]);
        $item2 = Item::factory()->create([
            'user_id' => $user->id,
            'title' => '出品商品2'
        ]);

        //購入商品作成
        $seller = User::factory()->create();
        $purchaseItem = Item::factory()->create([
            'user_id' => $seller->id,
        ]);
        $purchase = Purchase::factory()->create([
            'user_id' => $user->id,
            'item_id' => $purchaseItem->id,
            'payment_method' => 2,
        ]);

        //出品タブで出品商品を確認
        $response = $this->get(route('profile.show', ['tab' => 'exhibited']));
        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('storage/profile.jpg');
        $response->assertSee('出品商品1');
        $response->assertSee('出品商品2');

        //購入タブで購入商品を確認
        $response = $this->get(route('profile.show', ['tab' => 'purchased']));
        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('storage/profile.jpg');
        $response->assertSee($purchaseItem->title);

    }
}
