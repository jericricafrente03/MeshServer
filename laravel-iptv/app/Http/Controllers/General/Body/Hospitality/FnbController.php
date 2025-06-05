<?php

namespace App\Http\Controllers\General\Body\Hospitality;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Hospitality\Fnbs\FnbStoreRequest;
use App\Http\Requests\General\Body\Hospitality\Fnbs\FnbUpdateRequest;
use App\Interfaces\General\Body\Hospitality\IFnbRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FnbController extends Controller
{
    private $fnbRepository;

    public function __construct(IFnbRepository $fnbRepository)
    {
        $this->fnbRepository = $fnbRepository;
        $this->middleware('permission:fnb.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/hospitality/fnbs/index';    

        $breadCrumb = ['Hospitality', 'FnBs'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->fnbRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(FnbStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->fnbRepository->store($request);
    
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
                $data = $this->fnbRepository->toggleEnable($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(FnbUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->fnbRepository->update($request);
    
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
                $data = $this->fnbRepository->delete($request);
    
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
