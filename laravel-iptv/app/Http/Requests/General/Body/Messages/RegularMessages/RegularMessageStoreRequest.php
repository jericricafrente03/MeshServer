<?php

namespace App\Http\Requests\General\Body\Messages\RegularMessages;

use Illuminate\Foundation\Http\FormRequest;

class RegularMessageStoreRequest extends FormRequest
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
            'from' => ['required'],
            'subject' => ['required'],
            'body' => ['required'],
            'type_id' => ['required'],
            'room_id' => [
                'required_if:type_id,21', // room_id is required if type_id is 21
            ],
            'category_id' => [
                'required_if:type_id,22', // category_id is required if type_id is 22
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required_if' => 'The room ID is required when type is Single.',
            'category_id.required_if' => 'The category ID is required when type is Group.',
        ];
    }

}
