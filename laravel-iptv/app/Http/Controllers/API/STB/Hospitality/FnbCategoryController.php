<?php

namespace App\Http\Controllers\API\STB\Hospitality;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\Hospitality\IFnbCategoryRepository;
use Illuminate\Http\Request;

/**
* @group API FnbCategoryController
*
* Fnb Category.
*/
class FnbCategoryController extends Controller
{
    private $fnbCategoryRepository;

    public function __construct(IFnbCategoryRepository $fnbCategoryRepository)
    {
        $this->fnbCategoryRepository = $fnbCategoryRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\Hospitality\FnbCategoryResource
     * @apiResourceModel App\Models\General\Body\Hospitality\FnbCategory
     * @apiResourceAdditional result=success
     */
    public function getFnbCategories(Request $request)
    {
        try {
            return $this->fnbCategoryRepository->getFnbCategories($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
