@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
    @endsection


@section('content')

    <div class="mypage-container">
        {{-- プロフィール部分 --}}
        <div class="profile-section">
            <div class="profile-image">
                <img src="{{ Auth::user()->profile_img ? asset('storage/' . Auth::user()->profile_img) : asset('images/default-icon.png') }}" alt="プロフィール画像">
            </div>
            <div class="profile-info">
                <h2 class="username">{{ Auth::user()->name }}</h2>
                <a href="{{ route('profile.edit') }}" class="edit-button">プロフィールを編集</a>
            </div>
        </div>

        {{-- タブ切り替え --}}
        <div class="tab-menu">
            <a href="{{ route('profile.show', ['tab' => 'exhibited']) }}" class="tab-button {{ $tab === 'exhibited' ? 'active' : '' }}">出品した商品</a>
            <a href="{{ route('profile.show', ['tab' => 'purchased']) }}" class="tab-button {{ $tab === 'purchased' ? 'active' : '' }}">購入した商品</a>
        </div>

        <div class="tab-content">
            {{-- 出品した商品 --}}
            @if($tab === 'exhibited')
                @if($exhibitedItems->isEmpty())
                    <p>出品した商品はありません</p>
                @else
                    <div class="item-list">
                        @foreach($exhibitedItems as $item)
                            <div class="item-card">
                                <img src="{{ \Illuminate\Support\Str::startsWith($item->img_url, ['http://', 'https://']) ? $item->img_url : asset('storage/' . $item->img_url) }}" alt="商品画像">
                                <p>{{ $item->title }}</p>
                            </div>
                        @endforeach
                    </div>
                @endif

            {{-- 購入した商品 --}}
            @elseif($tab === 'purchased')
                @if($purchasedItems->isEmpty())
                    <p>購入履歴はありません</p>
                @else
                    <div class="item-list">
                        @foreach($purchasedItems as $purchase)
                            @if($purchase->item)
                                <div class="item-card">
                                    <img src="{{ \Illuminate\Support\Str::startsWith($purchase->item->img_url, ['http://', 'https://']) ? $purchase->item->img_url : asset('storage/' . $purchase->item->img_url) }}" alt="商品画像">
                                    <p>{{ $purchase->item->title }}</p>
                                    <p>購入日: {{ $purchase->purchased_at }}</p>
                                    <p>支払い方法: {{ $purchase->payment_method == 1 ? 'コンビニ' : 'カード' }}</p>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    </div>

@endsection

