<?php

Route::get('district-select-province', 'Reports\FilterController@districtSelectByProvince')->name('district-select-province');
Route::get('municipality-select-district', 'Reports\FilterController@municipalitySelectByDistrict')->name('municipality-select-district');
Route::get('ward-or-healthpost-select-municipality', 'Reports\FilterController@WardOrHealthpostByMunicipality')->name('ward-or-healthpost-select-municipality');


Route::get('ward-select-municipality', 'Reports\FilterController@wardSelectByMunicipality')->name('ward-select-municipality');

Route::get('healthpost-select-municipality', 'Reports\FilterController@healthpostSelectByMunicipality')->name('healthpost-select-municipality');



Route::get('healthpost-select', 'Reports\FilterController@healthpostSelect');
Route::get('select-from-to', 'Reports\FilterController@selectFromTo');


Route::get('dashboard', 'Reports\DashboardController@index')->name('report.dashboard');