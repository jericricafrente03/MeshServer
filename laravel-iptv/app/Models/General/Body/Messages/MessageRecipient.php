<?php

namespace App\Models\General\Body\Messages;

use App\Models\General\Body\Guests\Room;
use App\Models\GeneralStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageRecipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_id', 
    ];

    public function message()
    {
        return $this->belongsTo(RegularMessage::class, 'regular_message_id');
    }

    public function status()
    {
        return $this->belongsTo(GeneralStatus::class, 'status_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
}
