<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CountryPhoneCode extends Model
{
    protected $table='countries_phone_code';
    protected $guarded = [];
    public $timestamps = false;
}
