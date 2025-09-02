<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_email_required()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/login')->assertStatus(200);

        //入力データを送信（パスワードのみ）
        $response = $this->post('/login', [
            'email' => '',
            'password' => 'password123',
        ]);

        //バリデーションが表示されたか確認
        $response->assertSessionHasErrors([
            'email' => 'メールアドレスを入力してください',
        ]);
    }

    /**
     * パスワードが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_password_required()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/login')->assertStatus(200);

        //入力データを送信（メールアドレスのみ）
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => '',
        ]);

        //バリデーションが表示されたか確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /**
     * 入力情報が間違っている場合、バリデーションメッセージが表示される
     */
    public function test_invalid_user()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/login')->assertStatus(200);

        //入力データを送信（誤った情報）
        $response = $this->post('/login', [
            'email' => 'nouser@example.com',
            'password' => 'misspassword',
        ]);

        //バリデーションが表示されたか確認
        $response->assertSessionHasErrors([
            'email' => 'ログイン情報が登録されていません',
        ]);
    }

    public function test_success()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/login')->assertStatus(200);

        //ユーザー作成
        $user = User::factory()->create([
        'email' => 'test@example.com',
        'password' => bcrypt('password123'),
        ]);

        //入力データを送信
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);


        //ログイン処理が実行されトップページに遷移したか確認
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
}
