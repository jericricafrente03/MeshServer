<?php

namespace App\Repositories\General;

use App\Interfaces\General\SearchInterface;
use App\Models\Country;
use App\Models\General\Body\Devices\DeviceCategory;
use App\Models\General\Body\Guests\Guest;
use App\Models\General\Body\Guests\Room;
use App\Models\General\Body\Guests\RoomCategory;
use App\Models\General\Body\Hospitality\FacilityCategory;
use App\Models\General\Body\Hospitality\Fnb;
use App\Models\General\Body\Hospitality\FnbCategory;
use App\Models\General\Body\Hospitality\HospitalityItem;
use App\Models\General\Body\Hospitality\HospitalityService;
use App\Models\General\Body\Tv\TvChannel;
use App\Models\General\Body\Tv\TvChannelCategory;
use App\Models\GeneralStatus;
use App\Models\SystemSettings\DeviceAdb\AdbSetting;
use Illuminate\Support\Facades\DB;

class SearchRepository implements SearchInterface
{
    public function searchDeviceGroup($request)
    {
        $data = DeviceCategory::all();
        return $data;
    }

    public function searchRoomCategories($request)
    {
        $data = RoomCategory::all();
        return $data;
    }

    public function searchItemRequests($request)
    {
        $data = HospitalityItem::all();
        return $data;
    }

    public function searchServiceRequests($request)
    {
        $data = HospitalityService::all();
        return $data;
    }

    public function searchFnbs($request)
    {
        $data = Fnb::all();
        return $data;
    }

    public function searchFnbCategories($request)
    {
        $data = FnbCategory::all();
        return $data;
    }

    public function searchTvChannelCategories($request)
    {
        $data = TvChannelCategory::all();
        return $data;
    }

    public function searchTvChannels($request)
    {
        $data = TvChannel::all();
        return $data;
    }

    public function searchFacilityCategories($request)
    {
        $data = FacilityCategory::all();
        return $data;
    }

    public function searchCountries($request)
    {
        $data = Country::all();
        return $data;
    }

    public function searchLanguages($request)
    {
        $data = GeneralStatus::where('category', $request->type)->get();
        return $data;
    }

    public function searchRoomStatuses($request)
    {
        $data = GeneralStatus::where('category', $request->category)->get();
        return $data;
    }

    public function searchMessageTypes($request)
    {
        $data = GeneralStatus::where('category', $request->category)->get();
        return $data;
    }

    public function searchSettings($request)
    {
        $data = AdbSetting::find(1);

        if($data == null){
            $data = [];
        }

        return $data;
    }

    public function searchRooms($request)
    {   
        $post = $request->all();
        
        if (!empty($post['room_category_id'])) {
            $data = Room::where('category_id', $post['room_category_id'])->get();
        } else {
            $data = Room::get();
        }

        return $data;
    }

    public function searchAvailableRooms($request)
    {   
        $post = $request->all();
        
        if (!empty($post['room_category_id'])) {
            if (!empty($post['id'])){
                $data = Room::where('category_id', $post['room_category_id'])
                    ->where(function($query) use ($post) {
                        $query->where('room_status', 1)
                            ->orWhere('id', $post['id']);
                    })
                    ->get();
            }
            else{
                $data = Room::where('category_id', $post['room_category_id'])
                    ->where('room_status', 1)
                    ->orWhere('id', $post['id'])
                    ->get();
            }
        } else {
            if (!empty($post['id'])){
                $data = Room::where('room_status', 1)->orWhere('id', $post['id'])->get();
            }
            else{
                $data = Room::where('room_status', 1)->get();
            }
        }

        return $data;
    }

    public function searchGuests($request)
    {
        $data = Guest::select(
                    'guests.*', 
                    DB::raw("CONCAT(guests.title, ' ', guests.firstname, ' ', guests.lastname) AS name"
                    )
                );
        if($request->has('term')) {
            $data = $data->where(function($data) use ($request){
                $data = $data->orWhere('guests.firstname', 'like', "%".$request->term."%");
                $data = $data->orWhere('guests.lastname', 'like', "%".$request->term."%");
                $data = $data->orWhere(DB::raw('CONCAT(guests.firstname," ",guests.lastname)'), 'like', "%".$request->term."%");
                $data = $data->orWhere(DB::raw('CONCAT(guests.lastname,", ",guests.firstname)'), 'like', "%".$request->term."%");
            });
        }
        if($request->has('limit')) {
            $data = $data->take($request->limit);
        }

        $data = $data->orderBy('guests.lastname','asc')->orderBy('guests.firstname','asc')->get();
        
        if($request->ajax()) {
            return json_encode(['data'=> $data]);
        }
        return $data;
    }
}