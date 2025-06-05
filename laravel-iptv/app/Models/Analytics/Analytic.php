<?php

namespace App\Models\Analytics;

use App\Models\General\Body\Hospitality\Fnb;
use App\Models\General\Body\Hospitality\HospitalityItem;
use App\Models\General\Body\Hospitality\HospitalityService;
use App\Models\General\Body\Tv\TvChannel;
use App\Models\SystemSettings\ThemeManager\DefaultApp;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Analytic extends Model
{
    use HasFactory;

    // Define relationship with DefaultApp
    public function app()
    {
        return $this->belongsTo(DefaultApp::class, 'item_id');
    }

    public function tvChannel()
    {
        return $this->belongsTo(TvChannel::class, 'item_id')->withTrashed();
    }

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
