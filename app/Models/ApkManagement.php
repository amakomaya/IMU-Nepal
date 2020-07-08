<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApkManagement extends Model
{
    protected $table = 'apk_management';

    protected $fillable = ['name','is_in_google_play','apk_path'];

}
