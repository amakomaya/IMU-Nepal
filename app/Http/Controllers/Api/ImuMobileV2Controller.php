<?php

namespace App\Http\Controllers\Api;

use App\Models\SampleCollection;
use App\Models\LabTest;
use App\Models\SuspectedCase;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yagiten\Nepalicalendar\Calendar;
use App\User;
use Exception;

class ImuMobileV2Controller extends Controller
{
    private function generateUniqueLabId($labId, $prefix = '', $index = '') {
      $prefixedLabId = $labId.$prefix.$index;
      $existsLabTest = SampleCollection::where('lab_token', $prefixedLabId);
      if($existsLabTest->count() > 0){
        $index =  $index==''?0:$index;
        $prefixedLabId = $this->generateUniqueLabId($labId, '_mdup_', $index+1);
      }
      return $prefixedLabId;
    }

    public function postSuspectedCaseV2(Request $request)
    {
      $syncErrors = [];
      $allData = $request->json()->all();
      $data = $allData['data']??[];
      foreach ($data as $value) {
          try {
             $currentErrorIndex = count($syncErrors);
              $existingCase = SuspectedCase::where('token',$value['token']);
              if($existingCase->count() > 0) {
                $caseObject = $existingCase->latest()->first();
                $caseObject->update($value);
              
              } else {
                $value['case_id'] = $allData['user_id'] .'-' . Carbon::now()->format('ymd') . '-'.  strtoupper(bin2hex(random_bytes(3)));
                $value['register_date_en'] = Carbon::parse($value['created_at'])->format('Y-m-d');
                $register_date_en = explode("-", $value['register_date_en']);
                $register_date_np = Calendar::eng_to_nep($register_date_en[0], $register_date_en[1], $register_date_en[2])->getYearMonthDayEngToNep();
                $value['register_date_np'] = $register_date_np;
                SuspectedCase::create($value);
              }
          } catch (\Exception $e) {
                $syncErrors[$currentErrorIndex]['status'] = 'fail';
                $syncErrors[$currentErrorIndex]['message'] = $e->getMessage();
                $syncErrors[$currentErrorIndex]['token'] = $value['token'];
          }
      }
      if(count($syncErrors) == 0) {
        return response()->json(['status' => 'success', 'message' => 'Data Successfully Sync']);
      } else {
        return response()->json([
          'status' => 'fail',
          'errors' =>  $syncErrors 
          ], 422
        );
      }
    }

    public function postSampleCollectionV2(Request $request)
    {
      $syncErrors = [];
      $data = $request->json()->all();
          foreach ($data as $index => $value) {
              try {
                $currentErrorIndex = count($syncErrors);
                $value['result'] = (int)$value['result'];
                if($value['token']) {
                  $value['collection_date_en'] = Carbon::parse($value['created_at'])->format('Y-m-d');
                  $collection_date_en = explode("-", Carbon::parse($value['created_at'])->format('Y-m-d'));
                  $collection_date_np = Calendar::eng_to_nep($collection_date_en[0], $collection_date_en[1], $collection_date_en[2])->getYearMonthDayEngToNep();
                  $value['collection_date_np'] = $collection_date_np;
                  unset($value['created_at']);
                  unset($value['updated_at']);
                  $existingSample = SampleCollection::where('token',$value['token']);
                  if($existingSample->count() > 0) {
                    $sampleObject = $existingSample->latest()->first();
                    if(in_array($sampleObject->result, [3, 4, 5])){
                      $syncErrors[$currentErrorIndex]['status'] = 'fail';
                      $syncErrors[$currentErrorIndex]['message'] = 'Result already assigned in live server for Sample ID: '.$value['token'];
                      $syncErrors[$currentErrorIndex]['token'] = $value['token'];
                    } else if($sampleObject->result == 9 && $value['result'] == 2) {
                      $syncErrors[$currentErrorIndex]['status'] = 'fail';
                      $syncErrors[$currentErrorIndex]['message'] = 'Result already received in lab for Sample ID: '.$value['token'];
                      $syncErrors[$currentErrorIndex]['token'] = $value['token'];
                    } else {
                      $sampleObject->update($value);
                    }
                  } else {
                    SampleCollection::create($value);
                  }
                } else {
                  $syncErrors[$currentErrorIndex]['status'] = 'fail';
                  $syncErrors[$currentErrorIndex]['message'] = 'Blank Sample Token';
                  $syncErrors[$currentErrorIndex]['token'] = $value['token'];
                  continue;
                }
              } catch (Exception $e) {
                $syncErrors[$currentErrorIndex]['status'] = 'fail';
                $syncErrors[$currentErrorIndex]['message'] = $e->getMessage();
                $syncErrors[$currentErrorIndex]['token'] = $value['token'];
              }
              
          }
      if(count($syncErrors) == 0) {
        return response()->json(['status' => 'success', 'message' => 'Data Successfully Sync']);
      } else {
        return response()->json([
          'status' => 'fail',
          'errors' =>  $syncErrors 
          ], 422
        );
      }
    }

    public function postLabTestV2(Request $request)
    {
      //TODO check 15 day created at and dont leet restore old data
      $syncErrors = [];
      $data = $request->json()->all();
      foreach ($data as $value) {
          try {
              $dateTimeNow = Carbon::now();
              $currentErrorIndex = count($syncErrors);
              $received_date_np = $value['sample_recv_date'];
              $received_date_np_array = explode("-", $received_date_np);
              $received_date_en = Calendar::nep_to_eng($received_date_np_array[0], $received_date_np_array[1], $received_date_np_array[2])->getYearMonthDayNepToEng();
              $reporting_date_en = $dateTimeNow->toDateTimeString();
              $reporting_date_en_array = explode("-", $dateTimeNow->format('Y-m-d'));
              $reporting_date_np = Calendar::eng_to_nep($reporting_date_en_array[0], $reporting_date_en_array[1], $reporting_date_en_array[2])->getYearMonthDayEngToNep();
              if(in_array($value['sample_test_result'], ['3', '4', '5'])) {
                  $sample_test_date_np_array = explode("-", $value['sample_test_date']);
                  $sample_test_date_en = Calendar::nep_to_eng($sample_test_date_np_array[0], $sample_test_date_np_array[1], $sample_test_date_np_array[2])->getYearMonthDayNepToEng();
              }
              $existingSample = SampleCollection::where('token', $value['sample_token']);
              if($existingSample->count() > 0) {
                $existingLabTest = LabTest::where('sample_token', $value['sample_token']);
                if($existingLabTest->count() > 0) {
                  $labTestObject = $existingLabTest->latest()->first();
                  if(in_array($labTestObject->sample_test_result, ['3', '4', '5'])){
                    $syncErrors[$currentErrorIndex]['status'] = 'fail';
                    $syncErrors[$currentErrorIndex]['message'] = 'Result already assigned in live server for Lab ID: '.$value['token']. '& Sample ID: '.$value['sample_token'];
                    $syncErrors[$currentErrorIndex]['token'] = $value['token'];
                  } else {
                    if($value['token']==$labTestObject->token) {
                      $labTestObject->update($value);
                      if($value['sample_test_result'] == '9') {
                        SampleCollection::where('token', $value['sample_token'])->update([
                          'result' => (int)$value['sample_test_result'],
                          'received_by' => $value['checked_by'],
                          'received_by_hp_code' => $value['hp_code'],
                          'received_date_en' => $received_date_en,
                          'received_date_np' => $received_date_np,
                          'lab_token' => $value['token']
                        ]);
                      } else {
                        SampleCollection::where('token', $value['sample_token'])->update([
                          'result' => (int)$value['sample_test_result'],        
                          'sample_test_date_en' => $sample_test_date_en,
                          'sample_test_date_np' => $value['sample_test_date'],
                          'sample_test_time' => $value['sample_test_time'],
                          'received_by' => $value['checked_by'],
                          'received_by_hp_code' => $value['hp_code'],
                          'received_date_en' => $received_date_en,
                          'received_date_np' => $received_date_np,
                          'lab_token' => $value['token'],
                          'reporting_date_en' => $reporting_date_en,
                          'reporting_date_np' => $reporting_date_np
                        ]);
                      }
                    } else {
                      $syncErrors[$currentErrorIndex]['status'] = 'fail';
                      $syncErrors[$currentErrorIndex]['message'] = 'Result already assigned for Sample ID: '.$value['sample_token'].' with different LabId: '.$labTestObject->token;
                      $syncErrors[$currentErrorIndex]['token'] = $value['token'];
                    }
                  }
                } else {
                      $uniqueLabId = $this->generateUniqueLabId($value['token']);
                      if($uniqueLabId !== $value['token']){
                        $syncErrors[$currentErrorIndex]['status'] = 'updated';
                        $syncErrors[$currentErrorIndex]['message'] = "Updated Lab ID ".$value['token']." to ".$uniqueLabId." during syncing due to duplicate Lab Id";
                        $syncErrors[$currentErrorIndex]['new_token'] = $uniqueLabId;
                        $syncErrors[$currentErrorIndex]['old_token'] = $value['token'];
                      }
                      $value['token'] = $uniqueLabId;
                      LabTest::create($value);
                      if($value['sample_test_result'] == '9') {
                        SampleCollection::where('token', $value['sample_token'])->update([
                          'result' => $value['sample_test_result'],
                          'received_by' => $value['checked_by'],
                          'received_by_hp_code' => $value['hp_code'],
                          'received_date_en' => $received_date_en,
                          'received_date_np' => $received_date_np,
                          'lab_token' => $value['token'],
                          'reporting_date_en' => $reporting_date_en,
                          'reporting_date_np' => $reporting_date_np
                        ]);
                      } else {
                        SampleCollection::where('token', $value['sample_token'])->update([
                          'result' => $value['sample_test_result'],        
                          'sample_test_date_en' => $sample_test_date_en,
                          'sample_test_date_np' => $value['sample_test_date'],
                          'sample_test_time' => $value['sample_test_time'],
                          'received_by' => $value['checked_by'],
                          'received_by_hp_code' => $value['hp_code'],
                          'received_date_en' => $reporting_date_en,
                          'received_date_np' => $reporting_date_np,
                          'lab_token' => $value['token'],
                          'reporting_date_en' => $reporting_date_en,
                          'reporting_date_np' => $reporting_date_np
                        ]);
                      }
                }
              } else {
                $syncErrors[$currentErrorIndex]['status'] = 'fail';
                $syncErrors[$currentErrorIndex]['message'] = 'Sample ID: '.$value['sample_token'].'was not found in IMU live system.';
                $syncErrors[$currentErrorIndex]['token'] = $value['token'];
              }
          } catch (Exception $e) {
            $syncErrors[$currentErrorIndex]['status'] = 'fail';
            $syncErrors[$currentErrorIndex]['message'] = $e->getMessage();
            $syncErrors[$currentErrorIndex]['token'] = $value['token'];
          }
      }
      if(count($syncErrors) == 0) {
        return response()->json(['status' => 'success', 'message' => 'Data Successfully Sync']);
      } else {
        return response()->json([
          'status' => 'fail',
          'errors' =>  $syncErrors 
          ], 422
        );
      }
    }
}
