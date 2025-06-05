<?php

namespace App\Http\Requests\General\Body\Messages\BroadcastMessages\Emergencies;

use Illuminate\Foundation\Http\FormRequest;

class EmergencyStoreRequest extends FormRequest
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
            'duration' => ['required', 'integer'],
            'message' => ['required'],
            'type_id' => ['required'],
            'category_id' => [
                'required_if:type_id,41', // category_id is required if type_id is 41
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required_if' => 'The category ID is required when type is Group.',
        ];
    }
}
