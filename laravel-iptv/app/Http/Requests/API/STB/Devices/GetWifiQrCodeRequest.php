<?php

namespace App\Http\Requests\API\STB\Devices;

use Illuminate\Foundation\Http\FormRequest;

class GetWifiQrCodeRequest extends FormRequest
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
            'mac_address' => ['required'],
        ];
    }

    public function queryParameters()
    {
        return [
            'mac_address' => [
                'description' => 'The mac address of specific device.',
                'example' => '900eb3510b0b',
            ],
        ];
    }
}
