<?php

namespace App\Http\Requests\API\STB\Devices;

use Illuminate\Foundation\Http\FormRequest;

class GetLanguageRequest extends FormRequest
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
            'room' => ['required'],
        ];
    }

    public function queryParameters()
    {
        return [
            'room' => [
                'description' => 'The room number to fetch messages for.',
                'example' => 102,
            ],
        ];
    }
}
