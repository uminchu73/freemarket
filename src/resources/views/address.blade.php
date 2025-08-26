@extends('layouts.app')

    @section('css')
        <link rel="stylesheet" href="{{ asset('css/address.css') }}">
    @endsection


@section('content')

    <div class="content">
        <div class="content-title">
            <h2>住所の変更</h2>
        </div>
        <form class="address-edit" action="{{ route('purchase.address.update', ['item' => $item->id]) }}" method="post">
            @csrf
            @method('PUT')
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