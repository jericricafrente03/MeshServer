<?php

namespace App\Http\Controllers\API\STB\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Hospitality\IFnbRepository;
use Illuminate\Http\Request;

/**
* @group API FnbController
*
* Fnb.
*/
class FnbController extends Controller
{
    private $fnbRepository;

    public function __construct(IFnbRepository $fnbRepository)
    {
        $this->fnbRepository = $fnbRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Hospitality\FnbResource
     * @apiResourceModel App\Models\General\Body\Hospitality\Fnb
     * @apiResourceAdditional result=success
     */
    public function getFnbs(Request $request)
    {
        try {
            return $this->fnbRepository->getFnbs($request);
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
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Hospitality\TopRecommendedFnbResource
     * @apiResourceModel App\Models\General\Body\Hospitality\TopRecommendedFnb
     * @apiResourceAdditional result=success
     */
    public function getRecommendedFnbs(Request $request)
    {
        try {
            return $this->fnbRepository->getRecommendedFnbs($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
