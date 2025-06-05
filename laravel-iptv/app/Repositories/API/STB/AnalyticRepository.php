<?php

namespace App\Repositories\API\STB;

use App\Http\Resources\API\STB\AnalyticTypeResource;
use App\Interfaces\API\STB\IAnalyticRepository;
use App\Models\Analytics\Analytic;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Tv\TvChannel;
use App\Models\GeneralStatus;
use App\Models\SystemSettings\ThemeManager\DefaultApp;
use Illuminate\Support\Facades\DB;

class AnalyticRepository implements IAnalyticRepository
{
    public function getAnalyticType($request)
    {
        $tvChannels = GeneralStatus::where('category', 'analytics_type')->get();

        return AnalyticTypeResource::collection($tvChannels)
        ->additional([
                'result' => __('success'),
            ])
        ->response()
        ->setStatusCode(200); // HTTP status 200 OK
    }

    public function postAnalytics($request)
    {
        $data = $request->data;
        $createdIds = []; // Store created IDs

        DB::beginTransaction(); // Begin the transaction before processing

        foreach ($data as $index => $row) {
            
            $result = $this->process_data($row['type_id'], $row['room_id'], $row['item_id'], $row['counter']);

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
             // Collect created ID
            if (isset($result['id'])) {
                $createdIds[] = $result['id'];
            }           
        }

        DB::commit(); 

        // Fetch newly created records from the database
        $analytics = Analytic::whereIn('id', $createdIds)->get();

        return AnalyticTypeResource::collection($analytics)
            ->additional([
                    'result' => __('success'),
                    'message' => __('Analytics created successfully'),
                ])
            ->response()
            ->setStatusCode(201); // HTTP status 200 OK
    }

    private function process_data($type_id, $room_id, $item_id, $counter)
    {
        try {
            // Retrieve the existing record
            $room = Room::where('id', $room_id);

            // Check if the record exists
            if (!$room) { 
                return [
                    'result' => 'Failed',
                    'message' => 'Room not found for room ID' . $room_id
                ];
            }
            
            $item = $this->analyticsType($type_id, $item_id);
            if(!$item){
                return [
                    'result' => 'Failed',
                    'message' => 'Item not found for ID ' . $item_id .' with type ID = '. $type_id
                ];
            }

            $data = new Analytic();
            $data->counter = $counter;
            $data->type_id = $type_id;
            $data->item_id = $item_id;
            $data->category_key = $item;
            $data->room_id = $room_id;

            if (!$data->save()) {
                return [
                    'status'=>'Failed',
                    'message'=>'Record is not saved.'
                ];
            }

            return ['result' => 'Success', 'id' => $data->id];

        } catch (\Throwable $e) {
            \Log::error('Transaction error: ' . $e->getMessage());
            return [
                'result'=>'Failed',
                'message'=> $e->getMessage()
            ];
        }
    }

    private function analyticsType($type_id, $item_id)
    {
        switch ($type_id) {
            case '51':
                return DefaultApp::find($item_id) ? 'application' : null;
            case '52':
                return TvChannel::find($item_id) ? 'tv' : null;
            default:
                return null;
        }
    }
}