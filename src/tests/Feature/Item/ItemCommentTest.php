<?php

namespace Tests\Feature\Item;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Item;

class ItemCommentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログイン済みのユーザーはコメントを送信できる
     */
    public function test_user_can_comment()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $item = Item::factory()->create();

        //コメント送信
        $response = $this->post(route('item.comment', $item), [
            'comment' => 'テストコメント',
        ]);

        //DBに保存されているか確認
        $this->assertDatabaseHas('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
            'comment' => 'テストコメント',
        ]);

        //商品詳細ページにリダイレクトされコメントが表示されるか
        $response->assertRedirect(route('items.show', $item));
    }

    /**
     * ログイン前のユーザーはコメントを送信できない
     */
    public function test_guest_cannot_comment()
    {
        //商品作成
        $item = Item::factory()->create();

        //コメント送信
        $response = $this->post(route('item.comment', $item), [
            'comment' => 'ゲストコメント',
        ]);

        //未認証時はログイン画面にリダイレクトされる
        $response->assertRedirect(route('login'));


        //DBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
        'comment' => 'ゲストコメント',
        ]);
    }

    /**
     * コメントが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_comment_required()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        //ユーザ作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $item = Item::factory()->create();

        //コメントをから文字で送信
        $response = $this->post(route('item.comment', $item), [
            'comment' => '',
        ]);

        //バリデーションエラーがセッションにあるか
        $response->assertSessionHasErrors('comment');

        //DBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * コメントが255字以上の場合、バリデーションメッセージが表示される
     */
    public function test_comment_too_long()
    {
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        //ユーザー作成・ログイン
        $user = User::factory()->create();
        $this->actingAs($user);

        //商品作成
        $item = Item::factory()->create();

        //256文字のコメント
        $longComment = str_repeat('あ', 256);

        //コメントをから文字で送信
        $response = $this->post(route('item.comment', $item), [
            'comment' => $longComment,
        ]);

        //バリデーションエラーがセッションにあるか
        $response->assertSessionHasErrors('comment');

        //DBに保存されていないことを確認
        $this->assertDatabaseMissing('comments', [
            'item_id' => $item->id,
            'user_id' => $user->id,
        ]);
    }
}
