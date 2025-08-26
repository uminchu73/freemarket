<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    /**
     * カテゴリは複数の商品に紐づく（多対多）
     */
    public function items()
    {
        return $this->belongsToMany(Item::class);
    }

}
