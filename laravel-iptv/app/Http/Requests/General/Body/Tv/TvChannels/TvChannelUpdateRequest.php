<?php

namespace App\Http\Requests\General\Body\Tv\TvChannels;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TvChannelUpdateRequest extends FormRequest
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
                'required', 'min:1', 
                Rule::unique('tv_channels', 'name')->ignore($id)->whereNull('deleted_at'),
            ],
            'order_no' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('tv_channels', 'order_no')->ignore($id),
            ],
            'channel' => [
                'required',
                'integer',
                Rule::unique('tv_channels', 'channel')->ignore($id),
            ],
            'category_id' => ['required'],
            'img_uri' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif','max:5048'],
        ];
    }
}
