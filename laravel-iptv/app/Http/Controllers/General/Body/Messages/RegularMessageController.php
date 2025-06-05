<?php

namespace App\Http\Controllers\General\Body\Messages;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Messages\RegularMessages\RegularMessageStoreRequest;
use App\Http\Requests\General\Body\Messages\RegularMessages\RegularMessageUpdateRequest;
use App\Interfaces\General\Body\Messages\IRegularMessageRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RegularMessageController extends Controller
{
    private $regularMessageRepository;

    public function __construct(IRegularMessageRepository $regularMessageRepository)
    {
        $this->regularMessageRepository = $regularMessageRepository;
        $this->middleware('permission:regular_messages.index');
    }
    
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/messages/regularMessages/index';    

        $breadCrumb = ['Messages', 'Regular Messages'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    public function getData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->regularMessageRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function store(RegularMessageStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->regularMessageRepository->store($request);
    
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
                $data = $this->regularMessageRepository->delete($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    public function update(RegularMessageUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->regularMessageRepository->update($request);
    
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
