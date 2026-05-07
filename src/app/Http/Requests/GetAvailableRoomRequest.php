<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetAvailableRoomRequest extends FormRequest
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
            'date' => 'required|date_format:Y-m-d',
            'start_time' => 'required|date_format:H:i:s|after_or_equal:today',
            'end_time' => 'required|date_format:H:i:s|after:start_time'
        ];
    }

    public function messages():array
    {
        return [
            'date.date_format' => 'Неверный формат даты',
            'start_time.date_format' => 'Неверный формат времени',
            'start_time.after_or_equal' => 'Время не может быть раньше настоящего времени',
            'end_time.date_format' => 'Неверный формат времени',
            'end_time.after' => 'Время конца не может быть раньше начала'
        ];
    }
}
