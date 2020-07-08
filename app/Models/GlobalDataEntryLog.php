<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GlobalDataEntryLog extends Model
{
    protected $table = 'global_data_entry_log';

    protected $fillable = ['token','type','hp_code'];

}
