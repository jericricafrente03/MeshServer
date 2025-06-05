<?php

namespace App\Http\Controllers\General\Body\Tv;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Tv\TvChannels\TvChannelChangeOrderRequest;
use App\Http\Requests\General\Body\Tv\TvChannels\TvChannelStoreRequest;
use App\Http\Requests\General\Body\Tv\TvChannels\TvChannelUpdateRequest;
use App\Interfaces\General\Body\Tv\ITvChannelRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TvChannelController extends Controller
{
    private $tvChannelRepository;

    public function __construct(ITvChannelRepository $tvChannelRepository)
    {
        $this->tvChannelRepository = $tvChannelRepository;
        $this->middleware('permission:tv_channels.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/tv/tvChannels/index';    

        $breadCrumb = ['TV', 'TV Channels'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(TvChannelStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelRepository->store($request);
    
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
                $data = $this->tvChannelRepository->toggleEnable($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function changeOrder(TvChannelChangeOrderRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelRepository->changeOrder($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(TvChannelUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tvChannelRepository->update($request);
    
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
                $data = $this->tvChannelRepository->delete($request);
    
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
