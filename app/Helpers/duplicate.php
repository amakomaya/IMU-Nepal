<?php
use App\Models\LabTest;
use App\Models\SampleCollection;

/*TODO All these checks are giving load to server. Remove after proper integrety check is added to anc table. */
if (! function_exists('generate_unique_lab_id_web')) {
    function generate_unique_lab_id_web($labId, $suffix = '', $index = ''){
        $suffixedLabId = $labId.$suffix.$index;
        $existsLabTest = SampleCollection::where('lab_token', $suffixedLabId);
        if($existsLabTest->count() > 0){
          $index =  $index==''?0:$index;
          $suffixedLabId = generate_unique_lab_id_web($labId, '_wdup_', $index+1);
        }
        return $suffixedLabId;
    }
}

if (! function_exists('generate_unique_lab_id_excel')) {
  function generate_unique_lab_id_excel($labId, $suffix = '', $index = ''){
      $suffixedLabId = $labId.$suffix.$index;
      $existsLabTest = SampleCollection::where('lab_token', $suffixedLabId);
      if($existsLabTest->count() > 0){
        $index =  $index==''?0:$index;
        $suffixedLabId = generate_unique_lab_id_excel($labId, '_edup_', $index+1);
      }
      return $suffixedLabId;
  }
}


if (! function_exists('generate_unique_lab_id_api')) {
    function generate_unique_lab_id_api($labId, $suffix = '', $index = ''){
        $suffixedLabId = $labId.$suffix.$index;
        $existsLabTest = SampleCollection::where('lab_token', $suffixedLabId);
        if($existsLabTest->count() > 0){
          $index =  $index==''?0:$index;
          $suffixedLabId = generate_unique_lab_id_api($labId, '_adup_', $index+1);
        }
        return $suffixedLabId;
    }
}

if (! function_exists('lab_id_exists')) {
  function lab_id_exists($labId) {
      $existsLabTest = SampleCollection::where('lab_token', $labId);
      if($existsLabTest->count() > 0){
        return true;
      } else {
        return false;
      }
  }
}

if (! function_exists('generate_unique_sid')) {
  function generate_unique_sid($sId){
      $existsSid = SampleCollection::where('token', $sId);
      $uniqueSid = $sId;
      if($existsSid->count() > 0){
        $sIdArray = explode('-', $sId);
        $sIdLastUnique = (int)$sIdArray[2]+10;
        $uniqueSid = generate_unique_sid($sIdArray[0].'-'.$sIdArray[1].'-'.$sIdLastUnique);
      }
      return $uniqueSid;
  }
}


if (! function_exists('check_lab_id_unique')) {
  function check_lab_id_unique($labId){
      $existsLabTest = SampleCollection::where('lab_token', $labId);
      if($existsLabTest->count() > 0){
        return true;
      }
      return false;
  }
}