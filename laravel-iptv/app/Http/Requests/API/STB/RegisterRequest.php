<?php

namespace App\Http\Requests\API\STB;

use App\Models\General\Body\Guests\Room;
use App\Rules\UniqueAreaInRoom;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterRequest extends FormRequest
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
        $roomNumber = $this->input('data.room_number');
        $macAddress = $this->input('data.mac_address');

        $room = Room::where('name', $roomNumber)->first();
        $roomId = $room?->id;
        
        return [
            'data.api_key' => ['required'],
            'data.mac_address' => ['required'],
            'data.room_number' => ['required'],
            'data.version' => ['required'],
            'data.area_id' => [
                'required',
                new UniqueAreaInRoom($roomId, $macAddress),
            ],
        ];
    }

    public function bodyParameters()
    {
        return [
            'data.api_key' => [
                'type' => 'string',
                'description' => 'The created API.',
                'example' => 'e2b9a548f72c0f2b63b822e0ab734cd8',
            ],
            'data.mac_address' => [
                'type' => 'string',
                'description' => "Mac Address below your device.",
                'example' => 'c44eac205e9c',
            ],
            'data.room_number' => [
                'type' => 'string',
                'description' => 'The set room number for the device.',
                'example' => '105'
            ],
            'data.version' => [
                'type' => 'string',
                'description' => 'The system version.',
                'example' => '4.1'

            ],
            'data.area_id' => [
                'type' => 'integer',
                'description' => 'The Area Id of device.',
                'example' => '1'

            ],
            // Add other parameters similarly...
        ];
    }

    // protected function failedValidation(Validator $validator)
    // {
    //     $response = response()->json([
    //         'result' => 'failed',
    //         'message' => 'Validation error',
    //         'errors' => $validator->errors(),
    //     ], 200); // returns 200 instead of 422

    //     throw new HttpResponseException($response);
    // }
}
