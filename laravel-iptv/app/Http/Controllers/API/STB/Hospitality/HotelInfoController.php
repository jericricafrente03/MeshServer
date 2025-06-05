<?php

namespace App\Http\Controllers\API\STB\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Hospitality\IHotelInfoRepository;
use Illuminate\Http\Request;


/**
* @group API HotelInfoController
*
* Hotel Info.
*/
class HotelInfoController extends Controller
{
    private $hotelInfoRepository;

    public function __construct(IHotelInfoRepository $hotelInfoRepository)
    {
        $this->hotelInfoRepository = $hotelInfoRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Hospitality\HotelInfoResource
     * @apiResourceModel App\Models\General\Body\Hospitality\HotelInfo
     * @apiResourceAdditional result=success
     */
    public function getHotelInfos(Request $request)
    {
        try {
            return $this->hotelInfoRepository->getHotelInfos($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
