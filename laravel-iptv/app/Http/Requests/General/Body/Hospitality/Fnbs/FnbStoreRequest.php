<?php

namespace App\Http\Requests\General\Body\Hospitality\Fnbs;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class FnbStoreRequest extends FormRequest
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
    // protected function prepareForValidation()
    // {
    //     // Log all incoming request data to inspect it
    //     Log::info('FnbUpdateRequest data:', $this->all());
    // }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', 
                'min:1', 
                // 'unique:fnbs,name'
                Rule::unique('fnbs', 'name')->whereNull('deleted_at')
            ],
            'unit_price' => ['required', 'numeric', 'decimal:0,2'],
            'category_id' => ['required'],
            'img_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:2048'],
        ];
    }
}
