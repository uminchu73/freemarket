@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
    @endsection


@section('content')
    {{-- 商品情報 --}}
    <div class="container">
        <div class="item-info">
            <div class="item-box">
                <div class="item-image">
                    <img src="{{ $item->img_url? ( \Illuminate\Support\Str::startsWith($item->img_url, ['http://', 'https://'])? $item->img_url: asset('storage/' . $item->img_url) ): 'https://via.placeholder.com/300' }}"
                    alt="商品画像">
                </div>
                <div class="item-info">
                    <h2 class="title">{{ $item->title }}</h2>
                    <p class="price">¥{{ number_format($item->price) }} </p>
                </div>
            </div>

            {{-- 支払い方法 --}}
            <div class="payment-method">
                <h3>支払い方法</h3>
                <select id="paymentSelect">
                    <option value="">選択してください</option>
                    <option value="1">コンビニ払い</option>
                    <option value="2">カード払い</option>
                </select>
            </div>

            {{-- 配送先 --}}
            <div class="address">
                <h3>配送先</h3>
                <p>〒 {{ $user->address ? $user->address->postal_code : 'XXX-YYYY' }}</p>
                <p>
                    {{ $user->address ? $user->address->address : 'ここには住所が入ります' }}
                    @if($user->address && $user->address->building)
                        {{ $user->address->building }}
                    @endif
                </p>
                <a href="{{ route('purchase.address.edit', ['item' => $item->id]) }}">変更する</a>
            </div>
        </div>

        {{-- 注文概要 --}}
        <div class="purchase-check">
            <div class="summary-box">
                <div class="summary-row">
                    <span>商品代金</span>
                    <span>¥{{ number_format($item->price) }} </span>
                </div>
                <div class="summary-row">
                    <span>支払い方法</span>
                    <span id="paymentSummary">未選択</span>
                </div>
            </div>
            @if($item->status == 0)
                <form action="{{ route('purchase.store', ['item' => $item->id]) }}" method="POST">
                    @csrf
                    <input type="hidden" name="payment_method" id="paymentMethodInput">
                    <button class="purchase-button" type="submit">購入する</button>
                </form>
                @else
                <button class="purchase-button" disabled>売り切れ</button>
            @endif
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentSelect = document.getElementById('paymentSelect');
            const paymentSummary = document.getElementById('paymentSummary');
            const paymentInput = document.getElementById('paymentMethodInput');

            paymentSelect.addEventListener('change', function () {
                const selectedText = this.options[this.selectedIndex].text;
                paymentSummary.textContent = selectedText || '未選択';
                paymentInput.value = this.value; // hidden に値をセット
            });
        });
    </script>
@endsection