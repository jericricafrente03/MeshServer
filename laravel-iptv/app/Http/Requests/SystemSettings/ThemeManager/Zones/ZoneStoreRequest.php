<?php

namespace App\Http\Requests\SystemSettings\ThemeManager\Zones;

use Illuminate\Foundation\Http\FormRequest;

class ZoneStoreRequest extends FormRequest
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
            'name' => ['required', 'unique:zones,name', 'regex:/^[a-zA-Z]+$/'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.regex' => 'The name must contain only letters.',
        ];
    }
}
