<?php

namespace App\Http\Requests\General\Body\Guests\RoomCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomCategoryUpdateRequest extends FormRequest
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
                Rule::unique('room_categories', 'name')->ignore($id),
            ],
            'order_no' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('room_categories', 'order_no')->ignore($id),
            ],
        ];
    }
}
