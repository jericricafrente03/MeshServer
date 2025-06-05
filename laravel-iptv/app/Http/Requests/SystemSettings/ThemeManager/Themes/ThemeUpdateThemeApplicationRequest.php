<?php

namespace App\Http\Requests\SystemSettings\ThemeManager\Themes;

use App\Rules\HexColorRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ThemeUpdateThemeApplicationRequest extends FormRequest
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
        $theme_id = $this->input('theme_id');
        return [
            'order_no' => [
                'required',
                Rule::unique('theme_applications', 'order_no')
                    ->ignore($id)
                    ->where('theme_id', $theme_id),
            ],
            'icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:5048'],
            'active_icon' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:5048'],
            'text_color' => ['nullable', new HexColorRule],
            'active_text_color' => ['nullable', new HexColorRule],
        ];
    }
}
