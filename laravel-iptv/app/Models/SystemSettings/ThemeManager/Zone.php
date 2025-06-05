<?php

namespace App\Models\SystemSettings\ThemeManager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    public function themes()
    {
        return $this->belongsToMany(Theme::class, 'theme_zones');
    }

    public function theme_zones()
    {
        return $this->hasMany(ThemeZone::class, 'zone_id');
    }
}
