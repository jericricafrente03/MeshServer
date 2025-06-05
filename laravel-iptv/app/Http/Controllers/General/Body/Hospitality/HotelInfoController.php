<?php

namespace App\Http\Controllers\General\Body\Hospitality;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Hospitality\HotelInfos\HotelInfoChangeOrderRequest;
use App\Http\Requests\General\Body\Hospitality\HotelInfos\HotelInfoStoreRequest;
use App\Http\Requests\General\Body\Hospitality\HotelInfos\HotelInfoUpdateRequest;
use App\Interfaces\General\Body\Hospitality\IHotelInfoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HotelInfoController extends Controller
{
    private $hotelInfoRepository;

    public function __construct(IHotelInfoRepository $hotelInfoRepository)
    {
        $this->hotelInfoRepository = $hotelInfoRepository;
        $this->middleware('permission:hotel_infos.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/hospitality/hotelInfos/index';    

        $breadCrumb = ['Hospitality', 'Hotel Infos'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->hotelInfoRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(HotelInfoStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->hotelInfoRepository->store($request);
    
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
                $data = $this->hotelInfoRepository->toggleEnable($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(HotelInfoUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->hotelInfoRepository->update($request);
    
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
                $data = $this->hotelInfoRepository->delete($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function changeOrder(HotelInfoChangeOrderRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->hotelInfoRepository->changeOrder($request);
    
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
