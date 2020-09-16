<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTest extends Model
{
    use SoftDeletes;

    protected $table = 'lab_tests';

    protected $dates =['deleted_at'];

    protected $fillable = ['token','test_date','woman_token','urine_protin','urine_sugar','blood_sugar','hbsag','vdrl','retro_virus','other', 'status','created_at','updated_at',

        'sample_recv_date', 'sample_test_date', 'sample_test_time', 'sample_test_result', 'checked_by', 'td_reg_no', 'checked_by_name'
];

    public function woman()
    {
        return $this->belongsTo('App\Models\Woman','woman_token','token');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFromToDate($query, $from, $to)
    {
        return $query->whereBetween('test_date', [$from, $to]);
    }

}
