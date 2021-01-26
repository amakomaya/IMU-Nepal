<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VialDetail extends Model
{
    protected $table = 'vial_details';

    protected $fillable = ['hp_code', 'vaccine_name', 'vial_image', 'maximum_doses', 'vial_used_doses', 'vial_wastage_doses', 'vial_damaged_reason',
        'vial_opened_date', 'status', 'created_at', 'updated_at'];
}
