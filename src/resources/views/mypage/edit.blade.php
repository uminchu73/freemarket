@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    @endsection


@section('content')

    <div class="content">
        <div class="content-title">
            <h2>プロフィール設定</h2>
        </div>
        <form class="profile-edit" action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            {{-- プロフィール画像 --}}
            <div class="profile-image">
                <div class="profile-image-box">
                    <div class="profile-image-preview">
                        <img id="preview" src="{{ $user->profile_img ? asset('storage/' . $user->profile_img) : 'https://via.placeholder.com/120?text=No+Image' }}" alt="プロフィール画像">
                    </div>
                    <label class="image-label">
                        <input type="file" id="profile_img" name="profile_img" accept="image/*">
                        画像を選択する
                    </label>
                </div>
                <div class="error">
                    @error('profile_img')
                        {{ $message }}
                    @enderror
                </div>
            </div>

            {{-- ユーザー名 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="label">ユーザー名</span>
                </div>
                <div class="form__group-content">
                    <div class="input-box">
                        <input type="text" name="name" value="{{ old('name',$user->name ?? '') }}" />
                        <div class="error">
                            @error('name')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 郵便番号 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="label">郵便番号</span>
                </div>
                <div class="form__group-content">
                    <div class="input-box">
                        <input type="text" name="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}" />
                        <div class="error">
                            @error('postal_code')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            {{-- 住所 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="label">住所</span>
                </div>
                <div class="form__group-content">
                    <div class="input-box">
                        <input type="text" name="address" value="{{ old('address', $address->address ?? '') }}"  />
                        <div class="error">
                            @error('address')
                                {{ $message }}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
            {{-- 建物名 --}}
            <div class="form__group">
                <div class="form__group-title">
                    <span class="label">建物名</span>
                </div>
                <div class="form__group-content">
                    <div class="input-box">
                        <input type="text" name="building" value="{{ old('building', $address->building ?? '') }}"  />
                    </div>
                </div>
            </div>
            <div class="form__button">
                <button class="form__button-submit" type="submit">更新する</button>
            </div>
        </form>
    </div>


@endsection


@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('profile_img');
    const preview = document.getElementById('preview');

    if (input && preview) {
        input.addEventListener('change', function (event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    }
});
</script>
@endsection