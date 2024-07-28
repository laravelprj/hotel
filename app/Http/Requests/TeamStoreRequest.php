<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeamStoreRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'postion' => 'required|string',
            'facebook' => 'required|string',
            'image' => 'required',
        ];
    }

public function messages(): array
{
    return [
        'name.required' => 'Vui lòng nhập vào tên.',
        'position.required' => 'Vui lòng nhập vào vị trí.',
        'facebook.required' => 'Vui lòng nhập vào liên kết Facebook.',
        'image.required' => 'Vui lòng tải lên hình ảnh.',
    ];
}

}
