<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\ITvChannelRepository;
use Illuminate\Http\Request;

/**
* @group API TvChannelController
*
* Tv Channel.
*/
class TvChannelController extends Controller
{
    private $tvChannelRepository;

    public function __construct(ITvChannelRepository $tvChannelRepository)
    {
        $this->tvChannelRepository = $tvChannelRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\TvChannelResource
     * @apiResourceModel App\Models\General\Body\Tv\TvChannel
     * @apiResourceAdditional result=success
     */
    public function getTvChannels(Request $request)
    {
        try {
            return $this->tvChannelRepository->getTvChannels($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
