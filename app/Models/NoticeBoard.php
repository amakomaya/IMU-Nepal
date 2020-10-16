<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NoticeBoard extends Model
{
    protected $table='notices_board';
    protected $fillable = ['title','description','type','created_at','updated_at'];
}
