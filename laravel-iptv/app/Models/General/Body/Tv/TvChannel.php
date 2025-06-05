<?php

namespace App\Models\General\Body\Tv;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TvChannel extends Model
{
    use HasFactory, SoftDeletes;

    public function category()
    {
        return $this->belongsTo(TvChannelCategory::class, 'category_id');
    }
}
