<?php

namespace App\Http\Requests\General\Body\Hospitality\HotelInfos;

use Illuminate\Foundation\Http\FormRequest;

class HotelInfoStoreRequest extends FormRequest
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
            'name' => ['required', 'min:1', 'unique:hotel_infos,name'],
            'order_no' => ['required', 'integer', 'min:1', 'unique:hotel_infos,order_no'],
            'img_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:5048'],
        ];
    }
}
