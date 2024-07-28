<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'check_in' => 'date',
            'check_out' => 'date|after:check_in',
            // 'number_of_rooms' => 'required|min:1',
        ];
    }
    public function messages()
    {
        return [
            'check_in.required' => 'Vui lòng nhập ngày nhận phòng.',
            'check_in.date' => 'Ngày nhận phòng phải là một ngày hợp lệ.',
            'check_out.required' => 'Vui lòng nhập ngày trả phòng.',
            'check_out.date' => 'Ngày trả phòng phải là một ngày hợp lệ.',
            'check_out.after' => 'Ngày trả phòng phải sau ngày nhận phòng.',
            'persion.required' => 'Vui lòng nhập số người.',
            'persion.min' => 'Số người phải ít nhất là 1.',
            'number_of_rooms.required' => 'Vui lòng nhập số phòng.',
            'number_of_rooms.min' => 'Số phòng phải ít nhất là 1.',
        ];
    }
}
