<?php

namespace App\Http\Controllers\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Guests\Rooms\RoomDeleteRequest;
use App\Http\Requests\General\Body\Guests\Rooms\RoomStoreRequest;
use App\Http\Requests\General\Body\Guests\Rooms\RoomUpdateRequest;
use App\Interfaces\General\Body\Guests\IRoomRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
* @group Guests > Rooms
*
* 
* WEB page for managing rooms.
*/
class RoomController extends Controller
{
    private $roomRepository;

    public function __construct(IRoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
        $this->middleware('permission:room.index');
    }

    /**
    * Display the rooms index page.
    * 
    * @response 200
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/guests/rooms/index';    

        $breadCrumb = ['Guests', 'Rooms'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    /**
    * Get rooms data for DT.
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
                $data = $this->roomRepository->get_data($request);
    
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
    * Store a new room.
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
    public function store(RoomStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomRepository->store($request);
    
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
    * Update an existing room.
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
    public function update(RoomUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomRepository->update($request);
    
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
    * Delete a room.
    * 
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
    public function destroy(RoomDeleteRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomRepository->delete($request);
    
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
