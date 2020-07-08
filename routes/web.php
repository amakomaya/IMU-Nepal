<?php

Route::get('locale/{locale}', function ($locale) {
    \Session::put('locale', $locale);
    return redirect()->back();
});

//Frontend

Route::get('/', function(){
    return redirect('/login');
});

Route::get('/welcome', function(){
    return view('welcome');
});

//Auth
Auth::routes();

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
Route::get('/admin/center-woman-maps', 'Backend\MapController@map')->name('center.woman.map');

//Backend Dho
Route::resource('admin/child', 'Backend\BabyDetailController');

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

//Bakend Healthpost
Route::resource('admin/healthpost', 'Backend\HealthpostController');

//Bakend Route
Route::resource('admin/ward', 'Backend\WardController');

//Bakend Fchv
Route::resource('admin/fchv', 'Backend\FchvController');

//Backend Health Worker
Route::resource('admin/health-worker', 'Backend\HealthWorkerController');

//Bakend Woman
Route::resource('admin/woman', 'Backend\WomanController');

//Route::delete('admin/woman/{token}','Backend\WomanController@delete');

Route::get('/admin/woman/{id}/checkup', 'Backend\WomanController@checkup')->name('woman.checkup');
Route::get('/admin/safe-maternity-program', 'Backend\WomanController@safeMaternityProgram')->name('woman.safe-maternity-program');
Route::get('/admin/woman-information', 'Backend\WomanController@information')->name('woman.information');
Route::get('/admin/woman-register-again/{id}', 'Backend\WomanController@registerAgain')->name('woman.register-again');
Route::put('/admin/womanregister-again-store/{id}', 'Backend\WomanController@registerAgainStore')->name('woman.register-again-store');
Route::get('/admin/report/{id}', 'Backend\WomanController@report')->name('woman.report');
Route::get('/admin/safe-maternity-program-report', 'Backend\WomanController@safeMaternityProgramReport')->name('woman.safe-maternity-program-report');
Route::get('/admin/delivery-service-according-to-castes', 'Backend\WomanController@deliveryServiceAccordingToCastes')->name('woman.delivery-service-according-to-castes');
Route::get('/admin/delivery-service-according-to-castes-report', 'Backend\WomanController@deliveryServiceAccordingToCastesReport')->name('woman.delivery-service-according-to-castes-report');
Route::get('/admin/td-vaccine-service', 'Backend\WomanController@tdVaccineService')->name('woman.td-vaccine-service');
Route::get('/admin/woman.td-vaccine-report', 'Backend\WomanController@tdVaccineReport')->name('woman.td-vaccine-report');
Route::get('/admin/details-about-maternal-and-newborn-infants', 'Backend\WomanController@detailsAboutMaternalAndNewbornInfants')->name('woman.details-about-maternal-and-newborn-infants');
Route::get('/admin/details-about-maternal-and-newborn-infants-report', 'Backend\WomanController@detailsAboutMaternalAndNewbornInfantsReport')->name('woman.details-about-maternal-and-newborn-infants-report');
Route::get('/admin/report-health-service-register','Backend\WomanController@WomanHealthServiceRegisterReport')->name('woman.report.health-service-register');
Route::get('/admin/report-health-service-register','Backend\WomanController@WomanHealthServiceRegisterReport')->name('woman.report.health-service-register');
Route::get('/admin/security-program-of-mother','Backend\WomanController@securityProgramOfMother')->name('woman.security-program-of-mother');


// Route::get('/admin/vaccination-program-report','Backend\WomanController@vaccinationProgram')->name('woman.vaccination-program');
Route::get('/admin/woman/{id}/anc-visit-schedule','Backend\WomanController@ancVisitSchedule')->name('woman.anc-visit-schedule');
Route::get('/admin/woman-maps', 'Backend\WomanController@womanMaps')->name('woman.map');
Route::get('/admin/vaccine-detail-list','Backend\WomanController@vaccineDetailList')->name('woman.vaccination-detail-list');
Route::get('/admin/raw-details-about-maternal-and-newborn-infants','Backend\WomanController@rawDetailsAboutMaternalAndNewbornInfantsReport')->name('woman.raw-details-about-maternal-and-newborn-infants-report');
Route::get('/admin/woman-anc-visit-schedule','Backend\WomanController@womanANCVisitSchedule')->name('woman.visit-schedule');

Route::get('/admin/vaccination-program-report','Reports\VaccineProgramController@vaccinationProgram')->name('woman.vaccination-program');


//Backend ChildReport
Route::get('/admin/vaccine-received-usage-wastage','Backend\ChildReportController@vaccineReceivedUsageWastage')->name('child-report.vaccine-received-usage-wastage');
Route::get('/admin/registered-child','Backend\ChildReportController@registeredChild')->name('child-report.registered-child');
Route::get('/admin/immunized-child','Backend\ChildReportController@immunizedChild')->name('child-report.immunized-child');
Route::get('/admin/droupout-child','Backend\ChildReportController@droupoutChild')->name('child-report.droupout-child');
Route::get('/admin/eligible-child','Backend\ChildReportController@eligibleChild')->name('child-report.eligible-child');

Route::get('/admin/vaccinated-child-report','Backend\ChildReportController@vaccinatedChildReport')->name('child.raw-details-about-vaccinated-child');
Route::get('/admin/child-health-report-card/{id}','Backend\ChildReportController@healthReport')->name('child-report.health-report-card');

//Bakend Anc
Route::resource('admin/anc', 'Backend\Woman\AncController');

//Bakend Delivery
Route::resource('admin/delivery', 'Backend\Woman\DeliveryController');

//Bakend BabyDetail
Route::resource('admin/baby-detail', 'Backend\Woman\Delivery\BabyDetailController');
Route::get('/admin/baby', 'Backend\Woman\Delivery\BabyDetailController@baby')->name('baby.baby');
Route::get('/admin/baby/{id}/show', 'Backend\Woman\Delivery\BabyDetailController@babyShow')->name('baby-detail.baby-show');

//Bakend LabTest
Route::resource('admin/lab-test', 'Backend\Woman\LabTestController');

//Bakend Pnc
Route::resource('admin/pnc', 'Backend\Woman\PncController');

//Backend TransferWoman
Route::get('/transfer-woman/transfer/{from_hp_code}/{woman_token}', 'Backend\TransferWomanController@transfer')->name('transfer-woman.transfer');
Route::post('/transfer-woman/transfer-store', 'Backend\TransferWomanController@transferStore')->name('transfer-woman.transfer-store');
Route::get('/transfer-woman/transfer-confirm/{from_hp_code}/{woman_token}', 'Backend\TransferWomanController@transferConfirm')->name('transfer-woman.transfer-confirm');
Route::post('/transfer-woman/transfer-complete/{id}', 'Backend\TransferWomanController@transferComplete')->name('transfer-woman.transfer-complete');

//Bakend OutReachClinic
Route::resource('admin/out-reach-clinic', 'Backend\OutReachClinicController');

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


Route::get('/api/delivery', 'Api\DeliveryController@index')->name('api.delivery.index');
Route::get('/api/delivery/1', 'Api\DeliveryController@show')->name('api.delivery.show');
Route::get('/api/delivery-show-by-woman-token', 'Api\DeliveryController@showDeliveryByWomanToken')->name('api.delivery-show-by-woman-token');
Route::post('/api/delivery', 'Api\DeliveryController@store')->name('api.delivery.store');
Route::post('/api/delivery/{id}', 'Api\DeliveryController@update')->name('api.delivery.update');

Route::get('/api/baby-detail', 'Api\BabyDetailController@index')->name('api.baby-detail.index');
Route::get('/api/baby-detail/1', 'Api\BabyDetailController@show')->name('api.baby-detail.show');
Route::get('/api/baby-detail-show-by-woman-token', 'Api\BabyDetailController@showBabyDetailByWomanToken')->name('api.baby-detail-show-by-woman-token');
Route::post('/api/baby-detail', 'Api\BabyDetailController@store')->name('api.baby-detail.store');
Route::post('/api/baby-detail-individual', 'Api\BabyDetailController@storeIndividual')->name('api.baby-detail.baby-detail-individual');
Route::post('/api/baby-detail/{id}', 'Api\BabyDetailController@update')->name('api.baby-detail.update');

Route::get('/api/pnc', 'Api\PncController@index')->name('api.pnc.index');
Route::get('/api/pnc/1', 'Api\PncController@show')->name('api.pnc.show');
Route::get('/api/pnc-show-by-woman-token', 'Api\PncController@showPncByWomanToken')->name('api.pnc-show-by-woman-token');
Route::post('/api/pnc', 'Api\PncController@store')->name('api.pnc.store');
Route::post('/api/pnc/{id}', 'Api\PncController@update')->name('api.pnc.update');

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

Route::get('/api/aefi', 'Api\AefiController@index')->name('api.aefi.index');
Route::get('/api/aefi/1', 'Api\AefiController@show')->name('api.aefi.show');
Route::post('/api/aefi', 'Api\AefiController@store')->name('api.aefi.store');
Route::post('/api/aefi/{id}', 'Api\AefiController@update')->name('api.aefi.update');

Route::get('/api/vaccination-record', 'Api\VaccinationRecordController@index')->name('api.vaccination-record.index');
Route::get('/api/vaccination-record/1', 'Api\VaccinationRecordController@show')->name('api.vaccination-record.show');
Route::post('/api/vaccination-record', 'Api\VaccinationRecordController@store')->name('api.vaccination-record.store');
Route::post('/api/vaccination-record/{id}', 'Api\VaccinationRecordController@update')->name('api.vaccination-record.update');

Route::get('/api/vaccine-vial', 'Api\VaccineVialController@index')->name('api.vaccine-vial.index');
Route::get('/api/vaccine-vial/1', 'Api\VaccineVialController@show')->name('api.vaccine-vial.show');
Route::post('/api/vaccine-vial', 'Api\VaccineVialController@store')->name('api.vaccine-vial.store');
Route::post('/api/vaccine-vial/{id}', 'Api\VaccineVialController@update')->name('api.vaccine-vial.update');

Route::get('/api/vaccine-vial-stock', 'Api\VaccineVialStockController@index')->name('api.vaccine-vial-stock.index');
Route::get('/api/vaccine-vial-stock/1', 'Api\VaccineVialStockController@show')->name('api.vaccine-vial-stock.show');
Route::post('/api/vaccine-vial-stock', 'Api\VaccineVialStockController@store')->name('api.vaccine-vial-stock.store');
Route::post('/api/vaccine-vial-stock-update', 'Api\VaccineVialStockController@update')->name('api.vaccine-vial-stock.update');

Route::get('/api/baby_weight', 'Api\BabyWeightController@index')->name('api.baby_weight.index');
Route::get('/api/baby_weight/1', 'Api\BabyWeightController@show')->name('api.baby_weight.show');
Route::post('/api/baby_weight', 'Api\BabyWeightController@store')->name('api.baby_weight.store');
Route::post('/api/baby_weight/{id}', 'Api\BabyWeightController@update')->name('api.baby_weight.update');

Route::post('/api/vial-to-child/login', 'Api\VialToChildController@login')->name('api.vial-to-child.login');
Route::get('/api/vial-to-child/baby', 'Api\VialToChildController@baby')->name('api.vial-to-child.baby');
Route::post('/api/vial-to-child/create-baby', 'Api\VialToChildController@storeBaby')->name('api.vial-to-child.create-baby');

Route::get('/admin/overview-data', 'Backend\OverviewController@index')->name('admin.overview');
Route::get('/admin/fchv-overview', 'Backend\OverviewController@fchv')->name('admin.fchv-overview'); 

Route::resource('/admin/download-dev-apks', 'Backend\DownloadApksController');

Route::resource('/admin/content-app/multimedia', 'ContentApp\MultimediaController');
Route::resource('/admin/content-app/news-feed', 'ContentApp\NewsFeedController');
Route::resource('/admin/content-app/advertisement', 'ContentApp\AdvertisementController');
Route::get('/admin/content-app/woman-registered', 'ContentApp\WomanRegistrationController@index')->name('content-app.reg.woman.index');
Route::delete('/admin/content-app/woman-registered/{id}', 'ContentApp\WomanRegistrationController@delete')->name('content-app.reg.woman.delete');
Route::get('/admin/content-app/woman-registered/{id}', 'ContentApp\WomanRegistrationController@edit')->name('content-app.reg.woman.edit');
Route::patch('/admin/content-app/woman-registered/{id}', 'ContentApp\WomanRegistrationController@update')->name('content-app.reg.woman.update');

Route::post('/admin/content-app/woman-registered-send-care', 'ContentApp\WomanRegistrationController@sendCare')->name('content-app.reg.woman.send-care');
Route::get('/admin/content-app/woman-registered-send-care/{id}', 'ContentApp\WomanRegistrationController@showSendCare')->name('content-app.reg.woman.show');

Route::get('/admin/content-app/map', 'ContentApp\WomanMapController@index');

Route::get('/report/woman/self-evaluation', 'ContentApp\SelfEvaluationController@report')->name('woman-self-evaluation.report');

Route::resource('/admin/backup-restore', 'Backend\BackupRestoreController');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/laravel-filemanager', '\UniSharp\LaravelFilemanager\Controllers\LfmController@show');
    Route::post('/laravel-filemanager/upload', '\UniSharp\LaravelFilemanager\Controllers\UploadController@upload');
    // list all lfm routes here...
});

if (version_compare(PHP_VERSION, '7.2.0', '>=')) {
    // Ignores notices and reports all other kinds... and warnings
    // error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING);
    error_reporting(E_ALL ^ E_WARNING); // Maybe this is enough
}
/*Nirjal Routes*/
/*Ancs routes*/

Route::get('woman','Backend\Woman\HealthCardController@woman_edit')->name('woman.woman_edit');
Route::post('woman/{woman}','Backend\Woman\HealthCardController@woman_update')->name('woman.woman_update');

Route::delete('woman/{woman}','Backend\Woman\HealthCardController@woman_destroy')->name('woman.woman_destroy');


Route::post('ancs/{ancs}','Backend\Woman\HealthCardController@ancs_update')->name('ancs.ancs_update');

Route::get('ancs/{ancs}','Backend\Woman\HealthCardController@ancs_edit')->name('ancs.ancs_edit');

Route::delete('ancs/{ancs}','Backend\Woman\HealthCardController@ancs_destroy')->name('ancs.ancs_destroy');


Route::post('labtest/{test}','Backend\Woman\HealthCardController@lab_test_update')->name('labtest.labtest');
Route::get('labtest/{test}','Backend\Woman\HealthCardController@lab_test_edit')->name('labtest.laptest_edit');
Route::delete('labtest/{test}','Backend\Woman\HealthCardController@labtest_destroy')->name('labtest.labtest_destroy');

Route::post('delivery/{deliveries}','Backend\Woman\HealthCardController@delivery_update')->name('delivery.delivery_update');
Route::get('delivery/{deliveries}','Backend\Woman\HealthCardController@delivery_edit')->name('delivery.edit_delivery');
Route::delete('delivery/{deliveries}','Backend\Woman\HealthCardController@delivery_desctory')->name('delivery.delivery_desctory');

Route::post('pncs/{pncs}','Backend\Woman\HealthCardController@pncs_update')->name('pncs.pncs_update');

Route::get('pncs/{pncs}','Backend\Woman\HealthCardController@pncs_edit')->name('pncs.pncs_edit');

Route::delete('pncs/{pncs}','Backend\Woman\HealthCardController@pncs_destroy')->name('pncs.pncs_destroy');
/*End routes*/


Route::resource('baby','Backend\Baby\BabyController');


Route::post('/api/v2/send_message', 'Backend\SendMessageController@fromWebAdmin');

Route::get('/vaccine-vial-management/{hp_code}', 'Backend\VaccineVialManagement@showAll')->name('vaccine-vial-management');

Route::get('/admin/aamakomaya-qrcode', 'Backend\AamakomayaGenerateQrcode@get')->name('aamakomaya.qrcode');
Route::get('/admin/activity-log', 'Backend\ActivityLogController@index')->name('activity-log.index');

Route::get('/artisan-clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
    return "Cleared!";
});

Route::get('/covid-19/user','SurveyController@user')->name('survey.user');
Route::post('/covid-19/user','SurveyController@userStore')->name('survey.user.store');
Route::get('/covid-19/report','SurveyController@index')->name('report.survey');
Route::post('/covid-19/report/login','SurveyController@login')->name('report.survey.login');
Route::get('/covid-19/report/logout','SurveyController@logout')->name('report.survey.logout');
Route::post('/covid-19/export','SurveyController@export')->name('report.survey.export');

Route::resource('admin/appointment', 'Backend\AppointmentController');
Route::get('admin/appointment-plan', 'Backend\AppointmentController@indexPlan')->name('appointment.plan.index');
Route::get('admin/appointment-plan/create', 'Backend\AppointmentController@createPlan')->name('appointment.plan.create');
Route::post('admin/appointment-plan', 'Backend\AppointmentController@storePlan')->name('appointment.plan.store');
Route::get('admin/appointment-plan/{id}', 'Backend\AppointmentController@editPlan')->name('appointment.plan.edit');
Route::patch('admin/appointment-plan/{id}', 'Backend\AppointmentController@updatePlan')->name('appointment.plan.update');