<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yagiten\Nepalicalendar\Calendar;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ExtCaseController extends Controller
{
    public function index()
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $record = DB::table('women')
                ->join('ancs', 'ancs.woman_token', '=', 'women.token')
                ->join('lab_tests', 'lab_tests.sample_token', '=', 'ancs.token')
                ->where('women.hp_code', $healthworker->hp_code)
                ->select('women.token','women.name','women.age','women.age_unit','women.sex','women.caste','women.province_id','women.district_id','women.municipality_id','women.ward',
                    'women.tole','women.emergency_contact_one','women.travelled','women.occupation','women.created_at'
                    ,'ancs.token as sample_token', 'ancs.service_for', 'ancs.service_type','ancs.sample_type',
                    'ancs.infection_type','ancs.created_at as sample_created_at','lab_tests.sample_recv_date', 'lab_tests.sample_test_date','lab_tests.sample_test_time',
                    'lab_tests.sample_test_result')
                ->get();
            $data = collect($record)->map(function ($row) {
                $response['token'] = $row->token;
                $response['name'] = $row->name ?? '';
                $response['age'] = $row->age ?? '';
                $response['age_unit'] = $row->age_unit ?? 0;
                $response['sex'] = $row->sex ?? '';
                $response['caste'] = $row->caste ?? '';
                $response['province_id'] = $row->province_id ?? '';
                $response['district_id'] = $row->district_id ?? '';
                $response['municipality_id'] = $row->municipality_id ?? '';
                $response['ward'] = $row->ward ?? '';
                $response['tole'] = $row->tole ?? '';
                $response['emergency_contact_one'] = $row->emergency_contact_one ?? '';
                $response['travelled'] = $row->travelled ?? '';
                $response['occupation'] = $row->occupation ?? '';
                $response['registered_at'] = $row->created_at ?? '';
                $response['sample_token'] = $row->sample_token ?? '';
                $response['service_for'] = $row->service_for ?? '';
                $response['service_type'] = $row->service_type ?? '';
                $response['sample_type'] = $this->sampleType($row->sample_type) ?? '';
                $response['sample_collected_date'] = $row->sample_created_at ?? '';
                $response['infection_type'] = $row->infection_type ?? '';
                $response['lab_received_date'] = $this->bs2ad($row->sample_recv_date);
                $response['lab_test_date'] = $this->bs2ad($row->sample_test_date);
                $response['lab_test_time'] = $row->sample_test_time ?? '';
                $response['lab_result'] = $row->sample_test_result ?? '';

                return $response;
            })->values();

            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }

    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }

    public function store(Request $request)
    {
        $key = request()->getUser();
        $secret = request()->getPassword();

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();
        if (!empty($user)) {
            $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
            $latest_test = LabTest::where('checked_by', $user->token)->latest()->first();
            $randomLabId = '';
            if($latest_test) {
              $token = $latest_test->token;
              $tokenArray = explode('-', $token);
              if(count($tokenArray)>2) {
                $randomLabId = $tokenArray[1];
              }
            }
            if($randomLabId == '') {
              $randomLabId = '000000';
            }
            $data = json_decode($request->getContent(), true);
            DB::beginTransaction();
            foreach ($data as $index=>$value) {
                $case_token = 'a-' . md5(microtime(true) . mt_Rand());
                $case = [
                    'token' => $case_token,
                    'name' => $value['name'],
                    'age' => $value['age'],
                    'age_unit' => $value['age_unit'],
                    'caste' => $value['caste'],
                    'sex' => $value['sex'],
                    'province_id' => $value['province_id'],
                    'district_id' => $value['district_id'],
                    'municipality_id' => $value['municipality_id'],
                    'ward' => $value['ward'],
                    'tole' => $value['tole'],
                    'emergency_contact_one' => $value['emergency_contact_one'],
                    'travelled' => $value['travelled'],
                    'occupation' => $value['occupation'],
                    'created_at' => $value['registered_at'],
                    'status' => 1,
                    'hp_code' => $healthworker->hp_code,
                    'checked_by' => $healthworker->token,
                    'checked_by_name' => $healthworker->name,
                    'registered_device' => 'api'
                ];
                $id = OrganizationMember::where('token', $user->token)->first()->id;
                $swab_id = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon::now()->format('ymd') . '-' . $this->convertTimeToSecond(Carbon::now()->format('H:i:s'));
                
                $update = false;
                $existingSampleCollection = $existingSuspectedCase = $existingLabTest = '';
                if(!in_array(strval($value['lab_result']), ['3', '4'])) {
                  DB::rollback();
                  return response()->json(['message' => 'The data couldnot be uploaded due to following errors: Invalid Lab Result. Need value 3 For Positive, 4 For Negative' ]);
                }
                if(array_key_exists('imu_swab_id', $value) && $value['imu_swab_id']) {
                  $existingSampleCollection = SampleCollection::where('checked_by', $user->token)->where('token', $value['imu_swab_id'])->first();
                  if($existingSampleCollection) {
                    $existingSuspectedCase = SuspectedCase::where('token', $existingSampleCollection->woman_token)->first();
                    $existingLabTest = LabTest::where('sample_token', $value['imu_swab_id'])->first();
                    $update = true;
                    $swab_id = $value['imu_swab_id'];
                  } else {
                    DB::rollback();
                    return response()->json(['message' => 'The data couldnot be uploaded due to following errors: This IMU Swab ID was not found in IMU System. Please enter valid swab ID to update record or leave it blank to create new record' ]);
                  }
                }
                if(!$value['sample_type']) {
                  $sample_type = $value['sample_type'];
                } else {
                  $sample_type = (strpos($value['sample_type'], '[') !== false)?$value['sample_type']:'['.$value['sample_type'].']';
                }
                $sample = [
                    'token' => $swab_id,
                    'woman_token' => $case_token,
                    'service_for' => $value['service_for'],
                    'service_type' => $value['service_type'],
                    'sample_type' => $sample_type,
                    'created_at' => $value['sample_collected_date'],
                    'infection_type' => strval($value['infection_type']),
                    'result' => strval($value['lab_result']),
                    'hp_code' => $healthworker->hp_code,
                    'checked_by' => $healthworker->token,
                    'checked_by_name' => $healthworker->name,
                    'status' => 1,
                    'regdev' => 'api',
                    'lab_token' => $user->token.'-'.$lab_id,
                    'received_date_en' => $value['lab_received_date'],
                    'received_date_np' => $this->ad2bs($value['lab_received_date']),
                    'sample_test_date_en' => $value['lab_test_date'],
                    'sample_test_date_np' => $this->ad2bs($value['lab_test_date']),
                    'sample_test_time' => $value['lab_test_time'],
                ];
                $randomLetter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 2);
                $singleRandomLabId = (int)$randomLabId+$index+1;
                $lab_id = array_key_exists('lab_id', $value) && $value['lab_id']?$value['lab_id']:str_pad($singleRandomLabId, 6, '0', STR_PAD_LEFT).'-'.$randomLetter;
                $lab_test = [
                    'token' => $user->token.'-'.$lab_id,
                    'sample_token' => $swab_id,
                    'sample_recv_date' => $this->ad2bs($value['lab_received_date']),
                    'sample_test_date' => $this->ad2bs($value['lab_test_date']),
                    'sample_test_time' => $value['lab_test_time'],
                    'sample_test_result' => strval($value['lab_result']),
                    'hp_code' => $healthworker->hp_code,
                    'checked_by' => $healthworker->token,
                    'checked_by_name' => $healthworker->name,
                    'status' => 1,
                    'regdev' => 'api'
                ];
                try {
                    if(!$update) {
                      SuspectedCase::create($case);
                      SampleCollection::create($sample);
                      LabTest::create($lab_test);
                    } else {
                      unset($case['token']);
                      unset($sample['woman_token']);
                      $existingSuspectedCase->update($case);
                      $existingSampleCollection->update($sample);
                      if($existingLabTest) {
                        unset($lab_test['token']);
                        $existingLabTest->update($lab_test);
                      } else {
                        LabTest::create($lab_test);
                      }
                    }
                } catch (\Exception $e) {
                    DB::rollback();
                    return response()->json(['message' => 'The data couldnot be uploaded due to following errors: \n '. $e->getMessage()]);
                }
            }
            DB::commit();
            return response()->json(['message' => 'Data Successfully Sync']);
        }
        return ['message' => 'Authentication Failed'];
    }

    public function getCaseDetailBySample(Request $request)
    {
        $key = request()->getUser();
        $secret = request()->getPassword();
        $sample_token = $request->sample_token;

        $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();

        if (!empty($user)) {
            $record = DB::table('ancs')->where('ancs.token', $sample_token)
                ->join('women', 'women.token', '=', 'ancs.woman_token')
                ->join('lab_tests', 'lab_tests.sample_token', '=', 'ancs.token')
                ->select('women.token','women.name','women.age','women.age_unit','women.sex','women.caste','women.province_id','women.district_id','women.municipality_id','women.ward',
                    'women.tole','women.emergency_contact_one','women.travelled','women.occupation','women.created_at'
                    ,'ancs.token as sample_token', 'ancs.service_for', 'ancs.service_type','ancs.sample_type',
                    'ancs.infection_type','ancs.created_at as sample_created_at','lab_tests.sample_recv_date', 'lab_tests.sample_test_date','lab_tests.sample_test_time',
                    'lab_tests.sample_test_result')
                ->get();
            $data = collect($record)->map(function ($row) {
                $response['token'] = $row->token;
                $response['name'] = $row->name ?? '';
                $response['age'] = $row->age ?? '';
                $response['age_unit'] = $row->age_unit ?? 0;
                $response['sex'] = $row->sex ?? '';
                $response['caste'] = $row->caste ?? '';
                $response['province_id'] = $row->province_id ?? '';
                $response['district_id'] = $row->district_id ?? '';
                $response['municipality_id'] = $row->municipality_id ?? '';
                $response['ward'] = $row->ward ?? '';
                $response['tole'] = $row->tole ?? '';
                $response['emergency_contact_one'] = $row->emergency_contact_one ?? '';
                $response['emergency_contact_two'] = $row->emergency_contact_two ?? '';
                $response['travelled'] = $row->travelled ?? '';
                $response['occupation'] = $row->occupation ?? '';
                $response['registered_at'] = $row->created_at ?? '';
                $response['sample_token'] = $row->sample_token ?? '';
                $response['service_for'] = $row->service_for ?? '';
                $response['service_type'] = $row->service_type ?? '';
                $response['sample_type'] = $this->sampleType($row->sample_type);
                $response['sample_collected_date'] = $row->sample_created_at ?? '';
                $response['infection_type'] = $row->infection_type ?? '';
                $response['lab_received_date'] = $this->bs2ad($row->sample_recv_date);
                $response['lab_test_date'] = $this->bs2ad($row->sample_test_date);
                $response['lab_test_time'] = $row->sample_test_time ?? '';
                $response['lab_result'] = $row->sample_test_result ?? '';

                return $response;
            })->values();

            return response()->json($data);
        }
        return ['message' => 'Authentication Failed'];
    }

    private function sampleType($data): string
    {
        try{
            preg_match_all('!\d+!', $data, $matches);
            return collect($matches)->flatten()->implode(', ');
        }catch (\Exception $e){
            return '';
        }
    }

    private function bs2ad($date){
        try{
            $date_np_array = explode("-", $date);
            return Calendar::nep_to_eng($date_np_array[0], $date_np_array[1], $date_np_array[2])->getYearMonthDay();
        }catch (\Exception $e){
            return '';
        }
    }

    private function ad2bs($date){
        try{
            $date_en_array = explode("-", $date);
            return Calendar::eng_to_nep($date_en_array[0], $date_en_array[1], $date_en_array[2])->getYearMonthDay();
        }catch (\Exception $e){
            return '';
        }
    }
}