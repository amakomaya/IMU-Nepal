<?php

namespace App\Imports\BackDate;

use Carbon\Carbon;
use App\Models\LabTest;
use App\Models\SampleCollection;
use App\Models\OrganizationMember;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Notifications\ImportHasFailedNotification;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yagiten\Nepalicalendar\Calendar;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Validators\Failure;

use App\User;

class BackdateLabReceivedResultImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable, RemembersRowNumber;

    public static $importedRowCount = 0;
    public function __construct(User $importedBy)
    {
        ini_set('max_execution_time', '300');
        $userToken = auth()->user()->token;
        $healthWorker = \App\Models\OrganizationMember::where('token', $userToken)->first();
        $hpCode = $healthWorker->org_code;
        $this->importedBy = $importedBy;
        $this->healthWorker = $healthWorker;
        $this->userToken =  $userToken;
        $this->hpCode = $hpCode;
        $this->organizationType = \App\Models\Organization::where('org_code', $hpCode)->first()->hospital_type;
        $this->enums = [
          'result' => array('positive' => '3', 'negative' => '4', "don't know"=>'5')
        ];
        $this->todayDateEn = Carbon::now();
        $this->todayDateNp = Calendar::eng_to_nep($this->todayDateEn->year,$this->todayDateEn->month,$this->todayDateEn->day)->getYearMonthDay();
    }
    
    public function registerEvents(): array
    {
        return [
            ImportFailed::class => function(ImportFailed $event) {
                $this->importedBy->notify(new ImportHasFailedNotification);
            },
        ];
    }

    public function model(array $row)
    {
        if(!array_filter($row)) { return null;} //Ignore empty rows.
        self::$importedRowCount++;
        $currentRowNumber = $this->getRowNumber();
        $sId = $row['sid'];
        $patientLabId = $this->userToken.'-'.$row['patient_lab_id'];
        if(lab_id_exists($patientLabId)) {
          $error = ['patient_lab_id' => 'The test with the given Patient Lab ID already exists in the system.'];
          $failures[] = new Failure($currentRowNumber, 'patient_lab_id', $error, $row);
          throw new ValidationException(
              \Illuminate\Validation\ValidationException::withMessages($error),
              $failures
          );
          return;
        }
        $labResult = $row['result'];
        $sampleTestTime = $this->todayDateEn->format('g : i A');
        $sample_collection = $this->getAncsBySid($sId);
        if(!$sample_collection) {
          $error = ['sid' => 'The patient with the given Sample ID couldnot be found. Please create the data of the patient & try again.'];
          $failures[] = new Failure($currentRowNumber, 'sid', $error, $row);
          throw new ValidationException(
              \Illuminate\Validation\ValidationException::withMessages($error),
              $failures
          );
        } else {
          $pcrAllowedOrganizationType = ['2', '3'];
          if($sample_collection->first()->service_for == '1' && !in_array($this->organizationType, $pcrAllowedOrganizationType)) {
            $error = ['sid' => 'Your organization is not eligible for PCR Lab Test. Please contact IMU support to update your organization type.'];
            $failures[] = new Failure($currentRowNumber, 'sid', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
            return;
          }
          $backDateEn = $row['date_of_resultyyyy_mm_dd_ad'];
          $backDateEn = $this->returnValidEnDate($backDateEn);
          if (!$backDateEn) {
            $error = ['date_of_resultyyyy_mm_dd_ad' => 'Invalid Date. Date must be in AD & YYYY-MM-DD Format'];
              $failures[] = new Failure($currentRowNumber, 'date_of_resultyyyy_mm_dd_ad', $error, $row);
              throw new ValidationException(
                  \Illuminate\Validation\ValidationException::withMessages($error),
                  $failures
              );
              return;
          }
          list($bdYearEn, $bdMonthEn, $bdDayEn) = explode('-', $backDateEn);
          $backDateNp = Calendar::eng_to_nep($bdYearEn,$bdMonthEn,$bdDayEn)->getYearMonthDay();
          //check if sid exists
          try {
            LabTest::create([
              'token' => $patientLabId,
              'org_code' => $this->hpCode,
              'status' => 1,
              'sample_recv_date' =>  $backDateNp,
              'sample_test_date' => $backDateNp,
              'sample_test_time' => $sampleTestTime,
              'sample_test_result' => $labResult,
              'checked_by' => $this->userToken,
              'checked_by_name' => $this->healthWorker->name,
              'sample_token' => $sId,
              'regdev' => 'excel-bd'
            ]);
          } catch (\Illuminate\Database\QueryException $e) {
            $error = ['sid' => 'The test with the given Sample ID/Patient Lab ID already exists in the system.'];
            $failures[] = new Failure($currentRowNumber, 'sid', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
            return;
          }
          $sample_collection->update([
              'result' => $labResult,
              'sample_test_date_en' => $backDateEn,
              'sample_test_date_np' => $backDateNp,
              'sample_test_time' => $sampleTestTime,
              'received_by' => $this->userToken,
              'received_by_hp_code' => $this->hpCode,
              'received_date_en' => $backDateEn,
              'received_date_np' => $backDateNp,
              'lab_token' => $patientLabId,
              'reporting_date_en' => $this->todayDateEn,
              'reporting_date_np' => $this->todayDateNp
          ]);
        }
        return;
    }
    
    private function returnValidEnDate($date){
      try{
        //TODO: stop future date
        if($date) {
          if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
          {
            $year = $parts[1];
            $month = $parts[2];
            $day = $parts[3];
            if (checkdate($month ,$day, $year)) {
              if((int)$year <= Carbon::now()->year) {
                return $date;
              }
            }
          } else {
            $parsedDate = Date::excelToDateTimeObject($date);
            $carbonDate = Carbon::instance($parsedDate);
            $year = $carbonDate->year;
            $month = $carbonDate->month;
            $day = $carbonDate->day;
            if($year>2000 && $year <= Carbon::now()->year) {
              return $carbonDate->format('Y-m-d');
            }
          }
        } else {
          return false;
        }
      } catch(\Exception $e){
        return false;
      } 
    }

    private function getAncsBySid ($sId) {
      $sample_collection = SampleCollection::where('token', $sId);
      if($sample_collection->count() > 0){
        return $sample_collection;
      }
      return false;
    }
  
    private function filterEmptyRow($data) {
      $required_row = ['result', 'patient_lab_id', 'sid', 'date_of_resultyyyy_mm_dd_ad']; //added to solve teplate throwing wierd default values
      $unset = true;
      foreach($data as $key=>$col){
        if($col && in_array($key, $required_row)) {
          $unset = false;
          break;
        }
      }
      if($unset){
        $data = array();
      }
      return $data;
    }

    private function getLabTestByPatientLabId ($patientLabId) {
      $organiation_member_tokens = OrganizationMember::where('org_code', $this->hpCode)->pluck('token');
      $labTokens = [];
      foreach ($organiation_member_tokens as $item) {
          array_push($labTokens, $item."-".$patientLabId);
      }
      $labTests = LabTest::whereIn('token', $labTokens);
      if($labTests->count() > 0){
        return $labTests;
      }
      return false;
    }
  
    public function prepareForValidation($data, $index)
    {
        $data = $this->filterEmptyRow($data);
        if(array_filter($data)) {
          $data['result'] = $this->enums['result'][strtolower(trim($data['result']))]?? null;
        }
        return $data;
    }
  
    public function rules(): array
    {
        return [
            'sid' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null || strlen($value) !== 17) {
                   $onFailure('Invalid SID');
              }
            },
            'patient_lab_id' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Lab ID');
              }
            },
            'result' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                $onFailure('Invalid Result');
              }
            },
            'date_of_resultyyyy_mm_dd_ad'  => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Lab Result Date');
              }
            },
         ];
    }

    public function getImportedRowCount() {
      return self::$importedRowCount;
    }

    public function chunkSize(): int
    {
        return 2000;
    }
}