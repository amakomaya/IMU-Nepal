<?php

use App\Models\District;
use App\Models\HealthProfessional;
use App\Models\Municipality;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;

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
Route::get('/admin/healthpost-select', 'AdminController@healthpostSelect')->name('admin.healthpost-select');
Route::get('/admin/municipality-select-province', 'AdminController@municipalitySelectByProvince')->name('admin.municipality-select-province');
Route::get('/admin/municipality-select-district', 'AdminController@municipalitySelectByDistrict')->name('admin.municipality-select-district');
Route::get('/admin/district-select-province', 'AdminController@districtSelectByProvince')->name('admin.district-select-province');
Route::get('/admin/ward-select-municipality', 'AdminController@wardSelectByMunicipality')->name('admin.ward-select-municipality');
Route::get('/admin/healthpost-select-ward', 'AdminController@healthpostSelectByWard')->name('admin.healthpost-select-ward');
Route::get('/admin/select-from-to', 'AdminController@selectFromTo')->name('admin.select-from-to');
Route::get('/admin/district-value', 'AdminController@getDistrictValue')->name('admin.district-value');

Route::get('/admin/organization-select', 'AdminController@organizationSelect')->name('admin.organization-select');


Route::get('/health-professional/add', function (\Illuminate\Http\Request $request) {
    $province_id = 1;
    $district_id = 1;
    $municipality_id = 1;
    $districts = District::where('province_id', $province_id)->orderBy('district_name', 'asc')->get();
    $municipalities = Municipality::where('district_id', $district_id)->orderBy('municipality_name', 'asc')->get();

    try{
        $data = HealthProfessional::where('checked_by', Auth::user()->token)->latest()->first();
        $data['organization_type'] = $data->organization_type ?? '';
        $data['organization_name'] = $data->organization_name ?? '';
        $data['organization_phn'] = $data->organization_phn ?? '';
        $data['organization_address'] = $data->organization_address ?? '';
    }catch (\Exception $exception){
        $data['organization_type'] = '';
        $data['organization_name'] = '';
        $data['organization_phn'] = '';
        $data['organization_address'] = '';
        return view('health-professional.public-create', compact('province_id', 'district_id', 'municipality_id', 'districts','municipalities', 'data'));
    }
    return view('health-professional.add', compact('province_id', 'district_id', 'municipality_id', 'districts','municipalities', 'data'));
})->name('health.professional.add');
Route::post('/health-professional', 'Backend\HealthProfessionalController@store')->name('health-professional.store');
Route::get('/health-professional/index', 'Backend\HealthProfessionalController@index')->name('health-professional.index');
Route::get('/health-professional/immunized', 'Backend\HealthProfessionalController@immunized')->name('health-professional.immunized');
Route::get('/health-professional/show/{id}', 'Backend\HealthProfessionalController@show')->name('health-professional.show');
Route::get('/health-professional/edit/{id}', 'Backend\HealthProfessionalController@edit')->name('health-professional.edit');
Route::put('/health-professional/update/{id}', 'Backend\HealthProfessionalController@update')->name('health-professional.update');
Route::get('/health-professional/temp-municipality-select-district', 'Backend\AddressController@municipalitySelectByDistrict')->name('temp-municipality-select-district');
Route::get('/health-professional/temp-district-select-province', 'Backend\AddressController@districtSelectByProvince')->name('temp-district-select-province');
Route::get('/health-professional/perm-municipality-select-district', 'Backend\AddressController@permMunicipalitySelectByDistrict')->name('perm-municipality-select-district');
Route::get('/health-professional/perm-district-select-province', 'Backend\AddressController@permDistrictSelectByProvince')->name('perm-district-select-province');
Route::get('/health-professional/export', 'Backend\HealthProfessionalController@export')->name('health-professional.export');
Route::get('/vaccination/reports', 'Backend\VaccinationReportsController@index')->name('vaccination.report');

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
Route::get('admin/organization/{id}/edit-record', 'Backend\HealthpostController@editRecord');
Route::post('admin/organization/update-record/{id}', 'Backend\HealthpostController@updateRecord')->name('admin.organization.update-record');
Route::post('admin/vaccination_center_update', 'Backend\HealthpostController@organizationUpdate')->name('admin.organization.update');

//Bakend Route
Route::resource('admin/ward', 'Backend\WardController');

//Bakend Lab
Route::resource('admin/lab-user', 'Backend\FchvController', ['names' => 'fchv']);
Route::get('/admin/lab-patients', function () {
    return view('backend.lab.index');
})->name('lab.patient.index');
Route::get('/admin/lab-case-report', 'Backend\LabReportController@index')->name('lab.patient.report.index');
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
Route::get('admin/add-multiple-sample', 'Backend\WomanController@addSampleCollection')->name('patient.multiple-sample.create');
Route::get('admin/negative-patients', 'Backend\WomanController@negativeIndex')->name('patients.negative.index');
Route::get('admin/positive-patients', 'Backend\WomanController@positiveIndex')->name('patients.positive.index');
Route::get('admin/tracing-patients', 'Backend\WomanController@tracingIndex')->name('patients.tracing.index');
Route::get('admin/lab-received-patients', 'Backend\WomanController@labReceivedIndex')->name('patients.lab-received.index');
Route::get('admin/cases-recovered', 'Backend\WomanController@casesRecoveredIndex')->name('cases.recovered.index');
Route::get('admin/cases-payment', 'Backend\WomanController@casesPaymentIndex')->name('cases.payment.index');
Route::get('admin/cases-discharge-payment', 'Backend\WomanController@casesPaymentDischargeIndex')->name('cases.payment.index-discharge');
Route::get('admin/cases-death-payment', 'Backend\WomanController@casesPaymentDeathIndex')->name('cases.payment.index-death');
Route::get('admin/cases-payment-create', 'Backend\WomanController@casesPaymentCreate')->name('cases.payment.create');
Route::get('admin/cases-death', 'Backend\WomanController@casesDeathIndex')->name('cases.death.index');
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
Route::get('admin/cict-patient-detail', 'Reports\CaseDetailController@cictDetail');

Route::get('/admin/patient/{token}/edit', 'Reports\CaseDetailController@edit');
Route::put('/admin/patient/{token}', 'Reports\CaseDetailController@update')->name('patient.update');
Route::get('/admin/sample/{token}/edit', 'Reports\AncDetailController@edit');
Route::put('/admin/sample/{token}', 'Reports\AncDetailController@update')->name('sample.update');

//Route::resource('/observation-cases', 'Backend\ObservationCasesController');
Route::resource('/admin/cases-payment-observation', 'Backend\ObservationCasesController', ['names' => 'observation-cases']);

Route::get('/admin/ancs-search', 'AdminController@ancsSearch')->name('admin.ancs.search');
Route::post('/admin/ancs-search/update', 'AdminController@ancsUpdate')->name('admin.ancs.update');
Route::get('/admin/case-payment-dropdown', 'Backend\WomanController@casePaymentDropdown');


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

Route::get('admin/cases-report-payment', 'CasesPaymentController@report')->name('cases.payment.report');

Route::post('admin/cases-report-payment-send', 'CasesPaymentController@sendToDhis')->name('cases.payment.report-send');
Route::get('admin/cases-payment-all-by-organization', 'CasesPaymentController@allByOrganization')->name('cases.payment.by.organization');
Route::get('admin/cases-payment-all-by-institutional', 'CasesPaymentController@byInstitutional')->name('cases.payment.by.institutional');
Route::get('admin/cases-payment-all-by-lab-and-treatment', 'CasesPaymentController@byLabAndTreatment')->name('cases.payment.by.lab-treatment');

Route::get('home', 'PublicDataController@index')->name('public.home.index');
Route::get('api/status', 'PublicDataController@publicPortal');

Route::get('admin/cases-payment-stock-update', 'StockController@listStock')->name('stock.list');
Route::get('admin/cases-payment-stock-history', 'StockController@stockTransactionList')->name('stock.transaction.list');
Route::post('admin/stock-update', 'StockController@updateStock')->name('stock.update');


