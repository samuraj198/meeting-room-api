<?php

namespace App\Http\Requests;

use App\Rules\AvailableRoomRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
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
            'room_id' => ['required','integer','exists:rooms,id', new AvailableRoomRule()],
            'start_time' => 'required|date|after_or_equal:now',
            'end_time' => 'required|date|after:start_time',
            'purpose' => 'nullable|string|max:500'
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required' => 'Поле с id комнаты обязательно для заполнения',
            'room_id.exists' => 'Комнаты с таким id не существует',
            'start_time.required' => 'Поле с начальным временем брони обязательно для заполнения',
            'start_time.after_or_equal' => 'Начальное время брони не должно быть раньше этого момента',
            'end_time.required' => 'Поле с конечным временем брони обязательно для заполнения',
            'end_time.after' => 'Конечное время брони должно быть позже начального времени',
            'purpose' => 'Цель брони должна состоять максимум из 500 символов'
        ];
    }
}
