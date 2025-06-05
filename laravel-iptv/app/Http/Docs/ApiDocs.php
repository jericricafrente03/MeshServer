<?php 

namespace App\Http\Docs;

/**
* @group API
*
* IPTV STB's API.
*/
class ApiDocs
{
    /**
     * Guest Billing API.
     *
     * This endpoint handles the creation of guest billing transactions.
     *
     * @response 200 {
     *   "result": "success",
     *   "message": "All transactions saved successfully.",
     *   "data": [
     *     {
     *       "id": 14,
     *       "transaction_datetime": "2024-10-23 16:58:31",
     *       "room_number": "105",
     *       "room_id": 6,
     *       "category": "fnb",
     *       "item_name": "Sample",
     *       "item_id": 1,
     *       "quantity": 1,
     *       "unit_price": "100.00",
     *       "refno": "1729673911415",
     *       "status": "New Order",
     *       "status_id": 11,
     *       "user": "STB",
     *       "user_id": 1,
     *       "reserve_no": null,
     *       "notes": null,
     *       "is_paid": 0,
     *       "room_assignment_id": 9,
     *       "guest_name": "Engr Steward Apostol",
     *       "created_at": "2024-10-23T08:58:31.000000Z",
     *       "updated_at": "2024-10-23T08:58:31.000000Z"
     *     },
     *     // additional entries...
     *   ]
     * }
     *
     * @response 422 {
     *   "result": "Failed",
     *   "message": "This field is invalid.",
     *   "errors": {
     *     "data.1.room_id": [
     *       "The data.1.room_id field is required."
     *     ]
     *   }
     * }
     *
     * @response 404 {
     *   "result": "Failed",
     *   "message": "Record is not updated."
     * }
     *
     * @response 500 {
     *   "error": "Some error message",
     *   "message": "Something went wrong in [current class].[current function]."
     * }
     */
    public static function meshTransactionDocs()
    {
        // This method is not doing anything but serves to house the documentation.
    }
}
