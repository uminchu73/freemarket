@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    @endsection


@section('content')

    <div class="menu">
        <a href="{{ url()->current() }}?tab=all&keyword={{ request('keyword') }}" class="tab-button {{ $tab === 'all' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ url()->current() }}?tab=mylist&keyword={{ request('keyword') }}" class="tab-button {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

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
