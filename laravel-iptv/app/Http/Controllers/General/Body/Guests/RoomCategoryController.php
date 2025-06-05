<?php

namespace App\Http\Controllers\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Http\Requests\General\Body\Guests\RoomCategories\RoomCategoryChangeOrderRequest;
use App\Http\Requests\General\Body\Guests\RoomCategories\RoomCategoryDeleteRequest;
use App\Http\Requests\General\Body\Guests\RoomCategories\RoomCategoryStoreRequest;
use App\Http\Requests\General\Body\Guests\RoomCategories\RoomCategoryUpdateRequest;
use App\Interfaces\General\Body\Guests\IRoomCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
* @group Guests > Room Categories
*
* 
* WEB page for managing room categories.
*/
class RoomCategoryController extends Controller
{   
    private $roomCategoryRepository;

    public function __construct(IRoomCategoryRepository $roomCategoryRepository)
    {
        $this->roomCategoryRepository = $roomCategoryRepository;
        $this->middleware('permission:room_categories.index');
    }

    /**
    * Display the room categories index page.
    * 
    * @response 200
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/guests/roomCategories/index';    

        $breadCrumb = ['Guests', 'Room Categories'];
        return view($viewPath, compact('user', 'breadCrumb'));
    }

    /**
    * Get room categories data for DT.
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
                $data = $this->roomCategoryRepository->get_data($request);
    
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
    * Store a new room category.
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
    public function store(RoomCategoryStoreRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomCategoryRepository->store($request);
    
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
    * Update an existing room ctegory.
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
    public function update(RoomCategoryUpdateRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomCategoryRepository->update($request);
    
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
    * Delete a room category.
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
    public function destroy(RoomCategoryDeleteRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomCategoryRepository->delete($request);
    
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
    public function changeOrder(RoomCategoryChangeOrderRequest $request)
    {
        if($request->ajax()){
            try {
                $data = $this->roomCategoryRepository->changeOrder($request);
    
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
