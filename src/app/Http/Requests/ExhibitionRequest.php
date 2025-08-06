<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png',
            'category_ids' => 'required|array',
            'category_ids.*' => 'exists:categories,id',
            'condition' => 'required|in:1,2,3,4',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '商品名は必須です',
            'description.required' => '商品説明は必須です',
            'description.max' => '商品説明は255文字以内で入力してください',
            'image.required' => '商品画像は必須です。',
            'image.image' => '有効な画像ファイルをアップロードしてください',
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください',
            'category_id.required' => 'カテゴリーを選択してください',
            'category_id.exists' => '正しいカテゴリーを選択してください',
            'condition.required' => '商品の状態を選択してください',
            'condition.in' => '正しい商品の状態を選択してください',
            'price.required' => '販売価格は必須です',
            'price.numeric' => '販売価格は数値で入力してください',
            'price.min' => '販売価格は0円以上で入力してください',
        ];
    }
}
