<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
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
            "firstname" => 'required|min:2|max:50',
            "lastname" => 'required|min:2|max:50',
            "name" => 'required|min:2|max:50',
            'email' => 'required|email|unique:users',
            'role_id' => 'required',
            'password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                        'confirmed'
                        //->uncompromised(),
                    
            ],
        ];
    }

    /**
     * Get the custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {   
        return [
            'firstname.required' => 'First name is required.',
            'lastname.required' => 'Last name is required.',
            // Add custom messages for other fields as needed...
        ];
    }
}
