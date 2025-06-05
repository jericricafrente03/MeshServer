<?php

namespace App\Models\General\Body\Guests;

use App\Models\General\Body\Hospitality\Fnb;
use App\Models\General\Body\Hospitality\HospitalityItem;
use App\Models\General\Body\Hospitality\HospitalityService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestBilling extends Model
{
    use HasFactory;

    public function fnb()
    {
        return $this->belongsTo(Fnb::class, 'item_id')->withTrashed();
    }

    public function itemRequest()
    {
        return $this->belongsTo(HospitalityItem::class, 'item_id')->withTrashed();
    }

    public function serviceRequest()
    {
        return $this->belongsTo(HospitalityService::class, 'item_id')->withTrashed();
    }
}
