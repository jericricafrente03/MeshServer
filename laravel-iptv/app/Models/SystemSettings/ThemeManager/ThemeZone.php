<?php

namespace App\Models\SystemSettings\ThemeManager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeZone extends Model
{
    use HasFactory;

    public function zones()
    {
        return $this->belongsTo(Zone::class, 'zone_id');
    }
}
