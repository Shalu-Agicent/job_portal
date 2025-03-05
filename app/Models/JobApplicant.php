<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobApplicant extends Model
{
    protected  $guarded = [];

    public function Interview_status(){
        return $this->belongsTo(InterviewStatus::class,'status');
    }

}
