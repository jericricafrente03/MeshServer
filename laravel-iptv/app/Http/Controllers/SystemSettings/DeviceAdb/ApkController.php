<?php

namespace App\Http\Controllers\SystemSettings\DeviceAdb;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettings\DeviceAdb\Apk\ApkStoreRequest;
use App\Interfaces\SystemSettings\DeviceAdb\IApkRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApkController extends Controller
{
    private $apkRepository;

    public function __construct(IApkRepository $apkRepository)
    {
        $this->apkRepository = $apkRepository;
        $this->middleware('permission:device_adb_apk.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/deviceAdb/apk/index';    

        $breadCrumb = ['Device ADB ', 'APK'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->apkRepository->getData($request);
    
                return response()->json($data, 200);
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(ApkStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->apkRepository->store($request);
    
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
                $data = $this->apkRepository->delete($request);
    
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
