<?php

namespace App\Console\Commands;

use Symfony\Component\Process\Process;
use App\Models\LabTest;
use App\Models\SampleCollection;
use Yagiten\Nepalicalendar\Calendar;
use Carbon\Carbon;
use DB;
use Exception;
use DateTime;

use Illuminate\Console\Command;

class FixLabPCRData extends Command
{
    protected $signature = 'fix:pcr:data {limit}';

    protected $description = 'Fix Corrupted PCR data';

    protected $process;

    public function __construct()
    {
        parent::__construct();
    }

    public function addZeroToDate($date) {
      $dateArray = explode("-", $date);
      return $dateArray[0].'-'.(strlen($dateArray[1])==1?'0':'').$dateArray[1].'-'.(strlen($dateArray[2])==1?'0':'').$dateArray[2];
    }
    
    public function generateDateFromSid($sid){
      $sidArray = explode("-", $sid);
     
      $dateInfo = $sidArray[1];
      $year = substr($dateInfo, 0, 2);
      $dates = DateTime::createFromFormat('y', $year);
      
      $year = $dates->format('Y');
      $month = substr($dateInfo, 2, 2);
      $day = substr($dateInfo, 4, 2);
      return [
        'en' => $year.'-'.$month.'-'.$day,
        'np' => Calendar::eng_to_nep($year,$month,$day)->getYearMonthDay()
      ];
    }

    public function generateUniqueLabIdFromSid($sampleData){
      $sid = $sampleData->token;
      $sidArray = explode("-", $sid);
      $healthWorkerToken = $sampleData->checked_by;
      $dateInfo = $sidArray[1];
      //todo if found labid in system store as _dup1
      $labId = $this->generateUniqueLabId($healthWorkerToken, $dateInfo, 'dup_', 1);
      return $labId;
    }

    public function generateUniqueLabId($healthWorkerToken, $dateInfo, $labId, $prefix) {
      $labId = $healthWorkerToken.'-'.$dateInfo.'-'.$labId.$prefix;
      $existsLabTest = LabTest::where('token', $labId);
      if($existsLabTest->count() > 0){
        $labId = $this->generateUniqueLabId($healthWorkerToken, $dateInfo, 'dup_', $prefix+1);
      }
      return $labId;
    }

    public function handle()
    {
      $limit = $this->argument('limit');
      $corruptedAntigenData = SampleCollection::where('service_for', '2')
        ->whereNull('sample_test_date_en')
        ->whereNotNull('checked_by') //TODO discover these type of cases
        ->whereIn('result', ['3', '4', '5'])
        ->whereNull('lab_token');
      if($limit) {
        $corruptedAntigenData =  $corruptedAntigenData->take($limit);
      }
      $corruptedAntigenData =  $corruptedAntigenData->get();
      DB::beginTransaction();
      foreach($corruptedAntigenData as $sampleData){
        $sId = $sampleData->token;
        $this->info('Fixing data for ' . $sId);
        $sampleCreatedDate = $this->generateDateFromSid($sId);
        $uniqueLabId = $this->generateUniqueLabIdFromSid($sampleData);
        $sampleTestTime = Carbon::parse($sampleData->created_at)->format('H:i:s');
        $labResult = $sampleData->result;
        try {
          $sampleData->update(
            [
              // 'received_by' => $sampleData->checked_by,
              // 'received_by_org_code' => $sampleData->org_code,
              'received_date_en' => $sampleCreatedDate['en'],
              'received_date_np' =>  $sampleCreatedDate['np'],
              'sample_test_date_en' => $sampleCreatedDate['en'],
              'sample_test_date_np' => $sampleCreatedDate['np'],
              'sample_test_time' => $sampleTestTime,
              'lab_token' => $uniqueLabId,
            ]
          );
          LabTest::create([
            // 'token' => $uniqueLabId,
            'org_code' => $sampleData->org_code,
            'status' => 1,
            'sample_recv_date' =>  $sampleCreatedDate['np'],
            'sample_test_date' => $sampleCreatedDate['np'],
            'sample_test_time' => $sampleTestTime,
            'sample_test_result' => $labResult,
            // 'checked_by' => $sampleData->checked_by,
            // 'checked_by_name' => $sampleData->checked_by,
            'sample_token' => $sId,
            'registered_device' => $sampleData->registered_device
          ]);
          $this->info('Successfully assigned LabId '. $uniqueLabId);
        } catch (Exception $e){
          DB::rollback();
          $this->error($e->getMessage());
          break;
        }
      }
      DB::commit();
    }
}
