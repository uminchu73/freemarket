<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Item extends Model
{
    use HasFactory;

    public const CONDITION = [
        1 => '良好',
        2 => '目立った傷や汚れなし',
        3 => 'やや傷や汚れあり',
        4 => '状態が悪い',
    ];

    public const STATUS = [
        0 => '販売中',
        1 => '売り切れ',
    ];


    protected $fillable = [
        'user_id',
        'title',
        'brand',
        'description',
        'price',
        'condition',
        'status',
        'img_url'
    ];

    // 商品状態のラベル
    public function getConditionLabelAttribute()
    {
        return self::CONDITION[$this->condition] ?? '不明';
    }

    // ステータスのラベル
    public function getStatusLabelAttribute()
    {
        return self::STATUS[$this->status] ?? '不明';
    }


    //リレーション：商品は1人のユーザー（出品者）に属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //レーション：商品は複数のカテゴリに属する（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    //リレーション：商品は複数の画像を持つ
    public function images()
    {
        return $this->hasMany(Image::class);
    }

    //リレーション：商品は複数のお気に入りに登録される可能性がある
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // 商品は複数のユーザーにお気に入りされる
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }


    //リレーション：商品は複数のコメントを持つ
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    //リレーション：商品は1つの購入履歴を持つことがある（売れた場合）
    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }


    /**
     * キーワード検索
     */
    public function scopeKeywordSearch($query, $keyword)
    {
        if (!empty($keyword)) {
            $query->where('title', 'like', '%' . $keyword . '%');
        }
    }

    /**
     * 新しい商品を作成して保存、カテゴリもattachする
     */
    public static function createWithCategories(array $data, array $categoryIds)
    {
        $data['user_id'] = Auth::id();
        $data['status'] = 0;

        $item = self::create($data);

        if (!empty($categoryIds)) {
            $item->categories()->attach($categoryIds);
        }

        return $item;
    }



}
