<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;


class UserProfileUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_profile_edit()
    {
        //ユーザー作成・ログイン
        $user = User::factory()->create([
            'name' => 'テスト太郎',
            'profile_img' => 'profile.jpg',
        ]);
        $user->address()->create([
            'postal_code' => '123-4567',
            'address' => '東京都新宿区テスト1-2-3',
            'building' => 'テストビル101',
        ]);
        $this->actingAs($user);

        // 編集画面にアクセスし初期値を正しく表示
        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200);
        $response->assertSee('テスト太郎');
        $response->assertSee('profile.jpg');
        $response->assertSee('123-4567');
        $response->assertSee('東京都新宿区テスト1-2-3');
        $response->assertSee('テストビル101');
    }
}
