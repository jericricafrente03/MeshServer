<?php

namespace App\Http\Requests\API\STB;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostAnalyticsRequest extends FormRequest
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
            'data' => ['required', 'array'], // 'data' must be an array
            'data.*.counter' => ['required', 'integer'],
            'data.*.type_id' => ['required', 'integer'],
            'data.*.item_id' => ['required', 'integer'],
            'data.*.room_id' => ['required', 'integer'], 
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        // Get the error messages
        $errors = $validator->errors()->toArray();
        $firstError = array_values($errors)[0][0]; // Get the first error message
        $totalErrors = count($errors); // Count the total number of errors

        // Customize the response
        $response = response()->json([
            'result' => 'Failed',
            'reason' => ($totalErrors > 1)?'Some fields are invalid.':'This field is invalid.',
            'message' => $firstError . ($totalErrors > 1 ? ' (and ' . ($totalErrors - 1) . ' more error(s))' : ''),
            'errors' => $errors
        ], 422);

        // Throw an HTTP exception with the customized response
        throw new HttpResponseException($response);
    }

    public function bodyParameters()
    {
        return [
            'data' => [
                'type' => 'array',
                'description' => 'Array of analytics',
            ],
            'data.*.room_id' => [
                'type' => 'integer',
                'description' => 'The id of room',
                'example' => '2'
            ],
            'data.*.type_id' => [
                'type' => 'integer',
                'description' => "The id of analytics type",
                'example' => '2'
            ],
            'data.*.counter' => [
                'type' => 'integer',
                'description' => "Total Count for target item for analytics",
                'example' => '3'
            ],
            'data.*.item_id' => [
                'type' => 'integer',
                'description' => "The id of the target",
                'example' => '2'
            ],
        ];
    }
}
