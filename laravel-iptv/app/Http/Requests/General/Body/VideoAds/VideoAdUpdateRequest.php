<?php

namespace App\Http\Requests\General\Body\VideoAds;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class VideoAdUpdateRequest extends FormRequest
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
                Rule::unique('video_ads', 'name')->ignore($id),
            ],
            'video_uri' => ['nullable', 'file', 'mimes:mp4,avi,mov,wmv', 'max:512000'], // 500MB max size (in KB)
            'room_id' => ['required', 'array', 'min:1'], // Ensure at least one room is selected
            'room_id.*' => ['integer', 'exists:rooms,id'], // Validate each item in the array
        ];
    }
}
