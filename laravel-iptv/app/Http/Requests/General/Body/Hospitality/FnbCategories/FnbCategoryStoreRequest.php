<?php

namespace App\Http\Requests\General\Body\Hospitality\FnbCategories;

use Illuminate\Foundation\Http\FormRequest;

class FnbCategoryStoreRequest extends FormRequest
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
            'name' => ['required', 'min:1', 'unique:fnb_categories,name'],
            'order_no' => ['required', 'integer', 'min:1', 'unique:fnb_categories,order_no'],
        ];
    }
}
