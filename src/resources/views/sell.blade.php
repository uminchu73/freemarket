@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/sell.css') }}">
    @endsection


@section('content')
    <div class="sell-form">
        <h1>商品の出品</h1>

        {{-- 出品フォーム --}}
        <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <label for="image">商品画像</label>
            <input type="file" id="image" name="image" required style="display:none;">
            <label for="image" class="custom-file-label">画像を選択する</label>

            <h2>商品の詳細</h2>

            {{-- カテゴリー選択 --}}
            <label>カテゴリー</label>
            <div class="category-group">
                @foreach($categories as $category)
                    <label for="category-{{ $category->id }}">
                        <input type="checkbox" id="category-{{ $category->id }}" name="category_ids[]" value="{{ $category->id }}"
                        {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                    <span>{{ $category->content }}</span>
                    </label>
                @endforeach
            </div>

            {{-- 商品の状態選択 --}}
            <label for="condition">商品の状態</label>
            <select id="condition" name="condition">
                <option value="" disabled {{ old('condition') === null ? 'selected' : '' }}>選択してください</option>
                <option value="1" {{ old('condition') == '1' ? 'selected' : '' }}>良好</option>
                <option value="2" {{ old('condition') == '2' ? 'selected' : '' }}>目立った傷や汚れなし</option>
                <option value="3" {{ old('condition') == '3' ? 'selected' : '' }}>やや傷や汚れあり</option>
                <option value="4" {{ old('condition') == '4' ? 'selected' : '' }}>状態が悪い</option>
            </select>

            <h2>商品名と説明</h2>

            <label for="title">商品名</label>
            <input type="text" id="title" name="title" required>

            <label for="brand">ブランド名</label>
            <input type="text" id="brand" name="brand">

            <label for="description">商品の説明</label>
            <textarea id="description" name="description"></textarea>

            <label for="price">販売価格</label>
            <input type="text" id="price" name="price" inputmode="numeric" pattern="[0-9]*" required placeholder="¥">

            <button type="submit">出品する</button>
        </form>
    </div>

    <script>
        const fileInput = document.getElementById('image');
        const customLabel = document.querySelector('.custom-file-label');

        fileInput.addEventListener('change', function() {
            if (fileInput.files.length > 0) {
                //ファイル名を表示
                customLabel.textContent = fileInput.files[0].name;
            } else {
                //ファイル未選択時
                customLabel.textContent = '画像を選択する';
            }
        });
    </script>

@endsection