<?php

namespace App\Http\Controllers\General\Body\Guests;

use App\Http\Controllers\Controller;
use App\Interfaces\General\Body\Guests\IGuestBillingRepository;
use App\Models\GeneralStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
* @group Guests > Guest Billing
*
* WEB pages for managing guest billing.
*/
class GuestBillingController extends Controller
{
    private $guestBillingRepository;

    public function __construct(IGuestBillingRepository $guestBillingRepository)
    {
        $this->guestBillingRepository = $guestBillingRepository;
        $this->middleware('permission:guest_billings.index');
    }

    /**
    * Display the guest billing index page.
    * 
    * @response 200
    * @return \Illuminate\View\View
    */
    public function index()
    {
        $user = Auth::user();

        $viewPath = '/general/body/guests/guestBilling/index';   
        $statusDropdown = GeneralStatus::where('category', 'guest_billing_status')->get(); 

        $breadCrumb = ['Guests', 'Guest Billing'];
        return view($viewPath, compact('user', 'breadCrumb', 'statusDropdown'));
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
                $data = $this->guestBillingRepository->getData($request);
    
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
    * Update status of an existing guest billing.
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
                $data = $this->guestBillingRepository->update($request);
    
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
    * Update payment of an existing guest billing.
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
    public function updatePayment(Request $request)
    {
        if($request->ajax()){
            try {
                $data = $this->guestBillingRepository->updatePayment($request);
    
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
