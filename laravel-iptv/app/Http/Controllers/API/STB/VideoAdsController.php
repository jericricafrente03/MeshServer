<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\STB\GetVideoAdsRequest;
use App\Interfaces\API\STB\IVideoAdsRepository;
use Illuminate\Http\Request;

/**
* @group API VideoAdsController
*
* Video Ads.
*/
class VideoAdsController extends Controller
{
    private $videoAdsRepository;

    public function __construct(IVideoAdsRepository $videoAdsRepository)
    {
        $this->videoAdsRepository = $videoAdsRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\VideoAdsResource
     * @apiResourceModel App\Models\General\Body\VideoAds\VideoAd
     * @apiResourceAdditional result=success
     */
    public function getVideoAds(GetVideoAdsRequest $request)
    {
        try {
            return $this->videoAdsRepository->getVideoAds($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
