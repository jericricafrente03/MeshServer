<?php

namespace App\Http\Requests\SystemSettings\ThemeManager\Themes;

use App\Rules\HexColorRule;
use Illuminate\Foundation\Http\FormRequest;

class ThemeUpdateThemeZoneRequest extends FormRequest
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
            'bg' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:50048'],
            'text_color' => ['nullable', new HexColorRule],
            'active_text_color' => ['nullable', new HexColorRule],
        ];
    }
}
