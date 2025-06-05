<?php

namespace App\Models\General\Body\Messages;

use App\Models\GeneralStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegularMessage extends Model
{
    use HasFactory, SoftDeletes;

    // public function transferTaskAssignees()
    // {
    //     return $this->hasMany(TransferTaskAssignee::class);
    // }

    public function type()
    {
        return $this->belongsTo(GeneralStatus::class, 'type_id');
    }
}
