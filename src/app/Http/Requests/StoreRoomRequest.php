<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRoomRequest extends FormRequest
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
            'name' => 'required|string|max:100|unique:rooms,name',
            'capacity' => 'required|integer|min:1|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Имя комнаты обязательно для заполнения',
            'name.max' => 'Имя комнаты должно состоять максимум из 100 символов',
            'name.unique' => 'Имя комнаты должно быть уникально',
            'capacity.required' => 'Вместимость комнаты обязательна для заполнения',
            'capacity.min' => 'Вместительность комнаты должна быть минимум 1',
            'capacity.max' => 'Вместительность комнаты должна быть максимум 50',
            'description.max' => 'Описание комнаты должно состоять максимум из 500 символов',
        ];
    }
}
