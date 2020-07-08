<?php

namespace App\Models\Woman;

use Illuminate\Database\Eloquent\Model;
use App\Models\Woman;
use App\Models\HealthWorker;
use App\Models\BabyDetail;
use App\Models\BabyWeight;
use App\Models\Anc;
use App\Models\Pnc;
use App\Models\LabTest;
use App\Models\VaccinationRecord;
use App\Models\Delivery;
use App\Models\VaccineVialStock;
use App\Models\Aefi;
use DB;
use Yagiten\Nepalicalendar\Calendar;
use App\Models\Woman\Vaccination;

class WomanInfoForContainApp extends Model
{
    public function getGeneralInfo($token){

        $woman = Woman::where('token', $token)->get()->first();

        $guest_user = \App\Models\Woman\WomanRegisterApp::where('token', $token)->get()->first();

        $woman_token =  $woman->token ?? $guest_user->token;
        $full_name = $woman->name ?? $guest_user->name;
        $age = $woman->age ?? $guest_user->age;
        try {
            $no_of_pregnant_before = isset($guest_user->mis_data) ? json_decode($guest_user->mis_data)->no_of_pregnant_before : '';
        } catch (\Exception $e) {
            $no_of_pregnant_before = 0;
        }
        $height = $woman->height ?? '';
        $district = $woman->district_id ?? $guest_user->district_id;
        $municipality = $woman->municipality_id ?? $guest_user->municipality_id;
        $ward = $woman->ward ?? $guest_user->ward_no;
        $tole = $woman->tole ?? $guest_user->tole;
        $phone = $woman->phone ?? $guest_user->phone;
        $bloodGroup = isset($woman) ? $woman->getBloodGroup($woman->blood_group) : '';
        $husband_name = $woman->husband_name ?? '';
        $lmp_date_en = $woman->lmp_date_en ?? $guest_user->lmp_date_en;
        $lmp_date_np = isset($woman) ? $woman->getLMPNP($woman->lmp_date_en) : $guest_user->lmp_date_en;
        $hp_code = isset($woman) ? $woman->hp_code : '';
        $health_post = isset($woman) ? $woman->getHealthpost($woman->hp_code) : '';
        $hp_district = isset($woman) ? $woman->getHealthPostInfo($woman->hp_code)->district_id : 0;
        $hp_municipality_name = isset($woman) ? $woman->getHealthPostInfo($woman->hp_code)->municipality_id : 0;
        $hp_ward = isset($woman) ? $woman->getWard($woman->hp_code) : 0;

        $chronic_illness = isset($guest_user->mis_data) ? json_decode($guest_user->mis_data)->chronic_illness : '';
        $current_healthpost = isset($guest_user->mis_data) ? json_decode($guest_user->mis_data)->current_healthpost : '';

        $mool_darta_no = $woman->mool_darta_no ?? '';
        $sewa_darta_no = $woman->sewa_darta_no ?? '';
        $orc_darta_no = $woman->orc_darta_no ?? '';
        $healthWorkerFullName = isset($woman) ? HealthWorker::findHealthWorkerByToken($woman->created_by) : '';
        $healthWorkerPost = isset($woman) ? HealthWorker::findHealthWorkerPostByToken($woman->created_by) : '';
        $healthWorkerPhone = isset($woman) ? HealthWorker::findHealthPhoneByToken($woman->created_by) : '';

        try {
            $data = json_decode($guest_user->mis_data);
            $height = $data->height ?? '';
            $bloodGroup = $data->bloodGroup ?? '';
            $husband_name = $data->husband_name ?? '';
        } catch (\Exception $e) {
                       
        }

        $generalInfo = [ 
                        'token' => $woman_token,
                        'name' => $full_name,
                        'no_of_pregnant_before' => $no_of_pregnant_before ?? '',
                        'age' => $age,        
                        'height' => $height,        
                        'district_id' => $district,        
                        'municipality_id' => $municipality,        
                        'ward' => $ward ?? '',        
                        'tole' => $tole,        
                        'phone' => $phone,        
                        'bloodGroup' => $bloodGroup,        
                        'husband_name' => $husband_name,
                        'lmp_date_en' => $lmp_date_en,
                        'lmp_date_np' => $lmp_date_np,
                        'hp_code' => $hp_code,
                        'healthpost_name' => $health_post,
                        'hp_district' => $hp_district,
                        'hp_municipality' => $hp_municipality_name,
                        'hp_ward' => $hp_ward,
                        'chronic_illness' => $chronic_illness ?? '',
                        'current_healthpost' => $current_healthpost ?? '',
                        'mool_darta_no' => $mool_darta_no,
                        'sewa_darta_no' => $sewa_darta_no,
                        'orc_darta_no' => $orc_darta_no,
                        'health_worker_full_name' => $healthWorkerFullName,                            
                        'health_worker_post' => $healthWorkerPost,                            
                        'health_worker_phone' => $healthWorkerPhone,   
                    ];
        return $generalInfo; 
    }

    public function updateWomanInfo($data)
    {
        try {
            if (starts_with($data['token'], 'guest-')) {
                $row = WomanRegisterApp::where('token', $data['token'])->first();
                if(isset($row)) { 
                    $data['mis_data'] = json_encode($data);
                    $data['ward_no'] = $data['ward'];
                    $row->update($data); 
                    return ['message'=>'Woman information updated'];
                }
                return ['message' => 'Woman with token = '.$data['token'].' not found.'];
            }
            $row = Woman::where('token', $data['token'])->first();
            if(isset($row)){
                $data['mis_data'] = json_encode($data);
                $row->update($data);
                return ['message'=>'Woman information updated'];
            }
            return ['message' => 'Woman with token = '.$data['token'].' not found.'];
        } catch (\Exception $e) {
            return ['message'=>'Unable to update record.'];
        }
    }

    public function getWomanVaccinationInfo($token)
    {
        $collection = Vaccination::where('woman_token', $token)->latest()->get();
        $changed = $collection->map(function ($value, $key) {
            $value['vaccine_type'] = $value->getVaccineTypeName($value['vaccine_type']);
            return $value;
        });

        $subset = $changed->map(function ($user) {
            return $user->only(['token', 'woman_token','vaccine_reg_no','vaccine_type','vaccinated_date_en','vaccinated_date_np', 'no_of_pills', 'hp_code','created_at']);
        });
         
        return $subset;
    }

    public function getANCInfo($token){
        $collection = Anc::where('woman_token', $token)->latest()->get();
        // return $ancs;
        $changed = $collection->map(function ($value, $key) {
            $date_array = explode("-", $value['visit_date']);
            $value['visit_date'] = Calendar::eng_to_nep($date_array[0],$date_array[1],$date_array[2])->getYearMonthDayEngToNep();
            $value['checked_by'] = HealthWorker::findHealthWorkerByToken($value['checked_by']);
            $value['other'] = ucfirst($value['other']);
            if ($value['next_visit_date'] != NULL) {
                $next_visit_date_array = explode("-", $value['next_visit_date']);
                $value['next_visit_date'] = Calendar::eng_to_nep($next_visit_date_array[0],$next_visit_date_array[1],$next_visit_date_array[2])->getYearMonthDayEngToNep();
            }
            if( $value['worm_medicine'] == 1 ){ $value['worm_medicine'] = 'Yes'; }else{ $value['worm_medicine'] = 'No'; }
            return $value;
        });

        $subset = $changed->map(function ($user) {
            return $user->only(['token','woman_token','weight','anemia','swelling','blood_pressure','uterus_height', 'baby_presentation', 'baby_heart_beat','other','visit_date', 'checked_by', 'next_visit_date','situation']);
        });
         
        return $subset;
    }

    public function getDeliveryInfo($token)
    {
        $collection = Delivery::where('woman_token', $token)->get();
        $changed = $collection->map(function ($value, $key) {
            $date_array = explode("-", $value['delivery_date']);
            $value['delivery_date'] = Calendar::eng_to_nep($date_array[0],$date_array[1],$date_array[2])->getYearMonthDayEngToNep();
            $value['delivery_by'] = HealthWorker::findHealthWorkerByToken($value['delivery_by']);
            $date_array = explode(",", $value['complexity']);
            $data_complexity = array('Excess Bloodflow', '12 Hrs Labor pain', 'Placental Retention');
            $value['complexity'] = array_combine($data_complexity, $date_array);
            return $value;
        });

        $subset = $changed->map(function ($user) {
            return $user->only(['token','woman_token','delivery_date','delivery_time','delivery_place','presentation', 'delivery_type', 'complexity','other_problem','advice','delivery_by']);
        });
         
        return $subset;
    }

    public function getPncInfo($token)
    {
        $collection = Pnc::where('woman_token', $token)->latest()->get();
        $changed = $collection->map(function ($value, $key) {
            $date_array = explode("-", $value['date_of_visit']);
            $value['date_of_visit'] = Calendar::eng_to_nep($date_array[0],$date_array[1],$date_array[2])->getYearMonthDayEngToNep();
            $value['checked_by'] = HealthWorker::findHealthWorkerByToken($value['checked_by']);
            return $value;
        });

        $subset = $changed->map(function ($user) {
            return $user->only(['token','woman_token','date_of_visit','visit_time','mother_status','baby_status','advice','family_plan', 'checked_by']);
        });
         
        return $subset;
    }

    public function getLabTestInfo($token)
    {
        $collection = LabTest::where('woman_token', $token)->latest()->get();
        $changed = $collection->map(function ($value, $key) {
        if ($value['test_date'] != NULL) {
            $date_array = explode("-", $value['test_date']);
            $value['test_date'] = Calendar::eng_to_nep($date_array[0],$date_array[1],$date_array[2])->getYearMonthDayEngToNep();
            }
        return $value;
        });

        $subset = $changed->map(function ($user) {
            return $user->only(['token','woman_token','test_date','hb','albumin','urine_protein','urine_sugar','blood_sugar','hbsag','vdrl','retro_virus','other']);
        });
        return $subset;
    }

    public function getBabyDetails($token)
    {
        $collection = BabyDetail::where('delivery_token', $token)->orWhere('token', $token)->latest()->get();
        $changed = $collection->map(function ($value, $key) {
            $value['baby_name'] = title_case($value['baby_name']);

            if ($value['baby_status']) {
                $date_array = explode(",", $value['baby_status']);
                $baby_status = array('Healthy', 'Cried after birth', 'Suffocating', 'Disabled');
                $value['baby_status'] = array_combine($baby_status, $date_array);           
            }

            return $value;
        });
        $subset = $changed->map(function ($user) {
            return $user->only(['token','delivery_token','baby_name','gender','weight','premature_birth','baby_alive','baby_status','advice','card_no']);
        });
         
        return $subset;
    }
    
    public function babyVaccination($token)
    {
        $collection = VaccinationRecord::where('baby_token', $token)->latest()->get();
        $changed = $collection->map(function ($value, $key) {
            return $value;
        });

        $subset = $changed->map(function ($user) {
            return $user->only(['token', 'vaccine_name','vaccine_period','vaccinated_date_np','vaccinated_address']);
        });
         
        return $subset;
    }  

    public function babyWeight($token)
    {
        $collection = BabyWeight::where('baby_token', $token)->latest()->get();
        $changed = $collection->map(function ($value, $key) {
            return $value;
        });

        $subset = $changed->map(function ($user) {
            return $user->only(['token', 'weight','weighed_date']);
        });
        return $subset;
    }  

    public function babyAefi($token)
    {
        $collection = Aefi::where('baby_token', $token)->latest()->get();
        $changed = $collection->map(function ($value, $key) {
                if ($value['vaccinated_date'] != NULL) {
                $date_array = explode("-", $value['vaccinated_date']);
                $value['vaccinated_date'] = Calendar::eng_to_nep($date_array[0],$date_array[1],$date_array[2])->getYearMonthDayEngToNep();
                }
                if ($value['aefi_date'] != NULL) {
                $date_array = explode("-", $value['aefi_date']);
                $value['aefi_date'] = Calendar::eng_to_nep($date_array[0],$date_array[1],$date_array[2])->getYearMonthDayEngToNep();
                }
            return $value;
        });

        $subset = $changed->map(function ($user) {
            return $user->only(['token', 'vaccine','vaccinated_date','aefi_date','problem','advice']);
        });
        return $subset;
    }
}