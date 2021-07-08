<?php

use App\Models\SampleCollection;
use App\Models\LabTest;
use App\Models\SuspectedCase;



Route::post('/v2/client', 'Api\ImuMobileV2Controller@postSuspectedCaseV2');
Route::post('/v2/client-tests', 'Api\ImuMobileV2Controller@postSampleCollectionV2');
Route::post('/v2/lab-test', 'Api\ImuMobileV2Controller@postLabTestV2');
