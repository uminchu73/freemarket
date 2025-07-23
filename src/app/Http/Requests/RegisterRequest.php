<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
    public static array $rules = [
        'name' => ['required', 'string' ,'max:255'],
        'email' => ['required', 'email' ,'max:255','unique:users,email'],
        'password' => ['required', 'string' ,'min:8', 'confirmed'],
        'password_confirmation' => ['required', 'string', 'min:8'],
    ];

    public static array $messages = [
        'name.required' => 'お名前を入力してください',
        'email.required' => 'メールアドレスを入力してください',
        'email.email' => '正しいメールアドレス形式で入力してください',
        'email.unique' => 'このメールアドレスは既に使われています',
        'password.required' => 'パスワードを入力してください',
        'password.min' => 'パスワードは8文字以上で入力してください',
        'password.confirmed' => 'パスワードと一致しません',
        'password_confirmation.required' => '確認用パスワードを入力してください',
        'password_confirmation.min' => '確認用パスワードは8文字以上で入力してください',
    ];

    public function rules()
    {
        return self::$rules;
    }

    public function messages()
    {
        return self::$messages;
    }

}
