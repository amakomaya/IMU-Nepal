<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyUser extends Model
{
    protected $dates =['survey_users'];

    protected $fillable = ['username', 'password', 'is_provience', 'is_district', 'is_municipality', 'is_ward'];
    
}
