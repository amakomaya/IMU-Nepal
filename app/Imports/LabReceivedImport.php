<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\LabTest;
use App\Models\SampleCollection;
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

class LabReceivedImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable, RemembersRowNumber;

    public static $importedRowCount = 0;
    public function __construct(User $importedBy)
    {
        ini_set('max_execution_time', '300');
        $userToken = auth()->user()->token;
        $healthWorker = \App\Models\OrganizationMember::where('token', $userToken)->first();
        $hpCode = $healthWorker->hp_code;
        $this->importedBy = $importedBy;
        $this->userToken =  $userToken;
        $this->hpCode = $hpCode;
        $this->healthWorker = $healthWorker;
        $this->organizationType = \App\Models\Organization::where('hp_code', $hpCode)->first()->hospital_type;
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
        $labId = $row['patient_lab_id'];
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
            $error = ['sid' => 'Your organization is not eligible for PCR Lab Received. Please contact IMU support to update your organization type.'];
            $failures[] = new Failure($currentRowNumber, 'sid', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
            return;
          }
          try {
            LabTest::create([
              'token' => $this->userToken.'-'.$labId,
              'hp_code' => $this->hpCode,
              'status' => 1,
              'sample_recv_date' =>  $this->todayDateNp,
              'sample_test_result' => '9',
              'checked_by' => $this->userToken,
              'checked_by_name' => $this->healthWorker->name,
              'sample_token' => $sId,
              'regdev' => 'excel'
          ]);
          
          } catch (\Illuminate\Database\QueryException $e) {
            $error = ['sid' => 'The patient with the given Sample ID and Lab ID already exists in the system.'];
            $failures[] = new Failure($currentRowNumber, 'sid', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
            return;
          }

            $ancs->update([
              'result' => '9',
              'received_by' => $this->userToken,
              'received_by_hp_code' => $this->hpCode,
              'received_date_en' => $this->todayDateEn,
              'received_date_np' => $this->todayDateNp,
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
        ];
    }

    private function filterEmptyRow($data) {
      $required_row = ['sid', 'patient_lab_id']; //added to solve teplate throwing wierd default values
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
  
    public function prepareForValidation($data, $index)
    {
        $data = $this->filterEmptyRow($data);
        return $data;
    }
  
    public function getImportedRowCount() {
      return self::$importedRowCount;
    }

    public function chunkSize(): int
    {
        return 2000;
    }
}