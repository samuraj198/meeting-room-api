<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:255|exists:users,email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'Почта пользователя обязательна для заполнения',
            'email.max' => 'Длина почты не должна превышать 255 символов',
            'email.exists' => 'Пользователя с такой почтой не зарегистрировано',
            'password.required' => 'Пароль пользователя обязателен для заполнения',
            'password.min' => 'Пароль должен содержать минимум 8 символов',
        ];
    }
}
