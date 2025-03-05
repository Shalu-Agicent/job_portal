<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqCategory extends Model
{
    protected $table = 'mcq_category_master';
    protected $guarded = [];

    public function mcqs() // use a plural method name for a hasMany relationship
    {
        return $this->hasMany(McqMaster::class, 'mcq_category_id', 'id');
    }
}
