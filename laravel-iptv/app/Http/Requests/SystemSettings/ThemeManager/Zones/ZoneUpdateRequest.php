<?php

namespace App\Http\Requests\SystemSettings\ThemeManager\Zones;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ZoneUpdateRequest extends FormRequest
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
        $id = $this->input('id');
        return [
            'name' => [
                'required',
                Rule::unique('zones', 'name')->ignore($id),
                'regex:/^[a-zA-Z]+$/'
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The name must contain only letters.',
        ];
    }
}
