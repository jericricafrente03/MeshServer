<?php

namespace App\Models\General\Body\Messages;

use App\Models\General\Body\Devices\DeviceCategory;
use App\Models\GeneralStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BroadcastMessage extends Model
{
    use HasFactory, SoftDeletes;

    public function type()
    {
        return $this->belongsTo(GeneralStatus::class, 'type_id');
    }

    public function broadcastType()
    {
        return $this->belongsTo(GeneralStatus::class, 'broadcast_type_id');
    }

    public function device_group()
    {
        return $this->belongsTo(DeviceCategory::class, 'device_category_id')->withDefault();
        // return $this->belongsTo(DeviceCategory::class, 'device_category_id')->withDefault([
        //     'name' => 'N/A', // Default value for null relationships
        // ]);
    }
}
