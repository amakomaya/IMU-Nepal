<?php

use App\Imports\CasesPaymentImport;
use App\Models\SampleCollection;
use App\Models\CaseManagement;
use App\Models\ContactDetail;
use App\Models\ContactFollowUp;
use App\Models\ContactTracing;
use App\Models\OrganizationMember;
use App\Models\LaboratoryParameter;
use App\Models\LabTest;
use App\Models\SuspectedCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yagiten\Nepalicalendar\Calendar;

Route::get('/data/aggregate', 'Data\Api\AggregateController@forMonitor');

Route::get('/v2/healthpost', 'Api\v2\HealthpostController@get');
Route::get('/v2/municipality-by-district', 'Api\v2\MunicipalityByDistrictController@get');

Route::get('analysis/v1/overview', 'Api\Analysis\v1\OverviewController@get');

// New Login
Route::post('/v2/amc/login', 'Api\LoginController@v2AmcLogin');
Route::post('/v3/amc/login', 'Api\LoginController@v3AmcLogin');


Route::get('/v1/healthposts', function () {
    $healthpost = \App\Models\Organization::with(['province', 'municipality', 'district'])->get();
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


// New update
Route::get('/v1/contact-tracing', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = ContactTracing::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/contact-tracing', function (Request $request) {
    $data = $request->json()->all();
    try {
        ContactTracing::insert($data);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/case-mgmt', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = CaseManagement::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/case-mgmt', function (Request $request) {
    $data = $request->json()->all();
    try {
        CaseManagement::insert($data);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::post('/v1/case-mgmt-update', function (Request $request) {
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            CaseManagement::where('token', $value['token'])->update($value);
        } catch (\Exception $e) {

        }
    }
    return response()->json(['message' => 'Data Successfully Sync and Update']);
});

Route::get('/v1/contact-follow-up', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = ContactFollowUp::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/contact-follow-up', function (Request $request) {
    $data = $request->json()->all();
    try {
        ContactFollowUp::insert($data);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Something went wrong, Please try again.']);
    }
    return response()->json(['message' => 'Data Successfully Sync']);
});

Route::get('/v1/contact-detail', function (Request $request) {
    $hp_code = $request->hp_code;
    $data = ContactDetail::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/contact-detail', function (Request $request) {
    $data = $request->json()->all();
    try {
        ContactDetail::insert($data);
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


// external apis
Route::get('/v1/ext/district', 'External\ExtDistrictController@index');
Route::get('/v1/ext/municipality', 'External\ExtMunicipalityController@index');
Route::get('/v1/ext/province', 'External\ExtProvinceController@index');

Route::get('/v1/ext/cases', 'External\ExtCaseController@index');
Route::post('/v1/ext/cases', 'External\ExtCaseController@store');
Route::get('/v1/ext/get-case-detail', 'External\ExtCaseController@getCaseDetailBySample');


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
    $data['hp_code'] = \App\Models\Organization::where('token', auth()->user()->token)->first()->hp_code;
    try {
        \App\Models\PaymentCase::insert($data);
    } catch (\Exception $e) {
        return response()->json(['message' => 'error']);
    }
    return response()->json(['message' => 'success']);
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
        ->select('women.name', 'women.age', 'women.emergency_contact_one', 'women.sex', 'municipalities.municipality_name', 'women.tole', 'women.ward')->first();

    if(count($response_data) == 0){
        return response()->json(['message' => 'error']);
    }
    return response()->json(['message' => 'success',
    'data' => $response_data
    ]);
});