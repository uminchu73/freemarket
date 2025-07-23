<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'condition',
        'status',
    ];

    //リレーション：商品は1人のユーザー（出品者）に属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //レーション：商品は複数のカテゴリに属する（多対多）
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item');
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

}
