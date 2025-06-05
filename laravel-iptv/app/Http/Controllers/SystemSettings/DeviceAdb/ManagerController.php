<?php

namespace App\Http\Controllers\SystemSettings\DeviceAdb;

use App\Http\Controllers\Controller;
use App\Http\Requests\SystemSettings\DeviceAdb\Manager\GroupInstallRequest;
use App\Http\Requests\SystemSettings\DeviceAdb\Manager\GroupUninstallRequest;
use App\Http\Requests\SystemSettings\DeviceAdb\Manager\SettingsRequest;
use App\Interfaces\SystemSettings\DeviceAdb\IManagerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Routing\RequestContext;

class ManagerController extends Controller
{
    private $managerRepository;

    public function __construct(IManagerRepository $managerRepository)
    {
        $this->managerRepository = $managerRepository;
        $this->middleware('permission:device_adb_manager.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/deviceAdb/manager/index';    

        $breadCrumb = ['Device ADB ', 'Manager'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->managerRepository->getData($request);
    
                return response()->json($data, 200);
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function getApkList(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->managerRepository->getApkList($request);
    
                return response()->json($data, 200);
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function groupInstall(GroupInstallRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->managerRepository->groupInstall($request);
    
                return $data;
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function groupUninstall(GroupUninstallRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->managerRepository->groupUninstall($request);
    
                return $data;
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function settings(SettingsRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->managerRepository->settings($request);
    
                return $data;
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function screenCapture(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->managerRepository->screenCapture($request);
    
                return $data;
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function screenRecord(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->managerRepository->screenRecord($request);
    
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
