<?php

namespace App\Models;

use App\Support\Dataviewer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class SuspectedCase extends Model
{
    use Notifiable;
    use Dataviewer;
    use SoftDeletes;

    public static $caste = [
        '1' => 'दलित',
        '2' => 'जनजाति',
        '3' => 'मधेसी',
        '4' => 'मुस्लिम',
        '5' => 'ब्राह्मण / क्षेत्री',
        '6' => 'अन्य',
    ];
    protected $table = 'suspected_cases';
    protected $fillable = [
        'id', 'token', 'name', 'age', 'age_unit', 'province_id', 'district_id',
        'municipality_id', 'hp_code', 'tole', 'ward', 'caste', 'registered_device',
        'created_by', 'longitude', 'latitude', 'status', 'created_at', 'updated_at',
        'deleted_at', 'sex', 'symptoms', 'travelled', 'travelled_date', 'travel_medium',
        'travel_detail', 'travelled_where', 'occupation', 'symptoms_specific',
        'symptoms_comorbidity', 'symptoms_comorbidity_specific', 'screening',
        'screening_specific', 'emergency_contact_one', 'emergency_contact_two',
        'cases', 'case_where', 'end_case', 'payment', 'result', 'case_id', 'parent_case_id',
        'symptoms_recent', 'symptoms_within_four_week', 'symptoms_date', 'case_reason',
        'temperature', 'date_of_onset_of_first_symptom', 'reson_for_testing', 'case_type',
        'nationality','id_card_detail', 'id_card_issue', 'name_of_poe','covid_vaccination_details',
        'nearest_contact' ,
        'register_date_en' , 'register_date_np', 'malaria',
        'id_card_type', 'id_card_type_other', 'dose_details'
    ];
    protected $dates = ['deleted_at'];
    protected $allowedFilters = [
        'name', 'age',
        'created_at',
        'emergency_contact_one',
        'case_id',
        'register_date_en',
        // nested
        'sample_collection.service_for',
        'sample_collection.created_at', 'sample_collection.token' , 'sample_collection.updated_at' ,
        'sample_collection.collection_date_en',
        'sample_collection.received_date_en',
        'sample_collection.sample_test_date_en',
        'sample_collection.reporting_date_en'
    ];
    protected $appends = ['formated_age_unit', 'formated_gender'];
    // protected $appends = ['anc_with_protocol', 'anc_visits'];
    protected $orderable = ['name', 'age', 'lmp_date_en', 'created_at', 'register_date_en'];

    protected $supportedRelations = ['sampleCollection', 'latestAnc', 'healthworker' ,'healthpost', 'district', 'municipality'];

    public function scopeWithAll($query)
    {
        return $query->with($this->supportedRelations);
    }

    public function getFillable()
    {
        return $this->fillable;
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeGetNotifiableByToken($query, $token)
    {
        return $query->where('token', $token)->first();
    }

    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function registerBy()
    {
        return $this->hasOne('App\Models\OrganizationMember', 'token', 'created_by');
    }
    public function sampleCollection()
    {
        return $this->hasMany('App\Models\SampleCollection', 'woman_token', 'token');
    }
    public function ancsWithLabReport()
    {
        return $this->hasMany('App\Models\SampleCollection', 'woman_token', 'token')->with('labreport');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'token', 'token');
    }

    public function healthpost()
    {
        return $this->hasOne('App\Models\Organization', 'hp_code', 'hp_code');
    }

    public function healthworker()
    {
        return $this->hasOne('App\Models\OrganizationMember', 'token', 'created_by');
    }
    
    public function latestAnc()
    {
        return $this->hasOne(SampleCollection::class, 'woman_token', 'token')->latest();
    }
   
    public function cictTracing()
    {
        return $this->hasOne('App\Models\CictTracing', 'woman_token', 'token');
    }

    public function caseManagement()
    {
        return $this->hasOne('App\Models\CaseManagement', 'woman_token', 'token');
    }

    public function clinicalParameter()
    {
        return $this->hasMany('App\Models\ClinicalParameter', 'woman_token', 'token');
    }

    public function contactTracing()
    {
        return $this->hasMany('App\Models\ContactTracing', 'woman_token', 'token');
    }

    public function laboratoryParameter()
    {
        return $this->hasMany('App\Models\LaboratoryParameter', 'woman_token', 'token');
    }

    public function symptomsRelation()
    {
        return $this->hasMany('App\Models\Symptoms', 'woman_token', 'token');
    }

    public function getFormatedAgeUnitAttribute()
    {
        return $this->ageUnitCheck($this->age_unit);
    }

    private function ageUnitCheck($data)
    {
        switch ($data) {
            case '1':
                return 'Months';
            case '2':
                return 'Days';
            default:
                return 'Years';
        }
    }

    public function getFormatedGenderAttribute()
    {
        switch ($this->sex) {
            case '1':
                return 'Male';
            case '2':
                return 'Female';
            default:
                return 'Don\'t Know';
        }
    }

    public function occupation($data)
    {
        switch ($data) {
            case '1':
                return 'Front Line Healthworker';

            case '2':
                return 'Doctor';

            case '3':
                return 'Nurse';

            case '4':
                return 'Police / Army';

            case '5':
                return 'Business / Industry';

            case '6':
                return 'Teacher / Student ( Education )';

            case '7':
                return 'Civil Servant';

            case '8':
                return 'Journalist';

            case '9':
                return 'Agriculture';

            case '10':
                return 'Transport / Delivery';

            default:
                return 'Other';
        }
    }

    public function caste($data)
    {
        switch ($data) {
            case '1':
                return 'Dalit';

            case '2':
                return 'Janajati';

            case '3':
                return 'Madhesi';

            case '4':
                return 'Muslim';

            case '5':
                return 'Brahmin / Chhetri';

            case '6':
                return 'Other';

            default:
                return 'Dnt\'t Know';
        }
    }



    public function scopeActivePatientList($query)
    {
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->whereNotIn('sample_collection.result', [ '3', '4', '9']);
        })->doesntHave('latestAnc', 'or');
    }

    public function scopePassivePatientList($query)
    {
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', '4');
        });
    }

    public function scopePositivePatientList($query)
    {
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', '3');
        });
    }

    public function scopeLabReceivedList($query)
    {
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', '9')->whereHas('labReport');
        });
    }
    public function scopeLabAddReceived($query)
    {
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', '9')->whereHas('labReport');
        });
    }

    public function scopeLabAddReceivedNegative($query)
    {
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', '4')->whereHas('labReport');
        });
    }

    public function scopeLabAddReceivedPositive($query)
    {
        return $query->whereHas('latestAnc', function ($latest_anc_query) {
            $latest_anc_query->where('result', '3')->whereHas('labReport');
        });
    }

    public function scopeCasesRecoveredList($query)
    {
        return $query->where('end_case', '1');
    }

    public function scopeCasesDeathList($query)
    {
        return $query->where('end_case', '2');
    }

    public function getCreatedAtAttribute($date)
    {
        return $date;
    }
}
