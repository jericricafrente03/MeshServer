<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UpdateRoleRequest extends FormRequest
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
        $authUser = Auth::user();

        return [
            "name" => 'required|min:2|max:50',
            "description" => 'min:2|max:100',
            'level' => [
                'required',
                'integer',
                'gt:' . $authUser->role->level,  // Ensure level is greater than or equal to auth user's level
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
        $authUser = Auth::user();
        
        return [
            'level.gt' => 'The level must be greater than your current level of ' . $authUser->role->level . '.',
            // Add custom messages for other fields as needed...
        ];
    }
}
