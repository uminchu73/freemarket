@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    @endsection


@section('content')

    <div class="menu">
        <a href="#" class="all {{ $tab === 'all' ? 'active' : '' }}" data-tab="all">おすすめ</a>
        <a href="#" class="mylist {{ $tab === 'mylist' ? 'active' : '' }}" data-tab="mylist">マイリスト</a>
    </div>

    <script>
        document.querySelectorAll('.menu a').forEach(link => {
            link.addEventListener('click', function(e){
                e.preventDefault();
                const tab = this.dataset.tab;
                const form = document.querySelector('.search-form');
                form.querySelector('input[name="tab"]').value = tab; // hidden タブ値更新
                form.submit(); // フォーム submit で検索処理に渡す
            });
        });
    </script>


    <div class="item-list">
        @foreach($items as $item)
            <div class="item-card">
                <a href="{{ route('items.show', $item->id) }}">
                    <div class="item-image-wrapper">
                        <div class="item-image">
                            <img src="{{ \Illuminate\Support\Str::startsWith($item->img_url, ['http://', 'https://']) ? $item->img_url : asset('storage/' . $item->img_url) }}" alt="商品画像">
                                @if ($item->status == 1)
                                    <span class="sold-label">Sold</span>
                                @endif
                        </div>
                    </div>
                    <div class="item-title">{{ $item->title }}</div>
                </a>
            </div>
        @endforeach
    </div>

@endsection
