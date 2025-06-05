<?php

namespace App\Http\Controllers\General\Body\Messages\BroadcastMessages;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Messages\BroadcastMessages\Emergencies\EmergencyStoreRequest;
use App\Interfaces\General\Body\Messages\BroadcastMessages\IEmergencyRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmergencyController extends Controller
{
    private $emergencyRepository;

    public function __construct(IEmergencyRepository $emergencyRepository)
    {
        $this->emergencyRepository = $emergencyRepository;
        $this->middleware('permission:emergencies.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/messages/broadcastMessages/emergencies/index';    

        $breadCrumb = ['Messages', 'Broadcast Messaging - Emergency'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->emergencyRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(EmergencyStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->emergencyRepository->store($request);
    
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
                $data = $this->emergencyRepository->delete($request);
    
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
                $data = $this->emergencyRepository->resend($request);
    
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
