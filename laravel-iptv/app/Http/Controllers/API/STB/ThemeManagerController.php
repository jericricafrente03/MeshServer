<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\STB\GetThemeRequest;
use App\Interfaces\API\STB\IThemeManagerRepository;
use Illuminate\Http\Request;

/**
* @group API ThemeManagerController
*
* ThemeManager.
*/
class ThemeManagerController extends Controller
{
    private $themeManagerRepository;

    public function __construct(IThemeManagerRepository $themeManagerRepository)
    {
        $this->themeManagerRepository = $themeManagerRepository;
    }

    /**
     * @authenticated
     * @header Authorization Bearer *TOKEN*
     * @apiResource status=200 App\Http\Resources\API\STB\ThemeManagerResource
     * @apiResourceModel App\Models\SystemSettings\ThemeManager\Theme
     * @apiResourceAdditional result=success
     */
    public function getTheme(GetThemeRequest $request)
    {
        try {
            return $this->themeManagerRepository->getTheme($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
