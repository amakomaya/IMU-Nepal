<?php


namespace App\Models;


use App\Support\Dataviewer;
use Illuminate\Database\Eloquent\Model;

class PaymentCase extends Model
{
    use Dataviewer;

    protected $table='payment_cases';

    protected $fillable = [
    'id', 'name', 'age', 'gender', 'phone', 'address', 'health_condition', 'is_death',
    'remarks', 'lab_name', 'lab_id', 'is_in_imu', 'hp_code', 'created_at', 'updated_at'
        ];

    protected $orderable = ['name', 'age', 'created_at'];

    protected $allowedFilters = [
        'name', 'age',
        'created_at',
        'phone'
    ];
}