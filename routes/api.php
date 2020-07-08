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

Route::post('/vial-to-child/baby', 'Api\VialToChildController@batchStore');
Route::post('/vial-to-child/baby-update', 'Api\VialToChildController@batchUpdate');
Route::post('/vaccination-record-new', 'Api\VaccinationRecordController@batchStore');
Route::post('/vaccination-record-new-update', 'Api\VaccinationRecordController@batchUpdate');

Route::get('/vial-detail', 'Api\VialDetailController@index');
Route::post('/vial-detail', 'Api\VialDetailController@batchStore');
Route::post('/vial-detail-update', 'Api\VialDetailController@batchUpdate');

// Content App Apis
Route::post('/v1/woman-login', 'Api\WomanController@womanLogin');
Route::post('/v1/woman-qrlogin', 'Api\WomanController@womanQRLogin');

Route::post('/v1/woman-registration', 'Api\WomanController@womanRregistrationStore');
Route::post('/v1/woman-update', 'Api\WomanController@womanUpdate');
Route::get('/v1/woman-ancs/{token}', 'Api\WomanController@womanAnc');
Route::get('/v1/woman-vaccination/{token}', 'Api\WomanController@womanVaccination');

Route::post('/v1/woman-survey', 'Api\WomanController@WomanSurvey');

Route::post('/v1/registration/woman', 'Api\WomanController@registrationWoman');

Route::get('/v1/woman-delivery/{token}', 'Api\WomanController@womanDelivery');
Route::get('/v1/woman-pnc/{token}', 'Api\WomanController@womanPnc');
Route::get('/v1/woman-labtest/{token}', 'Api\WomanController@womanLabTest');
Route::get('/v1/amc-baby/{token}', 'Api\WomanController@babyDetails');
Route::get('/v1/amc-baby-vaccination/{token}', 'Api\WomanController@babyVaccination');
Route::get('/v1/amc-baby-weight/{token}', 'Api\WomanController@babyWeight');
Route::get('/v1/amc-baby-aefi/{token}', 'Api\WomanController@babyAefi');

Route::post('/v3/content-app/login', 'Api\v3\ContentAppController@login');
Route::post('/v3/content-app/qrlogin', 'Api\v3\ContentAppController@qrlogin');
Route::post('/v3/content-app/register', 'Api\v3\ContentAppController@register');

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

// HMIS
Route::post('/hmis/vaccination-program', 'Hmis\VaccinationProgramController@send');
Route::post('/hmis/safe-maternity-program', 'Hmis\SafeMaternityProgramController@send');

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

Route::post('/v1/vtc/baby-transfer', function(Request $request){

    $data = json_decode($request->getContent(), true);

    // Store in TransferLog Model name : baby, token : token , from : old hp_code and to : new hp_code
    $data['name'] = 'baby';
    $transfer = \App\Models\TransferLog::create($data);

    // update baby, vaccination all the child relations
    \App\Models\BabyDetail::where('token', $data['token'])
        ->update(['hp_code' => $data['to']]);
    \App\Models\VaccinationRecord::where('baby_token', $data['token'])
          ->update(['hp_code' => $data['to']]);
    \App\Models\BabyWeight::where('baby_token', $data['token'])
          ->update(['hp_code' => $data['to']]);
    \App\Models\Aefi::where('baby_token', $data['token'])
            ->update(['hp_code' => $data['to']]);
    return response()->json($data['token']);
});


Route::post('/v1/survey', function(Request $request){

    $data = $request->json()->all();

    foreach ($data as $value) {
        $record = json_encode($value);
            \App\Models\Survey::create(['data' => $record ]);
    }

    return response()->json(['message' => 'Data Sussessfully Sync']);
});

Route::post('/v1/raw/fhr', function(Request $request){
    try{
        $data = json_decode($request->getContent(), true);
        if (!empty($data)) {
            $final_data = json_encode($data);
            \DB::table('raw_data')->insert(['data' => $final_data]);
        }
        return response()->json(['message' => 'Data Sussessfully Sync']);
    }catch(\Exception $e){
        return response()->json(['message'=> 'Error in Sync']);
    }
});