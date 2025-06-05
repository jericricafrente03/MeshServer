<?php

use App\Http\Controllers\API\STB\AielloController;
use App\Http\Controllers\API\STB\AnalyticController;
use App\Http\Controllers\API\STB\AuthController;
use App\Http\Controllers\API\STB\BroadcastMessagingController;
use App\Http\Controllers\API\STB\DeviceController;
use App\Http\Controllers\API\STB\GuestBillingController;
use App\Http\Controllers\API\STB\Guests\RoomAssignmentController;
use App\Http\Controllers\API\STB\Guests\RoomCategoryController;
use App\Http\Controllers\API\STB\Guests\RoomController;
use App\Http\Controllers\API\STB\Hospitality\FacilityCategoryController;
use App\Http\Controllers\API\STB\Hospitality\FacilityController;
use App\Http\Controllers\API\STB\Hospitality\FnbCategoryController;
use App\Http\Controllers\API\STB\Hospitality\FnbController;
use App\Http\Controllers\API\STB\Hospitality\HotelInfoController;
use App\Http\Controllers\API\STB\Hospitality\ItemRequestController;
use App\Http\Controllers\API\STB\Hospitality\ServiceRequestController;
use App\Http\Controllers\API\STB\RegularMessageController;
use App\Http\Controllers\API\STB\SystemConfigController;
use App\Http\Controllers\API\STB\ThemeManagerController;
use App\Http\Controllers\API\STB\TvChannelCategoryController;
use App\Http\Controllers\API\STB\TvChannelController;
use App\Http\Controllers\API\STB\VideoAdsController;
use App\Http\Controllers\API\STB\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('stb_register', [AuthController::class, 'stb_register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('check_token', [AuthController::class, 'checkToken']);
Route::get('curl_weather_api_for_hourly_forecast', [WeatherController::class, 'curlWeatherApiForHourlyForecast']);
Route::get('curl_weather_api_for_daily_forecast', [WeatherController::class, 'curlWeatherApiForDailyForecast']);
Route::get('enter_netflix', [DeviceController::class, 'enterNetflix']);
Route::get('clear_cache', [DeviceController::class, 'clearCache']);
Route::middleware('vpn.ip')->prefix('aiello')->group(function () {
    Route::post('/toggleTvPower', [AielloController::class, 'toggleTvPower']);
    Route::post('/volumeChange', [AielloController::class, 'volumeChange']);
    Route::get('/getTvChannels', [AielloController::class, 'getTvChannels']);
    Route::post('/openApplication', [AielloController::class, 'openApplication']);
    Route::post('/changeTvChannel', [AielloController::class, 'changeTvChannel']);
});
Route::middleware('auth:sanctum')->group(function(){
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('stb_time', [AuthController::class, 'stb_time']);
    Route::post('mesh_transaction', [GuestBillingController::class, 'meshTransaction']);
    Route::get('get_message', [RegularMessageController::class, 'getMessage']);
    Route::put('read_message', [RegularMessageController::class, 'readMessage']);
    Route::put('delete_message', [RegularMessageController::class, 'deleteMessage']);
    Route::get('get_broadcast_messaging', [BroadcastMessagingController::class, 'getBroadcastMessaging']);
    Route::get('get_video_ads', [VideoAdsController::class, 'getVideoAds']);
    Route::get('get_theme', [ThemeManagerController::class, 'getTheme']);
    Route::get('get_tv_channels', [TvChannelController::class, 'getTvChannels']);
    Route::get('get_tv_channel_categories', [TvChannelCategoryController::class, 'getTvChannelCategories']);
    Route::get('get_system_config', [SystemConfigController::class, 'getSystemCOnfig']);
    Route::get('get_facilities', [FacilityController::class, 'getFacilities']);
    Route::get('get_facility_categories', [FacilityCategoryController::class, 'getFacilityCategories']);
    Route::get('get_fnbs', [FnbController::class, 'getFnbs']);
    Route::get('get_fnb_categories', [FnbCategoryController::class, 'getFnbCategories']);
    Route::get('get_fnb_categories', [FnbCategoryController::class, 'getFnbCategories']);
    Route::get('get_customer', [RoomAssignmentController::class, 'getCustomer']);
    Route::get('get_hotel_infos', [HotelInfoController::class, 'getHotelInfos']);
    Route::get('get_item_requests', [ItemRequestController::class, 'getItemRequests']);
    Route::get('get_service_requests', [ServiceRequestController::class, 'getServiceRequests']);
    Route::get('get_rooms', [RoomController::class, 'getRooms']);
    Route::get('get_room_categories', [RoomCategoryController::class, 'getRoomCategories']);
    Route::get('get_weather_daily_forecast', [WeatherController::class, 'getWeatherDailyForecast']);
    Route::get('get_weather_hourly_forecast', [WeatherController::class, 'getWeatherHourlyForecast']);
    Route::get('get_analytic_type', [AnalyticController::class, 'getAnalyticType']);
    Route::post('analytics', [AnalyticController::class, 'postAnalytics']);
    Route::get('get_language', [DeviceController::class, 'getLanguage']);
    Route::get('get_wifi_qr_code', [DeviceController::class, 'getWifiQrCode']);
    Route::get('get_recommended_fnbs', [FnbController::class, 'getRecommendedFnbs']);
});

   