<?php

namespace App\Http\Controllers\General\Body\Tv;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Tv\TvChannelCategories\TvChannelCategoryChangeOrderRequest;
use App\Http\Requests\General\Body\Tv\TvChannelCategories\TvChannelCategoryDeleteRequest;
use App\Http\Requests\General\Body\Tv\TvChannelCategories\TvChannelCategoryStoreRequest;
use App\Http\Requests\General\Body\Tv\TvChannelCategories\TvChannelCategoryUpdateRequest;
use App\Interfaces\General\Body\Tv\ITvChannelCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvChannelCategoryController extends Controller
{
    private $tvChannelCategoryRepository;

    public function __construct(ITvChannelCategoryRepository $tvChannelCategoryRepository)
    {
        $this->tvChannelCategoryRepository = $tvChannelCategoryRepository;
        $this->middleware('permission:tv_channel_categories.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/tv/tvChannelCategories/index';    

        $breadCrumb = ['TV', 'TV Channel Categories'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelCategoryRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(TvChannelCategoryStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelCategoryRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(TvChannelCategoryUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelCategoryRepository->update($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function changeOrder(TvChannelCategoryChangeOrderRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelCategoryRepository->changeOrder($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function destroy(TvChannelCategoryDeleteRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelCategoryRepository->delete($request);
    
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
