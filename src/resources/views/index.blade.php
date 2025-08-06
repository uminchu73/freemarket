@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    @endsection


@section('content')

<div class="menu">
    <a href="#" class="favorite">おすすめ</a>
    <a href="#" class="mylist">マイリスト</a>
</div>

<div class="item-list">
    @foreach($items as $item)
    <div class="item-card">
        <div class="item-image">
            <img src="{{ $item->img_url }}" alt="{{ $item->title }}" />
        </div>
        <div class="item-name">{{ $item->title }}</div>
    </div>

    @endforeach
</div>

@endsection
