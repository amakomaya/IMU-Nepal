<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CictCloseContact extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'cict_id', 'case_id', 'parent_case_id', 'hp_code', 'checked_by', 
        'name', 'age', 'age_unit', 'sex',
        'phone', 'relationship', 'relationship_others', 'contact_type'
    ];
}
