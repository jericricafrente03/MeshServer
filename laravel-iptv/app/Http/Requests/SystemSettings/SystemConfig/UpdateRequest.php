<?php

namespace App\Http\Requests\SystemSettings\SystemConfig;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            "name" => 'required|min:2|max:50',
            "logo" => 'nullable|file|mimes:jpeg,jpg,png|max:5120',
            "max_idle" => 'required|integer|between:0,99',
            "lat" => 'nullable|numeric|between:-90,90', // Latitude must be between -90 and 90
            "lon" => 'nullable|numeric|between:-180,180', // Longitude must be between -180 and 180   
        ];
    }
}
