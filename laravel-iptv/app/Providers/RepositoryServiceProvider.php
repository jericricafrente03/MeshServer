<?php

namespace App\Providers;

use App\Interfaces\API\STB\Guests\IRoomAssignmentRepository as APIIRoomAssignmentRepository;
use App\Interfaces\API\STB\Guests\IRoomCategoryRepository as APIIRoomCategoryRepository;
use App\Interfaces\API\STB\Guests\IRoomRepository as APIIRoomRepository;
use App\Interfaces\API\STB\Hospitality\IFacilityCategoryRepository as APIIFacilityCategoryRepository;
use App\Interfaces\API\STB\Hospitality\IFacilityRepository as APIIFacilityRepository;
use App\Interfaces\API\STB\Hospitality\IFnbCategoryRepository as APIIFnbCategoryRepository;
use App\Interfaces\API\STB\Hospitality\IFnbRepository as APIIFnbRepository;
use App\Interfaces\API\STB\Hospitality\IHotelInfoRepository as APIIHotelInfoRepository;
use App\Interfaces\API\STB\Hospitality\IItemRequestRepository as APIIItemRequestRepository;
use App\Interfaces\API\STB\Hospitality\IServiceRequestRepository as APIIServiceRequestRepository;
use App\Interfaces\API\STB\IAielloRepository;
use App\Interfaces\API\STB\IAnalyticRepository as APIIAnalyticRepository;
use App\Interfaces\API\STB\IAuthRepository;
use App\Interfaces\API\STB\IBroadcastMessagingRepository;
use App\Interfaces\API\STB\IDeviceRepository as APIIDeviceRepository;
use App\Interfaces\API\STB\IGuestBillingRepository as APIIGuestBillingRepository;
use App\Interfaces\API\STB\IRegularMessageRepository as APIIRegularMessageRepository;
use App\Interfaces\API\STB\ISystemConfigRepository as APIISystemConfigRepository;
use App\Interfaces\API\STB\IThemeManagerRepository as APIIThemeManagerRepository;
use App\Interfaces\API\STB\ITvChannelCategoryRepository as APIITvChannelCategoryRepository;
use App\Interfaces\API\STB\ITvChannelRepository as APIITvChannelRepository;
use App\Interfaces\API\STB\IVideoAdsRepository as APIIVideoAdsRepository;
use App\Interfaces\API\STB\IWeatherRepository as APIIWeatherRepository;
use App\Interfaces\General\Body\Analytics\IAnalyticRepository;
use Illuminate\Support\ServiceProvider;
use App\Interfaces\General\Body\Devices\IDeviceCategoryRepository;
use App\Interfaces\General\Body\Devices\IDeviceMonitorRepository;
use App\Interfaces\General\Body\Devices\IDeviceRepository;
use App\Interfaces\General\Body\Guests\IGuestBillingRepository;
use App\Interfaces\General\Body\Guests\IGuestRepository;
use App\Interfaces\General\Body\Guests\IRoomAssignmentRepository;
use App\Interfaces\General\Body\Guests\IRoomCategoryRepository;
use App\Interfaces\General\Body\Guests\IRoomRepository;
use App\Interfaces\General\Body\Hospitality\IFacilityCategoryRepository;
use App\Interfaces\General\Body\Hospitality\IFacilityRepository;
use App\Interfaces\General\Body\Hospitality\IFnbCategoryRepository;
use App\Interfaces\General\Body\Hospitality\IFnbRepository;
use App\Interfaces\General\Body\Hospitality\IHotelInfoRepository;
use App\Interfaces\General\Body\Hospitality\IItemRequestRepository;
use App\Interfaces\General\Body\Hospitality\IServiceRequestRepository;
use App\Interfaces\General\Body\Messages\BroadcastMessages\IAdvertisementRepository;
use App\Interfaces\General\Body\Messages\BroadcastMessages\IEmergencyRepository;
use App\Interfaces\General\Body\Messages\BroadcastMessages\ITickerRepository;
use App\Interfaces\General\Body\Messages\IRegularMessageRepository;
use App\Interfaces\General\Body\Notifications\INotificationRepository;
use App\Interfaces\General\Body\Tv\ITvChannelCategoryRepository;
use App\Interfaces\General\Body\Tv\ITvChannelRepository;
use App\Interfaces\General\Body\VideoAds\IVideoAdsRepository;
use App\Interfaces\General\SearchInterface;
use App\Interfaces\IUserHistoryLogRepository;
use App\Interfaces\SystemSettings\DeviceAdb\Files\IScreenCaptureRepository;
use App\Interfaces\SystemSettings\DeviceAdb\Files\IScreenRecordRepository;
use App\Interfaces\SystemSettings\DeviceAdb\IApkRepository;
use App\Interfaces\SystemSettings\DeviceAdb\IManagerRepository;
use App\Interfaces\SystemSettings\ThemeManager\IDefaultAppRepository;
use App\Interfaces\SystemSettings\ThemeManager\IThemeRepository;
use App\Interfaces\SystemSettings\ThemeManager\IZoneRepository;
use App\Repositories\API\STB\AielloRepository;
use App\Repositories\API\STB\AnalyticRepository as APIAnalyticRepository;
use App\Repositories\API\STB\AuthRepository;
use App\Repositories\API\STB\BroadcastMessagingRepository;
use App\Repositories\API\STB\DeviceRepository as APIDeviceRepository;
use App\Repositories\API\STB\GuestBillingRepository as APIGuestBillingRepository;
use App\Repositories\API\STB\Guests\RoomAssignmentRepository as APIRoomAssignmentRepository;
use App\Repositories\API\STB\Guests\RoomCategoryRepository as APIRoomCategoryRepository;
use App\Repositories\API\STB\Guests\RoomRepository as APIRoomRepository;
use App\Repositories\API\STB\Hospitality\FacilityCategoryRepository as APIFacilityCategoryRepository;
use App\Repositories\API\STB\Hospitality\FacilityRepository as APIFacilityRepository;
use App\Repositories\API\STB\Hospitality\FnbCategoryRepository as APIFnbCategoryRepository;
use App\Repositories\API\STB\Hospitality\FnbRepository as APIFnbRepository;
use App\Repositories\API\STB\Hospitality\HotelInfoRepository as APIHotelInfoRepository;
use App\Repositories\API\STB\Hospitality\ItemRequestRepository as APIItemRequestRepository;
use App\Repositories\API\STB\Hospitality\ServiceRequestRepository as APIServiceRequestRepository;
use App\Repositories\API\STB\RegularMessageRepository as APIRegularMessageRepository;
use App\Repositories\API\STB\SystemConfigRepository as APISystemConfigRepository;
use App\Repositories\API\STB\ThemeManagerRepository as APIThemeManagerRepository;
use App\Repositories\API\STB\TvChannelCategoryRepository as APITvChannelCategoryRepository;
use App\Repositories\API\STB\TvChannelRepository as APITvChannelRepository;
use App\Repositories\API\STB\VideoAdsRepository as APIVideoAdsRepository;
use App\Repositories\API\STB\WeatherRepository as APIWeatherRepository;
use App\Repositories\General\Body\Analytics\AnalyticRepository;
use App\Repositories\General\Body\Devices\DeviceCategoryRepository;
use App\Repositories\General\Body\Devices\DeviceMonitorRepository;
use App\Repositories\General\Body\Devices\DeviceRepository;
use App\Repositories\General\Body\Guests\GuestBillingRepository;
use App\Repositories\General\Body\Guests\GuestRepository;
use App\Repositories\General\Body\Guests\RoomAssignmentRepository;
use App\Repositories\General\Body\Guests\RoomCategoryRepository;
use App\Repositories\General\Body\Guests\RoomRepository;
use App\Repositories\General\Body\Hospitality\FacilityCategoryRepository;
use App\Repositories\General\Body\Hospitality\FacilityRepository;
use App\Repositories\General\Body\Hospitality\FnbCategoryRepository;
use App\Repositories\General\Body\Hospitality\FnbRepository;
use App\Repositories\General\Body\Hospitality\HotelInfoRepository;
use App\Repositories\General\Body\Hospitality\ItemRequestRepository;
use App\Repositories\General\Body\Hospitality\ServiceRequestRepository;
use App\Repositories\General\Body\Messages\BroadcastMessages\AdvertisementRepository;
use App\Repositories\General\Body\Messages\BroadcastMessages\EmergencyRepository;
use App\Repositories\General\Body\Messages\BroadcastMessages\TickerRepository;
use App\Repositories\General\Body\Messages\RegularMessageRepository;
use App\Repositories\General\Body\Notifications\NotificationRepository;
use App\Repositories\General\Body\Tv\TvChannelCategoryRepository;
use App\Repositories\General\Body\Tv\TvChannelRepository;
use App\Repositories\General\Body\VideoAds\VideoAdsRepository;
use App\Repositories\General\SearchRepository;
use App\Repositories\SystemSettings\DeviceAdb\ApkRepository;
use App\Repositories\SystemSettings\DeviceAdb\Files\ScreenCaptureRepository;
use App\Repositories\SystemSettings\DeviceAdb\Files\ScreenRecordRepository;
use App\Repositories\SystemSettings\DeviceAdb\ManagerRepository;
use App\Repositories\SystemSettings\ThemeManager\DefaultAppRepository;
use App\Repositories\SystemSettings\ThemeManager\ThemeRepository;
use App\Repositories\SystemSettings\ThemeManager\ZoneRepository;
use App\Repositories\UserHistoryLogRepository;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Bind Interface and Repository class together
        $this->app->bind(IUserHistoryLogRepository::class, UserHistoryLogRepository::class);
        //General
        $this->app->bind(SearchInterface::class, SearchRepository::class);
        $this->app->bind(IDeviceRepository::class, DeviceRepository::class);
        $this->app->bind(IDeviceCategoryRepository::class, DeviceCategoryRepository::class);
        $this->app->bind(IDeviceMonitorRepository::class, DeviceMonitorRepository::class);
        $this->app->bind(IRoomRepository::class, RoomRepository::class);
        $this->app->bind(IRoomCategoryRepository::class, RoomCategoryRepository::class);
        $this->app->bind(IGuestRepository::class, GuestRepository::class);
        $this->app->bind(IGuestBillingRepository::class, GuestBillingRepository::class);
        $this->app->bind(IRoomAssignmentRepository::class, RoomAssignmentRepository::class);
        $this->app->bind(IFnbCategoryRepository::class, FnbCategoryRepository::class);
        $this->app->bind(IFnbRepository::class, FnbRepository::class);
        $this->app->bind(IItemRequestRepository::class, ItemRequestRepository::class);
        $this->app->bind(IServiceRequestRepository::class, ServiceRequestRepository::class);
        $this->app->bind(IFacilityCategoryRepository::class, FacilityCategoryRepository::class);
        $this->app->bind(IFacilityRepository::class, FacilityRepository::class);
        $this->app->bind(IHotelInfoRepository::class, HotelInfoRepository::class);
        $this->app->bind(ITvChannelCategoryRepository::class, TvChannelCategoryRepository::class);
        $this->app->bind(ITvChannelRepository::class, TvChannelRepository::class);
        $this->app->bind(IRegularMessageRepository::class, RegularMessageRepository::class);
        $this->app->bind(ITickerRepository::class, TickerRepository::class);
        $this->app->bind(IEmergencyRepository::class, EmergencyRepository::class);
        $this->app->bind(IAdvertisementRepository::class, AdvertisementRepository::class);
        $this->app->bind(IVideoAdsRepository::class, VideoAdsRepository::class);
        $this->app->bind(IAnalyticRepository::class, AnalyticRepository::class);
        $this->app->bind(INotificationRepository::class, NotificationRepository::class);

        //System Settings
        $this->app->bind(IDefaultAppRepository::class, DefaultAppRepository::class);
        $this->app->bind(IZoneRepository::class, ZoneRepository::class);
        $this->app->bind(IThemeRepository::class, ThemeRepository::class);
        $this->app->bind(IManagerRepository::class, ManagerRepository::class);
        $this->app->bind(IScreenCaptureRepository::class, ScreenCaptureRepository::class);
        $this->app->bind(IScreenRecordRepository::class, ScreenRecordRepository::class);
        $this->app->bind(IApkRepository::class, ApkRepository::class);

        //API
        $this->app->bind(IAuthRepository::class, AuthRepository::class);
        $this->app->bind(APIIGuestBillingRepository::class, APIGuestBillingRepository::class);
        $this->app->bind(APIIRegularMessageRepository::class, APIRegularMessageRepository::class);
        $this->app->bind(IBroadcastMessagingRepository::class, BroadcastMessagingRepository::class);
        $this->app->bind(APIIVideoAdsRepository::class, APIVideoAdsRepository::class);
        $this->app->bind(APIIThemeManagerRepository::class, APIThemeManagerRepository::class);
        $this->app->bind(APIITvChannelRepository::class, APITvChannelRepository::class);
        $this->app->bind(APIITvChannelCategoryRepository::class, APITvChannelCategoryRepository::class);
        $this->app->bind(APIISystemConfigRepository::class, APISystemConfigRepository::class);
        $this->app->bind(APIIFacilityRepository::class, APIFacilityRepository::class);
        $this->app->bind(APIIFacilityCategoryRepository::class, APIFacilityCategoryRepository::class);
        $this->app->bind(APIIFnbRepository::class, APIFnbRepository::class);
        $this->app->bind(APIIFnbCategoryRepository::class, APIFnbCategoryRepository::class);
        $this->app->bind(APIIRoomAssignmentRepository::class, APIRoomAssignmentRepository::class);
        $this->app->bind(APIIHotelInfoRepository::class, APIHotelInfoRepository::class);
        $this->app->bind(APIIItemRequestRepository::class, APIItemRequestRepository::class);
        $this->app->bind(APIIServiceRequestRepository::class, APIServiceRequestRepository::class);
        $this->app->bind(APIIRoomRepository::class, APIRoomRepository::class);
        $this->app->bind(APIIRoomCategoryRepository::class, APIRoomCategoryRepository::class);
        $this->app->bind(APIIWeatherRepository::class, APIWeatherRepository::class);
        $this->app->bind(APIIAnalyticRepository::class, APIAnalyticRepository::class);
        $this->app->bind(APIIDeviceRepository::class, APIDeviceRepository::class);
        $this->app->bind(IAielloRepository::class, AielloRepository::class);
    }
}