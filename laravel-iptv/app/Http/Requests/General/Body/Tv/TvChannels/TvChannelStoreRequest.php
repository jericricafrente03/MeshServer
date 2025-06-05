<?php

namespace App\Http\Requests\General\Body\Tv\TvChannels;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TvChannelStoreRequest extends FormRequest
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
            'name' => [
                'required', 
                'min:1', 
                // 'unique:tv_channels,name'
                Rule::unique('tv_channels', 'name')->whereNull('deleted_at'),
            ],
            'channel' => ['required', 'integer', 'unique:tv_channels,channel'],
            'category_id' => ['required'],
            'img_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:5048'],
            'order_no' => ['required', 'integer', 'min:1', 'unique:tv_channels,order_no'],
        ];
    }
}
