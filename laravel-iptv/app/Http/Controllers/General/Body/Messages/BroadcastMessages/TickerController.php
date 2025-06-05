<?php

namespace App\Http\Controllers\General\Body\Messages\BroadcastMessages;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Messages\BroadcastMessages\Tickers\TickerStoreRequest;
use App\Interfaces\General\Body\Messages\BroadcastMessages\ITickerRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TickerController extends Controller
{
    private $tickerRepository;

    public function __construct(ITickerRepository $tickerRepository)
    {
        $this->tickerRepository = $tickerRepository;
        $this->middleware('permission:tickers.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/messages/broadcastMessages/tickers/index';    

        $breadCrumb = ['Messages', 'Broadcast Messaging - Ticker'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tickerRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(TickerStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tickerRepository->store($request);
    
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
                $data = $this->tickerRepository->delete($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function resend(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->tickerRepository->resend($request);
    
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
