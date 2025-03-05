<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgrammingQuestions extends Model
{
    protected $table = 'programming_questions';
    protected $guarded = [];    

    public function programming_category(){
        return $this->belongsTo(ProgrammingCategory::class, 'programming_cat_id', 'id');
    }
}
