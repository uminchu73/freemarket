<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * ログアウトができる
     */
    public function test_logout()
    {
        //ユーザー作成
        $user = User::factory()->create();

        //ログインしていることを確認
        $this->actingAs($user);

        $this->assertAuthenticated();

        //ログアウトリクエストを送信
        $response = $this->post('/logout');

        //ログアウトしてトップ画面に遷移したか確認
        $this->assertGuest();

        $response->assertRedirect('/');

    }
}
