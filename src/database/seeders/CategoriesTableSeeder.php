<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 挿入したいデータを配列でまとめる
        $params = [
            ['content' => 'ファッション'],
            ['content' => '家電'],
            ['content' => 'インテリア'],
            ['content' => 'レディース'],
            ['content' => 'コスメ'],
            ['content' => '本'],
            ['content' => 'ゲーム'],
            ['content' => 'スポーツ'],
            ['content' => 'キッチン'],
            ['content' => 'ハンドメイド'],
            ['content' => 'アクセサリー'],
            ['content' => 'おもちゃ'],
            ['content' => 'ベビー・キッズ'],
        ];

        // まとめてDBに挿入するためにループで回す
        foreach ($params as $param) {
            DB::table('categories')->insert([
                'content' => $param['content'],// contentカラムに値をセット
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
