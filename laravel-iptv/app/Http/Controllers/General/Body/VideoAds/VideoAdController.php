<?php

namespace App\Http\Controllers\General\Body\VideoAds;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\VideoAds\VideoAdChangeOrderRequest;
use App\Http\Requests\General\Body\VideoAds\VideoAdStoreRequest;
use App\Http\Requests\General\Body\VideoAds\VideoAdUpdateRequest;
use App\Interfaces\General\Body\VideoAds\IVideoAdsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoAdController extends Controller
{
    private $videoAdRepository;

    public function __construct(IVideoAdsRepository $videoAdRepository)
    {
        $this->videoAdRepository = $videoAdRepository;
        $this->middleware('permission:video_ads.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/videoAds/index';    

        $breadCrumb = ['Video Ads', 'Video Ads'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->videoAdRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(VideoAdStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->videoAdRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function toggleEnable(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->videoAdRepository->toggleEnable($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(VideoAdUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->videoAdRepository->update($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function destroy(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->videoAdRepository->delete($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function changeOrder(VideoAdChangeOrderRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->videoAdRepository->changeOrder($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }
}
