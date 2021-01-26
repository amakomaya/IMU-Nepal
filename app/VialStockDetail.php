<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VialStockDetail extends Model
{
    protected $table = 'vaccine_vial_stocks';

    protected $fillable = ['token', 'name', 'hp_code', 'dose_in_stock', 'new_dose', 'reuseable_dose', 'vaccination_ending_at',
        'status', 'created_at', 'updated_at', 'return_date_np', 'return_date_en', 'return_dose'];
}
