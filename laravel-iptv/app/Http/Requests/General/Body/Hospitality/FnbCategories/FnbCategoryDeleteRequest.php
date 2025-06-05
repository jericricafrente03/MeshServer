<?php

namespace App\Http\Requests\General\Body\Hospitality\FnbCategories;

use App\Exceptions\IntegrityErrorException;
use App\Models\General\Body\Hospitality\Fnb;
use Illuminate\Foundation\Http\FormRequest;

class FnbCategoryDeleteRequest extends FormRequest
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
        
        $dataCount = Fnb::where('category_id', $id)->count();
        if ($dataCount > 0) {
            throw IntegrityErrorException::dataInUse();
        }
        

        return [
    
        ];
    }
}
