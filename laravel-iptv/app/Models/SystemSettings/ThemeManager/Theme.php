<?php

namespace App\Models\SystemSettings\ThemeManager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Theme extends Model
{
    use HasFactory;

    public function zones()
    {
        return $this->belongsToMany(Zone::class, 'theme_zones');
    }

    public function themeRooms()
    {
        return $this->hasMany(ThemeRoom::class, 'theme_id');
    }

    public function applications()
    {
        return $this->belongsToMany(DefaultApp::class, 'theme_applications', 'theme_id', 'application_id');
    }

    public function theme_applications()
    {
        return $this->hasMany(ThemeApplication::class, 'theme_id');
    }
}
