<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\STB\Devices\EnterNetflixRequest;
use App\Http\Requests\API\STB\Devices\GetLanguageRequest;
use App\Http\Requests\API\STB\Devices\GetWifiQrCodeRequest;
use App\Interfaces\API\STB\IDeviceRepository;
use Illuminate\Http\Request;

/**
* @group API DeviceController
*
* Device.
*/
class DeviceController extends Controller
{
    private $deviceRepository;

    public function __construct(IDeviceRepository $deviceRepository)
    {
        $this->deviceRepository = $deviceRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResource  status=200 App\Http\Resources\API\STB\LanguageResource
     * @apiResourceAdditional result=success
     */
    public function getLanguage(GetLanguageRequest $request)
    {
        try {
            return $this->deviceRepository->getLanguage($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResource  status=200 App\Http\Resources\API\STB\WifiQrCodeResource
     * @apiResourceAdditional result=success
     */
    public function getWifiQrCode(GetWifiQrCodeRequest $request)
    {
        try {
            return $this->deviceRepository->getWifiQrCode($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceAdditional result=success
     */
    public function enterNetflix(Request $request)
    {
        try {
            return $this->deviceRepository->enterNetflix($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceAdditional result=success
     */
    public function clearCache(Request $request)
    {
        try {
            return $this->deviceRepository->clearCache($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
