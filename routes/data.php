<?php


Route::resource('api/women', 'Data\Api\WomenController');
Route::get('api/active-patient', 'Data\Api\WomenController@activeIndex');
Route::get('api/passive-patient', 'Data\Api\WomenController@passiveIndex');


Route::get('/api/patient/export', 'Data\Api\WomenController@export');
Route::get('/api/lab-patient/export', 'Data\Api\WomenController@labExport');

Route::put('api/women/anc/{token}', 'Data\Api\WomenController@updateAnc');
Route::delete('api/women/anc/{token}', 'Data\Api\WomenController@deleteAnc');

Route::put('api/women/delivery/{token}', 'Data\Api\WomenController@updateDelivery');
Route::delete('api/women/delivery/{token}', 'Data\Api\WomenController@deleteDelivery');

Route::put('api/women/user/{token}', 'Data\Api\WomenController@updateUser');

Route::resource('api/baby', 'Data\Api\BabyController');

Route::put('api/baby/vaccination/{id}', 'Data\Api\BabyController@updateVaccination');
Route::delete('api/baby/vaccination/{id}', 'Data\Api\BabyController@deleteVaccination');
