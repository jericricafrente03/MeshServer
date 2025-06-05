<?php

namespace App\Http\Requests\SystemSettings\ThemeManager\DefaultApps;

use App\Rules\HexColorRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DefaultAppUpdateRequest extends FormRequest
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
                Rule::unique('default_apps', 'name')->ignore($id),
            ],
            'method' => [
                'required',
                Rule::unique('default_apps', 'method')->ignore($id),
            ],
            'color' => ['required', new HexColorRule],
        ];
    }
}
