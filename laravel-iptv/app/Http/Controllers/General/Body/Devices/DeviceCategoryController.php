<?php

namespace App\Http\Controllers\General\Body\Devices;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Devices\DeviceGroup\ChangeOrderRequest;
use App\Http\Requests\General\Body\Devices\DeviceGroup\DeviceGroupDeleteRequest;
use App\Http\Requests\General\Body\Devices\DeviceGroup\StoreRequest;
use App\Http\Requests\General\Body\Devices\DeviceGroup\UpdateRequest;
use App\Interfaces\General\Body\Devices\IDeviceCategoryRepository;
use App\Interfaces\General\Body\Devices\IDeviceRepository;
use App\Interfaces\IUserHistoryLogRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
* @group Devices > Device Categories
*
* 
* WEB page for managing device categories.
*/
class DeviceCategoryController extends Controller
{
    private $deviceCategory;

    public function __construct(IDeviceCategoryRepository $deviceCategory)
    {
        $this->deviceCategory = $deviceCategory;
        $this->middleware('permission:device_group.index');
    }

    /**
    * Display the device categories index page.
    * 
    * @response 200
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/devices/deviceGroup/index';    

        $breadCrumb = ['Devices', 'Device Group'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }
    
    /**
    * Get device categories data for DT.
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
                $data = $this->deviceCategory->get_data($request);
    
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
    * Store a new device category.
    *
    * @response 201 {
    *   "status":"success",
    *   "message":"Record has been saved."
    * }
    * @response 404 {
    *   "status":"warning",
    *   "message":"Record is not saved."
    * }
    * @response 500 {
    *   "error": "Some error message",
    *   "message": "Something went wrong in [current class].[current function]."
    * }
    */
    public function store(StoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceCategory->store($request);
    
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
    * Update an existing device ctegory.
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
    public function update(UpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceCategory->update($request);
    
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
    * Delete a device category.
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
    public function destroy(DeviceGroupDeleteRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceCategory->delete($request);
    
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
    * Change order of an existing device category.
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
    public function changeOrder(ChangeOrderRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->deviceCategory->changeOrder($request);
    
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
