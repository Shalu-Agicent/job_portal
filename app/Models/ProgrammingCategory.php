<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class ProgrammingCategory extends Model
{
    protected $table = 'programming_categories';
    protected $guarded = [];


    protected function categoryName(): Attribute
    {
        return Attribute::make(
            set: fn (string $value) => ucwords($value),
        );
    }

    public function questions() // use a plural method name for a hasMany relationship
    {
        return $this->hasMany(ProgrammingQuestions::class, 'programming_cat_id');
    }
}
