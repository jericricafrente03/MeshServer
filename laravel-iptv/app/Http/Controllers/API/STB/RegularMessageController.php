<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\STB\DeleteMessageRequest;
use App\Http\Requests\API\STB\GetMessageRequest;
use App\Http\Requests\API\STB\ReadMessageRequest;
use App\Interfaces\API\STB\IRegularMessageRepository;
use Illuminate\Support\Facades\Request;

/**
* @group API RegularMessageController
*
* RegularMessage.
*/
class RegularMessageController extends Controller
{
    private $regularMessageRepository;

    public function __construct(IRegularMessageRepository $regularMessageRepository)
    {
        $this->regularMessageRepository = $regularMessageRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\RegularMessageResource
     * @apiResourceModel App\Models\General\Body\Messages\MessageRecipient
     * @apiResourceAdditional result=success
     */
    public function getMessage(GetMessageRequest $request)
    {
        try {
            return $this->regularMessageRepository->getMessage($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @response 200 {
     *   "result": "success",
     *   "message": "Record has been updated."
     * }
     */
    public function readMessage(ReadMessageRequest $request)
    {
        try {
            return $this->regularMessageRepository->readMessage($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @response 200 {
     *   "result": "success",
     *   "message": "Record has been deleted."
     * }
     */
    function deleteMessage(DeleteMessageRequest $request)
    {
        try {
            return $this->regularMessageRepository->deleteMessage($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
