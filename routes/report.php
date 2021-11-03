<?php

Route::get('district-select-province', 'Reports\FilterController@districtSelectByProvince')->name('district-select-province');
Route::get('municipality-select-district', 'Reports\FilterController@municipalitySelectByDistrict')->name('municipality-select-district');
Route::get('ward-or-healthpost-select-municipality', 'Reports\FilterController@WardOrHealthpostByMunicipality')->name('ward-or-healthpost-select-municipality');

Route::get('ward-select-municipality', 'Reports\FilterController@wardSelectByMunicipality')->name('ward-select-municipality');

Route::get('healthpost-select-municipality', 'Reports\FilterController@organizationSelectByMunicipality')->name('healthpost-select-municipality');

Route::get('healthpost-select', 'Reports\FilterController@healthpostSelect');
Route::get('select-from-to', 'Reports\FilterController@selectFromTo');

Route::get('dashboard', 'Reports\DashboardController@index')->name('report.dashboard');

Route::get('case-payment/overview' , 'Reports\CasesPaymentController@overview')->name('report.case-payment-overview');
Route::get('case-payment/monthly-line-listing' , 'Reports\CasesPaymentController@monthlyLineListing')->name('report.case-payment-monthly-line-listing');
Route::get('case-payment/daily-listing' , 'Reports\CasesPaymentController@dailyListing')->name('report.case-payment-daily-listing');
Route::get('case-payment/situation-report' , 'Reports\CasesPaymentController@situationReport')->name('report.case-payment-situation-report');

Route::get('cases-report/report' , 'Reports\SuspectedCaseReportController@casesReport')->name('report.cases-report-report');

Route::get('situation-report/sample_collection-report' , 'Reports\AncDetailController@sampleAncsReport')->name('report.sample-report.ancs');
Route::get('situation-report/lab-report' , 'Reports\AncDetailController@sampleLabReport')->name('report.sample-report.lab');

Route::get('lab-report-visualization', 'Reports\AncDetailController@labVisualizationReport')->name('report.visualization');
Route::get('registered_device-data', 'Reports\AncDetailController@organizationRegdevCount')->name('report.registered-device');
Route::get('organization-contact-tracing', 'Reports\AncDetailController@organizationContactTracing')->name('organization.contact.tracing');

Route::get('district-wise-cases-overview', 'Reports\DistrictWiseCasesOverview@provinceDistrictwiseReport')->name('report.district-wise-cases-overview')->middleware('role-control:province');;
Route::get('province-municipality-wise-cases-overview', 'Reports\DistrictWiseCasesOverview@provinceMunicipalitywiseReport')->name('report.province-municipality-wise-cases-overview')->middleware('role-control:province');;
Route::get('municipality-wise-cases-overview', 'Reports\DistrictWiseCasesOverview@municipalityReport')->name('report.municipality-wise-cases-overview')->middleware('role-control:dho');;

Route::get('poe-report', 'Reports\PoeController@poeReport')->name('report.poe');

Route::get('cict-old-report', 'Backend\CictTracingController@oldCictReport')->name('report.cict-old-report');