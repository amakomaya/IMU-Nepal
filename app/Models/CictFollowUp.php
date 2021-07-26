<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CictFollowUp extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'case_id', 'token', 'parent_case_id', 'woman_token', 'hp_code', 'checked_by',
        'date_of_follow_up_0', 'no_symptoms_0', 'fever_0', 'runny_nose_0', 'cough_0', 'sore_throat_0', 'breath_0', 'symptoms_other_0',
        'date_of_follow_up_1', 'no_symptoms_1', 'fever_1', 'runny_nose_1', 'cough_1', 'sore_throat_1', 'breath_1', 'symptoms_other_1',
        'date_of_follow_up_2', 'no_symptoms_2', 'fever_2', 'runny_nose_2', 'cough_2', 'sore_throat_2', 'breath_2', 'symptoms_other_2',
        'date_of_follow_up_3', 'no_symptoms_3', 'fever_3', 'runny_nose_3', 'cough_3', 'sore_throat_3', 'breath_3', 'symptoms_other_3',
        'date_of_follow_up_4', 'no_symptoms_4', 'fever_4', 'runny_nose_4', 'cough_4', 'sore_throat_4', 'breath_4', 'symptoms_other_4',
        'date_of_follow_up_5', 'no_symptoms_5', 'fever_5', 'runny_nose_5', 'cough_5', 'sore_throat_5', 'breath_5', 'symptoms_other_5',
        'date_of_follow_up_6', 'no_symptoms_6', 'fever_6', 'runny_nose_6', 'cough_6', 'sore_throat_6', 'breath_6', 'symptoms_other_6',
        'date_of_follow_up_7', 'no_symptoms_7', 'fever_7', 'runny_nose_7', 'cough_7', 'sore_throat_7', 'breath_7', 'symptoms_other_7',
        'date_of_follow_up_8', 'no_symptoms_8', 'fever_8', 'runny_nose_8', 'cough_8', 'sore_throat_8', 'breath_8', 'symptoms_other_8',
        'date_of_follow_up_9', 'no_symptoms_9', 'fever_9', 'runny_nose_9', 'cough_9', 'sore_throat_9', 'breath_9', 'symptoms_other_9',
        'date_of_follow_up_10', 'no_symptoms_10', 'fever_10', 'runny_nose_10', 'cough_10', 'sore_throat_10', 'breath_10', 'symptoms_other_10',
        'high_exposure', 'completion_date'
    ];

    public function checkedBy()
    {
        return $this->belongsTo(OrganizationMember::class, 'checked_by', 'token');
    }
}
