<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'item_id',
        'address_id',
        'payment_method',
        'purchased_at',
    ];

    /**
     * 購入は1人のユーザーに属する（買った人）
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 購入は1つの商品に属する
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * 購入は1つの住所に属する
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }

}
