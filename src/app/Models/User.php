<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //リレーション：ユーザーは1つの住所を持つ
    public function address()
    {
        return $this->hasOne(Address::class);
    }

    //リレーション：ユーザーが出品した商品
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    //リレーション：ユーザーが購入した商品
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    //リレーション：ユーザーがお気に入りした商品
    public function favorites()
    {
        return $this->belongsToMany(Item::class, 'favorites')->withTimestamps();
    }

    // ユーザーは複数の商品をお気に入りにできる
    public function favoriteItems()
    {
        return $this->belongsToMany(Item::class, 'favorites')->withTimestamps();
    }


    //リレーション：ユーザーがコメントした内容
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
