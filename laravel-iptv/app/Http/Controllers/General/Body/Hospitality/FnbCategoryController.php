<?php

namespace App\Http\Controllers\General\Body\Hospitality;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Hospitality\FnbCategories\FnbCategoryChangeOrderRequest;
use App\Http\Requests\General\Body\Hospitality\FnbCategories\FnbCategoryDeleteRequest;
use App\Http\Requests\General\Body\Hospitality\FnbCategories\FnbCategoryStoreRequest;
use App\Http\Requests\General\Body\Hospitality\FnbCategories\FnbCategoryUpdateRequest;
use App\Interfaces\General\Body\Hospitality\IFnbCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FnbCategoryController extends Controller
{
    private $fnbCategoryRepository;

    public function __construct(IFnbCategoryRepository $fnbCategoryRepository)
    {
        $this->fnbCategoryRepository = $fnbCategoryRepository;
        $this->middleware('permission:fnb_categories.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/hospitality/fnbCategories/index';    

        $breadCrumb = ['Hospitality', 'FnB Categories'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->fnbCategoryRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(FnbCategoryStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->fnbCategoryRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(FnbCategoryUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->fnbCategoryRepository->update($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function changeOrder(FnbCategoryChangeOrderRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->fnbCategoryRepository->changeOrder($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function destroy(FnbCategoryDeleteRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->fnbCategoryRepository->delete($request);
    
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
