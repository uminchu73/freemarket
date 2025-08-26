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

    public const PAYMENT_METHOD = [
    1 => 'コンビニ払い',
    2 => 'カード払い',
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


    /**
     * 商品状態のラベル
     */
    public function getConditionLabelAttribute()
    {
        return self::CONDITION[$this->condition] ?? '不明';
    }

    /**
     * ステータスのラベル
     */
    public function getStatusLabelAttribute()
    {
        return self::STATUS[$this->status] ?? '不明';
    }


    /**
     * 商品は1人のユーザー（出品者）に属する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 商品は複数のカテゴリに属する（多対多）
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * 商品は複数のお気に入り(Favoriteレコード)を持つ
     */
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * 商品は複数のユーザーにお気に入りされる
     */
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    /**
     * 商品は複数のコメントを持つ
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 商品は1つの購入履歴を持つことがある（売れた場合）
     */
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


    /**
     * 購入処理
     */
    public function purchaseBy(\App\Models\User $user, int $paymentMethod)
    {
        $purchase = $user->purchases()->create([
            'item_id' => $this->id,
            'address_id' => $user->address->id,
            'payment_method' => $paymentMethod,
            'purchased_at' => now(),
        ]);

        // ステータスを「売り切れ(=1)」に更新
        $this->update(['status' => 1]);

        return $purchase;

    }


    /**
     * 全商品（自分の出品は除外）
     */
    public function scopeExcludeOwn($query, $userId = null)
    {
        return $userId
            ? $query->where('user_id', '!=', $userId)
            : $query;
    }


    /**
     * おすすめ一覧
     */
    public static function recommended($userId = null)
    {
        return self::latest()->excludeOwn($userId)->get();
    }


    /**
     * Checkout セッション
     */
    public function createStripeSession(int $paymentMethod, $successUrl, $cancelUrl)
    {
        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $map = [
            1 => 'konbini',
            2 => 'card',
        ];

        $stripePaymentMethod = $map[$paymentMethod] ?? 'card';

        return \Stripe\Checkout\Session::create([
            'payment_method_types' => [$stripePaymentMethod],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => ['name' => $this->title],
                    'unit_amount' => $this->price,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $successUrl,
            'cancel_url' => $cancelUrl,
        ]);
    }

}
