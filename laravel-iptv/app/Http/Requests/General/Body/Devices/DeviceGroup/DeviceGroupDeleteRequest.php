<?php

namespace App\Http\Requests\General\Body\Devices\DeviceGroup;

use App\Exceptions\IntegrityErrorException;
use App\Models\General\Body\Devices\Device;
use Illuminate\Foundation\Http\FormRequest;

class DeviceGroupDeleteRequest extends FormRequest
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
        
        $dataCount = Device::where('category_id', $id)->count();
        if ($dataCount > 0) {
            throw IntegrityErrorException::dataInUse();
        }

        return [
            //
        ];
    }
}
