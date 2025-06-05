<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

//App Controllers
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\General\Body\Analytics\AnalyticController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RbacController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\General\SearchController;

//System Settings
use App\Http\Controllers\SystemSettings\SystemConfigController;
use App\Http\Controllers\SystemSettings\DeviceAdb\Files\ScreenCaptureController;
use App\Http\Controllers\SystemSettings\DeviceAdb\ManagerController;
use App\Http\Controllers\SystemSettings\ThemeManager\DefaultAppController;
use App\Http\Controllers\SystemSettings\ThemeManager\ThemeController;
use App\Http\Controllers\SystemSettings\ThemeManager\ZoneController;
// use Laravel\Socialite\Facades\Socialite;
// use App\Http\Controllers\SearchController;

//General
use App\Http\Controllers\General\Body\Devices\DeviceCategoryController;
use App\Http\Controllers\General\Body\Devices\DeviceController;
use App\Http\Controllers\General\Body\Devices\DeviceMonitorController;
use App\Http\Controllers\General\Body\Guests\GuestBillingController;
use App\Http\Controllers\General\Body\Guests\GuestController;
use App\Http\Controllers\General\Body\Guests\RoomAssignmentController;
use App\Http\Controllers\General\Body\Guests\RoomCategoryController;
use App\Http\Controllers\General\Body\Guests\RoomController;
use App\Http\Controllers\General\Body\Hospitality\FacilityCategoryController;
use App\Http\Controllers\General\Body\Hospitality\FacilityController;
use App\Http\Controllers\General\Body\Hospitality\FnbCategoryController;
use App\Http\Controllers\General\Body\Hospitality\FnbController;
use App\Http\Controllers\General\Body\Hospitality\HotelInfoController;
use App\Http\Controllers\General\Body\Hospitality\ItemRequestController;
use App\Http\Controllers\General\Body\Hospitality\ServiceRequestController;
use App\Http\Controllers\General\Body\Messages\BroadcastMessages\AdvertisementController;
use App\Http\Controllers\General\Body\Messages\BroadcastMessages\EmergencyController;
use App\Http\Controllers\General\Body\Messages\BroadcastMessages\TickerController;
use App\Http\Controllers\General\Body\Messages\RegularMessageController;
use App\Http\Controllers\General\Body\Notifications\NotificationController;
use App\Http\Controllers\General\Body\Tv\TvChannelCategoryController;
use App\Http\Controllers\General\Body\Tv\TvChannelController;
use App\Http\Controllers\General\Body\VideoAds\VideoAdController;
use App\Http\Controllers\SystemSettings\DeviceAdb\ApkController;
use App\Http\Controllers\SystemSettings\DeviceAdb\Files\ScreenRecordController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
// public route for getting tv images for digital signage
Route::get('tv-images/{filename}', function ($filename) {
    $filePath = storage_path('app/public/upload/tv_channels/' . $filename);

    if (!File::exists($filePath)) {
        Log::warning('TV Image NOT FOUND: ' . $filename . ' | IP: ' . request()->ip());
        abort(404);
    }
    Log::info('TV Image served: ' . $filename . ' | IP: ' . request()->ip());
    return Response::file($filePath);
})->where('filename', '.*');

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {

    /*-- new --*/

    Route::get('/admin/logout', [UserController::class, 'destroy'])->name('admin.logout');
    Route::get('/lost', [AuthenticatedSessionController::class, 'lost']);

    // DashboardController
    Route::middleware(['permission:dashboard.index'])->get('/admin', [DashboardController::class, 'index']);
    Route::middleware(['permission:dashboard.index'])->get('/dashboard', [DashboardController::class, 'index']);

    // ProfileController
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    // Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // For System Settings - CRUD for USERS
    Route::get('/admin/user', [UserSettingController::class, 'index']);
    Route::post('/admin/user/data', [UserSettingController::class, 'get_data']);
    Route::post('/admin/user/get_roles', [UserSettingController::class, 'get_roles']);
    Route::middleware(['permission:user.create'])->post('/admin/user/add_user', [UserSettingController::class, 'add_user']);
    Route::middleware(['permission:user.update'])->post('/admin/user/update_user', [UserSettingController::class, 'update_user']);
    Route::middleware(['permission:user.delete'])->post('/admin/user/delete_user', [UserSettingController::class, 'delete_user']);
    Route::get('/admin/user/security', [UserController::class, 'security_profile']);
    Route::put('/admin/user/update_password', [UserController::class, 'update_password']);
    Route::get('/admin/profile/profile_view', [UserController::class, 'profile_view']);
    Route::middleware(['permission:log.index'])->get('/admin/log', [UserSettingController::class, 'log_index']);
    Route::middleware(['permission:log.index'])->post('/admin/log/data', [UserSettingController::class, 'get_logData']);

    //RoleController
    Route::get('/admin/role', [RoleController::class, 'index']);
    Route::post('/admin/role/data', [RoleController::class, 'get_data']);
    Route::post('/admin/role/delete_role', [RoleController::class, 'delete_role']);
    Route::post('/admin/role/add_role', [RoleController::class, 'add_role']);
    Route::post('/admin/role/update_role', [RoleController::class, 'update_role']);

    // UserController
    Route::get('/admin/user/generatekey', [UserController::class, 'generatekey']);
    
    //UserController
    Route::get('/admin/profile/edit_profile_view', [UserController::class, 'editProfile_view']);
    Route::put('/admin/profile/update_profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::post('/admin/profile/update_avatar', [UserController::class, 'update_avatar']);

    //PermissionController
    Route::post('/admin/permission/data', [PermissionController::class, 'get_data']);

    //RbacController
    Route::get('/admin/rbac', [RbacController::class, 'index']);
    Route::post('/admin/rbac/data', [RbacController::class, 'get_data']);
    Route::post('/admin/rbac/get_roles', [RbacController::class, 'get_roles']);
    Route::middleware(['permission:rbac.index'])->post('/admin/rbac/update_permission', [RbacController::class, 'update_permission']);

    //SystemConfigController
    Route::get('/admin/system_config', [SystemConfigController::class, 'index']);
    Route::middleware(['permission:system_config.update'])->put('/system_config/update', [SystemConfigController::class, 'update']);

    //DeviceController
    Route::prefix('devices')->group(function () {
        Route::get('/', [DeviceController::class, 'index']);
        Route::get('/data', [DeviceController::class, 'get_data']);
        Route::middleware(['permission:devices.detect_devices'])->get('/detect-devices', [DeviceController::class, 'detectDevices']);
        Route::middleware(['permission:devices.update'])->put('/edit', [DeviceController::class, 'update']);
        Route::middleware(['permission:devices.adb'])->get('/adb-reboot', [DeviceController::class, 'adbReboot']);
        Route::middleware(['permission:devices.adb'])->get('/adb-reset-data', [DeviceController::class, 'adbResetData']);
        Route::middleware(['permission:devices.delete'])->delete('/delete', [DeviceController::class, 'destroy']);
    });

    //DeviceCategoryController
    Route::prefix('device_group')->group(function () {
        Route::get('/', [DeviceCategoryController::class, 'index']);
        Route::get('/data', [DeviceCategoryController::class, 'get_data']);
        Route::middleware(['permission:device_group.create'])->post('/add', [DeviceCategoryController::class, 'store']);
        Route::middleware(['permission:device_group.update'])->put('/edit', [DeviceCategoryController::class, 'update']);
        Route::middleware(['permission:device_group.delete'])->delete('/delete', [DeviceCategoryController::class, 'destroy']);
        Route::middleware(['permission:device_group.change_order'])->put('/order', [DeviceCategoryController::class, 'changeOrder']);
    });

    //DeviceMonitorController
    Route::prefix('device_monitor')->group(function () {
        Route::get('/', [DeviceMonitorController::class, 'index']);
        Route::middleware(['permission:device_monitor.index'])->get('/get', [DeviceMonitorController::class, 'get_devices']);
        Route::middleware(['permission:device_monitor.index'])->get('/chart-data', [DeviceMonitorController::class, 'chartData']);
    });
    // Route::get('/system_config', [SystemConfigController::class, 'index']);
    // Route::middleware(['permission:system_config.update'])->put('/system_config/update', [SystemConfigController::class, 'update']);

    //RoomController
    Route::prefix('rooms')->group(function () {
        Route::get('/', [RoomController::class, 'index']);
        Route::get('/data', [RoomController::class, 'get_data']);
        Route::middleware(['permission:room.create'])->post('/add', [RoomController::class, 'store']);
        Route::middleware(['permission:room.edit'])->put('/edit', [RoomController::class, 'update']);
        Route::middleware(['permission:room.delete'])->delete('/delete', [RoomController::class, 'destroy']);
    });

    //RoomCategoryController
    Route::prefix('room-categories')->group(function () {
        Route::get('/', [RoomCategoryController::class, 'index']);
        Route::get('/data', [RoomCategoryController::class, 'get_data']);
        Route::middleware(['permission:room_categories.create'])->post('/add', [RoomCategoryController::class, 'store']);
        Route::middleware(['permission:room_categories.edit'])->put('/edit', [RoomCategoryController::class, 'update']);
        Route::middleware(['permission:room_categories.delete'])->delete('/delete', [RoomCategoryController::class, 'destroy']);
        Route::middleware(['permission:room_categories.change_order'])->put('/order', [RoomCategoryController::class, 'changeOrder']);
    });

    //GuestController
    Route::prefix('guests')->group(function () {
        Route::get('/', [GuestController::class, 'index']);
        Route::get('/data', [GuestController::class, 'get_data']);
        Route::middleware(['permission:guests.create'])->post('/add', [GuestController::class, 'store']);
        Route::middleware(['permission:guests.edit'])->put('/edit', [GuestController::class, 'update']);
        Route::middleware(['permission:guests.delete'])->delete('/delete', [GuestController::class, 'destroy']);
    });

    //GuestBillingController
    Route::prefix('guest-billings')->group(function () {
        Route::get('/', [GuestBillingController::class, 'index']);
        Route::get('/data', [GuestBillingController::class, 'get_data']);
        Route::middleware(['permission:guest_billings.edit'])->put('/edit', [GuestBillingController::class, 'update']);
        Route::middleware(['permission:guest_billings.payment'])->put('/edit-payment', [GuestBillingController::class, 'updatePayment']);
    });

    //RoomAssignmentController
    Route::prefix('room-assignments')->group(function () {
        Route::get('/', [RoomAssignmentController::class, 'index']);
        Route::get('/data', [RoomAssignmentController::class, 'get_data']);
        Route::get('/billing-data', [RoomAssignmentController::class, 'getBillingData']);
        Route::middleware(['permission:room_assignments.create'])->post('/add', [RoomAssignmentController::class, 'store']);
        Route::middleware(['permission:room_assignments.checkout'])->put('/check-out', [RoomAssignmentController::class, 'checkout']);
        Route::middleware(['permission:room_assignments.changeroom'])->put('/change-room', [RoomAssignmentController::class, 'changeRoom']);
        Route::middleware(['permission:room_assignments.delete'])->delete('/delete', [RoomAssignmentController::class, 'destroy']);
    });

    //FnbCategoryController
    Route::prefix('fnb-categories')->group(function () {
        Route::get('/', [FnbCategoryController::class, 'index']);
        Route::get('/data', [FnbCategoryController::class, 'getData']);
        Route::middleware(['permission:fnb_categories.create'])->post('/add', [FnbCategoryController::class, 'store']);
        Route::middleware(['permission:fnb_categories.edit'])->put('/edit', [FnbCategoryController::class, 'update']);
        Route::middleware(['permission:fnb_categories.delete'])->delete('/delete', [FnbCategoryController::class, 'destroy']);
        Route::middleware(['permission:fnb_categories.change_order'])->put('/order', [FnbCategoryController::class, 'changeOrder']);
    });

    //FnbController
    Route::prefix('fnb')->group(function () {
        Route::get('/', [FnbController::class, 'index']);
        Route::get('/data', [FnbController::class, 'getData']);
        Route::middleware(['permission:fnb.create'])->post('/add', [FnbController::class, 'store']);
        Route::middleware(['permission:fnb.edit'])->put('/edit', [FnbController::class, 'update']);
        Route::middleware(['permission:fnb.delete'])->delete('/delete', [FnbController::class, 'destroy']);
        Route::middleware(['permission:fnb.enable'])->get('/toggle-enable', [FnbController::class, 'toggleEnable']);
    });

    //ItemRequestController
    Route::prefix('item-requests')->group(function () {
        Route::get('/', [ItemRequestController::class, 'index']);
        Route::get('/data', [ItemRequestController::class, 'getData']);
        Route::middleware(['permission:item_requests.create'])->post('/add', [ItemRequestController::class, 'store']);
        Route::middleware(['permission:item_requests.edit'])->put('/edit', [ItemRequestController::class, 'update']);
        Route::middleware(['permission:item_requests.delete'])->delete('/delete', [ItemRequestController::class, 'destroy']);
        Route::middleware(['permission:item_requests.enable'])->get('/toggle-enable', [ItemRequestController::class, 'toggleEnable']);
    });

    //ServiceRequestController
    Route::prefix('service-requests')->group(function () {
        Route::get('/', [ServiceRequestController::class, 'index']);
        Route::get('/data', [ServiceRequestController::class, 'getData']);
        Route::middleware(['permission:service_requests.create'])->post('/add', [ServiceRequestController::class, 'store']);
        Route::middleware(['permission:service_requests.edit'])->put('/edit', [ServiceRequestController::class, 'update']);
        Route::middleware(['permission:service_requests.delete'])->delete('/delete', [ServiceRequestController::class, 'destroy']);
        Route::middleware(['permission:service_requests.enable'])->get('/toggle-enable', [ServiceRequestController::class, 'toggleEnable']);
    });

    //FacilityCategoryController
    Route::prefix('facility-categories')->group(function () {
        Route::get('/', [FacilityCategoryController::class, 'index']);
        Route::get('/data', [FacilityCategoryController::class, 'getData']);
        Route::middleware(['permission:facility_categories.create'])->post('/add', [FacilityCategoryController::class, 'store']);
        Route::middleware(['permission:facility_categories.edit'])->put('/edit', [FacilityCategoryController::class, 'update']);
        Route::middleware(['permission:facility_categories.delete'])->delete('/delete', [FacilityCategoryController::class, 'destroy']);
        Route::middleware(['permission:facility_categories.change_order'])->put('/order', [FacilityCategoryController::class, 'changeOrder']);
    });

    //FacilityController
    Route::prefix('facilities')->group(function () {
        Route::get('/', [FacilityController::class, 'index']);
        Route::get('/data', [FacilityController::class, 'getData']);
        Route::middleware(['permission:facilities.create'])->post('/add', [FacilityController::class, 'store']);
        Route::middleware(['permission:facilities.edit'])->put('/edit', [FacilityController::class, 'update']);
        Route::middleware(['permission:facilities.delete'])->delete('/delete', [FacilityController::class, 'destroy']);
        Route::middleware(['permission:facilities.enable'])->get('/toggle-enable', [FacilityController::class, 'toggleEnable']);
    });

    //HotelInfoController
    Route::prefix('hotel-infos')->group(function () {
        Route::get('/', [HotelInfoController::class, 'index']);
        Route::get('/data', [HotelInfoController::class, 'getData']);
        Route::middleware(['permission:hotel_infos.create'])->post('/add', [HotelInfoController::class, 'store']);
        Route::middleware(['permission:hotel_infos.edit'])->put('/edit', [HotelInfoController::class, 'update']);
        Route::middleware(['permission:hotel_infos.delete'])->delete('/delete', [HotelInfoController::class, 'destroy']);
        Route::middleware(['permission:hotel_infos.enable'])->get('/toggle-enable', [HotelInfoController::class, 'toggleEnable']);
        Route::middleware(['permission:hotel_infos.change_order'])->put('/order', [HotelInfoController::class, 'changeOrder']);
    });

    //TvChannelCategoryController
    Route::prefix('tv-channel-categories')->group(function () {
        Route::get('/', [TvChannelCategoryController::class, 'index']);
        Route::get('/data', [TvChannelCategoryController::class, 'getData']);
        Route::middleware(['permission:tv_channel_categories.create'])->post('/add', [TvChannelCategoryController::class, 'store']);
        Route::middleware(['permission:tv_channel_categories.edit'])->put('/edit', [TvChannelCategoryController::class, 'update']);
        Route::middleware(['permission:tv_channel_categories.delete'])->delete('/delete', [TvChannelCategoryController::class, 'destroy']);
        Route::middleware(['permission:tv_channel_categories.change_order'])->put('/order', [TvChannelCategoryController::class, 'changeOrder']);
    });

     //TvChannelController
     Route::prefix('tv-channels')->group(function () {
        Route::get('/', [TvChannelController::class, 'index']);
        Route::get('/data', [TvChannelController::class, 'getData']);
        Route::middleware(['permission:tv_channels.create'])->post('/add', [TvChannelController::class, 'store']);
        Route::middleware(['permission:tv_channels.edit'])->put('/edit', [TvChannelController::class, 'update']);
        Route::middleware(['permission:tv_channels.delete'])->delete('/delete', [TvChannelController::class, 'destroy']);
        Route::middleware(['permission:tv_channels.enable'])->get('/toggle-enable', [TvChannelController::class, 'toggleEnable']);
        Route::middleware(['permission:tv_channels.change_order'])->put('/order', [TvChannelController::class, 'changeOrder']);
    });

    //RegularMessageController
    Route::prefix('regular-messages')->group(function () {
        Route::get('/', [RegularMessageController::class, 'index']);
        Route::get('/data', [RegularMessageController::class, 'getData']);
        Route::middleware(['permission:regular_messages.create'])->post('/add', [RegularMessageController::class, 'store']);
        Route::middleware(['permission:regular_messages.edit'])->put('/edit', [RegularMessageController::class, 'update']);
        Route::middleware(['permission:regular_messages.delete'])->delete('/delete', [RegularMessageController::class, 'destroy']);
    });

    //TickerController
    Route::prefix('tickers')->group(function () {
        Route::get('/', [TickerController::class, 'index']);
        Route::get('/data', [TickerController::class, 'getData']);
        Route::middleware(['permission:tickers.resend'])->get('/resend', [TickerController::class, 'resend']);
        Route::middleware(['permission:tickers.create'])->post('/add', [TickerController::class, 'store']);
        Route::middleware(['permission:tickers.delete'])->delete('/delete', [TickerController::class, 'destroy']);
    });

    //EmergencyController
    Route::prefix('emergencies')->group(function () {
        Route::get('/', [EmergencyController::class, 'index']);
        Route::get('/data', [EmergencyController::class, 'getData']);
        Route::middleware(['permission:emergencies.resend'])->get('/resend', [EmergencyController::class, 'resend']);
        Route::middleware(['permission:emergencies.create'])->post('/add', [EmergencyController::class, 'store']);
        Route::middleware(['permission:emergencies.delete'])->delete('/delete', [EmergencyController::class, 'destroy']);
    });

    //AdvertisementController
    Route::prefix('advertisements')->group(function () {
        Route::get('/', [AdvertisementController::class, 'index']);
        Route::get('/data', [AdvertisementController::class, 'getData']);
        Route::middleware(['permission:advertisements.resend'])->get('/resend', [AdvertisementController::class, 'resend']);
        Route::middleware(['permission:advertisements.create'])->post('/add', [AdvertisementController::class, 'store']);
        Route::middleware(['permission:advertisements.delete'])->delete('/delete', [AdvertisementController::class, 'destroy']);
    });

    //VideoAdController
    Route::prefix('video-ads')->group(function () {
        Route::get('/', [VideoAdController::class, 'index']);
        Route::get('/data', [VideoAdController::class, 'getData']);
        Route::middleware(['permission:video_ads.create'])->post('/add', [VideoAdController::class, 'store']);
        Route::middleware(['permission:video_ads.edit'])->put('/edit', [VideoAdController::class, 'update']);
        Route::middleware(['permission:video_ads.delete'])->delete('/delete', [VideoAdController::class, 'destroy']);
        Route::middleware(['permission:video_ads.enable'])->get('/toggle-enable', [VideoAdController::class, 'toggleEnable']);
        Route::middleware(['permission:video_ads.change_order'])->put('/order', [VideoAdController::class, 'changeOrder']);
    });

    //DefaultAppController
    Route::prefix('default-apps')->group(function () {
        Route::get('/', [DefaultAppController::class, 'index']);
        Route::get('/data', [DefaultAppController::class, 'getData']);
        Route::middleware(['permission:default_apps.create'])->post('/add', [DefaultAppController::class, 'store']);
        Route::middleware(['permission:default_apps.edit'])->put('/edit', [DefaultAppController::class, 'update']);
        Route::middleware(['permission:default_apps.delete'])->delete('/delete', [DefaultAppController::class, 'destroy']);
        Route::middleware(['permission:default_apps.enable'])->get('/toggle-enable', [DefaultAppController::class, 'toggleEnable']);
        // Route::middleware(['permission:video_ads.change_order'])->put('/order', [VideoAdController::class, 'changeOrder']);
    });

    //ZoneController
    Route::prefix('zones')->group(function () {
        Route::get('/', [ZoneController::class, 'index']);
        Route::get('/data', [ZoneController::class, 'getData']);
        Route::middleware(['permission:zones.create'])->post('/add', [ZoneController::class, 'store']);
        Route::middleware(['permission:zones.edit'])->put('/edit', [ZoneController::class, 'update']);
        Route::middleware(['permission:zones.delete'])->delete('/delete', [ZoneController::class, 'destroy']);
    });

    //ZoneController
    Route::prefix('themes')->group(function () {
        Route::get('/', [ThemeController::class, 'index']);
        Route::get('/data', [ThemeController::class, 'getData']);
        Route::middleware(['permission:themes.create'])->post('/add', [ThemeController::class, 'store']);
        Route::middleware(['permission:themes.edit_theme_zone'])->put('/edit-theme-zone', [ThemeController::class, 'updateThemeZone']);
        Route::middleware(['permission:themes.delete'])->delete('/delete', [ThemeController::class, 'destroy']);
        Route::middleware(['permission:themes.default'])->get('/toggle-default', [ThemeController::class, 'toggleDefault']);
        Route::middleware(['permission:themes.edit_theme'])->put('/edit-theme', [ThemeController::class, 'updateTheme']);
        Route::middleware(['permission:themes.edit_theme_zone'])->get('/theme-zone-data', [ThemeController::class, 'getThemeZoneData']);
        Route::middleware(['permission:themes.assign_room'])->put('/assign-room', [ThemeController::class, 'assignRoom']);
        Route::middleware(['permission:themes.edit_theme_application'])->get('/theme-application-data', [ThemeController::class, 'getThemeApplicationData']);
        Route::middleware(['permission:themes.edit_theme_application'])->put('/edit-theme-application', [ThemeController::class, 'updateThemeApplication']);
        Route::middleware(['permission:themes.edit_theme_application'])->get('/toggle-enable', [ThemeController::class, 'toggleEnable']);
    });

    //ManagerController
    Route::prefix('adb-manager')->group(function () {
        Route::get('/', [ManagerController::class, 'index']);
        Route::get('/data', [ManagerController::class, 'getData']);
        Route::get('/apk-list', [ManagerController::class, 'getApkList']);
        Route::middleware(['permission:device_adb_manager.group_install'])->post('/group-install', [ManagerController::class, 'groupInstall']);
        Route::middleware(['permission:device_adb_manager.group_uninstall'])->post('/group-uninstall', [ManagerController::class, 'groupUninstall']);
        Route::middleware(['permission:device_adb_manager.settings'])->post('/settings', [ManagerController::class, 'settings']);
        Route::middleware(['permission:device_adb_manager.screencapture'])->get('/screen-capture', [ManagerController::class, 'screenCapture']);
        Route::middleware(['permission:device_adb_manager.screenrecord'])->get('/screen-record', [ManagerController::class, 'screenRecord']);
    });

    //ScreenCaptureController
    Route::prefix('adb-screen-capture')->group(function () {
        Route::get('/', [ScreenCaptureController::class, 'index']);
        Route::get('/data', [ScreenCaptureController::class, 'getData']);
        Route::get('/check-table-changes', [ScreenCaptureController::class, 'checkTableChanges']);
        Route::middleware(['permission:device_adb_screen_capture.delete'])->delete('/delete', [ScreenCaptureController::class, 'destroy']);
    });

    //ScreenRecordController
    Route::prefix('adb-screen-record')->group(function () {
        Route::get('/', [ScreenRecordController::class, 'index']);
        Route::get('/data', [ScreenRecordController::class, 'getData']);
        Route::get('/check-table-changes', [ScreenRecordController::class, 'checkTableChanges']);
        Route::middleware(['permission:device_adb_screen_record.delete'])->delete('/delete', [ScreenRecordController::class, 'destroy']);
    });

    //ApkController
    Route::prefix('adb-apk')->group(function () {
        Route::get('/', [ApkController::class, 'index']);
        Route::get('/data', [ApkController::class, 'getData']);
        Route::middleware(['permission:device_adb_apk.delete'])->delete('/delete', [ApkController::class, 'destroy']);
        Route::middleware(['permission:device_adb_apk.create'])->post('/add', [ApkController::class, 'store']);
    });

    //AnalyticController
    Route::prefix('analytics')->group(function () {
        // Route::get('/', [ApkController::class, 'index']);
        Route::get('/ping-devices', [AnalyticController::class, 'pingDevices']);
        Route::get('/get-apps', [AnalyticController::class, 'getApps']);
        Route::get('/get-year-checkin', [AnalyticController::class, 'getYearCheckin']);
        Route::get('/get-rooms', [AnalyticController::class, 'getRooms']);
        Route::get('/get-guests', [AnalyticController::class, 'getGuests']);
        Route::get('/get-top-10-tv-channels', [AnalyticController::class, 'getTop10TvChannels']);
        Route::get('/get-top-10-fnbs', [AnalyticController::class, 'getTop10Fnbs']);
        Route::get('/get-top-10-item-requests', [AnalyticController::class, 'getTop10ItemRequests']);
        Route::get('/get-top-10-service-requests', [AnalyticController::class, 'getTop10ServiceRequests']);
        Route::middleware(['permission:analytics.tv'])
            ->get('/tv-channels', [AnalyticController::class, 'tvChannelsIndex']);
        Route::get('/get-tv-channel-data', [AnalyticController::class, 'getTvChannelData']);
        Route::get('/get-tv-channel-counter', [AnalyticController::class, 'getTvChannelCounter']);
        Route::middleware(['permission:analytics.regular_messages'])
            ->get('/regular-messaging', [AnalyticController::class, 'regularMessagingIndex']);
        Route::get('/get-regular-messaging-data', [AnalyticController::class, 'getRegularMessagingData']);
        Route::middleware(['permission:analytics.broadcast_messages'])
            ->get('/broadcast-messaging', [AnalyticController::class, 'broadcastMessagingIndex']);
        Route::get('/get-broadcast-messaging-data', [AnalyticController::class, 'getBroadcastMessagingData']);
        Route::middleware(['permission:analytics.fnbs'])
            ->get('/fnbs', [AnalyticController::class, 'fnbIndex']);
        Route::get('/get-fnb-data', [AnalyticController::class, 'getFnbData']);
        Route::get('/get-fnb-counter', [AnalyticController::class, 'getFnbCounter']);
        Route::middleware(['permission:analytics.item_requests'])
            ->get('/item-requests', [AnalyticController::class, 'itemRequestIndex']);
        Route::get('/get-item-request-data', [AnalyticController::class, 'getItemRequestData']);
        Route::get('/get-item-request-counter', [AnalyticController::class, 'getItemRequestCounter']);
        Route::middleware(['permission:analytics.service_requests'])
            ->get('/service-requests', [AnalyticController::class, 'serviceRequestIndex']);
        Route::get('/get-service-request-data', [AnalyticController::class, 'getServiceRequestData']);
        Route::get('/get-service-request-counter', [AnalyticController::class, 'getServiceRequestCounter']);
    });

    //NotificationController
    Route::prefix('notifications')->group(function () {
        // Route::get('/', [ApkController::class, 'index']);
        Route::get('/get-notifications', [NotificationController::class, 'getNotifications']);
        Route::post('/read', [NotificationController::class, 'readNotification']);
    });

    /**
     * Search for dropdowns
     */
    Route::get('/search/{name}', [SearchController::class, 'search']);
    /*-- end-new --*/

    /**
     * Search for dropdowns
     */
    // Route::post('/admin/search/{name}', [SearchController::class, 'search']);
    
    
    Route::prefix('errors')->group(function () {

        Route::get('/403', function () {
            return view('/errors/403/index', [], 403);
        });

        Route::get('/404', function () {
            return view('/errors/404/index', [], 404);
        });
    });

    
});


require __DIR__.'/auth.php';

// Auth::routes();
