<?php

namespace App\Http\Requests\General\Body\Hospitality\FacilityCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FacilityCategoryUpdateRequest extends FormRequest
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
                Rule::unique('facility_categories', 'name')->ignore($id),
            ],
            'order_no' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('facility_categories', 'order_no')->ignore($id),
            ],
            'img_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
            'img_preview_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
        ];
    }
}
