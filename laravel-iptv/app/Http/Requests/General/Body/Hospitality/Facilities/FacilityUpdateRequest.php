<?php

namespace App\Http\Requests\General\Body\Hospitality\Facilities;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FacilityUpdateRequest extends FormRequest
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
                Rule::unique('facilities', 'name')->ignore($id),
            ],
            'img_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
        ];
    }
}
