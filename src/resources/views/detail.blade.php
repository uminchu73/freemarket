@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
    <div class="item-detail-container">
        <div class="item-image">
            <img src="{{ $item->img_url? ( \Illuminate\Support\Str::startsWith($item->img_url, ['http://', 'https://'])? $item->img_url: asset('storage/' . $item->img_url) ): 'https://via.placeholder.com/300' }}"
            alt="商品画像">
        </div>

        <div class="item-info">
            <h2 class="title">{{ $item->title }}</h2>
            <p class="brand">{{ $item->brand }}</p>

            <p class="price">¥{{ number_format($item->price) }} <span class="tax">(税込)</span></p>

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
                        {{ $item->comments_count ?? 0 }}
                    </span>
                </div>
            </div>

            <a href="{{ route('purchase.show', $item) }}" class="purchase-btn">購入手続きへ</a>


            <div class="item-description">
                <h3>商品説明</h3>
                <p>{{ $item->description }}</p>
            </div>
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
        </div>
    </div>
@endsection
