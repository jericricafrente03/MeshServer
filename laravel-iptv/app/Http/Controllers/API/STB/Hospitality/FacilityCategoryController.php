<?php

namespace App\Http\Controllers\API\STB\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Hospitality\IFacilityCategoryRepository;
use Illuminate\Http\Request;

/**
* @group API FacilityCategoryController
*
* Facility Category.
*/
class FacilityCategoryController extends Controller
{
    private $facilityCategoryRepository;

    public function __construct(IFacilityCategoryRepository $facilityCategoryRepository)
    {
        $this->facilityCategoryRepository = $facilityCategoryRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Hospitality\FacilityCategoryResource
     * @apiResourceModel App\Models\General\Body\Hospitality\FacilityCategory
     * @apiResourceAdditional result=success
     */
    public function getFacilityCategories(Request $request)
    {
        try {
            return $this->facilityCategoryRepository->getFacilityCategories($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
