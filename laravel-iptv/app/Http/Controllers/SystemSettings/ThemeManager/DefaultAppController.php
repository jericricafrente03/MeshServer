<?php

namespace App\Http\Controllers\SystemSettings\ThemeManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettings\ThemeManager\DefaultApps\DefaultAppStoreRequest;
use App\Http\Requests\SystemSettings\ThemeManager\DefaultApps\DefaultAppUpdateRequest;
use App\Interfaces\SystemSettings\ThemeManager\IDefaultAppRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DefaultAppController extends Controller
{
    private $defaultAppRepository;

    public function __construct(IDefaultAppRepository $defaultAppRepository)
    {
        $this->defaultAppRepository = $defaultAppRepository;
        $this->middleware('permission:default_apps.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/systemSettings/themeManager/defaultApps/index';    

        $breadCrumb = ['System Settings ', 'Theme Manager - Default Apps'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->defaultAppRepository->getData($request);
    
                return response()->json($data, 200);
    
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
                $data = $this->defaultAppRepository->toggleEnable($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(DefaultAppStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->defaultAppRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(DefaultAppUpdateRequest $request)
    {   
        if($request->ajax()){
            try {
                $data = $this->defaultAppRepository->update($request);
                
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
                $data = $this->defaultAppRepository->delete($request);
    
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
