<?php

namespace App\Http\Requests\SystemSettings\DeviceAdb\Manager;

use Illuminate\Foundation\Http\FormRequest;

class SettingsRequest extends FormRequest
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
            'server_interface' => ['required', 'string'],
            'server_ip_address' => ['required', 'ip'],
            'package_name' => ['required', 'string'],
        ];
    }
}
