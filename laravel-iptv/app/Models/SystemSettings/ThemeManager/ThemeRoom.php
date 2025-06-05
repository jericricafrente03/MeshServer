<?php

namespace App\Models\SystemSettings\ThemeManager;

use App\Models\General\Body\Guests\Room;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeRoom extends Model
{
    use HasFactory;

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
