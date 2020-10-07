<?php

use Illuminate\Http\Request;

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/imeilogin', 'Api\LoginController@login');
Route::post('/imeilogin-vtc', 'Api\LoginController@vtcLogin');

Route::post('/baby-detail-new', 'Api\BabyDetailController@newStore');
Route::post('/vial-to-child/create-baby-new', 'Api\VialToChildController@storeBabyNew');
Route::put('/vial-to-child/update-baby-new/{token}', 'Api\VialToChildController@updateBabyNew');

// Care App Apis
Route::get('/v1/woman-vaccination', 'Api\WomanVaccinationController@index');
Route::post('/v1/woman-vaccination', 'Api\WomanVaccinationController@store');
Route::post('/v1/woman-vaccination/{id}', 'Api\WomanVaccinationController@update');
Route::get('/lab-test', 'Api\LabTestController@index');
Route::post('/lab-test', 'Api\LabTestController@store');
Route::post('/lab-test/{id}', 'Api\LabTestController@update');

Route::get('/v2/healthpost', 'Api\v2\HealthpostController@get');
Route::get('/v2/municipality-by-district', 'Api\v2\MunicipalityByDistrictController@get');
Route::get('/v2/woman', 'Api\v2\WomanController@get');
Route::get('/v2/entry-log', 'Api\v2\GlobalDataEntryLogController@index');
Route::Post('/v2/entry-log', 'Api\v2\GlobalDataEntryLogController@store');

Route::get('analysis/v1/overview', 'Api\Analysis\v1\OverviewController@get');

Route::get('/v2/content-app/newsfeed/{token?}', 'Api\v2\ContentAppNewsFeedController@getNewsFeed');
Route::get('/v2/content-app/text', 'Api\v2\ContentAppMultimediaController@getText');
Route::get('/v2/content-app/video', 'Api\v2\ContentAppMultimediaController@getVideo');
Route::get('/v2/content-app/audio', 'Api\v2\ContentAppMultimediaController@getAudio');
Route::get('/v2/content-app/advertisement', 'Api\v2\ContentAppAdvertisementController@get');
Route::post('/v2/content-app/notification', 'Api\v2\ContentAppNotificationController@postNotification');
Route::get('/v2/content-app/notification', 'Api\v2\ContentAppNotificationController@getNotification');

Route::post('/v2/content-app/notification-read-at', 'Api\v2\ContentAppNotificationController@updateReadAt');

Route::get('/v2/content-app/appointment', 'Api\v2\ContentAppAppointmentController@check');
Route::post('/v2/content-app/appointment/confirm', 'Api\v2\ContentAppAppointmentController@confirm');
Route::get('/v2/content-app/appointment/history', 'Api\v2\ContentAppAppointmentController@history');


// Amakomaya Care
Route::post('/v2/woman', 'Api\v2\WomanRegisterController@store');
Route::post('/v2/anc', 'Api\v2\AncController@store');

// New Login
Route::post('/v2/amc/login', 'Api\LoginController@v2AmcLogin');

// V3
Route::post('/v3/amc/login', 'Api\v3\AmakomayaCareController@login');
Route::post('/v3/vtc/login', 'Api\v3\VialToChildController@login');

Route::get('/v3/vtc', 'Api\v3\VialToChildController@index');
Route::post('/v3/vtc', 'Api\v3\VialToChildController@store');
Route::post('/v3/vtc-update', 'Api\v3\VialToChildController@update');
Route::get('/v3/vtc/quick/baby', 'Api\v3\VialToChildController@quickDataBaby');
Route::get('/v3/vtc/quick/vaccination', 'Api\v3\VialToChildController@quickDataVaccination');

Route::get('/v3/amc', 'Api\v3\AmakomayaCareController@index');
Route::get('/v3/amc/ancs', 'Api\v3\AmakomayaCareController@ancs');
Route::get('/v3/amc/hiv-syphillis-tests', 'Api\v3\HIVSyphillisTestController@index');
Route::post('/v3/amc/hiv-syphillis-tests', 'Api\v3\HIVSyphillisTestController@store');
Route::post('/v3/amc/hiv-syphillis-tests-update', 'Api\v3\HIVSyphillisTestController@update');

Route::resource('/v3/baby', 'Api\v3\BabyController');

// QrCode Download
Route::post('/aamakomaya-qrcode-download', 'Backend\AamakomayaGenerateQrcode@download')->name('aamakomaya.qrcode-download');

Route::get('/v1/healthposts', function ()
{
    $healthpost = \App\Models\Healthpost::with(['province', 'municipality', 'district'])->get();
    return response()->json($healthpost);
});

Route::post('/v1/client', function(Request $request){
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            $value['case_id'] = substr(md5(time()), 0, 5);
            \App\Models\Woman::create($value);
        } catch (\Exception $e) {
            
        }
    }
    return response()->json(['message' => 'Data Sussessfully Sync']);
});

Route::get('/v1/client', function(Request $request){
    $hp_code = $request->hp_code;
    $data = collect(\DB::table('women')
        ->leftJoin('ancs', 'ancs.woman_token', '=', 'women.token')
        ->where('women.hp_code', $hp_code)
        ->where('ancs.result', '!=', 4)
        ->select('women.*', 'ancs.result')
        ->get())->map(function ($row) {        

        $response = [];

        $response['token'] = $row->token;
        $response['name'] = $row->name ?? '';
        $response['age'] = $row->age ?? '';
        $response['sex'] = $row->sex ?? '';
        $response['caste'] = $row->caste ?? '';
        $response['phone'] = $row->phone ?? '';
        $response['emergency_name'] = $row->emergency_name ?? '';
        $response['emergency_name_person_relation'] = $row->emergency_name_person_relation ?? '';
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
        // $response['covid_infect'] = $row->covid_infect ?? '';
        // $response['covid_around_you'] = $row->covid_around_you ?? '';
                
        $response['family_member'] = $row->family_member ?? '';
        $response['family_chronic_illness'] = $row->family_chronic_illness ?? '';
        $response['family_above_sixty'] = $row->family_above_sixty ?? '';
        $response['family_below_ten'] = $row->family_below_ten ?? '';

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
        $response['emergency_name_person_phone'] = $row->emergency_name_person_phone ?? '';
        $response['email'] = $row->email ?? '';
        $response['nationality'] = $row->nationality ?? '';
        $response['country_name'] = $row->country_name ?? '';
        $response['passport_no'] = $row->passport_no ?? '';
        $response['quarantine_type'] = $row->quarantine_type ?? '';
        $response['quarantine_specific'] = $row->quarantine_specific ?? '';
        $response['province_quarantine_id'] = $row->province_quarantine_id ?? 0;
        $response['district_quarantine_id'] = $row->district_quarantine_id ?? 0;
        $response['municipality_quarantine_id'] = $row->municipality_quarantine_id ?? 0;
        $response['ward_quarantine'] = $row->ward_quarantine ?? '';
        $response['tole_quarantine'] = $row->tole_quarantine ?? '';
        $response['pcr_test'] = $row->pcr_test ?? '';
        $response['pcr_test_date'] = $row->pcr_test_date ?? '';
        $response['pcr_test_result'] = $row->pcr_test_result ?? '';
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
        $response['result'] = $row->result ?? '';
        $response['case_id'] = $row->case_id ?? '';
        $response['parent_case_id'] = $row->parent_case_id ?? '';

        $response['symptoms_recent'] = $row->symptoms_recent ?? '';
        $response['symptoms_within_four_week'] = $row->symptoms_within_four_week ?? '';
        $response['symptoms_date'] = $row->symptoms_date ?? '';
        $response['case_reason'] = $row->case_reason ?? '';
        $response['temperature'] = $row->temperature ?? '';

    return $response;
});
    return response()->json($data);
});

Route::post('/v1/client-update', function(Request $request){
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            \App\Models\Woman::where('token', $value['token'])->update($value);
        } catch (\Exception $e) {
            
        }
    }
    return response()->json(['message' => 'Data Sussessfully Sync and Update']);
});

Route::post('/v1/client-tests', function(Request $request){
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            \App\Models\Anc::create($value);
        } catch (\Exception $e) {
            
        }
    }
    return response()->json(['message' => 'Data Sussessfully Sync']);
});

Route::get('/v1/client-tests', function(Request $request){
    $hp_code = $request->hp_code;
    $data = collect(\DB::table('ancs')->where('hp_code', $hp_code)->get())->map(function ($row) {

        $response = [];
        $response['token'] = $row->token;
        $response['woman_token'] = $row->woman_token ?? '';
        $response['current_address'] = $row->current_address ?? '';
        $response['current_province'] = $row->current_province ?? '';
        $response['current_district'] = $row->current_district ?? '';
        $response['current_municipality'] = $row->current_municipality ?? '';
        $response['current_ward'] = $row->current_ward ?? '';
        $response['current_tole'] = $row->current_tole ?? '';
        $response['rdt_test'] = $row->rdt_test ?? '';
        $response['rdt_result'] = $row->rdt_result ?? '';
        $response['rdt_test_date'] = $row->rdt_test_date ?? '';
        $response['pcr_test'] = $row->pcr_test ?? '';
        $response['pcr_result'] = $row->pcr_result ?? '';
        $response['pcr_test_date'] = $row->pcr_test_date ?? '';
        $response['problem_suggestion'] = $row->problem_suggestion ?? '';
        $response['situation'] = $row->situation ?? '';
        $response['checked_by'] = $row->checked_by ?? '';
        $response['hp_code'] = $row->hp_code ?? '';
        $response['mobile'] = 'mobile';
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

    return $response;
});
    return response()->json($data);
});

Route::post('/v1/lab-test', function(Request $request){
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {

            if ($value['sample_test_date'] == '') {
                \App\Models\LabTest::create($value);
                \App\Models\Anc::where('token', $value['sample_token'])->update(['result' => '9']);
            }else{

            \App\Models\Anc::where('token', $value['sample_token'])->update(['result' => $value['sample_test_result']]);

            $find_test = \App\Models\LabTest::where('token', $value['token']);

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
            }else{
                \App\Models\LabTest::create($value);
            }

            }
        } catch (\Exception $e) {
            
        }
    }
    return response()->json(['message' => 'Data Sussessfully Sync']);
});



Route::post('/v1/patient-transfer', function(Request $request){

    $data = json_decode($request->getContent(), true);

    $data['from'] = \App\Models\Woman::where('token', $data['token'])->first()->hp_code;
    $data['to'] = $data['hp_code'];
    $data['name'] = 'patient';
    $transfer = \App\Models\TransferLog::create($data);

    \App\Models\Woman::where('token', $data['token'])
        ->update(['hp_code' => $data['hp_code']]);
    \App\Models\Anc::where('woman_token', $data['token'])->update(['hp_code' => $data['hp_code']]);
    
    return response()->json($data['token']);
});


Route::post('/v1/patient-symptoms', function(Request $request){
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            \App\Models\Symptoms::create($value);
        } catch (\Exception $e) {
            
        }
    }
    return response()->json(['message' => 'Data Sussessfully Sync']);
});

Route::get('/v1/patient-symptoms', function(Request $request){
    $hp_code = $request->hp_code;
    $data = \App\Models\Symptoms::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/patient-clinical-parameters', function(Request $request){
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            \App\Models\ClinicalParameter::create($value);
        } catch (\Exception $e) {
            
        }
    }
    return response()->json(['message' => 'Data Sussessfully Sync']);
});

Route::get('/v1/patient-clinical-parameters', function(Request $request){
    $hp_code = $request->hp_code;
    $data = \App\Models\ClinicalParameter::where('hp_code', $hp_code)->get();
    return response()->json($data);
});

Route::post('/v1/patient-laboratory-parameter', function(Request $request){
    $data = $request->json()->all();
    foreach ($data as $value) {
        try {
            \App\Models\LaboratoryParameter::create($value);
        } catch (\Exception $e) {
            
        }
    }
    return response()->json(['message' => 'Data Sussessfully Sync']);
});

Route::get('/v1/patient-laboratory-parameter', function(Request $request){
    $hp_code = $request->hp_code;
    $data = \App\Models\LaboratoryParameter::where('hp_code', $hp_code)->get();
    return response()->json($data);
});