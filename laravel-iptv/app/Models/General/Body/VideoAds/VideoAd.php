<?php

namespace App\Models\General\Body\VideoAds;

use App\Models\General\Body\Guests\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoAd extends Model
{
    use HasFactory;

    public function videoAdsRooms()
    {
        return $this->hasMany(VideoAdsRoom::class, 'video_ads_id');
    }
}
