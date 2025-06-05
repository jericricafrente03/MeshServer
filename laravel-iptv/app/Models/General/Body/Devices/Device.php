<?php

namespace App\Models\General\Body\Devices;

use App\Models\General\Body\Guests\Room;
use App\Models\GeneralStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(DeviceCategory::class, 'category_id');
    }

    public function language()
    {
        return $this->belongsTo(GeneralStatus::class, 'language_id');
    }
}
