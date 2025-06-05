<?php

namespace App\Models\General\Body\Hospitality;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Fnb extends Model
{
    use HasFactory, SoftDeletes;

    public function category()
    {
        return $this->belongsTo(FnbCategory::class, 'category_id');
    }
}
