<?php

namespace App\Models\SystemSettings\ThemeManager;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThemeApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'is_enable'
    ];

    public function application()
    {
        return $this->belongsTo(DefaultApp::class, 'application_id');
    }
}
