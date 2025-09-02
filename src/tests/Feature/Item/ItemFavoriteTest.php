<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemFavoriteTest extends TestCase
{
    use RefreshDatabase;

    /**
     * いいね登録（色、カウントチェック）
     */
    public function test_favorite_on()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        //ユーザーを作成しログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $item = Item::factory()->create();

        //いいねアイコンを押す
        $response = $this->actingAs($user)
                ->postJson(route('item.favorite', $item), [], ['X-Requested-With' => 'XMLHttpRequest']);

        //DBに登録されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        //いいね数の表示が正しいか確認
        $response->assertStatus(200);
        $response->assertJson([
            'favorites_count' => 1,
        ]);

        //HTMLを取得して'favorited'クラスが付いているか確認（色が変わるか）
        $htmlResponse = $this->get(route('items.show', $item));
        $htmlResponse->assertSee('class="count favorited"', false);
    }

    /**
     * いいね解除
     */
    public function test_favorite_off()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        //ユーザーを作成しログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $item = Item::factory()->create();

        //いいねアイコンを押す
        $response = $this->actingAs($user)
                ->postJson(route('item.favorite', $item), [], ['X-Requested-With' => 'XMLHttpRequest']);

        //DBに登録されているか確認
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'item_id' => $item->id,
        ]);

        //いいね数の表示が正しいか確認
        $response->assertStatus(200);
        $response->assertJson([
            'favorites_count' => 1,
        ]);

        //HTMLを取得して'favorited'クラスが付いているか確認（色が変わるか）
        $htmlResponse = $this->get(route('items.show', $item));
        $htmlResponse->assertSee('class="count favorited"', false);

        //再度いいねアイコンを押す（解除）
        $response = $this->postJson(route('item.favorite', $item), [], ['X-Requested-With' => 'XMLHttpRequest']);

        //DBから消えているか確認
        $this->assertDatabaseMissing('favorites', [
        'user_id' => $user->id,
        'item_id' => $item->id,
        ]);

        //カウントが減少表示されるか確認
        $response->assertJson(['favorites_count' => 0]);

        //クラスが消えてるか確認
        $html = $this->get(route('items.show', $item))->getContent();
        $this->assertStringNotContainsString('class="count favorited"', $html);

    }

}
