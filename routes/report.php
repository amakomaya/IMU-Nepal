<?php

Route::get('district-select-province', 'Reports\FilterController@districtSelectByProvince')->name('district-select-province');
Route::get('municipality-select-district', 'Reports\FilterController@municipalitySelectByDistrict')->name('municipality-select-district');
Route::get('ward-or-healthpost-select-municipality', 'Reports\FilterController@WardOrHealthpostByMunicipality')->name('ward-or-healthpost-select-municipality');

Route::get('ward-select-municipality', 'Reports\FilterController@wardSelectByMunicipality')->name('ward-select-municipality');

Route::get('healthpost-select-municipality', 'Reports\FilterController@healthpostSelectByMunicipality')->name('healthpost-select-municipality');

Route::get('healthpost-select', 'Reports\FilterController@healthpostSelect');
Route::get('select-from-to', 'Reports\FilterController@selectFromTo');

Route::get('dashboard', 'Reports\DashboardController@index')->name('report.dashboard');

Route::get('calc' , function (){
   $lab_record = \App\Models\LabTest::get(['sample_token', 'sample_test_result']);
   $lab_record->map(function ($record){
      $sample = \App\Models\SampleCollection::where('token', $record->sample_token)->first();
      if (!empty($sample)){
          $sample->update(['result' =>  $record->sample_test_result]);
      }
   });

   $sample_token = \App\Models\SampleCollection::where('result', '!=', 2)->get(['token', 'result']);

    $sample_token->map(function ($sample){
        $lab = \App\Models\LabTest::where('sample_token', $sample->token)->first();
        if (empty($lab)){
            $sample->update(['result' =>  2]);
        }
    });
   return "Complete";
});