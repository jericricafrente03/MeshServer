<?php

namespace App\Repositories\General\Body\Notifications;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Notifications\INotificationRepository;
use App\Interfaces\IUserHistoryLogRepository;
use App\Models\General\Body\Notifications\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationRepository extends Controller implements INotificationRepository
{
    protected $logHistoriesRepo;
    protected $activityName;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->activityName = 'notification';
    }

    function getNotifications($request)
    {
        $data = Notification::where('is_read', 0)->limit(20)->get();

        $data = $data->map(function ($item) {
            if ($item->type === 'fnb') {
                $item->url = '/guest-billings?search=?' . $item->item_id;
            } elseif ($item->type === 'item_request') {
                $item->url = '/guest-billings?search=?' . $item->item_id;
            } else {
                $item->url = '#'; // Default or unknown type
            }

            $item->readable_created_at = Carbon::parse($item->created_at)->format('M j, Y h:i A');

            return $item;
        });

        return $data;
    }

    function readNotification($request)
    {   
        try{
            DB::beginTransaction();
            $data = Notification::where('id', $request->id)->where('is_read', 0)->first();
            if (!$data) {
                return response()->json([
                    'status' => 'warning',
                    'message' => 'Record not found or already seen.'
                ], 404);
            }

            $oldDataArray = $data->toArray();
            $data->is_read = 1;

            if ($data->save()) {
                // Record saved successfully
                $newDataArray = $data->toArray();
                $this->logHistoriesRepo->update($oldDataArray, $newDataArray, $this->activityName);

                DB::commit();

                return response()->json([
                    'status'=>'success',
                    'message'=>'Record has been updated.'
                ], 200);
            } else {
                // Failed to save the record
                DB::rollBack(); 
                return response()->json([
                    'status'=>'warning',
                    'message'=>'Record is not updated.'
                ], 404);
            }

            return $data;
        } catch (\Exception $e) {
            DB::rollBack(); 
        
            return response()->json([
                'status'=>'warning',
                'message'=> $e->getMessage()
            ], 500);
        }
    }
}