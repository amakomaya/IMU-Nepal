<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SyncLogs extends Model
{
    protected $dates =['sync_logs'];

    protected $fillable = ['data','completed_at','created_at','updated_at'];
}
