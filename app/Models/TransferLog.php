<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferLog extends Model
{
    protected $table = 'transfer_logs';

    protected $fillable = ['name', 'token', 'from', 'to', 'created_at','updated_at'];
}
