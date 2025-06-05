<?php

namespace App\Http\Controllers\General\Body\Notifications;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Notifications\INotificationRepository;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    private $notificationRepository;

    public function __construct(INotificationRepository $notificationRepository)
    {
        $this->notificationRepository = $notificationRepository;
        // $this->middleware('permission:tv_channels.index');
    }

    public function getNotifications(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->notificationRepository->getNotifications($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function readNotification(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->notificationRepository->readNotification($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }
}
