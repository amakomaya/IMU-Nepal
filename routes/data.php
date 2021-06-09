<?php


Route::resource('api/women', 'Data\Api\WomenController');
Route::get('api/active-patient', 'Data\Api\WomenController@activeIndex');
Route::get('api/active-pending-patient', 'Data\Api\WomenController@activePendingIndex');
Route::get('api/active-pending-patient-old', 'Data\Api\WomenController@activePendingIndexOld');
Route::get('api/active-antigen-pending-patient', 'Data\Api\WomenController@activeAntigenPendingIndex');
Route::get('api/active-antigen-pending-patient-old', 'Data\Api\WomenController@activeAntigenPendingIndexOld');
Route::get('api/passive-patient', 'Data\Api\WomenController@passiveIndex');
Route::get('api/passive-patient-old', 'Data\Api\WomenController@passiveIndexOld');
Route::get('api/passive-patient-antigen', 'Data\Api\WomenController@passiveAntigenIndex');
Route::get('api/passive-patient-antigen-old', 'Data\Api\WomenController@passiveAntigenIndexOld');
Route::get('api/positive-patient', 'Data\Api\WomenController@positiveIndex');
Route::get('api/positive-patient-old', 'Data\Api\WomenController@positiveIndexOld');
Route::get('api/positive-patient-antigen', 'Data\Api\WomenController@positiveAntigenIndex');
Route::get('api/positive-patient-antigen-old', 'Data\Api\WomenController@positiveAntigenIndexOld');
Route::get('api/tracing-patient', 'Data\Api\WomenController@tracingIndex');
Route::get('api/lab-received', 'Data\Api\WomenController@labReceivedIndex');
Route::get('api/lab-received-old', 'Data\Api\WomenController@labReceivedIndexOld');
Route::get('api/lab-received-antigen', 'Data\Api\WomenController@labReceivedAntigenIndex');
Route::get('api/lab-received-antigen-old', 'Data\Api\WomenController@labReceivedAntigenIndexOld');
Route::get('api/cases-recovered', 'Data\Api\WomenController@casesRecoveredIndex');
Route::get('api/cases-death', 'Data\Api\WomenController@casesDeathIndex');
Route::get('api/cases-in-other-organization', 'Data\Api\WomenController@casesInOtherOrganization');
Route::get('api/cases-payment', 'Data\Api\WomenController@casesPaymentIndex');
Route::get('api/cases-payment-discharge', 'Data\Api\WomenController@casesPaymentDischargeIndex');
Route::get('api/cases-payment-death', 'Data\Api\WomenController@casesPaymentDeathIndex');

Route::get('api/lab/received-sample', 'Data\Api\WomenController@labAddReceivedIndex');
Route::get('api/lab/add-result-negative', 'Data\Api\WomenController@labAddResultNegativeIndex');
Route::get('api/lab/add-result-positive', 'Data\Api\WomenController@labAddResultPositiveIndex');

Route::get('api/admin/dashboard', 'Data\Api\DashboardController@index');

Route::get('/api/patient/export', 'Data\Api\WomenController@export');
Route::get('/api/lab-patient/export', 'Data\Api\WomenController@labExport');

Route::put('api/women/anc/{token}', 'Data\Api\WomenController@updateAnc');
Route::delete('api/women/anc/{token}', 'Data\Api\WomenController@deleteAnc');

Route::put('api/women/delivery/{token}', 'Data\Api\WomenController@updateDelivery');
Route::delete('api/women/delivery/{token}', 'Data\Api\WomenController@deleteDelivery');

Route::put('api/women/user/{token}', 'Data\Api\WomenController@updateUser');

Route::get('api/gender', 'Data\Api\AggregateController@gender');
Route::get('api/time-series', 'Data\Api\AggregateController@timeSeries');
Route::get('api/occupation', 'Data\Api\AggregateController@occupation');
Route::get('api/antigen', 'Data\Api\AggregateController@antigen');
