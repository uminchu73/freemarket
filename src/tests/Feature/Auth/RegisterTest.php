<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /**
     * 名前が入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_name_required()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/register')->assertStatus(200);

        //入力データを送信（名前以外）
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        //バリデーションが表示されたか確認
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください',
        ]);
    }

    /**
     * メールアドレスが入力されていない場合、バリデーションメッセージが表示される
     */
    public function test_email_required()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/register')->assertStatus(200);

        //入力データを送信（メールアドレス以外）
        $response = $this->post('/register', [
            'name' => 'テスト太朗',
            'email' => '',
            'password' => 'password123',
            'password_confirmation' => 'password123',
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
        $this->get('/register')->assertStatus(200);

        //入力データを送信（パスワード以外）
        $response = $this->post('/register', [
            'name' => 'テスト太朗',
            'email' => 'test@example.com',
            'password' => '',
            'password_confirmation' => 'password123',
        ]);

        //バリデーションが表示されたか確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードを入力してください',
        ]);
    }

    /**
     * パスワードが7文字以下の場合、バリデーションメッセージが表示される
     */
    public function test_password_too_short()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/register')->assertStatus(200);

        //入力データを送信（パスワード7文字以下）
        $response = $this->post('/register', [
            'name' => 'テスト太朗',
            'email' => 'test@example.com',
            'password' => 'pass',
            'password_confirmation' => 'pass',
        ]);

        //バリデーションが表示されたか確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードは8文字以上で入力してください',
        ]);
    }

    /**
     * パスワードが確認用パスワードと一致しない場合、バリデーションメッセージが表示される
     */
    public function test_password_mismatch()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/register')->assertStatus(200);

        //入力データを送信（確認パスワード不一致）
        $response = $this->post('/register', [
            'name' => 'テスト太朗',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'mismatch123',
        ]);

        //バリデーションが表示されたか確認
        $response->assertSessionHasErrors([
            'password' => 'パスワードと一致しません',
        ]);
    }

    /**
     * 全ての項目が入力されている場合、会員情報が登録され、ログイン画面に遷移される
     */
    public function test_success()
    {
        //会員登録ページを開けるか（ステータス200）
        $this->get('/register')->assertStatus(200);

        //入力データを送信
        $response = $this->post('/register', [
            'name' => 'テスト太朗',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        //DBに登録されたか確認
        $this->assertDatabaseHas('users', [
            'name'  => 'テスト太朗',
            'email' => 'test@example.com',
        ]);

        //プロフィール設定画面に遷移したか確認
        $response->assertRedirect('/mypage/profile');
    }
}
