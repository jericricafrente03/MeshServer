<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\IAielloRepository;
use Illuminate\Http\Request;

class AielloController extends Controller
{
    private $aielloRepository;

    public function __construct(IAielloRepository $aielloRepository)
    {
        $this->aielloRepository = $aielloRepository;
    }

    public function toggleTvPower(Request $request)
    {   
        try {
            return $this->aielloRepository->toggleTvPower($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    public function volumeChange(Request $request)
    {   
        try {
            return $this->aielloRepository->volumeChange($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    public function getTvChannels(Request $request)
    {   
        try {
            return $this->aielloRepository->getTvChannels($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    public function openApplication(Request $request)
    {   
        try {
            return $this->aielloRepository->openApplication($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    public function changeTvChannel(Request $request)
    {   
        try {
            return $this->aielloRepository->changeTvChannel($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
