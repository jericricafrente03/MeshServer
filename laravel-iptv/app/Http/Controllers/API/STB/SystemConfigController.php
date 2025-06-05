<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\ISystemConfigRepository;
use Illuminate\Http\Request;

/**
* @group API SystemConfigController
*
* System Config.
*/
class SystemConfigController extends Controller
{
    private $systemConfigRepository;

    public function __construct(ISystemConfigRepository $systemConfigRepository)
    {
        $this->systemConfigRepository = $systemConfigRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResource status=200 App\Http\Resources\API\STB\SystemConfigResource
     * @apiResourceModel App\Models\SystemSettings\SystemConfig
     * @apiResourceAdditional result=success
     */
    public function getSystemConfig(Request $request)
    {
        try {
            return $this->systemConfigRepository->getSystemConfig($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
