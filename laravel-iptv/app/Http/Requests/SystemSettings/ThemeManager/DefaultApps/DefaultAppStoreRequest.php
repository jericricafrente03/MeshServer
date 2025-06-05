<?php

namespace App\Http\Requests\SystemSettings\ThemeManager\DefaultApps;

use App\Rules\HexColorRule;
use Illuminate\Foundation\Http\FormRequest;

class DefaultAppStoreRequest extends FormRequest
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
            'name' => ['required', 'unique:default_apps,name'],
            'method' => ['required', 'unique:default_apps,method'],
            'color' => ['required', new HexColorRule],
        ];
    }
}
