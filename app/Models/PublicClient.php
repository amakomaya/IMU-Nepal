<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicClient extends Model
{
    protected $table = 'public-client';
    protected $fillable = ['name', 'caste', 'gender', 'date_of_birth', 'age', 'phone', 'nationality', 'card_no',
        'occupation', 'province_id', 'district_id', 'municipality_id', 'ward', 'tole', 'email_address', 'first_vaccinated_date',
        'second_vaccinated_date', 'status','updated_at','created_at'];
}