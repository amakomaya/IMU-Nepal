<?php


namespace App\Models;


use App\Support\Dataviewer;
use Illuminate\Database\Eloquent\Model;

class PaymentCase extends Model
{
    use Dataviewer;

    protected $table='payment_cases';
    protected $dates = ['register_date_en'];

    protected $fillable = [
    'id', 'name', 'age', 'gender', 'phone', 'address', 'health_condition', 'is_death',
    'remarks', 'lab_name', 'lab_id', 'is_in_imu', 'hp_code', 'created_at', 'updated_at',
        'register_date_en', 'register_date_np', 'hospital_register_id', 'date_of_outcome', 'date_of_outcome_en',
        'health_condition_update' , 'method_of_diagnosis', 'age_unit'
        ];

    protected $orderable = ['name', 'age', 'created_at', 'register_date_en'];

    protected $allowedFilters = [
        'name', 'age',
        'created_at',
        'register_date_en',
        'phone'
    ];
}