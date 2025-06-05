<?php

namespace App\Http\Controllers\SystemSettings\ThemeManager;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettings\ThemeManager\Zones\ZoneStoreRequest;
use App\Http\Requests\SystemSettings\ThemeManager\Zones\ZoneUpdateRequest;
use App\Interfaces\SystemSettings\ThemeManager\IZoneRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    private $zoneRepository;

    public function __construct(IZoneRepository $zoneRepository)
    {
        $this->zoneRepository = $zoneRepository;
        $this->middleware('permission:zones.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/systemSettings/themeManager/zones/index';    

        $breadCrumb = ['System Settings ', 'Theme Manager - Zones'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->zoneRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(ZoneStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->zoneRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(ZoneUpdateRequest $request)
    {   
        if($request->ajax()){
            try {
                $data = $this->zoneRepository->update($request);
                
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
                $data = $this->zoneRepository->delete($request);
    
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
