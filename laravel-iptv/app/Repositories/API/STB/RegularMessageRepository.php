<?php

namespace App\Repositories\API\STB;

use App\Events\General\Body\Devices\DevicesUpdated;
use App\Http\Resources\API\STB\RegularMessageResource;
use App\Interfaces\API\STB\IRegularMessageRepository;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Messages\MessageRecipient;
use Illuminate\Support\Facades\DB;

class RegularMessageRepository implements IRegularMessageRepository
{
    public function getMessage($request)
    {
        $data = $request;
       
        $room = Room::where('name', $data['room'])->first();
        if(!$room){
            return response()->json([
                'data' => [],
                'result' => 'failed',
                'message' => 'Record not found.'
            ], 404);
        } 

        $recipients = MessageRecipient::with('status')
            ->where('room_id', $room->id)
            ->where('status_id', '!=', 34)
            ->whereHas('message', function ($query) {
                $query->whereNull('deleted_at'); // Ensure related regular_messages are not soft deleted
            })
            ->get();

        foreach ($recipients as $recipient) {
            if ($recipient->status_id == 31) {
                $recipient->update(['status_id' => 32]);
            }
        }
        
        return RegularMessageResource::collection($recipients)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }

    public function readMessage($request)
    {
        $data = $request->data;
        DB::beginTransaction(); // Begin the transaction before processing
        // dd($data);
        $room = Room::where('name', $data['room'])->first();
        if(!$room){
            DB::rollBack(); 
            return response()->json([
                'result' => 'failed',
                'message' => 'Room not found.'
            ], 404);
        } 

        $recipient = MessageRecipient::where('id', $data['message_recipient_id'])->first();
        if(!$recipient){
            DB::rollBack(); 
            return response()->json([
                'result' => 'failed',
                'message' => 'Message Recipient Id not found.'
            ], 404);
        } 

        $recipient->status_id = 33;

        if ($recipient->save()) {
            // Now fire the event
            event(new DevicesUpdated($recipient));
            DB::commit();
            return response()->json([
                'result'=>'success',
                'message'=>'Record has been updated.'
            ], 200);
        }
        else {
            // Failed to save the record
            DB::rollBack(); 
            return response()->json([
                'result'=>'failed',
                'message'=>'Record is not updated.'
            ], 404);
        }

    }

    public function deleteMessage($request)
    {
        $data = $request->data;
        // DB::beginTransaction(); // Begin the transaction before processing
        // dd($data);
        $room = Room::where('name', $data['room'])->first();
        if(!$room){
            DB::rollBack(); 
            return response()->json([
                'result' => 'failed',
                'message' => 'Room not found.'
            ], 404);
        } 

        $recipient = MessageRecipient::where('id', $data['message_recipient_id'])->first();
        if(!$recipient){
            DB::rollBack(); 
            return response()->json([
                'result' => 'failed',
                'message' => 'Message Recipient Id not found.'
            ], 404);
        } 

        $recipient->status_id = 34;

        if ($recipient->save()) {
            // Now fire the event
            event(new DevicesUpdated($recipient));
            DB::commit();
            return response()->json([
                'result'=>'success',
                'message'=>'Record has been deleted.'
            ], 200);
        }
        else {
            // Failed to save the record
            DB::rollBack(); 
            return response()->json([
                'result'=>'failed',
                'message'=>'Record is not deleted.'
            ], 404);
        }
    }
}