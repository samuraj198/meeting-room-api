<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:100|unique:rooms,name' . $this->id,
            'capacity' => 'nullable|integer|min:1|max:50',
            'description' => 'nullable|string|max:500',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Имя комнаты должно состоять максимум из 100 символов',
            'name.unique' => 'Имя комнаты должно быть уникально',
            'capacity.min' => 'Вместительность комнаты должна быть минимум 1',
            'capacity.max' => 'Вместительность комнаты должна быть максимум 50',
            'description.max' => 'Описание комнаты должно состоять максимум из 500 символов',
        ];
    }
}
