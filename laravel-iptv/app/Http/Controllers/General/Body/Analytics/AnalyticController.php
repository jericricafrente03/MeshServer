<?php

namespace App\Http\Controllers\General\Body\Analytics;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Analytics\IAnalyticRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AnalyticController extends Controller
{
    private $analyticRepository;
    private $user;

    public function __construct(IAnalyticRepository $analyticRepository)
    {
        $this->analyticRepository = $analyticRepository;
        $this->user = Auth::user();
    }

    public function getApps(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getApps($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getYearCheckin(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getYearCheckin($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getRooms(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getRooms($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getGuests(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getGuests($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getTop10TvChannels(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getTop10TvChannels($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getTop10Fnbs(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getTop10Fnbs($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getTop10ItemRequests(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getTop10ItemRequests($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getTop10ServiceRequests(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getTop10ServiceRequests($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function pingDevices(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->pingDevices($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function tvChannelsIndex()
    {   
        $user = $this->user;

        $viewPath = '/general/body/analytics/tvChannels/index';    

        $breadCrumb = ['Analytics', 'Tv Channels'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getTvChannelData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getTvChannelData($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getTvChannelCounter(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getTvChannelCounter($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function regularMessagingIndex()
    {   
        $user = $this->user;

        $viewPath = '/general/body/analytics/regularMessaging/index';    

        $breadCrumb = ['Analytics', 'Regular Messaging'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getRegularMessagingData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getRegularMessagingData($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function broadcastMessagingIndex()
    {   
        $user = $this->user;

        $viewPath = '/general/body/analytics/broadcastMessaging/index';    

        $breadCrumb = ['Analytics', 'Broadcast Messaging'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getBroadcastMessagingData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getBroadcastMessagingData($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function fnbIndex()
    {   
        $user = $this->user;

        $viewPath = '/general/body/analytics/fnbs/index';    

        $breadCrumb = ['Analytics', 'FnB'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getFnbData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getFnbData($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getFnbCounter(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getFnbCounter($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function itemRequestIndex()
    {   
        $user = $this->user;

        $viewPath = '/general/body/analytics/itemRequests/index';    

        $breadCrumb = ['Analytics', 'Item Requests'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getItemRequestData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getItemRequestData($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getItemRequestCounter(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getItemRequestCounter($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function serviceRequestIndex()
    {   
        $user = $this->user;

        $viewPath = '/general/body/analytics/serviceRequests/index';    

        $breadCrumb = ['Analytics', 'Service Requests'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getServiceRequestData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getServiceRequestData($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getServiceRequestCounter(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->analyticRepository->getServiceRequestCounter($request);
    
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
