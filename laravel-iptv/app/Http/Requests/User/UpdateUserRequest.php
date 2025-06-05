<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
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
        $formdataId = $this->input('id');
        $formdataPassword = $this->input('password');
        return [
            // "name" => 'required|min:2|max:50',
            'email' => 'required|email|unique:users,email,'.$formdataId.',id',
            'role_id' => 'required',
            'password' => [ 
                ($formdataPassword != '')?
                    Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols():'','confirmed',
                    function ($attribute, $value, $fail) use ($formdataId) {
                        // Fetch the current password of the user from the database
                        $user = \App\Models\User::find($formdataId);

                        // Ensure the user exists and compare the password
                        if ($user && \Hash::check($value, $user->password)) {
                            $fail('The new password must be different from your current password.');
                        }
                    }
            ],
        ];
    }
}
