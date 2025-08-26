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
            <button class="tab-button active" data-tab="exhibited">出品した商品</button>
            <button class="tab-button" data-tab="purchased">購入した商品</button>
        </div>

        <div class="tab-content">
            {{-- 出品した商品 --}}
            <div id="exhibited" class="tab-pane active">
                <div class="item-list">
                    @foreach($exhibitedItems as $item)
                        <div class="item-card">
                            <img src="{{ \Illuminate\Support\Str::startsWith($item->img_url, ['http://', 'https://']) ? $item->img_url : asset('storage/' . $item->img_url) }}" alt="商品画像">
                            <p>{{ $item->title }}</p>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 購入した商品 --}}
            <div id="purchased" class="tab-pane">
                <div class="item-list">
                    @foreach($purchasedItems as $purchase)
                        <div class="item-card">
                            <img src="{{ \Illuminate\Support\Str::startsWith($purchase->item->img_url, ['http://', 'https://']) ? $purchase->item->img_url : asset('storage/' . $purchase->item->img_url) }}" alt="商品画像">
                            <p>{{ $purchase->item->title }}</p>
                            <p>購入日: {{ $purchase->purchased_at }}</p>
                            <p>支払い方法: {{ $purchase->payment_method == 1 ? 'コンビニ' : 'カード' }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script>
        // タブ切り替えスクリプト
        document.querySelectorAll('.tab-button').forEach(button => {
            button.addEventListener('click', () => {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));

                button.classList.add('active');
                document.getElementById(button.dataset.tab).classList.add('active');
            });
        });
    </script>

@endsection

