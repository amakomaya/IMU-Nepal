<?php

use App\Imports\CasesPaymentImport;
use App\Models\Organization;
use App\Models\SampleCollection;
use App\Models\CaseManagement;
use App\Models\ContactDetail;
use App\Models\ContactFollowUp;
use App\Models\ContactTracing;
use App\Models\OrganizationMember;
use App\Models\LaboratoryParameter;
use App\Models\LabTest;
use App\Models\SuspectedCase;
use App\Models\ProvinceInfo;
use App\Models\MunicipalityInfo;
use App\Models\PaymentCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yagiten\Nepalicalendar\Calendar;
use App\User;


Route::post('/user-manager/{id}/login-as', function ($id, Request $request)
{
    if($request->has('key')){
        $user = \App\User::where('token', $id)->first();
        $request->session()->put('user_show', true);
        Auth::login($user);
    }
});

Route::get('/data/aggregate', 'Data\Api\AggregateController@forMonitor');

Route::get('/v2/healthpost', 'Api\v2\HealthpostController@get');
Route::get('/v2/municipality-by-district', 'Api\v2\MunicipalityByDistrictController@get');

Route::get('analysis/v1/overview', 'Api\Analysis\v1\OverviewController@get');

// New Login
Route::post('/v2/amc/login', 'Api\LoginController@v2AmcLogin');
Route::post('/v3/amc/login', 'Api\LoginController@v3AmcLogin');


Route::get('/v1/healthposts', function () {
    $healthpost_query = \App\Models\Organization::with(['province', 'municipality', 'district']);
    if(Auth::user()->role == 'province') {
        $province_id = ProvinceInfo::where('token', Auth::user()->token)->first()->province_id;
        $healthpost_query->where('province_id', $province_id);
    }
    elseif(Auth::user()->role == 'municipality') {
        $municipality_id = MunicipalityInfo::where('token', Auth::user()->token)->first()->municipality_id;
        $healthpost_query->where('municipality_id', $municipality_id);
    }

    $healthpost = $healthpost_query->get();
    return response()->json($healthpost);
});

Route::get('/v1/healthposts-for-lab-and-hospital', function () {
    $healthpost = \App\Models\Organization::whereIn('hospital_type', [2,3,5,6])->with(['province', 'municipality', 'district'])->get();
    return response()->json($healthpost);
});

Route::get('/v1/suspected-cases', function (Request $request) {
    if($request->has('name')){
        $cases = \App\Models\SuspectedCase::where('name', 'like', '%' . $request->name . '%')->withAll()->latest()->take(10)->get();
        return response()->json($cases);
    }
    if($request->has('phone')){
        $cases = \App\Models\SuspectedCase::
        where('emergency_contact_one', 'like', '%' . $request->phone . '%')
        ->orWhere('emergency_contact_two', 'like', '%' . $request->phone . '%')
            ->withAll()->latest()->take(10)->get();
        return response()->json($cases);
    }
    if($request->has('sid')){
        $sid_to_caseid = SampleCollection::where('token', $request->sid)->first();
        if ($sid_to_caseid){
            $cases = \App\Models\SuspectedCase::where('token', $sid_to_caseid->woman_token)->withAll()->latest()->get();
            return response()->json($cases);
        }
        return response()->json([]);
    }
    return response()->json([]);
});

Route::get('/api/v1/check-by-sid-or-lab-id', function () {
    $healthpost = \App\Models\Organization::with(['province', 'municipality', 'district'])->get();
    return response()->json($healthpost);
});

Route::get('/api/v1/check-by-sid-or-lab-id-old', function () {
    $healthpost = \DB::connection('mysqldump')->table('healthposts')
        ->with(['province', 'municipality', 'district'])->get();
    return response()->json($healthpost);
});


Route::post('/v1/client', function (Request $request) {
    $data = $request->json()->all();
     try {
            SuspectedCase::insert($data);
        } catch (\Exception $e) {
         foreach ($data as $value) {
             try {
//                 $value['case_id'] = bin2hex(random_bytes(3));
                 SuspectedCase::create($value);
             } catch (\Exception $e) {
//                 return response()->json(['message' => 'Something went wrong, Please try again.']);
             }
         }
        }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/client', function (Request $request) {
    $hp_code = $request->hp_code;
    $record = \DB::table('women')
        ->leftJoin('ancs', 'ancs.woman_token', '=', 'women.token')
        ->where('women.hp_code', $hp_code)
        ->where('women.end_case', '0')
        ->select('women.*', 'ancs.result as sample_result')
        ->where('women.created_at', '>=', Carbon::now()->subDays(14)->toDateTimeString())
        ->where(function ($query) {
            $query->where('ancs.result', '!=', '4')
                ->orWhere('women.created_at', '>=', Carbon::now()->subDays(2)->toDateTimeString());
        })
        ->get();

    $data = collect($record)->map(function ($row) {

        $response = [];

        $response['token'] = $row->token;
        $response['name'] = $row->name ?? '';
        $response['age'] = $row->age ?? '';
        $response['sex'] = $row->sex ?? '';
        $response['caste'] = $row->caste ?? '';
        $response['province_id'] = $row->province_id ?? '';
        $response['district_id'] = $row->district_id ?? '';
        $response['municipality_id'] = $row->municipality_id ?? '';
        $response['ward'] = $row->ward ?? '';
        $response['tole'] = $row->tole ?? '';
        $response['chronic_illness'] = $row->chronic_illness ?? '';
        $response['symptoms'] = $row->symptoms ?? '';
        $response['travelled'] = $row->travelled ?? '';
        $response['travelled_date'] = $row->travelled_date ?? '';
        $response['travel_medium'] = $row->travel_medium ?? '';
        $response['travel_detail'] = $row->travel_detail ?? '';
        $response['travelled_where'] = $row->travelled_where ?? '';

        $response['hp_code'] = $row->hp_code ?? '';
        $response['registered_device'] = $row->registered_device ?? '';
        $response['created_by'] = $row->created_by ?? '';
        $response['longitude'] = $row->longitude ?? '';
        $response['latitude'] = $row->latitude ?? '';
        $response['status'] = $row->status ?? '';
        $response['created_at'] = $row->created_at ?? '';
        $response['updated_at'] = $row->updated_at ?? '';

        $response['age_unit'] = $row->age_unit ?? 0;
        $response['occupation'] = $row->occupation ?? '';

        $response['symptoms_specific'] = $row->symptoms_specific ?? '';
        $response['symptoms_comorbidity'] = $row->symptoms_comorbidity ?? '';
        $response['symptoms_comorbidity_specific'] = $row->symptoms_comorbidity_specific ?? '';
        $response['screening'] = $row->screening ?? '';
        $response['screening_specific'] = $row->screening_specific ?? '';
        $response['emergency_contact_one'] = $row->emergency_contact_one ?? '';
        $response['emergency_contact_two'] = $row->emergency_contact_two ?? '';
        $response['cases'] = $row->cases ?? '';
        $response['case_where'] = $row->case_where ?? '';
        $response['end_case'] = $row->end_case ?? '';
        $response['payment'] = $row->payment ?? '';
        $response['result'] = $row->sample_result ?? '';

        $response['nationality'] = $row->nationality ?? '';
        $response['id_card_detail'] = $row->id_card_detail ?? '';
        $response['id_card_issue'] = $row->id_card_issue ?? '';
        $response['name_of_poe'] = $row->name_of_poe ?? '';
        $response['covid_vaccination_details'] = $row->covid_vaccination_details ?? '';
        $response['nearest_contact'] = $row->nearest_contact ?? '';

        if ($response['result'] == '3') {
            $response['case_id'] = $row->case_id ?? '';
        } else {
            $response['case_id'] = '';
        }

        $response['parent_case_id'] = $row->parent_case_id ?? '';

        $response['symptoms_recent'] = $row->symptoms_recent ?? '';
        $response['symptoms_within_four_week'] = $row->symptoms_within_four_week ?? '';
        $response['symptoms_date'] = $row->symptoms_date ?? '';
        $response['case_reason'] = $row->case_reason ?? '';
        $response['temperature'] = $row->temperature ?? '';
        $response['date_of_onset_of_first_symptom'] = $row->date_of_onset_of_first_symptom ?? '';
        $response['reson_for_testing'] = $row->reson_for_testing ?? '';
        $response['case_type'] = $row->case_type ?? 1;

        return $response;
    })->values();


    return response()->json($data);
});

Route::post('/v1/client-update', function (Request $request) {
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            SuspectedCase::where('token', $value['token'])->update($value);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong, Please try again.']);
        }
    }
    return response()->json(['message' => 'Data Successfully Sync and Update']);
});

Route::post('/v1/client-tests', function (Request $request) {
    $data = $request->json()->all();
        try {
            SampleCollection::insert($data);
        } catch (\Exception $e) {
            foreach ($data as $value) {
                try {
                    SampleCollection::create($value);
                } catch (\Exception $e) {
//                    return response()->json(['message' => 'Something went wrong, Please try again.']);
                }
            }
        }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/client-tests', function (Request $request) {
    $hp_code = $request->hp_code;
    $record = \DB::table('ancs')->where('hp_code', $hp_code)->get();
    $data = collect($record)->map(function ($row) {
        $response = [];
        $response['token'] = $row->token;
        $response['woman_token'] = $row->woman_token ?? '';
        $response['checked_by'] = $row->checked_by ?? '';
        $response['hp_code'] = $row->hp_code ?? '';
        $response['status'] = $row->status ?? '';
        $response['created_at'] = $row->created_at ?? '';
        $response['updated_at'] = $row->updated_at ?? '';
        $response['checked_by_name'] = $row->checked_by_name ?? '';
        $response['sample_type'] = $row->sample_type ?? '';
        $response['sample_type_specific'] = $row->sample_type_specific ?? '';
        $response['sample_case_specific'] = $row->sample_case_specific ?? '';
        $response['sample_case'] = $row->sample_case ?? '';
        $response['sample_identification_type'] = $row->sample_identification_type ?? '';
        $response['service_type'] = $row->service_type ?? '';
        $response['result'] = $row->result ?? '';
        $response['infection_type'] = $row->infection_type ?? '';
        $response['service_for'] = $row->service_for ?? '';
        return $response;
    })->values();
    return response()->json($data);
});

Route::get('/v1/lab-test', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = LabTest::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/lab-test', function (Request $request) {
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            if ($value['sample_test_date'] == '') {
                $value['sample_test_result'] = 9;
                LabTest::create($value);
                SampleCollection::where('token', $value['sample_token'])->update(['result' => '9']);
            } else {
                SampleCollection::where('token', $value['sample_token'])->update(['result' => $value['sample_test_result']]);
                $find_test = LabTest::where('token', $value['token'])->first();
                if ($find_test) {
                    $find_test->update([
                        'sample_test_date' => $value['sample_test_date'],
                        'sample_test_time' => $value['sample_test_time'],
                        'sample_test_result' => $value['sample_test_result'],
                        'checked_by' => $value['checked_by'],
                        'hp_code' => $value['hp_code'],
                        'status' => $value['status'],
                        'created_at' => $value['created_at'],
                        'checked_by_name' => $value['checked_by_name']
                    ]);
                } else {
                    LabTest::create($value);
                }
            }
        } catch (\Exception $e) {

        }
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});


Route::post('/v1/patient-transfer', function (Request $request) {

    $data = json_decode($request->getContent(), true);

    $data['from'] = SuspectedCase::where('token', $data['token'])->first()->hp_code;
    $data['to'] = $data['hp_code'];
    $data['name'] = 'patient';
    $transfer = \App\Models\TransferLog::create($data);

    SuspectedCase::where('token', $data['token'])
        ->update(['hp_code' => $data['hp_code']]);
    SampleCollection::where('woman_token', $data['token'])->update(['hp_code' => $data['hp_code']]);

    return response()->json($data['token']);
});

Route::post('/v1/patient-symptoms', function (Request $request) {
    $data = $request->json()->all();
    try {
        \App\Models\Symptoms::insert($data);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/patient-symptoms', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = \App\Models\Symptoms::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/patient-clinical-parameters', function (Request $request) {
    $data = $request->json()->all();
    try {
        \App\Models\ClinicalParameter::insert($data);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/patient-clinical-parameters', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = \App\Models\ClinicalParameter::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/patient-laboratory-parameter', function (Request $request) {
    $data = $request->json()->all();
    try {
        LaboratoryParameter::insert($data);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/patient-laboratory-parameter', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = LaboratoryParameter::where('hp_code', $hp_code)->get();
    return response()->json($data);
});


// For web apis
Route::get('/v1/recieved-in-lab', function (Request $request) {
    $user = auth()->user();
    $sample_token = LabTest::where('checked_by', $user->token)->pluck('sample_token');
    $token = SampleCollection::whereIn('token', $sample_token)->pluck('woman_token');
    $data = SuspectedCase::whereIn('token', $token)->active()->withAll();
    return response()->json([
        'collection' => $data->advancedFilter()
    ]);
});

Route::post('/v1/received-in-lab', function (Request $request) {
    $data = $request->all();
    $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
    $data['token'] = auth()->user()->token . '-' . $data['token'];
    $data['hp_code'] = $healthworker->hp_code;
    $data['checked_by_name'] = $healthworker->name;
    $data['checked_by'] = $healthworker->token;
    $data['status'] = 1;
    $to_date_array = explode("-", Carbon::now()->format('Y-m-d'));
    $data['sample_recv_date'] = Calendar::eng_to_nep($to_date_array[0], $to_date_array[1], $to_date_array[2])->getYearMonthDay();
    try {
        $sample = SampleCollection::where('token', $data['sample_token']);
        if ($sample->count() < 1) {
            return response()->json('error');
        }
        $sample->update(['result' => 9]);
        LabTest::create($data);
        return response()->json('success');
    } catch (\Exception $e) {
        return response()->json('error');
    }
});

Route::post('/v1/result-in-lab-from-web', function (Request $request) {

    $value = $request->all();
    try {
        $value['token'] = auth()->user()->token . '-' . $value['token'];
        $find_test = LabTest::where('token', $value['token'])->first();
        SampleCollection::where('token', $find_test->sample_token)->update(['result' => $value['sample_test_result']]);
        if ($find_test) {
            $find_test->update([
                'sample_test_date' => $value['sample_test_date'],
                'sample_test_time' => $value['sample_test_time'],
                'sample_test_result' => $value['sample_test_result'],
            ]);
        }
        return response()->json('success');
    } catch (\Exception $e) {
        return response()->json('error');
    }
});

Route::post('/v1/antigen-result-in-lab-from-web', function (Request $request) {
  $user = auth()->user();
  $value = $request->all();
  try {
      $value['token'] = $user->token . '-' . $value['token'];
      $find_test = LabTest::where('token', $value['token'])->where('sample_token',$value['sample_token'])->first();
      if ($find_test) {
          $sample_collection = SampleCollection::where('token', $find_test->sample_token)->get()->first();
          $sample_collection->update(['result' => $value['sample_test_result']]);
          $find_test->update([
              'sample_test_date' => $value['sample_test_date'],
              'sample_test_time' => $value['sample_test_time'],
              'sample_test_result' => $value['sample_test_result'],
          ]);
      } else {
          $sample_collection = SampleCollection::where('token', $value['sample_token'])->get()->first();
          $sample_collection->update(['result' => $value['sample_test_result']]);
            $healthWorker = OrganizationMember::where('token', $user->token)->first();
            LabTest::create([
              'token' => $value['token'],
              'hp_code' => $healthWorker->hp_code,
              'status' => 1,
              'sample_recv_date' => $value['sample_test_date'],
              'sample_test_date' => $value['sample_test_date'],
              'sample_test_time' => $value['sample_test_time'],
              'sample_test_result' => $value['sample_test_result'],
              'checked_by' => $user->token,
              'checked_by' => $healthWorker->name,
              'sample_token' => $sample_collection->token,
              'regdev' => 'web'
            ]);
      }
      return response()->json('success');
  } catch (\Exception $e) {
      return response()->json(['error'=> $e->getMessage()]);
  }
});


// New update
Route::get('/v1/contact-tracing', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = ContactTracing::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/contact-tracing', function (Request $request) {
    $data = $request->json()->all();
    try {
        foreach ($data as $datum) {
            ContactTracing::create($datum);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/case-mgmt', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = CaseManagement::where('hp_code', $hp_code)->get()->toArray();

    array_walk_recursive($data, function (&$item, $key) {
        $item = null === $item ? '' : $item;
    });

    return response()->json($data);
});

Route::post('/v1/case-mgmt', function (Request $request) {
    $data = $request->json()->all();
    try {
        foreach ($data as $datum){
            CaseManagement::create($datum);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::post('/v1/case-mgmt-update', function (Request $request) {
    $data = $request->json()->all();
    try {
        foreach ($data as $value) {
            CaseManagement::where('token', $value['token'])->update($value);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync and Update']);
});

Route::post('/v1/payment-cases', function (Request $request) {
    ini_set('max_execution_time', 120 );

    $key = request()->getUser();
    $secret = request()->getPassword();
    $user = User::where([['username', $key], ['password', md5($secret)], ['role', 'healthworker']])->get()->first();
    if (!empty($user)) {
        $healthworker = OrganizationMember::where('token', $user->token)->get()->first();
        $payment_cases = $request->json()->all();
        try {
            foreach ($payment_cases as $payment_case){
                $payment_case_create = $payment_case;
                $payment_case_create['age'] = $payment_case['age'] ?? ' ';
                $payment_case_create['age_unit'] = $payment_case['age_unit'] ?? 0;
                $payment_case_create['gender'] = $payment_case['gender'] ?? 3;
                $payment_case_create['health_condition'] = $payment_case['health_condition'] ?? 1;
                $payment_case_create['method_of_diagnosis'] = $payment_case->method_of_diagnosis ?? 10;
                $payment_case_create['lab_id'] = $payment_case['lab_id'] ?? 'N/A';
                $payment_case_create['is_in_imu'] = $payment_case['is_in_imu'] ?? 0;
                $payment_case_create['pregnant_status'] = $payment_case['pregnant_status'] ?? 0;
                $payment_case_create['hp_code'] = $healthworker->hp_code;
                
                if(isset($payment_case['register_date_en'])) {
                    $date_en_array = explode("-", date("Y-m-d", strtotime($payment_case['register_date_en'])));
                    $payment_case_create['register_date_np'] = Calendar::eng_to_nep($date_en_array[0], $date_en_array[1], $date_en_array[2])->getYearMonthDay();
                }
                if(isset($payment_case['date_of_outcome_en'])) {
                    $date_en_array = explode("-", date("Y-m-d", strtotime($payment_case['date_of_outcome_en'])));
                    $payment_case_create['date_of_outcome'] = Calendar::eng_to_nep($date_en_array[0], $date_en_array[1], $date_en_array[2])->getYearMonthDay();
                }
                if(isset($payment_case['date_of_positive'])) {
                    $date_en_array = explode("-", date("Y-m-d", strtotime($payment_case['date_of_positive'])));
                    $payment_case_create['date_of_positive_np'] = Calendar::eng_to_nep($date_en_array[0], $date_en_array[1], $date_en_array[2])->getYearMonthDay();
                }
                PaymentCase::create($payment_case_create);
            }
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong, Please try again.']);
        }
        return response()->json(['message' => 'Data Successfully Sync']);
    } else {
        return response()->json(['message' => 'Something went wrong, User not found.']);
    }
});

Route::get('/v1/contact-follow-up', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = ContactFollowUp::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/contact-follow-up', function (Request $request) {
    $data = $request->json()->all();
    try {
        foreach ($data as $datum){
            ContactFollowUp::create($datum);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/contact-detail', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = ContactDetail::where('hp_code', $hp_code)->get();
    array_walk_recursive($data, function (&$item, $key) {
        $item = null === $item ? '' : $item;
    });
    return response()->json($data);
});

Route::post('/v1/contact-detail', function (Request $request) {
    $data = $request->json()->all();
    try {
        foreach ($data as $row){
            ContactDetail::create($row);
        }
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::post('/v1/contact-detail-update', function (Request $request) {
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            ContactDetail::where('token', $value['token'])->update($value);
        } catch (\Exception $e) {

        }
    }
    return response()->json(['message' => 'Data Successfully Sync and Update']);
});

Route::get('/v1/check-sid', 'Data\Api\AncController@checkSID');

Route::post('/v1/sample-update', function (Request $request) {
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            SampleCollection::where('token', $value['token'])->update($value);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong, Please try again.']);
        }
    }
    return response()->json(['message' => 'Data Successfully Sync and Update']);
});

Route::get('/v1/check-by-sid-or-lab-id', function (Request $request) {
    $token = $request->token;
    $lab_details = [];
    if (strlen($token) !== 17){
        $lab_result = LabTest::where('token', auth()->user()->token.'-'.$token)->first();
        if ($lab_result){
            $lab_details = OrganizationMember::where('hp_code', $lab_result->hp_code)->first();
        }
        if (!$lab_result){
            return response()->json();
        }
        $token = $lab_result->sample_token;
    }

    $sample_detail = SampleCollection::where('token', $token)->first();

    if (!$sample_detail){
        return response()->json();
    }

    $case = SuspectedCase::where('token', $sample_detail->woman_token)
        ->with(['healthworker' ,'healthpost', 'district', 'municipality'])
        ->first();
        
    $case = [
        'organization_name' => ($lab_details) ? $lab_details->name : $case->healthpost->name,
        'organization_address_province_district' =>($lab_details) ? '' : $case->healthpost->province->province_name .', '.$case->healthpost->district->district_name,
        'organization_address_municipality_ward' => ($lab_details) ? '' : $case->healthpost->municipality->municipality_name .' - '.$case->healthpost->ward_no,
        'organization_address' => ($lab_details) ? $lab_details->tole : $case->healthpost->office_address ?? '',
        'organization_phone' => ($lab_details) ? $lab_details->phone : $case->healthpost->phone ?? '',
        'organization_email' => ($lab_details) ? '' : $case->healthpost->email ?? '',
        'current_date' => \Carbon\Carbon::now()->format('Y-m-d H:i'),

        'name' => $case->name,
        'formatted_age' => $case->age.' / '.$case->formated_age_unit,
        'gender' => $case->formated_gender,
        'address' => $case->district->district_name.', '.$case->municipality->municipality_name.' - '.$case->ward.', '.$case->tole,
        'phone_no' => $case->emergency_contact_one.' / '.$case->emergency_contact_two,

        'patient_no' => '',
        'sample_no' => $sample_detail->token,
        'sample_collected_date' => $sample_detail->created_at->format('Y-m-d H:i'),
        'lab_no' => ($sample_detail->labreport !== null) ? $sample_detail->labreport->formated_token : '',
        'sample_received_date' => ($sample_detail->labreport !== null) ? $sample_detail->labreport->sample_recv_date : '',
        'date_and_time_of_analysis' => ($sample_detail->labreport !== null) ? $sample_detail->labreport->sample_test_date.' '.$sample_detail->labreport->sample_test_time : '',
        'sample_tested_by' => ($sample_detail->labreport !== null) ? $sample_detail->labreport->checked_by_name : '',

        'test_type' => ($sample_detail->service_for == "2") ? 'Rapid Antigen Test' : 'SARS-CoV-2 RNA Test',
        'test_result' => $sample_detail->formatted_result
    ];


    return response()->json($case);
});

Route::get('/v1/check-by-sid-or-lab-id-old', function (Request $request) {
    $token = $request->token;
    $lab_details = [];
    if (strlen($token) !== 17){
        $lab_result = \DB::connection('mysqldump')->table('lab_tests')
            ->where('token', auth()->user()->token.'-'.$token)->first();
            // dd($lab_result);
        if ($lab_result){
            $lab_details = \DB::connection('mysqldump')->table('health_workers')
                ->where('hp_code', $lab_result->hp_code)->first();
        }
        if (!$lab_result){
            return response()->json();
        }
        $token = $lab_result->sample_token;
    }

    // dd($lab_details);


    $sample_detail = \DB::connection('mysqldump')->table('ancs')
        ->join('lab_tests', 'ancs.token', '=', 'lab_tests.sample_token')
        ->select(
            'ancs.*',
            'lab_tests.token as lab_token',
            'lab_tests.sample_recv_date',
            'lab_tests.sample_test_date',
            'lab_tests.sample_test_time'
        )
        ->where('ancs.token', $token)->first();

        // dd($sample_detail);

    if (!$sample_detail){
        return response()->json();
    }

    $case = \DB::connection('mysqldump')->table('women')
        ->where('women.token', $sample_detail->woman_token)
        ->leftjoin('healthposts', 'women.hp_code', '=', 'healthposts.hp_code')
        ->leftjoin('provinces as p1', 'women.province_id', '=', 'p1.id', 'AND' , 'p1.id', '=', 'healthposts.province_id')
        ->leftjoin('municipalities as m1', 'women.municipality_id', '=', 'm1.id', 'AND' , 'm1.id', '=', 'healthposts.municipality_id')
        ->leftjoin('provinces as p2', 'healthposts.province_id', '=', 'p2.id')
        ->leftjoin('municipalities as m2', 'healthposts.municipality_id', '=', 'm2.id')
        ->leftjoin('health_workers', 'women.hp_code', '=', 'health_workers.hp_code')
        ->select(
            'women.*',
            'p1.province_name as p1_province_name',
            'm1.district_name as m1_district_name',
            'm1.municipality_name as m1_municipality_name',
            'p2.province_name as p2_province_name',
            'm2.district_name as m2_district_name',
            'm2.municipality_name as m2_municipality_name',
            'healthposts.name as healthpost_name',
            'healthposts.ward_no as healthpost_ward_no',
            'healthposts.phone as healthpost_phone'
        )
        // ->with(['healthworker' ,'healthpost', 'district', 'municipality'])
        ->first();
    // dd($case->healthpost->name);
    if($case->sex == '1'){ $sex = 'Male'; }
    elseif($case->sex =='2'){ $sex = 'Female'; }
    else { $sex = 'Don\'t Know'; }

    if($case->age_unit == '1'){ $age_unit = 'Months'; }
    elseif($case->age_unit =='2'){ $age_unit = 'Days'; }
    else { $age_unit = 'Years'; }


    if($sample_detail->result == 2) {
        $result =  'Pending';
    }
    elseif($sample_detail->result == 3){
        $result =  'Positive';
    }
    elseif($sample_detail->result == 4){
        $result =  'Negative';
    }
    elseif($sample_detail->result == 9){
        $result =  'Received';
    }
    else {
        $result =  'Don\'t Know';
    }

    if(isset($sample_detail->lab_token)){
        $lab_token = explode('-', $sample_detail->lab_token,2)[1] ?? $sample_detail->lab_token;
    } else {
        $lab_token = '';
    }

    $case = [
        'organization_name' => ($lab_details) ? $lab_details->name : $case->healthpost_name,
        'organization_address_province_district' =>($lab_details) ? '' : $case->p2_province_name .', '.$case->m2_district_name,
        'organization_address_municipality_ward' => ($lab_details) ? '' : $case->m2_municipality_name .' - '.$case->healthpost_ward_no,
        'organization_address' => ($lab_details) ? $lab_details->tole : $case->office_address ?? '',
        'organization_phone' => ($lab_details) ? $lab_details->phone : $case->healthpost_phone ?? '',
        'organization_email' => ($lab_details) ? '' : $case->healthpost->email ?? '',
        'current_date' => \Carbon\Carbon::now()->format('Y-m-d H:i'),

        'name' => $case->name,
        'formatted_age' => $case->age.' / '.$age_unit,
        'gender' => $sex ,
        'address' => $case->m1_district_name.', '.$case->m1_municipality_name.' - '.$case->ward.', '.$case->tole,
        'phone_no' => $case->emergency_contact_one.' / '.$case->emergency_contact_two,

        'patient_no' => '',
        'sample_no' => $sample_detail->token,
        'sample_collected_date' => $sample_detail->created_at,
        'lab_no' => $lab_token,
        'sample_received_date' => ($sample_detail->sample_recv_date) ? $sample_detail->sample_recv_date : '',
        'date_and_time_of_analysis' => ($sample_detail->sample_test_date) ? $sample_detail->sample_test_date.' '.$sample_detail->sample_test_time : '',
        'sample_tested_by' => ($sample_detail->checked_by_name) ? $sample_detail->checked_by_name : '',

        'test_type' => ($sample_detail->service_for == "2") ? 'Rapid Antigen Test' : 'SARS-CoV-2 RNA Test',
        'test_result' => $result
    ];


    return response()->json($case);
});


// external apis
Route::get('/v1/ext/district', 'External\ExtDistrictController@index');
Route::get('/v1/ext/municipality', 'External\ExtMunicipalityController@index');
Route::get('/v1/ext/province', 'External\ExtProvinceController@index');

Route::get('/v1/ext/cases', 'External\ExtCaseController@index');
Route::post('/v1/ext/cases', 'External\ExtCaseController@store');
Route::get('/v1/ext/get-case-detail', 'External\ExtCaseController@getCaseDetailBySample');
Route::get('/v1/federal-info', 'PublicDataController@federalInfo');


//Route::get('/v1/ext/cases', 'External\ExtCaseController@index');
//Route::post('/v1/ext/cases', 'External\ExtCaseController@store');
//Route::get('/v1/ext/district', 'External\ExtDistrictController@index');
//Route::get('/v1/ext/municipality', 'External\ExtMunicipalityController@index');
//Route::get('/v1/ext/province', 'External\ExtProvinceController@index');
//Route::get('/v1/ext/samples', 'External\ExtSampleController@index');
//Route::post('/v1/ext/samples', 'External\ExtSampleController@store');
//Route::get('/v1/ext/lab-test', 'External\ExtLabTestController@index');
//Route::post('/v1/ext/lab-test', 'External\ExtLabTestController@store');
//Route::get('/v1/ext/patient-symptoms', 'External\ExtSymptomsController@index');
//Route::post('/v1/ext/patient-symptoms', 'External\ExtSymptomsController@store');
//Route::get('/v1/ext/patient-clinical-parameters', 'External\ExtClinicalController@index');
//Route::post('/v1/ext/patient-clinical-parameters', 'External\ExtClinicalController@store');
//Route::get('/v1/ext/patient-laboratory-parameter', 'External\ExtLaboratoryController@index');
//Route::post('/v1/ext/patient-laboratory-parameter', 'External\ExtLaboratoryController@store');
//Route::get('/v1/ext/contact-tracing', 'External\ExtContactTracingController@index');
//Route::post('/v1/ext/contact-tracing', 'External\ExtContactTracingController@store');
//Route::get('/v1/ext/case-mgmt', 'External\ExtCaseMgmtController@index');
//Route::post('/v1/ext/case-mgmt', 'External\ExtCaseMgmtController@store');
//Route::get('/v1/ext/contact-detail', 'External\ExtContactDetailController@index');
//Route::post('/v1/ext/contact-detail', 'External\ExtContactDetailController@store');
//Route::get('/v1/ext/contact-follow-up', 'External\ExtContactFollowUpController@index');
//Route::post('/v1/ext/contact-follow-up', 'External\ExtContactFollowUpController@store');
//Route::post('/v1/ext/contact-follow-up', 'External\ExtContactFollowUpController@store');


Route::get('vaccinated/list', 'Api\VaccinationController@index');
Route::get('vaccinated/qr-search', 'Api\VaccinationController@qrSearch');
Route::post('vaccinated/record', 'Api\VaccinationController@record');
Route::post('/v1/vaccination-data', 'Api\VaccinationController@store');
Route::get('/v1/vial-detail', 'Api\VialDetailController@index');
Route::post('/v1/vial-detail', 'Api\VialDetailController@store');
Route::post('/v1/vial-detail-update', 'Api\VialDetailController@update');
Route::get('/v1/vial-stock-detail', 'Api\VialStockDetailController@index');
Route::post('/v1/vial-stock-detail', 'Api\VialStockDetailController@store');
Route::post('/v1/vial-stock-detail-update', 'Api\VialStockDetailController@update');
Route::get('/v1/covid-immunization-list', 'Api\CovidImmunizationController@show');
Route::get('/v1/covid-vaccination-list', 'Api\CovidImmunizationController@showDataByUserLogin');
Route::get('/v1/health-professional/immunized', 'Api\CovidImmunizationController@immunized');
Route::get('/v1/health-professionals-list', 'Backend\DHOController@findAllHealthProfessionalDatas')->name('health-professionals-list');

Route::post('/v1/cases-payment', function (Request $request) {
    $data = $request->all();
    if($data['comorbidity']) {
      $data['comorbidity'] = '[' . implode(',', $data['comorbidity']) . ']';
    }
    // dd($data);
    try {
        if (isset($data['id'])){
            $data = \App\Models\PaymentCase::where('id', $data['id'])->update($data);
        }else{
            $dateToday = Carbon::now(); //#TODO validate register_date_en from serverside.
            if (auth()->user()->role == 'healthworker'){
                $data['hp_code'] = OrganizationMember::where('token', auth()->user()->token)->first()->hp_code;
            }else{
                $data['hp_code'] = \App\Models\Organization::where('token', auth()->user()->token)->first()->hp_code;
            }
            \App\Models\PaymentCase::insert($data);
        }

    } catch (\Exception $e) {
      dump($e);
        return response()->json(['message' => 'error']);
    }
    return response()->json(['message' => 'success']);
});

Route::get('/v1/search-cases-payment-by-id', function (Request $request) {
    $data = $request->all();
    $response = \App\Models\PaymentCase::where('id', $data['id'])->first();
    return response()->json($response);
});


Route::post('/v1/cases-payment-import', function (Request $request) {
    $path = $request->file('file');
//    $data = Excel::load($path, function($reader) {})->get();
    return response()->json($path);
});

Route::post('/v1/cases-search-by-lab-and-id', function (Request $request) {
    $id = $request->id;
    $hp_code = $request->hp_code;
    $organiation_member_tokens = OrganizationMember::where('hp_code', $hp_code)->pluck('token');
    $lab_token = [];
    foreach ($organiation_member_tokens as $item) {
        array_push($lab_token, $item."-".$id);
    }

    $response_data = LabTest::whereIn('lab_tests.token', $lab_token)->where('women.name', '!=', null)
        ->leftJoin('ancs', 'lab_tests.sample_token', '=', 'ancs.token')
        ->leftJoin('women', 'ancs.woman_token', '=', 'women.token')
        ->leftJoin('municipalities', 'women.municipality_id', '=', 'municipalities.id')
        ->select('ancs.service_for', \DB::raw('DATE(ancs.updated_at) AS date_of_positive'), 'women.name', 'women.age', 'women.age_unit', 'women.province_id', 'women.district_id', 'women.municipality_id', 'women.emergency_contact_one', 'women.sex', 'municipalities.municipality_name', 'women.tole', 'women.ward')->first();

    if(count($response_data) == 0){
        return response()->json(['message' => 'error']);
    }
    return response()->json(['message' => 'success',
    'data' => $response_data
    ]);
});


Route::post('/v1/cases-payment/delete', function(Request $request){
    try{
        $data = \App\Models\PaymentCase::find($request->id);
            if(!empty($data)){
                $data->delete();
            }else{
                return response()->json(['message' => 'error']);
            }
        return response()->json(['message' => 'success']);
    }
    catch (\Exception $e){
        return response()->json(['message' => 'error']);
    }
});
Route::post('/v1/bulk-case-payment', 'CasesPaymentController@bulkUpload')->name('cases.payment.bulk.upload');

Route::post('/v1/bulk-upload/lab-received', 'Backend\BulkUploadController@labReceived')->name('bulk.upload.lab-received');
Route::post('/v1/bulk-upload/lab-result', 'Backend\BulkUploadController@labResult')->name('bulk.upload.lab-result');
Route::post('/v1/bulk-upload/lab-received-result', 'Backend\BulkUploadController@labReceivedResult')->name('bulk.upload.lab-received.lab-result');
Route::post('/v1/bulk-upload/registration-sample-collection', 'Backend\BulkUploadController@registrationSampleCollection')->name('bulk.upload.register.sample-collection');
Route::post('/v1/bulk-upload/registration-sample-collection-lab-test', 'Backend\BulkUploadController@registrationSampleCollectionLabTest')->name('bulk.upload.register.sample.lab');
Route::get('/v1/server-date', 'Data\Api\DateController@index');