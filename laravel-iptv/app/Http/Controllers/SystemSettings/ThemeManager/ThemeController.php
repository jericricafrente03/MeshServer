<?php

namespace App\Http\Controllers\SystemSettings\ThemeManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettings\ThemeManager\Themes\ThemeUpdateThemeRequest;
use App\Http\Requests\SystemSettings\ThemeManager\Themes\ThemeStoreRequest;
use App\Http\Requests\SystemSettings\ThemeManager\Themes\ThemeUpdateThemeApplicationRequest;
use App\Http\Requests\SystemSettings\ThemeManager\Themes\ThemeUpdateThemeZoneRequest;
use App\Interfaces\SystemSettings\ThemeManager\IThemeRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThemeController extends Controller
{
    private $themeRepository;

    public function __construct(IThemeRepository $themeRepository)
    {
        $this->themeRepository = $themeRepository;
        $this->middleware('permission:themes.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/systemSettings/themeManager/themes/index';    

        $breadCrumb = ['System Settings ', 'Theme Manager - Themes'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->themeRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(ThemeStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->themeRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function toggleDefault(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->themeRepository->toggleDefault($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function updateTheme(ThemeUpdateThemeRequest $request)
    {   
        if($request->ajax()){
            try {
                $data = $this->themeRepository->updateTheme($request);
                
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getThemeZoneData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->themeRepository->getThemeZoneData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function updateThemeZone(ThemeUpdateThemeZoneRequest $request)
    {   
        if($request->ajax()){
            try {
                $data = $this->themeRepository->updateThemeZone($request);
                
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
                $data = $this->themeRepository->delete($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function assignRoom(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->themeRepository->assignRoom($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getThemeApplicationData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->themeRepository->getThemeApplicationData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function updateThemeApplication(ThemeUpdateThemeApplicationRequest $request)
    {   
        if($request->ajax()){
            try {
                $data = $this->themeRepository->updateThemeApplication($request);
                
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
                $data = $this->themeRepository->toggleEnable($request);
    
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
