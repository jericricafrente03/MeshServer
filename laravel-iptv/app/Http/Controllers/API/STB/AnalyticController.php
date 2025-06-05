<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\STB\PostAnalyticsRequest;
use App\Interfaces\API\STB\IAnalyticRepository;
use Illuminate\Http\Request;

/**
* @group API AnalyticController
*
* Analytics.
*/
class AnalyticController extends Controller
{
    private $analyticRepository;

    public function __construct(IAnalyticRepository $analyticRepository)
    {
        $this->analyticRepository = $analyticRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\AnalyticTypeResource
     * @apiResourceModel App\Models\Analytics\Analytic
     * @apiResourceAdditional result=success
     */
    public function getAnalyticType(Request $request)
    {
        try {
            return $this->analyticRepository->getAnalyticType($request);
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
     * @apiResourceCollection status=201 App\Http\Resources\API\STB\AnalyticTypeResource
     * @apiResourceModel App\Models\Analytics\Analytic
     * @apiResourceAdditional result=success message="Analytics created successfully"
     */
    public function postAnalytics(PostAnalyticsRequest $request)
    {   
        try {
            return $this->analyticRepository->postAnalytics($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
