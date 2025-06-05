<?php

namespace App\Http\Requests\SystemSettings\DeviceAdb\Manager;

use Illuminate\Foundation\Http\FormRequest;

class GroupUninstallRequest extends FormRequest
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
            'package_name' => ['required', 'string'],
            'ip_start' => ['required', 'ip'], // IP start must be a valid IP
            'ip_end' => ['nullable', 'ip', function ($attribute, $value, $fail) {
                if ($value) {
                    $startIp = request()->input('ip_start');
                    if (!$startIp) {
                        return $fail('ip_start is required when ip_end is provided.');
                    }

                    // Extract last octet
                    $startParts = explode('.', $startIp);
                    $endParts = explode('.', $value);

                    if (count($startParts) !== 4 || count($endParts) !== 4) {
                        return $fail('Invalid IP format.');
                    }

                    $startLastOctet = (int) $startParts[3];
                    $endLastOctet = (int) $endParts[3];

                    if ($endLastOctet > $startLastOctet + 9) {
                        return $fail('The ip range end must be within 10 IPs from ip range start.');
                    }
                }
            }],
        ];
    }
}
