<?php

namespace App\Http\Requests\API\STB;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
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
            'data.name' => ['required'],
            'data.password' => ['required'],
        ];
    }

    public function bodyParameters()
    {
        return [
            'data.name' => [
                'type' => 'string',
                'description' => 'The mac address of device.',
                'example' => 'c44eac205e6b',
            ],
            'data.password' => [
                'type' => 'string',
                'description' => 'The api_key after you register .',
                'example' => '2b9e031e600bb7a40e3a57ba6e0df8d4'

            ],
            // Add other parameters similarly...
        ];
    }
}
