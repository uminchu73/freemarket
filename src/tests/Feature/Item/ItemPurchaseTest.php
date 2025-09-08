<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Purchase;

class ItemPurchaseTest extends TestCase
{

    use RefreshDatabase;

    /**
     * 「購入する」ボタンを押下すると購入が完了しSold表示になる。
     */
    public function test_purchase_item()
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


        //購入リクエスト
        $response = $this->post(route('purchase.store', $item), [
            'payment_method' => 2,
            'address_id' => $user->address->id,
        ]);

        //DBに購入記録があるか確認
        $this->assertDatabaseHas('purchases', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);

        //購入後はトップページにリダイレクト
        $response->assertRedirect('/');

        //商品がsold状態になっているか確認
        $topResponse = $this->get('/');
        $topResponse->assertSee('Sold');
    }

    /**
     * 「プロフィール/購入した商品一覧」に追加されている
     */
    public function test_purchase_item_in_profile()
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


        //購入リクエスト
        $response = $this->post(route('purchase.store', $item), [
            'payment_method' => 2,
            'address_id' => $user->address->id,
        ]);

        //プロフィール画面で確認
        $response = $this->get(route('profile.show', ['tab' => 'purchased']));

        $response->assertStatus(200);
        $response->assertSee($item->title);
        $response->assertSee($item->img_url);
    }
}
