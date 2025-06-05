<?php

namespace App\Models\SystemSettings\ThemeManager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultApp extends Model
{
    use HasFactory;

    public function themes()
    {
        return $this->belongsToMany(Theme::class, 'theme_applications', 'application_id', 'theme_id');
    }

    public function theme_applications()
    {
        return $this->hasMany(ThemeApplication::class, 'application_id');
    }
}
