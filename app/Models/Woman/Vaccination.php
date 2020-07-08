<?php

namespace App\Models\Woman;

use Illuminate\Database\Eloquent\Model;

class Vaccination extends Model
{
    protected $table = 'woman_vaccinations';

    protected $fillable = ['token', 'woman_token','vaccine_name','vaccine_reg_no','hp_code','vaccine_type','vaccinated_date_en','vaccinated_date_np','no_of_pills','status'];

    protected $dates = ['created_at', 'updated_at'];

    public function woman()
    {
        return $this->belongsTo('App\Models\Woman', 'woman_token', 'token');
    }

    public function scopeFromToDate($query, $from, $to)
    {
        return $query->whereBetween('vaccinated_date_en', [$from, $to]);
    }

    public function getVaccineTypeName($vaccine_type)
    {
        switch ($vaccine_type) {
            case 0:
            return 'टी.डी. भ्याक्सिन';
                break;
            case 1:
            return 'आइरन चक्की';
                break;
            case 2:
            return 'जुकाको औषधी';
                break;
            case 3:
            return 'भिटामिन क्याप्सुल';
                break;
        }
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeVitaminA($query){
        return $query->where('vaccine_type', 3);
    }

    public function scopeTdVaccine($query){
        return $query->where('vaccine_type', 0);
    }

    public function scopeTdRegNo($query)
    {
        return $query->where('vaccine_reg_no', '!=' ,'');
    }

    public function getVaccineDetailsForService($data){
        $vaccine_name = $this->getVaccineTypeName($data->vaccine_type);
        if ($data->vaccine_type == 1 ){
            $iron_capsule = $data->no_of_pills;
            return $vaccine_name. ', Visit Date : '. $data->vaccinated_date_np. ', No of iron pills :'. $iron_capsule;
        }
        return $vaccine_name. ', Visit Date : '. $data->vaccinated_date_np;
    }
}