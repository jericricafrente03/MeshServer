<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\STB\MeshTransactionRequest;
use App\Interfaces\API\STB\IGuestBillingRepository;
use Illuminate\Http\Request;

/**
* @group API GuestBillingController
*
* Guest Billing.
*/
class GuestBillingController extends Controller
{
    private $guestBillingRepository;

    public function __construct(IGuestBillingRepository $guestBillingRepository)
    {
        $this->guestBillingRepository = $guestBillingRepository;
    }
    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @bodyParam data array required An array of transaction data. Example: [
     * {"room_id": 101, "type": "fnb", "item_id": 1, "quantity": 2, "unit_price": 29.99, "room_assignment_id": 1},
     * {"room_id": 101, "type": "item_request", "item_id": 2, "quantity": 1, "unit_price": 550, "room_assignment_id": 1}
     * ]
     * @apiResourceCollection status=201 App\Http\Resources\API\STB\GuestBillingResource
     * @apiResourceModel App\Models\General\Body\Guests\GuestBilling
     * @apiResourceAdditional result=success message="Guest Billing created successfully"
     */
    public function meshTransaction(MeshTransactionRequest $request)
    {   
        try {
            return $this->guestBillingRepository->meshTransaction($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
