@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')

    <div class="item-detail-container">
        {{-- 商品画像 --}}
        <div class="item-image">
            <img src="{{ $item->img_url? ( \Illuminate\Support\Str::startsWith($item->img_url, ['http://', 'https://'])? $item->img_url: asset('storage/' . $item->img_url) ): 'https://via.placeholder.com/300' }}"
            alt="商品画像">
        </div>

        {{-- 商品情報 --}}
        <div class="item-info">
            <h2 class="title">{{ $item->title }}</h2>
            <p class="brand">{{ $item->brand }}</p>
            <p class="price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>
            {{-- いいねとコメント数 --}}
            <div class="response">
                <div class="favorites-count">
                    @if(auth()->check())
                        <form action="{{ route('item.favorite', $item) }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="favorite-button">
                                <span class="icon">
                                    <img src="{{ asset('images/cd32a5631fc8ae66e54e525cb4afafb0a04b1deb.png') }}" alt="⭐">
                                </span>
                                <span class="count {{ auth()->check() && auth()->user()->favoriteItems->contains($item) ? 'favorited' : '' }}">
                                    {{ $item->favoritedByUsers->count() ?? 0 }}
                                </span>
                            </button>
                        </form>
                    @else
                        {{-- 未認証はボタンなし --}}
                        <span class="icon">
                            <img src="{{ asset('images/cd32a5631fc8ae66e54e525cb4afafb0a04b1deb.png') }}" alt="⭐️">
                        </span>
                        <span class="count">
                            {{ $item->favoritedByUsers->count() ?? 0 }}
                        </span>
                    @endif
                </div>

                <div class="comments-count">
                    <span class="icon">
                        <img src="{{ asset('images/2eff6a259403a7440cf0d1765014bcdbe8540f70.png') }}" alt="💬">
                    </span>
                    <span class="count">
                        {{ $item->comments->count() ?? 0 }}
                    </span>
                </div>
            </div>

            {{-- 購入ボタン・売り切れ表示 --}}
            @if($item->status == 0)
                <form action="{{ route('purchase.show', $item) }}" method="GET">
                    @csrf
                    <button type="submit" class="purchase-button">購入手続きへ</button>
                </form>
            @else
                <button class="purchase-button" disabled>Sold</button>
            @endif

            {{-- 商品説明 --}}
            <div class="item-description">
                <h3>商品説明</h3>
                <p>{{ $item->description }}</p>
            </div>

            {{-- 商品詳細情報 --}}
            <div class="item-info-detail">
                <h3>商品の情報</h3>
                <dl>
                    <dt>カテゴリー</dt>
                    <dd>
                        @foreach($item->categories as $category)
                            <span class="category-label">{{ $category->content }}</span>
                        @endforeach
                    </dd>
                    <dt>商品の状態</dt>
                <dd>{{ $item->condition_label }}</dd>
                </dl>
            </div>

            {{-- コメント一覧 --}}
            <div class="item-comments">
                <h3>コメント ({{ $item->comments->count() }})</h3>
                @foreach($item->comments as $comment)
                    <div class="comment-wrapper">
                        {{-- 投稿者情報 --}}
                        <div class="comment-header">
                            <img class="comment-profile-img"
                                src="{{ $comment->user->profile_img
                                        ? asset('storage/' . $comment->user->profile_img)
                                        : asset('images/default-icon.png') }}"
                                alt="{{ $comment->user->name }}">
                            <span class="comment-username">{{ $comment->user->name }}</span>
                        </div>
                        {{-- コメント本文 --}}
                        <div class="comment-body">
                            {{ $comment->comment }}
                        </div>
                    </div>
                @endforeach

                {{-- コメント投稿フォーム --}}
                @auth
                    <form action="{{ route('item.comment', $item->id) }}" method="POST" class="comment-form">
                        @csrf
                        <label for="comment">商品へのコメント</label>
                        <textarea name="comment" id="comment"  placeholder="コメントを入力してください"></textarea>
                        {{-- エラー表示 --}}
                        <div class="error">
                            @error('comment')
                                {{ $message }}
                            @enderror
                            @error('auth')
                                {{ $message }}
                            @enderror
                        </div>
                        <button type="submit">コメントを送信する</button>
                    </form>
                @endauth
            </div>
        </div>
    </div>

@endsection
