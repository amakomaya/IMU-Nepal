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

class LabResultImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable, RemembersRowNumber;

    public function __construct(User $importedBy)
    {
        $userToken = auth()->user()->token;
        $healthWorker = \App\Models\OrganizationMember::where('token', $userToken)->first();
        $hpCode = $healthWorker->hp_code;
        $this->importedBy = $importedBy;
        $this->userToken =  $userToken;
        $this->healthWorker =  $healthWorker;
        $this->hpCode = $hpCode;
        $this->enums = [
          'result' => array('Positive' => '3', 'Negative' => '4')
        ];
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
        $currentRowNumber = $this->getRowNumber();
        $date_en = Carbon::now();
        $date_np = Calendar::eng_to_nep($date_en->year,$date_en->month,$date_en->day)->getYearMonthDay();
        $labResult = $row['result'];
        $patientLabId = $row['patient_lab_id'];
        $sampleTestTime = $date_en->format('g : i A');
        $labTests = $this->getLabTestByPatientLabId($patientLabId);
        if(!$labTests) {
          $error = ['patient_lab_id' => 'The patient with the given Patient Lab ID couldnot be found in your lab. Please create the data of the patient & try again.'];
          $failures[] = new Failure($currentRowNumber, 'patient_lab_id', $error, $row);
          throw new ValidationException(
              \Illuminate\Validation\ValidationException::withMessages($error),
              $failures
          );
        } else {
            $sId = $labTests->get()->first()->sample_token;
            if($sId) {
              $labTests->update([
                'sample_test_date' => $date_np,
                'sample_test_time' => $sampleTestTime,
                'sample_test_result' => $labResult
              ]);
              $ancs = SampleCollection::where('token', $sId);
              $ancs->update([
                'result' => $labResult
              ]);
            } else {
              $error = ['patient_lab_id' => 'The patient with the given Patient Lab ID doesnot have Sample Collection record. Please create the data of the patient & try again.'];
              $failures[] = new Failure($currentRowNumber, 'patient_lab_id', $error, $row);
              throw new ValidationException(
                  \Illuminate\Validation\ValidationException::withMessages($error),
                  $failures
              );
            }
        }
        return;
    }
    
    private function getLabTestByPatientLabId ($patientLabId) {
      $labTests = LabTest::where('token', $this->userToken.'-'.$patientLabId);
      if($labTests->count() > 0){
        return $labTests;
      }
      return false;
    }
  
    private function filterEmptyRow($data) {
      $unset = true;
      foreach($data as $key=>$col){
        if($col) {
          $unset = false;
          break;
        }
      }
      if($unset){
        foreach($data as $key=>$col){
          unset($data[$key]);
        }
      }
      return $data;
    }
  
    public function prepareForValidation($data, $index)
    {
        $data['result'] = $this->enums['result'][$data['result']]?? null;
        $data = $this->filterEmptyRow($data);
        return $data;
    }
  
    public function rules(): array
    {
        return [
            'result' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Result');
              }
            },
            'patient_lab_id' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Patient Lab ID');
              }
            },
        ];
    }

    public function chunkSize(): int
    {
        return 2000;
    }
}