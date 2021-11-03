<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTestOld extends Model
{
    protected $connection = 'mysqldump';

    use SoftDeletes;

    protected $table = 'lab_tests';

    protected $dates =['deleted_at'];

    protected $fillable = ['token', 'status','created_at','updated_at',
        'sample_recv_date', 'sample_test_date', 'sample_test_time',
        'sample_test_result', 'checked_by', 'checked_by_name', 'sample_token', 'org_code', 'regdev'
    ];

    protected $appends = ['formated_token'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function getFormatedTokenAttribute()
    {
        return explode('-', $this->token,2)[1] ?? $this->token;
    }
}
