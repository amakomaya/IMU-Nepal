<?php

namespace App\Imports;

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

class LabReceivedResultImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable, RemembersRowNumber;

    public static $importedRowCount = 0;
    public function __construct(User $importedBy)
    {
        $userToken = auth()->user()->token;
        $healthWorker = \App\Models\OrganizationMember::where('token', $userToken)->first();
        $hpCode = $healthWorker->hp_code;
        $this->importedBy = $importedBy;
        $this->healthWorker = $healthWorker;
        $this->userToken =  $userToken;
        $this->hpCode = $hpCode;
        $this->organizationType = \App\Models\Organization::where('hp_code', $hpCode)->first()->hospital_type;
        $this->enums = [
          'result' => array('positive' => '3', 'negative' => '4')
        ];
        $this->sample_recv_date_en = Carbon::now()->format('Y-m-d');
        $to_date_array = explode("-", Carbon::now()->format('Y-m-d'));
        $this->sample_recv_date_np = Calendar::eng_to_nep($to_date_array[0], $to_date_array[1], $to_date_array[2])->getYearMonthDay();
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
        $date_en = Carbon::now();
        $date_np = Calendar::eng_to_nep($date_en->year,$date_en->month,$date_en->day)->getYearMonthDay();
        $sId = $row['sid'];
        $labId = $row['patient_lab_id'];
        $labResult = $row['result'];
        $sampleTestTime = $date_en->format('g : i A');
        $ancs = $this->getAncsBySid($sId);
        if(!$ancs) {
          $error = ['sid' => 'The patient with the given Sample ID couldnot be found. Please create the data of the patient & try again.'];
          $failures[] = new Failure($currentRowNumber, 'sid', $error, $row);
          throw new ValidationException(
              \Illuminate\Validation\ValidationException::withMessages($error),
              $failures
          );
        } else {
          $pcrAllowedOrganizationType = ['2', '3'];
          if($ancs->first()->service_for == '1' && !in_array($this->organizationType, $pcrAllowedOrganizationType)) {
            $error = ['sid' => 'Your organization is not eligible for PCR Lab Test. Please contact IMU support to update your organization type.'];
            $failures[] = new Failure($currentRowNumber, 'sid', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
            return;
          }
          //check if sid exists
          try {
            LabTest::create([
              'token' => $this->userToken.'-'.$labId,
              'hp_code' => $this->hpCode,
              'status' => 1,
              'sample_recv_date' =>  $date_np,
              'sample_test_date' => $date_np,
              'sample_test_time' => $sampleTestTime,
              'sample_test_result' => $labResult,
              'checked_by' => $this->userToken,
              'checked_by_name' => $this->healthWorker->name,
              'sample_token' => $sId,
              'regdev' => 'excel'
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
          $ancs->update([
              'result' => $labResult,
              'sample_test_date_en' => $date_en->toDateString(),
              'sample_test_date_np' => $date_np,
              'sample_test_time' => $sampleTestTime,
              'received_by' => $this->userToken,
              'received_by_hp_code' => $this->hpCode,
              'received_date_en' => $this->sample_recv_date_en,
              'received_date_np' => $this->sample_recv_date_np,
              'lab_token' => $this->userToken.'-'.$labId
          ]);
        }
        return;
    }
    
    private function getAncsBySid ($sId) {
      $ancs = SampleCollection::where('token', $sId);
      if($ancs->count() > 0){
        return $ancs;
      }
      return false;
    }
  
    private function filterEmptyRow($data) {
      $required_row = ['result', 'patient_lab_id', 'sid']; //added to solve teplate throwing wierd default values
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
      $organiation_member_tokens = OrganizationMember::where('hp_code', $this->hpCode)->pluck('token');
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
            }
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