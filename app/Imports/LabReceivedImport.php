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

    public function __construct(User $importedBy)
    {
        $userToken = auth()->user()->token;
        $healthWorker = \App\Models\OrganizationMember::where('token', $userToken)->first();
        $hpCode = $healthWorker->hp_code;
        $this->importedBy = $importedBy;
        $this->userToken =  $userToken;
        $this->hpCode = $hpCode;
        $this->healthWorker = $healthWorker;
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
        $sId = $row['sid'];
        $labId = $row['patient_lab_id'];
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
            LabTest::create([
              'token' => $this->userToken.'-'.$labId,
              'hp_code' => $this->hpCode,
              'status' => 1,
              'sample_recv_date' =>  $date_np,
              'sample_test_result' => '9',
              'checked_by' => $this->userToken,
              'checked_by_name' => $this->healthWorker->name,
              'sample_token' => $sId,
              'regdev' => 'excel'
          ]);
          $ancs->update([
            'result' => 9
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

    public function chunkSize(): int
    {
        return 2000;
    }
}