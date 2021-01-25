<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class VaccinationRecord extends Model
{
    protected $dates =['vaccination_records'];

    protected $fillable = ['token', 'vaccinated_id', 'hp_code', 'vaccine_name', 'vaccine_period',
        'vaccinated_date_en', 'vaccinated_date_np', 'vaccinated_address', 'vial_image', 'created_at',
        'updated_at', 'deleted_at'
    ];

    public function healthProfessional()
    {
        return $this->belongsTo('App\Models\HealthProfessional', 'vaccinated_id', 'id');
    }
}