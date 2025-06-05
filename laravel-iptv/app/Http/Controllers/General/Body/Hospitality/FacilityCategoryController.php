<?php

namespace App\Http\Controllers\General\Body\Hospitality;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Hospitality\FacilityCategories\FacilityCategoryChangeOrderRequest;
use App\Http\Requests\General\Body\Hospitality\FacilityCategories\FacilityCategoryDeleteRequest;
use App\Http\Requests\General\Body\Hospitality\FacilityCategories\FacilityCategoryStoreRequest;
use App\Http\Requests\General\Body\Hospitality\FacilityCategories\FacilityCategoryUpdateRequest;
use App\Interfaces\General\Body\Hospitality\IFacilityCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FacilityCategoryController extends Controller
{
    private $facilityCategoryRepository;

    public function __construct(IFacilityCategoryRepository $facilityCategoryRepository)
    {
        $this->facilityCategoryRepository = $facilityCategoryRepository;
        $this->middleware('permission:facility_categories.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/hospitality/facilityCategories/index';    

        $breadCrumb = ['Hospitality', 'Facility Categories'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->facilityCategoryRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(FacilityCategoryStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->facilityCategoryRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function changeOrder(FacilityCategoryChangeOrderRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->facilityCategoryRepository->changeOrder($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(FacilityCategoryUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->facilityCategoryRepository->update($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function destroy(FacilityCategoryDeleteRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->facilityCategoryRepository->delete($request);
    
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
