<?php

namespace App\Http\Requests\API\STB;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ReadMessageRequest extends FormRequest
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
            'data.room' => ['required'], // Validate each item's 'room_id'
            'data.message_recipient_id' => ['required', 'integer'], // Validate each item's 'item_id'
        ];
    }

    public function bodyParameters()
    {
        return [
            'data.room' => [
                'type' => 'string',
                'description' => 'The name of room',
            ],
            'data.message_recipient_id' => [
                'type' => 'integer',
                'description' => "Id of message"
            ],
            // Add other parameters similarly...
        ];
    }
}
