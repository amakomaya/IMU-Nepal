<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FrontPage extends Model
{
    protected $fillable = [
        'title', 'description', 'status'
    ];
}
