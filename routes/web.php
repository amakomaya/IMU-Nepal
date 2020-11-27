<?php

Route::get('locale/{locale}', function ($locale) {
    \Session::put('locale', $locale);
    return redirect()->back();
});

//Frontend

Route::get('/', function(){
    return redirect('/login');
});

//Auth
Auth::routes();
Route::resource('admin/permissions','PermissionController', ['name' => 'permissions']);

//Backend Home
Route::get('/admin', 'AdminController@index')->name('admin');
Route::get('/admin/healthpost-select', 'AdminController@healthpostSelect')->name('admin.healthpost-select');
Route::get('/admin/municipality-select-province', 'AdminController@municipalitySelectByProvince')->name('admin.municipality-select-province');
Route::get('/admin/municipality-select-district', 'AdminController@municipalitySelectByDistrict')->name('admin.municipality-select-district');
Route::get('/admin/district-select-province', 'AdminController@districtSelectByProvince')->name('admin.district-select-province');
Route::get('/admin/ward-select-municipality', 'AdminController@wardSelectByMunicipality')->name('admin.ward-select-municipality');
Route::get('/admin/healthpost-select-ward', 'AdminController@healthpostSelectByWard')->name('admin.healthpost-select-ward');
Route::get('/admin/select-from-to', 'AdminController@selectFromTo')->name('admin.select-from-to');

//Backend Center
Route::resource('admin/center', 'Backend\CenterController');
Route::get('/admin/center-maps', 'Backend\MapController@map')->name('center.woman.map');

//Backend Dho
Route::resource('admin/dho', 'Backend\DHOController');

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
Route::resource('admin/healthpost', 'Backend\HealthpostController');

//Bakend Route
Route::resource('admin/ward', 'Backend\WardController');

//Bakend Lab
Route::resource('admin/lab-user', 'Backend\FchvController', ['names' => 'fchv']);
Route::get('/admin/lab-patients', function (){
    return view('backend.lab.index');
})->name('lab.patient.index');
Route::get('/admin/lab-negative-patients', function (){
    return view('backend.lab.negative-index');
})->name('lab.negative.patients.index');
Route::get('/admin/lab-positive-patients', function (){
    return view('backend.lab.positive-index');
})->name('lab.positive.patients.index');

//Backend Health Worker
Route::resource('admin/health-worker', 'Backend\HealthWorkerController');

//Bakend Patients
Route::resource('admin/patients', 'Backend\WomanController', ['names' => 'woman']);
Route::get('admin/negative-patients', 'Backend\WomanController@negativeIndex')->name('patients.negative.index');
Route::get('admin/positive-patients', 'Backend\WomanController@positiveIndex')->name('patients.positive.index');
Route::get('admin/lab-received-patients', 'Backend\WomanController@labReceivedIndex')->name('patients.lab-received.index');
Route::get('admin/cases-recovered', 'Backend\WomanController@casesRecoveredIndex')->name('cases.recovered.index');
Route::get('admin/cases-death', 'Backend\WomanController@casesDeathIndex')->name('cases.death.index');
Route::get('admin/sample-collection/create/{token}', 'Backend\WomanController@sampleCollectionCreate')->name('patients.sample-collection.store');
Route::post('admin/sample-collection', 'Backend\WomanController@sampleCollectionStore')->name('patient.sample.store');

Route::resource('admin/profile', 'Backend\ProfileController');


//Bakend Api
Route::get('/api/healthpost', 'Api\HealthpostController@index')->name('api.healthpost.index');
Route::get('/api/healthpost/1', 'Api\HealthpostController@show')->name('api.healthpost.show');
Route::post('/api/healthpost', 'Api\HealthpostController@store')->name('api.healthpost.store');
Route::post('/api/healthpost/{id}', 'Api\HealthpostController@update')->name('api.healthpost.update');

Route::get('/api/anc', 'Api\AncController@index')->name('api.anc.index');
Route::get('/api/anc/1', 'Api\AncController@show')->name('api.anc.show');
Route::get('/api/anc-show-by-woman-token', 'Api\AncController@showAncByWomanToken')->name('api.anc.anc-show-by-woman-token');
Route::post('/api/anc', 'Api\AncController@store')->name('api.anc.store');
Route::post('/api/anc/{id}', 'Api\AncController@update')->name('api.anc.update');

Route::get('/api/user', 'Api\UserController@index')->name('api.user.index');
Route::get('/api/user/1', 'Api\UserController@show')->name('api.user.show');
Route::post('/api/user', 'Api\UserController@store')->name('api.user.store');
Route::post('/api/user/{id}', 'Api\UserController@update')->name('api.user.update');

Route::get('/api/woman', 'Api\WomanController@index')->name('api.woman.index');
Route::get('/api/woman/1', 'Api\WomanController@show')->name('api.woman.show');
Route::post('/api/woman', 'Api\WomanController@store')->name('api.woman.store');
Route::post('/api/woman/{id}', 'Api\WomanController@update')->name('api.woman.update');
Route::post('/api/woman-login', 'Api\WomanController@login')->name('api.woman.login');
Route::post('/api/woman-signup', 'Api\WomanController@signup')->name('api.woman.signup');

Route::get('/api/health-worker', 'Api\HealthWorkerController@index')->name('api.health-worker.index');
Route::get('/api/health-worker/1', 'Api\HealthWorkerController@show')->name('api.health-worker.show');
Route::post('/api/health-worker', 'Api\HealthWorkerController@store')->name('api.health-worker.store');
Route::post('/api/health-worker/{id}', 'Api\HealthWorkerController@update')->name('api.health-worker.update');

Route::post('/api/login', 'Api\UserController@login')->name('api.user.login');
Route::get('/api/district', 'Api\DistrictController@index')->name('api.district.index');
Route::get('/api/municipality', 'Api\MunicipalityController@index')->name('api.municiplaity.index');
Route::get('/api/province' , function(){
	return \App\Models\Province::all();
});

Route::get('/admin/overview-data', 'Backend\OverviewController@index')->name('admin.overview');
Route::get('/admin/fchv-overview', 'Backend\OverviewController@fchv')->name('admin.fchv-overview'); 

Route::resource('/admin/download-dev-apks', 'Backend\DownloadApksController');

Route::resource('/admin/backup-restore', 'Backend\BackupRestoreController');

// Route::group(['prefix' => 'filemanager', 'middleware' => ['web', 'auth']], function () {
//     \UniSharp\LaravelFilemanager\Lfm::routes();
// });

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    // Ignores notices and reports all other kinds... and warnings
    // error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}

Route::post('/api/v2/send_message', 'Backend\SendMessageController@fromWebAdmin');

Route::get('/admin/qrcode', 'Backend\AamakomayaGenerateQrcode@get')->name('aamakomaya.qrcode');
Route::get('/admin/activity-log', 'Backend\ActivityLogController@index')->name('activity-log.index');

Route::resource('admin/notice-board', 'NoticeBoardController');

Route::get('/artisan-clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});

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

Route::get('/admin/patient/{token}/edit', 'Reports\CaseDetailController@edit');
Route::put('/admin/patient/{token}', 'Reports\CaseDetailController@update')->name('patient.update');
Route::get('/admin/sample/{token}/edit', 'Reports\AncDetailController@edit');
Route::put('/admin/sample/{token}', 'Reports\AncDetailController@update')->name('sample.update');
