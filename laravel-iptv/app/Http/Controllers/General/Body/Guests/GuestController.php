<?php

namespace App\Http\Controllers\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Guests\Guests\GuestStoreRequest;
use App\Interfaces\General\Body\Guests\IGuestRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
* @group Guests > Guests
*
* WEB pages for managing guests.
*/
class GuestController extends Controller
{
    private $guestRepository;

    public function __construct(IGuestRepository $guestRepository)
    {
        $this->guestRepository = $guestRepository;
        $this->middleware('permission:guests.index');
    }

    /**
    * Display the guests index page.
    * 
    * @response 200
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/guests/guests/index';    

        $breadCrumb = ['Guests', 'Guests'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    /**
    * Get guests data for DT.
    * 
    * @response 200 {
    *   "data": [
    *     {
    *       ...
    *     }
    *   ]
    * }
    * @response 500 {
    *   "error": "Some error message",
    *   "message": "Something went wrong in [current class].[current function]."
    * }
    */
    public function get_data(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->guestRepository->getData($request);
    
                return response()->json($data, 200);
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    /**
    * Store a new guest.
    *
    * @response 201 {
    *   "status":"success",
    *   "message":"Record has been saved."
    * }
    * @response 404 {
    *   "status":"warning",
    *   "message":"Record is not saved."
    * }
    * @response 500 {
    *   "error": "Some error message",
    *   "message": "Something went wrong in [current class].[current function]."
    * }
    */
    public function store(GuestStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->guestRepository->store($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    /**
    * Update an existing guest.
    * @response 200 {
    *   "status":"success",
    *   "message":"Record has been updated."
    * }
    * @response 404 {
    *   "status":"warning",
    *   "message":"Record is not updated."   
    * }
    * @response 500 {
    *   "error": "Some error message",
    *   "message": "Something went wrong in [current class].[current function]."
    * }
    */
    public function update(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->guestRepository->update($request);
    
                return $data;
    
            } catch (\Exception $e) { 
                return response()->json([
                    'error' => $e->getMessage(),
                    'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
                ]);
            }
        }
    }

    /**
    * Delete a guest.
    * 
    * @bodyParam id integer required The ID of the guest. Example: 1
    * @response 200 {
    *   "status":"success",
    *   "message":"Record has been deleted."
    * }
    * @response 404 {
    *   "status":"warning",
    *   "message":"Record is not deleted."
    * }
    * @response 500 {
    *   "error": "Some error message",
    *   "message": "Something went wrong in [current class].[current function]."
    * }
    */
    public function destroy(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->guestRepository->delete($request);
    
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
