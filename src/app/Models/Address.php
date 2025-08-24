<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'postal_code',
        'address',
        'building',
    ];

    /**
     * リレーション：住所は1人のユーザーに属する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リレーション：住所は複数の購入履歴に紐づくことがある
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

}
