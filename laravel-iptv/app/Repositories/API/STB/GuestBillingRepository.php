<?php

namespace App\Repositories\API\STB;

use App\Events\General\Body\Devices\DevicesUpdated;
use App\Http\Resources\API\STB\GuestBillingResource;
use App\Interfaces\API\STB\IGuestBillingRepository;
use App\Models\General\Body\Guests\GuestBilling;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Guests\RoomAssignment;
use App\Models\General\Body\Hospitality\Fnb;
use App\Models\General\Body\Hospitality\HospitalityItem;
use App\Models\General\Body\Hospitality\HospitalityService;
use App\Models\General\Body\Notifications\Notification;
use App\Models\GeneralStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\Hash;
use Jenssegers\Agent\Facades\Agent;

class GuestBillingRepository implements IGuestBillingRepository
{   
    public function meshTransaction($request)
    {   
        $data = $request->data;
        // $errors = []; // Array to hold errors for each row

        DB::beginTransaction(); // Begin the transaction before processing
        $refno = round(microtime(true) * 1000);

        foreach ($data as $index => $row) {
            
            $result = $this->process_transaction($row['type'], $row['room_id'], $row['item_id'], $row['quantity'], $row['unit_price'], $refno, $row['room_assignment_id']);

            // If the result is a response with an error, capture the error message
            if (isset($result['result']) && $result['result'] === 'Failed') {
                DB::rollBack(); 
                 // Return the error immediately and break the loop
                return response()->json([
                    'result' => 'Failed',
                    'message' => 'Error occurred.',
                    'errors' => [$result] // Return the specific error
                ], 422);
            }              
        }

        // If all transactions were successful, commit the transaction
        DB::commit(); 

        $guestBillings = GuestBilling::where('refno', $refno)->get();
        $event = [
            'type' => 'notifications',
            'data' => $guestBillings,
        ];
        event(new DevicesUpdated($event));
        // return response()->json([
        //     'result' => 'Success',
        //     'message' => 'All transactions saved successfully.'
        // ], 200);

        // To return a single event
        // $event = Event::findOrFail($id); // Find the event by ID
        
        // return UserResource::make($event)
        //     ->additional([
        //         'result' => __('success'),
        //         'message' => __('Event retrieved successfully'),
        //     ])
        //     ->response()
        //     ->setStatusCode(200); // HTTP status 200 OK
        
        // To return a collection of events
        // $events = Event::all(); // Retrieve all events

        // return UserResource::collection($events)
        //     ->additional([
        //         'result' => __('success'),
        //         'message' => __('Events retrieved successfully'),
        //     ])
        //     ->response()
        //     ->setStatusCode(200); // HTTP status 200 OK

        return GuestBillingResource::collection($guestBillings)
            ->additional([
                    'result' => __('success'),
                    'message' => __('Guest Billing created successfully'),
                ])
            ->response()
            ->setStatusCode(201); // HTTP status 200 OK
    }

    private function process_transaction($type, $room_id, $item_id, $quantity, $unit_price, $refno, $room_assignment_id)
    {
        try {
            // Retrieve the existing record
            $room = Room::find($room_id);

            // Check if the record exists
            if (!$room) { 
                return [
                    'result' => 'Failed',
                    'message' => 'Room not found for room ID ' . $room_id
                ];
            }
            
            // Retrieve the existing record
            $roomAssignment = RoomAssignment::find($room_assignment_id);

            // Check if the record exists
            if (!$roomAssignment) {
                return [
                    'result' => 'Failed',
                    'message' => 'Room Assignment not found for ID ' . $room_assignment_id
                ];
            }

            $guestName = ($roomAssignment->guest->title)?$roomAssignment->guest->title .' '. $roomAssignment->guest->firstname .' '. $roomAssignment->guest->lastname:$roomAssignment->guest->firstname .' '. $roomAssignment->guest->lastname;

            $status = GeneralStatus::find(11);

            // Check if the record exists
            if (!$status) {
                return [
                    'result' => 'Failed',
                    'message' => 'General Status not found for ID ' . 11
                ];
            }

            //to be use after all hospitality is finish
            $item = $this->meshTransactionType($type, $item_id);
            if(!$item){
                return [
                    'result' => 'Failed',
                    'message' => 'Item not found for ID ' . $item_id .' with type = '. $type
                ];
            }

            //to change after we finish fnb
            // $item = array(1,2,3);
            // if(!in_array($item_id, $item)){
            //     return [
            //         'result' => 'Failed',
            //         'message' => 'Item not found for ID ' . $item_id
            //     ];
            // }

            $data = new GuestBilling();
            $data->transaction_datetime = Carbon::now()->format('Y-m-d H:i:s');
            $data->room_number = $room->name;
            $data->room_id = $room->id;
            $data->category = $type;
            $data->item_name = $item->name;
            $data->item_id = $item_id;
            $data->quantity = $quantity;
            $data->unit_price = $unit_price;
            $data->refno = $refno;
            $data->status_id = $status->id;
            $data->status = $status->name;
            $data->user_id = 1;
            $data->room_assignment_id = $roomAssignment->id;
            $data->guest_name = $guestName;

            if (!$data->save()) {
                return [
                    'status'=>'Failed',
                    'message'=>'Record is not saved.'
                ];
            }

            $convertedType = ucwords(str_replace('_', ' ', $type));
            $notifData = new Notification();
            $notifData->type = $type;
            $notifData->item_id = $data->id;
            $notifData->message = 'New '.$convertedType.' order by room '.$room->name.'!';
            $notifData->save();
            
            return ['result' => 'Success'];
            
        } catch (\Throwable $e) {
            \Log::error('Transaction error: ' . $e->getMessage());
            return [
                'result'=>'Failed',
                'message'=> $e->getMessage()
            ];
        }
    }

    private function meshTransactionType($type, $item_id)
    {
        switch ($type) {
            case 'fnb':
                return Fnb::find($item_id);
            case 'item_request':
                return HospitalityItem::find($item_id);
            case 'service_request':
                return HospitalityService::find($item_id);
            default:
                return null;
        }
    }

    private function getRoom($id)
    {
        return Room::find($id);
    }
}