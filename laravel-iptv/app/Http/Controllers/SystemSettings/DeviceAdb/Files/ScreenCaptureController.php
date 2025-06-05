<?php

namespace App\Http\Controllers\SystemSettings\DeviceAdb\Files;

use App\Http\Controllers\Controller;
use App\Interfaces\SystemSettings\DeviceAdb\Files\IScreenCaptureRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScreenCaptureController extends Controller
{
    private $screenCaptureRepository;

    public function __construct(IScreenCaptureRepository $screenCaptureRepository)
    {
        $this->screenCaptureRepository = $screenCaptureRepository;
        $this->middleware('permission:device_adb_screen_capture.index');
    }

    public function index()
    {
        $user = Auth::user();

        $viewPath = '/deviceAdb/files/screenCapture/index';    

        $breadCrumb = ['Device ADB ', 'Files > Screen Capture'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->screenCaptureRepository->getData($request);
    
                return response()->json($data, 200);
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
                $data = $this->screenCaptureRepository->delete($request);
    
                return $data;
    
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
                $data = $this->screenCaptureRepository->checkTableChanges($request);
    
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
