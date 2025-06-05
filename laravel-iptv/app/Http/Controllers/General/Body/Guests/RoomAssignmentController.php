<?php

namespace App\Http\Controllers\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Guests\RoomAssignments\RoomAssignmentStoreRequest;
use App\Interfaces\General\Body\Guests\IRoomAssignmentRepository;
use App\Models\GeneralStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
* @group Guests > Room assignments
*
* 
* WEB page for managing room assignments.
*/
class RoomAssignmentController extends Controller
{
    private $roomAssignmentRepository;

    public function __construct(IRoomAssignmentRepository $roomAssignmentRepository)
    {
        $this->roomAssignmentRepository = $roomAssignmentRepository;
        $this->middleware('permission:room.index');
    }

    /**
    * Display the room assignments index page.
    * 
    * @response 200
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/guests/roomAssignments/index'; 
        $statusDropdown = GeneralStatus::where('category', 'guest_billing_status')->get();    

        $breadCrumb = ['Guests', 'Room Assignments'];
        return view($viewPath, compact('user', 'breadCrumb', 'statusDropdown'));
    }

    /**
    * Get room assignments data for DT.
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
                $data = $this->roomAssignmentRepository->get_data($request);
    
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
    * Get room assignments billing data for modal DT.
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
    public function getBillingData(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomAssignmentRepository->getBillingData($request);
    
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
    * Store a new room assignment.
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
    public function store(RoomAssignmentStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomAssignmentRepository->store($request);
    
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
    *  Checkout an existing room assignment.
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
    public function checkout(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomAssignmentRepository->checkout($request);
    
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
    * Delete a room assignment.
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
    public function destroy(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomAssignmentRepository->delete($request);
    
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
    * Change order of an existing room category.
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
    public function changeRoom(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomAssignmentRepository->changeRoom($request);
    
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
