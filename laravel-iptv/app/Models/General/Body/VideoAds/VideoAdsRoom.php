<?php

namespace App\Models\General\Body\VideoAds;

use App\Models\General\Body\Guests\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoAdsRoom extends Model
{
    use HasFactory;

    // Allow mass assignment for these fields
    protected $fillable = [
        'video_ads_id',
        'room_id', 
        'created_at',
        'updated_at', 
    ];

    public function videoAd()
    {
        return $this->belongsTo(VideoAd::class, 'video_ads_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
