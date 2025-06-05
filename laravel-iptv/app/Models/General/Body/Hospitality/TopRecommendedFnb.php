<?php

namespace App\Models\General\Body\Hospitality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopRecommendedFnb extends Model
{
    use HasFactory;

    protected $fillable = [
        'fnb_id',
        'recommended_month',
        'popularity_score',
    ];
}
