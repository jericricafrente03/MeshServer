<?php

namespace App\Http\Requests\General\Body\Hospitality\FacilityCategories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class FacilityCategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        // Log all incoming request data to inspect it
        Log::info('FacilityCategoryStoreRequest data:', $this->all());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'min:1', 'unique:facility_categories,name'],
            'order_no' => ['required', 'integer', 'min:1', 'unique:facility_categories,order_no'],
            'img_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
            'img_preview_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
        ];
    }
}
