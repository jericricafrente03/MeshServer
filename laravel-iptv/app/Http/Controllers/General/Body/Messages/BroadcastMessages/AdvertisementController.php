<?php

namespace App\Http\Controllers\General\Body\Messages\BroadcastMessages;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Messages\BroadcastMessages\Advertisements\AdvertisementStoreRequest;
use App\Interfaces\General\Body\Messages\BroadcastMessages\IAdvertisementRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdvertisementController extends Controller
{
    private $advertisementRepository;

    public function __construct(IAdvertisementRepository $advertisementRepository)
    {
        $this->advertisementRepository = $advertisementRepository;
        $this->middleware('permission:advertisements.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/messages/broadcastMessages/advertisements/index';    

        $breadCrumb = ['Messages', 'Broadcast Messaging - Advertisement'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->advertisementRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(AdvertisementStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->advertisementRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->advertisementRepository->delete($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function resend(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->advertisementRepository->resend($request);
    
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
