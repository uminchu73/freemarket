<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
    ];

    //リレーション：お気に入りは1人のユーザーに属する
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    //リレーション：お気に入りは1つの商品に属する
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

}
