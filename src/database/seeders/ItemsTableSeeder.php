<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Item;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $itemsData = [
            [
                'user_id' => 1,
                'category_ids' => [1],
                'title' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'price' => 15000,
                'condition' => 1,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Armani+Mens+Clock.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [2],
                'title' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'price' => 5000,
                'condition' => 2,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/HDD+Hard+Disk.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [9],
                'title' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'price' => 300,
                'condition' => 3,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/iLoveIMG+d.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [1],
                'title' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'price' => 4000,
                'condition' => 4,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Leather+Shoes+Product+Photo.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [2],
                'title' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'price' => 45000,
                'condition' => 1,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Living+Room+Laptop.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [2],
                'title' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'price' => 8000,
                'condition' => 2,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Music+Mic+4632231.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [1],
                'title' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'price' => 3500,
                'condition' => 3,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Purse+fashion+pocket.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [9],
                'title' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'price' => 500,
                'condition' => 4,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Tumbler+souvenir.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [9],
                'title' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'price' => 4000,
                'condition' => 1,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/Waitress+with+Coffee+Grinder.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'user_id' => 1,
                'category_ids' => [5],
                'title' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'price' => 2500,
                'condition' => 2,
                'status' => 0,
                'img_url' => 'https://coachtech-matter.s3.ap-northeast-1.amazonaws.com/image/%E5%A4%96%E5%87%BA%E3%83%A1%E3%82%A4%E3%82%AF%E3%82%A2%E3%83%83%E3%83%95%E3%82%9A%E3%82%BB%E3%83%83%E3%83%88.jpg',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($itemsData as $data) {
            $categoryIds = $data['category_ids'];
            unset($data['category_ids']);

            // Itemを作成
            $item = Item::create($data);

            // 中間テーブルにカテゴリをattach
            $item->categories()->attach($categoryIds);
        }
    }
}
