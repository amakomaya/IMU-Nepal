<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BabyWeight extends Model
{
    use SoftDeletes;

    protected $fillable = ['hp_code', 'token', 'baby_token', 'weight', 'weighed_date', 'status', 'created_at', 'updated_at'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}
