<?php

namespace App\Repositories\API\STB\Guests;

use App\Http\Resources\API\STB\Guests\RoomAssignmentResource;
use App\Interfaces\API\STB\Guests\IRoomAssignmentRepository;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Guests\RoomAssignment;

class RoomAssignmentRepository implements IRoomAssignmentRepository
{
    public function getCustomer($request)
    {
        $data = $request;
       
        $room = Room::where('name', $data['room'])->first();
        if(!$room){
            return response()->json([
                'data' => $this->emptyRoomAssignment(),
                'result' => 'failed',
                'message' => 'Record not found.'
            ], 200);
        } 

        // Fetch room assignment with the guest relationship
        $guest = RoomAssignment::with('guest')
            ->where('room_id', $room->id)
            ->where('is_checkout', '!=', 1)
            ->first();

        if (!$guest) {
            return response()->json([
                'data' => $this->emptyRoomAssignment(),
                'result' => 'failed',
                'message' => 'No active guest found in this room.'
            ], 200);
        }

        
        return RoomAssignmentResource::make($guest)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }

    /**
     * Returns an empty structure for RoomAssignment
     */
    private function emptyRoomAssignment()
    {
        return [
            "id" => null,
            "customer_name" => null,
            "customer_id" => null,
            "room_number" => null,
            "room_id" => null,
            "check_in" => null,
            "check_out" => null,
            "s_check_out" => null,
            "reserve_no" => null,
            "cs" => null,
            "gs" => null,
            "is_checkout" => null,
            "guest" => [
                "id" => null,
                "title" => null,
                "firstname" => null,
                "lastname" => null,
                "birthdate" => null,
                "street1" => null,
                "street2" => null,
                "city" => null,
                "state_region" => null,
                "country_id" => null,
                "zip_code" => null,
                "mobile_no" => null,
                "landline_no" => null,
                "email" => null,
            ]
        ];
    }
}