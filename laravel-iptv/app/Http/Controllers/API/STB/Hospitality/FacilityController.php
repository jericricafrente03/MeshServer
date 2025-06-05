<?php

namespace App\Http\Controllers\API\STB\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Hospitality\IFacilityRepository;
use Illuminate\Http\Request;

/**
* @group API FacilityController
*
* Facility.
*/
class FacilityController extends Controller
{
    private $facilityRepository;

    public function __construct(IFacilityRepository $facilityRepository)
    {
        $this->facilityRepository = $facilityRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Hospitality\FacilityResource
     * @apiResourceModel App\Models\General\Body\Hospitality\Facility
     * @apiResourceAdditional result=success
     */
    public function getFacilities(Request $request)
    {
        try {
            return $this->facilityRepository->getFacilities($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
