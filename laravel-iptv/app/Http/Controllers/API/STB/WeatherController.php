<?php

namespace App\Http\Controllers\API\STB;

use App\Http\Controllers\Controller;
use App\Interfaces\API\STB\IWeatherRepository;
use Illuminate\Http\Request;

/**
* @group API WeatherController
*
* Weather.
*/
class WeatherController extends Controller
{
    private $weatherRepository;

    public function __construct(IWeatherRepository $weatherRepository)
    {
        $this->weatherRepository = $weatherRepository;
    }

    /**
     * @authenticated
     * @apiResourceAdditional result=success
     * @response 200 {
     *   "result": "success",
     *   "message": "Weather data updated successfully."
     * }
     */
    public function curlWeatherApiForHourlyForecast(Request $request)
    {
        try {
            return $this->weatherRepository->curlWeatherApiForHourlyForecast($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }

    /**
     * @authenticated
     * @apiResourceAdditional result=success
     * @response 200 {
     *   "result": "success",
     *   "message": "Weather data updated successfully."
     * }
     */
    public function curlWeatherApiForDailyForecast(Request $request)
    {
        try {
            return $this->weatherRepository->curlWeatherApiForDailyForecast($request);
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
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\WeatherDailyForecastResource
     * @apiResourceModel App\Models\WeatherDailyForecast
     * @apiResourceAdditional result=success
     */
    public function getWeatherDailyForecast(Request $request)
    {
        try {
            return $this->weatherRepository->getWeatherDailyForecast($request);
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
     * @apiResourceCollection status=200 App\Http\Resources\API\STB\WeatherHourlyForecastResource
     * @apiResourceModel App\Models\WeatherHourlyForecast
     * @apiResourceAdditional result=success
     */
    public function getWeatherHourlyForecast(Request $request)
    {
        try {
            return $this->weatherRepository->getWeatherHourlyForecast($request);
        } catch (\Exception $e) { 
            return response()->json([
                'error' => $e->getMessage(),
                'message' => 'Something went wrong in ' . __CLASS__ . '.' . __FUNCTION__.' .'
            ]);
        }
    }
}
