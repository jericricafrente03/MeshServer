<?php

namespace App\Http\Controllers\General\Body\Devices;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Devices\IDeviceMonitorRepository;
use App\Models\General\Body\Devices\Device;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
* @group Devices > Device Monitor
*
* 
* WEB page for managing device monitor.
*/
class DeviceMonitorController extends Controller
{   
    private $deviceMonitorRepository;

    public function __construct(IDeviceMonitorRepository $deviceMonitorRepository)
    {
        $this->middleware('permission:device_monitor.index');
        $this->deviceMonitorRepository = $deviceMonitorRepository;
    }

    /**
    * Display the devices monitor index page.
    * 
    * @response 200
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/devices/deviceMonitor/index';    

        $breadCrumb = ['Devices', 'Device Monitor'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    /**
    * Get device monitor data for monitoring online/offline.
    * 
    */
    public function get_devices(Request $request)
    {
        if($request->ajax()){
            try {
                return $this->deviceMonitorRepository->get_devices();
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ], 500);
            }
        }
    }

    /**
    * Get device monitor data for pie chart.
    * 
    */
    public function chartData(Request $request)
    {
        if($request->ajax()){
            try {
                return $this->deviceMonitorRepository->chartData($request);
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ], 500);
            }
        }
    }
}
