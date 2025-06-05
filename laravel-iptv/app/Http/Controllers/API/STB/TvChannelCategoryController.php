<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\ITvChannelCategoryRepository;
use Illuminate\Http\Request;

/**
* @group API TvChannelCategoryController
*
* Tv Channel Category.
*/
class TvChannelCategoryController extends Controller
{
    private $tvChannelCategoryRepository;

    public function __construct(ITvChannelCategoryRepository $tvChannelCategoryRepository)
    {
        $this->tvChannelCategoryRepository = $tvChannelCategoryRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\TvChannelCategoryResource
     * @apiResourceModel App\Models\General\Body\Tv\TvChannelCategory
     * @apiResourceAdditional result=success
     */
    public function getTvChannelCategories(Request $request)
    {
        try {
            return $this->tvChannelCategoryRepository->getTvChannelCategories($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
