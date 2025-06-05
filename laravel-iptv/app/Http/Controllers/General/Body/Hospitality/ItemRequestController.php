<?php

namespace App\Http\Controllers\General\Body\Hospitality;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Hospitality\ItemRequests\ItemRequestStoreRequest;
use App\Http\Requests\General\Body\Hospitality\ItemRequests\ItemRequestUpdateRequest;
use App\Interfaces\General\Body\Hospitality\IItemRequestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemRequestController extends Controller
{
    private $itemRequestRepository;

    public function __construct(IItemRequestRepository $itemRequestRepository)
    {
        $this->itemRequestRepository = $itemRequestRepository;
        $this->middleware('permission:item_requests.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/hospitality/itemRequests/index';    

        $breadCrumb = ['Hospitality', 'Item Requests'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->itemRequestRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(ItemRequestStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->itemRequestRepository->store($request);
    
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
                $data = $this->itemRequestRepository->toggleEnable($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(ItemRequestUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->itemRequestRepository->update($request);
    
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
                $data = $this->itemRequestRepository->delete($request);
    
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
