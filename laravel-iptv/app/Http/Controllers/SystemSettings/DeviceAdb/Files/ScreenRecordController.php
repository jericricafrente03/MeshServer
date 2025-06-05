<?php

namespace App\Http\Controllers\SystemSettings\DeviceAdb\Files;

use App\Http\Controllers\Controller;
use App\Interfaces\SystemSettings\DeviceAdb\Files\IScreenRecordRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScreenRecordController extends Controller
{
    private $screenRecordRepository;

    public function __construct(IScreenRecordRepository $screenRecordRepository)
    {
        $this->screenRecordRepository = $screenRecordRepository;
        $this->middleware('permission:device_adb_screen_record.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/deviceAdb/files/screenRecord/index';    

        $breadCrumb = ['Device ADB ', 'Files > Screen Record'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->screenRecordRepository->getData($request);
    
                return response()->json($data, 200);
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function checkTableChanges(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->screenRecordRepository->checkTableChanges($request);
    
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
                $data = $this->screenRecordRepository->delete($request);
    
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
