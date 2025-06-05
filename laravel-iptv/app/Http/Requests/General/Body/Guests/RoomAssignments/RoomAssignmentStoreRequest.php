<?php

namespace App\Http\Requests\General\Body\Guests\RoomAssignments;

use Illuminate\Foundation\Http\FormRequest;

class RoomAssignmentStoreRequest extends FormRequest
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
            'customer_id' => ['required'],
            'room_id' => ['required'],
            's_check_out' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Please select a guest.',
            'room_id.required'     => 'Room selection is mandatory.',
            's_check_out.required' => 'Please provide a check-out date and time.',
        ];
    }
}
