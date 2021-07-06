<?php

namespace App\Http\Controllers\External;

use App\Http\Controllers\Controller;
use App\Models\LabTest;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Models\Municipality;
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

    public function testValidEnDate($date){
      if($date) {
        if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
        {
          $year = $parts[1];
          $month = $parts[2];
          $day = $parts[3];
          if (checkdate($month ,$day, $year)) {
            if((int)$year <= Carbon::now()->year) {
              return true;
            }
          }

        }
      }
      return false;
    }
  
    public function testValidEnDateTime($dateTime) {
      $date = Carbon::parse($dateTime)->toDateString();
      return $this->testValidEnDate($date);
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
                if(empty($value['registered_at'])){
                    $value['registered_at'] = $value['sample_collected_date'];
                }
                if(!is_numeric($value['municipality_id']) || ((int)$value['municipality_id']<1 || (int)$value['municipality_id']>754) ) {
                  //Put default id

                  $organization = Organization::where('hp_code', $healthworker->hp_code)->get()->first();
                  $value['province_id'] = $organization->province_id;
                  $value['district_id'] = $organization->district_id;
                  $value['municipality_id'] = $organization->municipality_id;
                  //DB::rollback();
                  // return response()->json(['message' => 'The data couldnot be uploaded due to following errors: Invalid Municipality ID. Error at index:'.$index ]);
                } else {
                  $municipality = Municipality::where('id', $value['municipality_id'])->get()->first();
                  // dd($municipality);
                  $value['province_id'] = $municipality->province_id;
                  $value['district_id'] = $municipality->district_id;
                }
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
                    'travelled' => $value['travelled']??'0',
                    'occupation' => $value['occupation']??0,
                    'status' => 1,
                    'hp_code' => $healthworker->hp_code,
                    'created_by' => $healthworker->token,
                    'checked_by_name' => $healthworker->name,
                    'case_id' => $healthworker->id . '-' . ctype_upper(bin2hex(random_bytes(3))),
                    'registered_device' => 'api',
                    'register_date_en' => $value['registered_at'],
                    'register_date_np' => $this->ad2bs($value['registered_at'])
                ];
                $id = OrganizationMember::where('token', $user->token)->first()->id;
                $swab_id = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon::now()->format('ymd') . '-' . $this->convertTimeToSecond(Carbon::now()->addSeconds($index)->format('H:i:s'));
                $update = false;
                $existingSampleCollection = $existingSuspectedCase = $existingLabTest = '';
                
                // if(!is_numeric($value['province_id']) || ((int)$value['province_id']<1 || (int)$value['province_id']>7) ) {
                //   DB::rollback();
                //   return response()->json(['message' => 'The data couldnot be uploaded due to following errors: Invalid Province ID. Error at index:'.$index ]);
                // }
                // if(!is_numeric($value['district_id']) || ((int)$value['district_id']<1 || (int)$value['district_id']>77) ) {
                //   DB::rollback();
                //   return response()->json(['message' => 'The data couldnot be uploaded due to following errors: Invalid District ID. Error at index:'.$index ]);
                // }
                
                if(!in_array(strval($value['lab_result']), ['3', '4'])) {
                  DB::rollback();
                  return response()->json(['message' => 'The data couldnot be uploaded due to following errors: Invalid Lab Result. Need value 3 For Positive, 4 For Negative. Error at index:'.$index ]);
                }
                $isValidLabDate = $this->testValidEnDate($value['lab_test_date']);
                if(!$isValidLabDate) {
                  DB::rollback();
                  return response()->json(['message' => 'Invalid Lab Test Date. Please send a valid english date in YYYY-MM-DD format as mentioned in API documentation. Error at index:'.$index  ]);
                }
                $isValidSampleCollectedDate = $this->testValidEnDate($value['sample_collected_date']);
                if(!$isValidSampleCollectedDate) {
                  DB::rollback();
                  return response()->json(['message' => 'Invalid Sample Collected Date. Please send a valid english date in YYYY-MM-DD format as mentioned in API documentation. Error at index:'.$index ]);
                }
                $isValidRegDate = $this->testValidEnDateTime($value['registered_at']);
                if(!$isValidRegDate) {
                  DB::rollback();
                  return response()->json(['message' => 'Invalid Registered Date. Please send a valid english date in YYYY-MM-DD format as mentioned in API documentation. Error at index:'.$index ]);
                }
                $isValidLabReceivedDate = $this->testValidEnDate($value['lab_received_date']);
                if(!$isValidLabReceivedDate) {
                  DB::rollback();
                  return response()->json(['message' => 'Invalid Lab Received Date. Please send a valid english date in YYYY-MM-DD format as mentioned in API documentation. Error at index:'.$index ]);
                }
                if(array_key_exists('imu_swab_id', $value) && $value['imu_swab_id']) {
                  $existingSampleCollection = SampleCollection::where('checked_by', $user->token)->where('token', $value['imu_swab_id'])->first();
                  if($existingSampleCollection) {
                    $existingSuspectedCase = SuspectedCase::where('token', $existingSampleCollection->woman_token)->first();
                    $existingLabTest = LabTest::where('sample_token', $value['imu_swab_id'])->first();
                    $update = true;
                    $swab_id = $value['imu_swab_id'];
                  } else {
                    //Bypass invvalid swab id. Remove comment to restrict.
                    // DB::rollback();
                    // return response()->json(['message' => 'The data couldnot be uploaded due to following errors: This IMU Swab ID was not found in IMU System. Please enter valid swab ID to update record or leave it blank to create new record. Error at index:'.$index ]);
                  }
                }
                if(!$value['sample_type']) {
                  $sample_type = $value['sample_type'];
                } else {
                  $sample_type = (strpos($value['sample_type'], '[') !== false)?$value['sample_type']:'['.$value['sample_type'].']';
                }
                $randomLetter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 2);
                $singleRandomLabId = (int)$randomLabId+$index+1;
                $lab_id = array_key_exists('lab_id', $value) && $value['lab_id']?$value['lab_id']:str_pad($singleRandomLabId, 6, '0', STR_PAD_LEFT).'-'.$randomLetter;

                $reporting_date_en = explode("-", Carbon::now()->toDateString());
                $reporting_date_np = Calendar::eng_to_nep($reporting_date_en[0], $reporting_date_en[1], $reporting_date_en[2])->getYearMonthDayEngToNep();
                
                $sample = [
                    'token' => $swab_id,
                    'woman_token' => $case_token,
                    'service_for' => $value['service_for'],
                    'collection_date_en' => $value['sample_collected_date'],
                    'collection_date_np' => $this->ad2bs($value['sample_collected_date']),
                    'service_type' => $value['service_type'],
                    'sample_type' => $sample_type,
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
                    'received_by' => $healthworker->token,
                    'received_by_hp_code' => $healthworker->hp_code,
                    'reporting_date_en' => Carbon::now()->toDateTimeString(),
                    'reporting_date_np' => $reporting_date_np
                ];
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
                    'regdev' => 'api',
                ];
                try {
                    if(!$update) {
                      SuspectedCase::create($case);
                      SampleCollection::create($sample);
                      LabTest::create($lab_test);
                    } else {
                        unset($case['token']);
                        unset($case['hp_code']);
                        unset($case['created_by']);
                        unset($case['checked_by_name']);
                        unset($case['registered_device']);
                        unset($case['register_date_en']);
                        unset($case['register_date_np']);

                      unset($sample['woman_token']);
                      unset($sample['collection_date_en']);
                      unset($sample['collection_date_np']);
                      unset($sample['regdev']);
//                      unset($sample['reporting_date_en']);
//                      unset($sample['reporting_date_np']);
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
                    return response()->json(['message' => 'Error at index:'.$index.'.The data couldnot be uploaded due to following errors: \n '. $e->getMessage()]);
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
            return Calendar::nep_to_eng($date_np_array[0], $date_np_array[1], $date_np_array[2])->getYearMonthDayNepToEng();
        }catch (\Exception $e){
            return '';
        }
    }

    private function ad2bs($date){
        try{
            $parse_date = Carbon::parse($date)->toDateString();
            $date_en_array = explode("-", $parse_date);
            return Calendar::eng_to_nep($date_en_array[0], $date_en_array[1], $date_en_array[2])->getYearMonthDayEngToNep();
        }catch (\Exception $e){
            return '';
        }
    }
}