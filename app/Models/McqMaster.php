<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class McqMaster extends Model
{
    protected $table = 'mcq_master';
    protected $guarded = [];

    public function category() // use singular method name for a belongsTo relationship
    {
        return $this->belongsTo(McqCategory::class, 'mcq_category_id', 'id');
    }
}
