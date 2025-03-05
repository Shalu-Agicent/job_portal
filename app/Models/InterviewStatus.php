<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewStatus extends Model
{
    protected $table = "interview_status";

    public function applicants(){
        return $this->hasMany(JobApplicant::class,'status');
    }

}
