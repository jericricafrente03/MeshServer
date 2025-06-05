<?php

namespace App\Http\Controllers\General\Body\Hospitality;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Hospitality\ServiceRequests\ServiceRequestStoreRequest;
use App\Http\Requests\General\Body\Hospitality\ServiceRequests\ServiceRequestUpdateRequest;
use App\Interfaces\General\Body\Hospitality\IServiceRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceRequestController extends Controller
{
    private $serviceRequestRepository;

    public function __construct(IServiceRequestRepository $serviceRequestRepository)
    {
        $this->serviceRequestRepository = $serviceRequestRepository;
        $this->middleware('permission:service_requests.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/hospitality/serviceRequests/index';    

        $breadCrumb = ['Hospitality', 'Service Requests'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->serviceRequestRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(ServiceRequestStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->serviceRequestRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function toggleEnable(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->serviceRequestRepository->toggleEnable($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(ServiceRequestUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->serviceRequestRepository->update($request);
    
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
                $data = $this->serviceRequestRepository->delete($request);
    
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
