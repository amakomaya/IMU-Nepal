<?php

use App\Helpers\GetHealthpostCodes;
use App\Models\District;
use App\Models\HealthProfessional;
use App\Models\Municipality;
use App\Models\SuspectedCase;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yagiten\Nepalicalendar\Calendar;

Route::get('locale/{locale}', function ($locale) {
    \Session::put('locale', $locale);
    return redirect()->back();
});

Route::get('admin/search-organization', 'PermissionController@userByPermission')->name('user-by-permissions.index');


//Frontend

Route::get('/', function () {
    return redirect('/login');
});

//Auth
Auth::routes();
Route::resource('admin/permissions', 'PermissionController', ['name' => 'permissions']);
Route::get('admin/user-by-permissions', 'PermissionController@userByPermission')->name('user-by-permissions.index');
Route::get('/admin/something', 'AdminController@index')->name('admin');

//Backend Home
Route::get('/admin/update-vaccination-center','UpdateVaccinationCenter@index')->name('updateVaccinationCenter');
Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin-new', 'AdminController@newDashbaord')->name('admin.new.dashboard');
Route::get('/admin/healthpost-select', 'AdminController@healthpostSelect')->name('admin.healthpost-select');
Route::get('/admin/municipality-select-province', 'AdminController@municipalitySelectByProvince')->name('admin.municipality-select-province');
Route::get('/admin/municipality-select-district', 'AdminController@municipalitySelectByDistrict')->name('admin.municipality-select-district');
Route::get('/admin/district-select-province', 'AdminController@districtSelectByProvince')->name('admin.district-select-province');
Route::get('/admin/ward-select-municipality', 'AdminController@wardSelectByMunicipality')->name('admin.ward-select-municipality');
Route::get('/admin/healthpost-select-ward', 'AdminController@healthpostSelectByWard')->name('admin.healthpost-select-ward');
Route::get('/admin/select-from-to', 'AdminController@selectFromTo')->name('admin.select-from-to');
Route::get('/admin/district-value', 'AdminController@getDistrictValue')->name('admin.district-value');

Route::get('/admin/organization-select', 'AdminController@organizationSelect')->name('admin.organization-select');

//Backend Center
Route::resource('admin/center', 'Backend\CenterController');
Route::get('/admin/maps', 'Backend\MapController@map')->name('center.woman.map');
Route::get('/admin/maps/data', 'Backend\MapController@data');

//Backend Dho
Route::resource('admin/dho', 'Backend\DHOController');
Route::get('admin/dho-vaccination', 'Backend\DHOController@findMunicipalities')->name('dho.vaccination.municipalities');
Route::post('admin/dho-vaccination-list', 'Api\CovidImmunizationController@immunizationListByDistrictLogin')->name('dho.vaccination.list.store');

//Bakend Province
Route::resource('admin/province', 'Backend\ProvinceController');

//Bakend Municipality
Route::resource('admin/municipality', 'Backend\MunicipalityController');

//User Manager
Route::get('/admin/user-manager/{id}/change-paswword', 'Backend\UserManagerController@changePassword')->name('user-manager.change-paswword');
Route::put('/admin/user-manager/change-paswword-update', 'Backend\UserManagerController@changePasswordUpdate')->name('user-manager.change-paswword-update');
Route::post('/admin/user-manager/{id}/login-as', 'Backend\UserManagerController@loginAs')->name('user-manager.login-as');
Route::post('/admin/user-manager/first-loggedin', 'Backend\UserManagerController@loginAsFirstLoggedIn')->name('user-manager.first-loggedin');

//Bakend Organization
Route::resource('admin/healthpost', 'Backend\HealthPostController');
Route::get('admin/organization/{id}/edit-record', 'Backend\HealthPostController@editRecord');
Route::post('admin/organization/update-record/{id}', 'Backend\HealthPostController@updateRecord')->name('admin.organization.update-record');
Route::post('admin/organization/api-delete/{id}', 'Backend\HealthPostController@apiDestroy')->name('admin.organization.api-destroy');
Route::post('admin/vaccination_center_update', 'Backend\HealthPostController@organizationUpdate')->name('admin.organization.update');

//Bakend Route
Route::resource('admin/ward', 'Backend\WardController');

//Bakend Lab
Route::resource('admin/lab-user', 'Backend\FchvController', ['names' => 'fchv']);
Route::get('/admin/lab-patients', function () {
    return view('backend.lab.index');
})->name('lab.patient.index');
Route::get('/admin/lab-case-report', 'Backend\LabReportController@index')->name('lab.patient.report.index');
Route::get('/admin/lab-case-report-old', 'Backend\LabReportController@indexOld')->name('lab.patient.report.index-old');
Route::get('/admin/lab-negative-patients', function () {
    return view('backend.lab.negative-index');
})->name('lab.negative.patients.index');
Route::get('/admin/lab-positive-patients', function () {
    return view('backend.lab.positive-index');
})->name('lab.positive.patients.index');

Route::get('/admin/vaccination-list', function () {
    return view('vaccination.list');
})->name('vaccination.web.index');

//Route::get('/admin/vaccinated-list', function () {
//    return view('vaccination.vaccinated');
//})->name('vaccinated.web.index');

//Backend Health Worker
Route::resource('admin/health-worker', 'Backend\HealthWorkerController');

//Bakend Patients
Route::resource('admin/patients', 'Backend\WomanController', ['names' => 'woman']);
Route::get('admin/patients-pending', 'Backend\WomanController@pendingIndex')->name('woman.pending-index');
Route::get('admin/patients-pending-old', 'Backend\WomanController@pendingIndexOld')->name('woman.pending-index-index');
Route::get('admin/antigen-patients-pending', 'Backend\WomanController@antigenPendingIndex')->name('woman.antigen-pending-index');
Route::get('admin/antigen-patients-pending-old', 'Backend\WomanController@antigenPendingIndexOld')->name('woman.antigen-pending-index-old');

Route::get('admin/add-multiple-sample', 'Backend\WomanController@addSampleCollection')->name('patient.multiple-sample.create');
Route::get('admin/negative-patients', 'Backend\WomanController@negativeIndex')->name('patients.negative.index');
Route::get('admin/negative-patients-old', 'Backend\WomanController@negativeIndexOld')->name('patients.negative.index.old');
Route::get('admin/negative-patients-antigen', 'Backend\WomanController@negativeAntigenIndex')->name('patients.negative-antigen.index');
Route::get('admin/negative-patients-antigen-old', 'Backend\WomanController@negativeAntigenIndexOld')->name('patients.negative-antigen.index.old');
Route::get('admin/positive-patients', 'Backend\WomanController@positiveIndex')->name('patients.positive.index');
Route::get('admin/positive-patients-old', 'Backend\WomanController@positiveIndexOld')->name('patients.positive.index.old');
Route::get('admin/positive-patients-antigen', 'Backend\WomanController@positiveAntigenIndex')->name('patients.positive-antigen.index');
Route::get('admin/positive-patients-antigen-old', 'Backend\WomanController@positiveAntigenIndexOld')->name('patients.positive-antigen.index.old');
Route::get('admin/tracing-patients', 'Backend\WomanController@tracingIndex')->name('patients.tracing.index');
Route::get('admin/lab-received-patients', 'Backend\WomanController@labReceivedIndex')->name('patients.lab-received.index');
Route::get('admin/lab-received-patients-old', 'Backend\WomanController@labReceivedIndexOld')->name('patients.lab-received.index.old');
Route::get('admin/lab-received-patients-antigen', 'Backend\WomanController@labReceivedAntigenIndex')->name('patients.lab-received-antigen.index');
Route::get('admin/lab-received-patients-antigen-old', 'Backend\WomanController@labReceivedAntigenIndexOld')->name('patients.lab-received-antigen.index.old');
Route::get('admin/cases-recovered', 'Backend\WomanController@casesRecoveredIndex')->name('cases.recovered.index');
Route::get('admin/cases-payment', 'Backend\WomanController@casesPaymentIndex')->name('cases.payment.index');
Route::get('admin/cases-discharge-payment', 'Backend\WomanController@casesPaymentDischargeIndex')->name('cases.payment.index-discharge');
Route::get('admin/cases-death-payment', 'Backend\WomanController@casesPaymentDeathIndex')->name('cases.payment.index-death');
Route::get('admin/cases-payment-create', 'Backend\WomanController@casesPaymentCreate')->name('cases.payment.create');
Route::get('admin/cases-death', 'Backend\WomanController@casesDeathIndex')->name('cases.death.index');
Route::get('admin/cases-patient-detail', 'Backend\WomanController@casesPatientDetail')->name('cases.patient.detail');
Route::get('admin/sample-collection/create/{token}', 'Backend\WomanController@sampleCollectionCreate')->name('patients.sample-collection.store');
Route::post('admin/sample-collection', 'Backend\WomanController@sampleCollectionStore')->name('patient.sample.store');

Route::get('admin/cases-in-other-organization', 'Backend\WomanController@casesInOtherOrganization')->name('patients.other-organization.index');

Route::resource('admin/profile', 'Backend\ProfileController');

Route::get('/api/district', 'Api\DistrictController@index')->name('api.district.index');
Route::get('/api/municipality', 'Api\MunicipalityController@index')->name('api.municiplaity.index');
Route::get('/api/province', function () {
    return \App\Models\Province::all();
});

Route::get('/admin/overview-data', 'Backend\OverviewController@index')->name('admin.overview');

Route::get('/admin/organization-overview-cict', 'Backend\OverviewController@cict')->name('organization.overview.cict');
Route::get('/admin/organization-overview-search', 'Backend\OverviewController@search')->name('organization.overview.search');
Route::get('/admin/organization-overview-hospital', 'Backend\OverviewController@hospital')->name('organization.overview.hospital');
Route::get('/admin/organization-overview-labtest', 'Backend\OverviewController@labtest')->name('organization.overview.labtest');
Route::get('/admin/organization-overview-both', 'Backend\OverviewController@both')->name('organization.overview.both');
Route::get('/admin/organization-overview-normal', 'Backend\OverviewController@normal')->name('organization.overview.normal');
Route::get('/admin/organization-overview-hospitalnopcr', 'Backend\OverviewController@hospitalnopcr')->name('organization.overview.hospitalnopcr');
Route::get('/admin/organization-overview-poe', 'Backend\OverviewController@poe')->name('organization.overview.poe');

Route::resource('/admin/download-dev-apks', 'Backend\DownloadApksController');

Route::resource('/admin/backup-restore', 'Backend\BackupRestoreController');

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    // Ignores notices and reports all other kinds... and warnings
    // error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}

Route::post('/api/v2/send_message', 'Backend\SendMessageController@fromWebAdmin');

Route::get('/admin/qrcode', 'Backend\AamakomayaGenerateQrcode@get')->name('aamakomaya.qrcode');
Route::get('/admin/activity-log', 'Backend\ActivityLogController@index')->name('activity-log.index');

Route::resource('admin/notice-board', 'NoticeBoardController');

Route::get('/artisan-clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});

Route::get('refresh-page', function () {
    \DB::table('cache')
        ->where('key', 'like', '%' . '-' . auth()->user()->token)->delete();
    return redirect()->back();
})->name('refresh-page');

Route::group(['prefix' => 'admin/messages'], function () {
    Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
    Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
    Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
});

Route::resource('/password-reset', 'ForgetPasswordController', ['names' => 'password-reset']);
Route::POST('/password-reset-user', 'ForgetPasswordController@updateUserPassword')->name('password-reset-user.update');

Route::get('/admin/patient', 'Reports\CaseDetailController@getCaseDetail');
Route::get('/admin/patient-old', 'Reports\CaseDetailController@getCaseDetailOld');
Route::get('admin/cict-patient-detail', 'Reports\CaseDetailController@cictDetail');

Route::get('/admin/patient/{token}/edit', 'Reports\CaseDetailController@edit');
Route::put('/admin/patient/{token}', 'Reports\CaseDetailController@update')->name('patient.update');
Route::get('/admin/sample/{token}/edit', 'Reports\AncDetailController@edit');
Route::put('/admin/sample/{token}', 'Reports\AncDetailController@update')->name('sample.update');
Route::get('/admin/sample/remove/{token}', 'Reports\AncDetailController@delete')->name('sample.remove');

//Route::resource('/observation-cases', 'Backend\ObservationCasesController');
Route::resource('/admin/cases-payment-observation', 'Backend\ObservationCasesController', ['names' => 'observation-cases']);

Route::get('/admin/sid-search', 'AdminController@sidSearch')->name('admin.sid.search');
Route::post('/admin/sid-search/update', 'AdminController@sidUpdate')->name('admin.sid.update');
Route::get('/admin/remaining-beds', 'Backend\WomanController@getRemainingBeds');


Route::get('/admin/analysis/gender', function () {
    return view('analysis.gender');
})->name('analysis.gender');

Route::get('/admin/analysis/occupation', function () {
    return view('analysis.occupation');
})->name('analysis.occupation');

Route::get('/admin/analysis/time-series', function () {
    return view('analysis.time-series');
})->name('analysis.time-series');

Route::get('/admin/analysis/antigen', function () {
    return view('analysis.antigen');
})->name('analysis.antigen');

///VACCINATIONS ///////////////
Route::post('/v1/covid-immunization-store', 'Api\CovidImmunizationController@store')->name('covid-immunization-store');
Route::get('/search/global', 'SearchGlobalController@index')->name('search.global');
Route::get('/search/global-card/{id}', 'SearchGlobalController@card')->name('search.global-card');


// send-hp-data?username=maiwakhola.rm&send=annapurna.app
Route::get('/send-hp-data', function (\Illuminate\Http\Request $request){
    $username = explode(",", $request->username);
    $send = $request->send;

    $token = \App\User::whereIn('username', $username)->pluck('token');
    try {
        $send_token =  \App\User::where('username', $send)->first()->token;
        $send_hp_code = \App\Models\Organization::where('token', $send_token)->first()->hp_code;
    }catch (\Exception $e){
        $send_hp_code = '';
    }


    $municipality_ids = \App\Models\MunicipalityInfo::whereIn('token', $token)->pluck('municipality_id');

    $org_token = \App\Models\Organization::whereIn('municipality_id', $municipality_ids)->pluck('token');

    $tokens = collect($token)->merge($org_token);

    $data_id = HealthProfessional::whereIn('checked_by', $tokens)->pluck('id')->toArray();

    $post = [
        'municipality_id' => 1,
        'hp_code' => $send_hp_code ?? '',
        'data_list' => implode(",", $data_id),
        'expire_date' => \Carbon\Carbon::now()->addDays(10)->format('Y-m-d')
    ];

    \App\CovidImmunization::create($post);
   return 'Pass';
});

Route::get('/public-client/add', 'Backend\PublicClientController@create')->name('public-client.add');
Route::post('/public-client/store', 'Backend\PublicClientController@store')->name('public-client.store');

Route::post('/excel-download/unvaccinated/{id}/{key}', function (\Illuminate\Http\Request $request, $id, $key){
    $arg = [
        'id' => $id,
        'key' => $key
    ];

    return Excel::download(new \App\Exports\UnVaccinatedExport($arg), $key.'-'.$id.'-unvaccinated-data-' . date('Y-m-d H:i:s') . '.xlsx');

})->name('excel-download.unvaccinated');

Route::post('/excel-download/vaccinated/{id}/{key}', function (\Illuminate\Http\Request $request, $id, $key){
    $arg = [
        'id' => $id,
        'key' => $key
    ];

    return Excel::download(new \App\Exports\VaccinatedExport($arg), $key.'-'.$id.'-vaccinated-data-' . date('Y-m-d H:i:s') . '.xlsx');

})->name('excel-download.vaccinated');

Route::get('admin/download/positive-list', function (\Illuminate\Http\Request $request){

    return Excel::download(new \App\Exports\DownloadablePositiveList($request), 'positive-list' . date('Y-m-d H:i:s') . '.xlsx');

});

Route::get('download/vaccination-list', function (\Illuminate\Http\Request $request){

    return Excel::download(new \App\Exports\VaccinationList($request), 'vaccination-list' . date('Y-m-d H:i:s') . '.xlsx');

});

Route::get('admin/download/sample-collection-download', function (\Illuminate\Http\Request $request){

    return Excel::download(new \App\Exports\NewDashboardSampleDownload($request), 'record-list' . date('Y-m-d H:i:s') . '.xlsx');

});


Route::get('admin/cases-report-payment', 'CasesPaymentController@report')->name('cases.payment.report');

Route::post('admin/cases-report-payment-send', 'CasesPaymentController@sendToDhis')->name('cases.payment.report-send');
Route::get('admin/cases-payment-all-by-organization', 'CasesPaymentController@allByOrganization')->name('cases.payment.by.organization');
Route::get('admin/cases-payment-all-by-institutional', 'CasesPaymentController@byInstitutional')->name('cases.payment.by.institutional');
Route::get('admin/cases-payment-all-by-lab-and-treatment', 'CasesPaymentController@byLabAndTreatment')->name('cases.payment.by.lab-treatment');
Route::get('admin/cases-payment-all-by-hospital-without-pcr-lab', 'CasesPaymentController@byHospitalWoPCRLab')->name('cases.payment.by.hospital.wo.pcrlab');

Route::get('e83249bd3ba79932e16fb1fb5100dafade9954c2', 'PublicDataController@index')->name('public.home.index');
Route::get('api/status', 'PublicDataController@publicPortal');


Route::get('admin/cases-payment-stock-list', 'StockController@listAdminStock')->name('stock.list');
Route::get('admin/cases-payment-stock-update', 'StockController@listStock')->name('stock.updateList');
Route::get('admin/cases-payment-stock-history', 'StockController@stockTransactionList')->name('stock.transaction.list');
Route::post('admin/stock-update', 'StockController@updateStock')->name('stock.update');



Route::get('admin/get-org-rep', function (Request $request){
    $response = FilterRequest::filter($request);
    $hpCodes = GetHealthpostCodes::filter($response);
    $dateReference = (\Carbon\Carbon::now()->subDays($request->date)->setTime(0, 0, 0));
    $data = \DB::table('payment_cases')
        ->where('payment_cases.updated_at', '>=' ,$dateReference)
        ->whereIn('payment_cases.hp_code', $hpCodes)
        ->join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
        ->join('municipalities', 'healthposts.municipality_id', '=', 'municipalities.id')
        ->select(['healthposts.name as organiation_name',
            'healthposts.no_of_beds',
            'healthposts.no_of_ventilators',
            'healthposts.no_of_icu',
            'healthposts.no_of_hdu',
            'healthposts.daily_consumption_of_oxygen',
            'healthposts.is_oxygen_facility',
            'municipalities.municipality_name as municipality',
            'payment_cases.health_condition', 'payment_cases.health_condition_update',
            'payment_cases.self_free', 'payment_cases.is_death'
        ])
        ->orderBy('healthposts.name', 'asc')
        ->get();


    $mapped_data = $data->map(function ($value) {
        $return = [];
        $return['organiation_name'] = $value->organiation_name.', '.$value->municipality;
        $return['no_of_beds'] = $value->no_of_beds;
        $return['no_of_ventilators'] = $value->no_of_ventilators;
        $return['no_of_icu'] = $value->no_of_icu;
        $return['no_of_hdu'] = $value->no_of_hdu;
        $return['daily_consumption_of_oxygen'] = $value->daily_consumption_of_oxygen;
        $return['is_oxygen_facility'] = $value->is_oxygen_facility;
        $return['self_free'] = $value->self_free;
        $return['is_death'] = $value->is_death;

        if ($value->health_condition_update == null){
            $return['health_condition'] = $value->health_condition;
        }else{
            $array_health_condition = json_decode($value->health_condition_update, true);
            $return['health_condition'] = collect($array_health_condition)->last()['id'];
        }
        return $return;
    })->groupBy(function($item) {
        return $item['organiation_name'];
    });
    $total = [
      'total_no_of_beds' => 0,
      'total_no_of_ventilators' => 0,
      'total_no_of_icu'=> 0,
      'total_no_of_hdu'=> 0,
      'daily_consumption_of_oxygen'=> 0,
      'used_total_no_of_beds'=> 0,
      'used_total_no_of_hdu'=> 0,
      'used_total_no_of_icu'=> 0,
      'used_total_no_of_ventilators'=> 0,
      'total_cases'=> 0,
      'total_under_treatment'=> 0,
      'total_discharge'=> 0,
      'total_death'=> 0
    ];
    $mapped_data_second = $mapped_data->map(function ($value) use (&$total){
        $return = [];
        $value = collect($value);
        $return['total_no_of_beds'] = collect($value->first())['no_of_beds'];
       
        $return['total_no_of_ventilators'] = collect($value->first())['no_of_ventilators'];
        $return['total_no_of_icu'] = collect($value->first())['no_of_icu'];
        $return['total_no_of_hdu'] = collect($value->first())['no_of_hdu'];
        $return['daily_consumption_of_oxygen'] = collect($value->first())['daily_consumption_of_oxygen'];
        $return['is_oxygen_facility'] = collect($value->first())['is_oxygen_facility'];

        $return['used_total_no_of_beds'] = collect($value)->where('is_death', null)->whereIn('health_condition', [1,2])->count();
        $return['used_total_no_of_hdu'] = collect($value)->where('is_death', null)->where('health_condition', 3)->count();
        $return['used_total_no_of_icu'] = collect($value)->where('is_death', null)->where('health_condition', 4)->count();
        $return['used_total_no_of_ventilators'] = collect($value)->where('is_death', null)->where('health_condition', 5)->count();

        $return['total_cases'] = $value->count();
        $return['total_under_treatment'] = $value->where('is_death', null)->count();

        $return['total_discharge'] = $value->where('is_death', 1)->count();
        $return['total_death'] = $value->where('is_death', 2)->count();
        foreach(array_keys($total) as $key) {
          $total[$key] = $total[$key] + $return[$key];
        }
        return $return;
    });
    return response()->json(['date_from' => date('Y-M-D H:i A', strtotime($dateReference)), 'date_to' => date('Y-M-D H:i A', strtotime(\Carbon\Carbon::now())),'total' => $total, 'data' => $mapped_data_second]);
});

Route::get('/admin/bulk-upload', 'Backend\BulkUploadController@list')->name('bulk.upload');



Route::get('/calc-data', function(){
    \App\Models\SampleCollectionOld::whereNull('collection_date_en')->whereRaw('LENGTH(token) > 16')->chunk(10000, function($collections) {
        $collections->map(function ($item){
            try{
                $date = explode('-', $item->token)[1];
                $parse_date = Carbon::parse('20'.$date);

                $collection_date_en = explode("-", $parse_date->toDateString());
                $collection_date_np = Calendar::eng_to_nep($collection_date_en[0], $collection_date_en[1], $collection_date_en[2])->getYearMonthDayEngToNep();

                $item->collection_date_en = $parse_date->toDateString();
                $item->collection_date_np = $collection_date_np;

                $token = $item->woman_token;
                $case = \App\Models\SuspectedCaseOld::where('token', $token)->first();
                if(!empty($case)) {
                    $case->register_date_np = $collection_date_np;
                    $case->register_date_en = $parse_date->toDateString();
                    $case->save();
                }
                $sample_token = $item->token;
                $lab_token = \App\Models\LabTestOld::where('sample_token', $sample_token)->first();
                if($lab_token){

                    try{
                        $received_date_np = explode("-", $lab_token->sample_recv_date);
                        $received_date_en_lab = Calendar::nep_to_eng($received_date_np[0], $received_date_np[1], $received_date_np[2])->getYearMonthDayNepToEng();
                        $sample_test_date_en = null;
                        if (!empty($lab_token->sample_test_date)){
                            $sample_test_date_np = explode("-", $lab_token->sample_test_date);
                            $sample_test_date_en = Calendar::nep_to_eng($sample_test_date_np[0], $sample_test_date_np[1], $sample_test_date_np[2])->getYearMonthDayNepToEng();
                        }
                        $item->received_by = $lab_token->checked_by;
                        $item->received_by_hp_code = $lab_token->hp_code;

                        $item->received_date_en = $received_date_en_lab;

                        $item->received_date_np = $lab_token->sample_recv_date;
                        $item->sample_test_date_en = $sample_test_date_en;
                        $item->sample_test_date_np = $lab_token->sample_test_date;

                        $item->sample_test_time = $lab_token->sample_test_time;
                        $item->lab_token = $lab_token->token;
                        $item->result = $lab_token->sample_test_result;
                    }catch (\Exception $e){
                    }
                }

                $item->save();

            }catch (\Exception $e){}

        });

    });

    return 'Success';
});

Route::get('/script/collection-date-fix/{type}', 'Backend\ScriptController@collectionDateFix');
Route::get('/script/reporting-date-fix/{type}', 'Backend\ScriptController@reportingDateFix');
Route::get('/script/received-date-fix/{type}', 'Backend\ScriptController@receivedDateFix');
