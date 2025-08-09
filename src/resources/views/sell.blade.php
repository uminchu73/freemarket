@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
    @endsection


@section('content')
    <div class="sell-form">
        <h1>商品の出品</h1>

        <form action="/sell" method="POST" enctype="multipart/form-data">
            @csrf
            <!-- 商品画像 -->
            <label>商品画像</label>
            <input type="file" name="image" required>

            <h2>商品の詳細</h2>
            <!-- カテゴリー -->
            <label>カテゴリー</label>
            <div class="category-group">
                @foreach($categories as $category)
                    <label>
                        <input type="checkbox" name="category_ids[]" value="{{ $category->id }}">
                        <span>{{ $category->content }}</span>
                    </label>
                @endforeach
            </div>

            <!-- 商品の状態 -->
            <label>商品の状態</label>
            <select name="condition" >
                <option value="" disabled {{ old('condition') === null ? 'selected' : '' }}>選択してください</option>
                <option value="1" {{ old('condition') == '1' ? 'selected' : '' }}>良好</option>
                <option value="2" {{ old('condition') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                <option value="3" {{ old('condition') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="4" {{ old('condition') == '4' ? 'selected' : '' }}>状態が悪い</option>
            </select>

            <h2>商品名と説明</h2>
            <!-- 商品名 -->
            <label>商品名</label>
            <input type="text" name="title" required>

            <!-- ブランド名 -->
            <label>ブランド名</label>
            <input type="text" name="brand">

            <!-- 商品説明 -->
            <label>商品の説明</label>
            <textarea name="description"></textarea>

            <!-- 販売価格 -->
            <label>販売価格</label>
            <input type="text" name="price" inputmode="numeric" pattern="[0-9]*" required placeholder="¥">

            <button type="submit">出品する</button>
        </form>
    </div>
@endsection