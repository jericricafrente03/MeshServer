<?php

namespace App\Http\Requests\General\Body\Messages\RegularMessages;

use Illuminate\Foundation\Http\FormRequest;

class RegularMessageUpdateRequest extends FormRequest
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
            'from' => ['required'],
            'subject' => ['required'],
            'body' => ['required'],
        ];
    }
}
