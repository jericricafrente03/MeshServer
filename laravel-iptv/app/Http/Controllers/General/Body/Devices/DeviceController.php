<?php

namespace App\Http\Controllers\General\Body\Devices;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Devices\IDeviceRepository;
use App\Interfaces\IUserHistoryLogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\Process\Process;

/**
* @group Devices > Devices
*
* 
* WEB page for managing devices.
*/
class DeviceController extends Controller
{
    protected $logHistoriesRepo;
    protected $activityName;
    private $deviceRepository;

    public function __construct(IUserHistoryLogRepository $logHistoriesRepo, IDeviceRepository $deviceRepository)
    {
        $this->logHistoriesRepo = $logHistoriesRepo;
        $this->deviceRepository = $deviceRepository;
        $this->middleware('permission:devices.index');
        $this->activityName = 'devices';
    }

    /**
    * Display the devices index page.
    * 
    * @response 200
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/devices/devices/index';    

        $breadCrumb = ['Devices', 'Devices'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    /**
    * Get devices data for DT.
    * 
    * @response 200 {
    *   "data": [
    *     {
    *       ...
    *     }
    *   ]
    * }
    * @response 500 {
    *   "error": "Some error message",
    *   "message": "Something went wrong in [current class].[current function]."
    * }
    */
    public function get_data(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceRepository->get_data($request);
                // $token = $user->createToken('user_token')->plainTextToken;
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    /**
    * Detect/ping a device.
    *
    */
    public function detectDevices(Request $request)
    {
        if($request->ajax()){
            try {
                // $data = $this->deviceRepository->get_data($request);
                // $token = $user->createToken('user_token')->plainTextToken;
                $data = $request->all();
                $data = $this->deviceRepository->detectDevices();

                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    /**
    * Update an existing device.
    * @response 200 {
    *   "status":"success",
    *   "message":"Record has been updated."
    * }
    * @response 404 {
    *   "status":"warning",
    *   "message":"Record is not updated."   
    * }
    * @response 500 {
    *   "error": "Some error message",
    *   "message": "Something went wrong in [current class].[current function]."
    * }
    */
    public function update(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceRepository->update($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    /**
    * Delete a device.
    * 
    * @response 200 {
    *   "status":"success",
    *   "message":"Record has been deleted."
    * }
    * @response 404 {
    *   "status":"warning",
    *   "message":"Record is not deleted."
    * }
    * @response 500 {
    *   "error": "Some error message",
    *   "message": "Something went wrong in [current class].[current function]."
    * }
    */
    public function destroy(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceRepository->delete($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    /**
    * ADB reboot a device.
    * 
    */
    public function adbReboot(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceRepository->adbReboot($request);
                return $data;

            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
            
    }

    /**
    * ADB reset data of a device.
    * 
    */
    public function adbResetData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceRepository->adbResetData($request);
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
