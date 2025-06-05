<?php

namespace App\Models\General\Body\Guests;

use App\Models\General\Body\Devices\Device;
use App\Models\General\Body\VideoAds\VideoAdsRoom;
use App\Models\GeneralStatus;
use App\Models\SystemSettings\ThemeManager\ThemeRoom;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function category()
    {
        return $this->belongsTo(RoomCategory::class, 'category_id');
    }

    public function status()
    {
        return $this->belongsTo(GeneralStatus::class, 'room_status');
    }

    public function videoAdsRooms()
    {
        return $this->hasMany(VideoAdsRoom::class, 'room_id');
    }

    public function themeRooms()
    {
        return $this->hasMany(ThemeRoom::class, 'room_id');
    }
}
