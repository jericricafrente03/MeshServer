<?php

namespace App\Http\Requests\API\STB;

use App\Rules\DecimalRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class MeshTransactionRequest extends FormRequest
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
            'data.*.room_id' => ['required', 'integer'], // Validate each item's 'room_id'
            'data.*.type' => ['required'], // Validate each item's 'type'
            'data.*.item_id' => ['required', 'integer'], // Validate each item's 'item_id'
            'data.*.quantity' => ['required', 'integer'], // Validate each item's 'quantity' as an integer
            'data.*.unit_price' => ['required', 'numeric', 'decimal:0,2'], //new DecimalRule(2)], // Validate up to 2 decimal places using created rule
            'data.*.room_assignment_id' => ['required', 'integer'], // Validate each item's 'room_assignment_id'
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param \Illuminate\Contracts\Validation\Validator $validator
     */
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
                'description' => 'Array of billing items',
            ],
            'data.*.room_id' => [
                'type' => 'integer',
                'description' => 'The ID of the room',
            ],
            'data.*.type' => [
                'type' => 'string',
                'description' => "Use string inside ['fnb', 'item_request', 'service_request']",
                'example' => 'fnb'
            ],
            'data.*.unit_price' => [
                'type' => 'number',
                'description' => "Total Amount of item",
                'example' => '497.75'
            ],
            // Add other parameters similarly...
        ];
    }
}
