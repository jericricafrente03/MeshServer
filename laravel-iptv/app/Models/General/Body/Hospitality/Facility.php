<?php

namespace App\Models\General\Body\Hospitality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facility extends Model
{
    use HasFactory;

    public function category()
    {
        return $this->belongsTo(FacilityCategory::class, 'category_id');
    }
}
