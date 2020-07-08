<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HIVSyphillisTest extends Model
{
    use SoftDeletes;

    protected $table = 'hiv_syphillis_tests';

    protected $dates =['deleted_at'];

    protected $fillable = ['token','woman_token','counselling_date','hiv_test_date',
                                'hiv_status','partner_hiv_status', 'partner_referred', 'result_recieved','syphillis_status','hp_code',
                                'syphillis_treated', 'syphillis_tested', 'art_started_date' , 'status','created_at','updated_at'];

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function woman(){
        return $this->belongsTo('App\Models\Woman', 'woman_token', 'token');
    }

    public function scopeByHpcode($query, $hp_code){
        return $query->where('hp_code', $hp_code);
    }
}