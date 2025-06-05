<?php

namespace App\Http\Requests\General\Body\Guests\Rooms;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomUpdateRequest extends FormRequest
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
                Rule::unique('rooms', 'name')->ignore($id),
            ],
            'category_id' => ['required'],
            'room_status' => ['required'],
        ];
    }
}
