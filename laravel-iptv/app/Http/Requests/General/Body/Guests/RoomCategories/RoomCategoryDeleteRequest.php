<?php

namespace App\Http\Requests\General\Body\Guests\RoomCategories;

use App\Exceptions\IntegrityErrorException;
use App\Models\General\Body\Guests\Room;
use Illuminate\Foundation\Http\FormRequest;

class RoomCategoryDeleteRequest extends FormRequest
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
        
        $roomDataCount = Room::where('category_id', $id)->count();
        if ($roomDataCount > 0) {
            // throw new IntegrityErrorException('Integrity Error. <br>(This data is in use!)');
            throw IntegrityErrorException::dataInUse();
        }
        

        return [
    
        ];
    }

    /**
     * Custom messages for validation errors
     */
    // public function messages()
    // {
    //     return [
    //         'id.required' => 'The category ID is required.',
    //         'id.exists' => 'The selected category does not exist.',
    //     ];
    // }
}
