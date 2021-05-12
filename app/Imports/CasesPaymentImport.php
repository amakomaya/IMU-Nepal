<?php

namespace App\Imports;

use App\Models\PaymentCase;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Notifications\ImportHasFailedNotification;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use PhpOffice\PhpSpreadsheet\Shared\Date;

use App\User;

class CasesPaymentImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable;

    // public function __construct(User $importedBy)
    // {
    //     $this->importedBy = $importedBy;
    // }
    
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
        $enums = array(
          'gender'=> array( 'Male' => 1, 'Female' => 2, 'Other' => 3 ),
          'health_condition' => array ('No Symptoms'=> 1, 'Mild' => 2, 'Moderate' => 3, 'Severe' => 4),
          'self_free' => array ('Self' => 1, 'Free' => 1)
        );
        return new PaymentCase([
            'hospital_register_id' => $row['hospital_registration_id'] ,
            'name' => $row['name'],
            'date_of_outcome_en'=> Date::excelToDateTimeObject($row['registered_date_english_ad']),
            'lab_id' => $row['lab_id']??'0123456789',
            'age' => $row['age'],
            'gender' => $enums['gender'][$row['gender']],
            'phone' => $row['phone'],
            'address' => $row['current_address'],
            'guardian_name' => $row['parentguardian_name'],
            'health_condition' => $enums['health_condition'][$row['health_condition']] ?? null,
            'self_free' => $enums['self_free'][$row['self_free']] ?? null,
            'remark' => $row['remark'],
            'is_in_imu' => 0,

        ]);
    }

    public function rules(): array
    {
        // return [
        //   'name' => 'required|string',
        //   'registered_date_english_ad' => 'required',
        //   'age' => 'required',
        //   'gender' => 'required',
        //   'health_condition' => 'required',
        // ];
        return [
            'name' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Name cannot be empty');
              }
            },
            'registered_date_english_ad' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Registered Date cannot be empty');
              }
            },
            'age' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Age be empty');
              }
            },
            'gender' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Gender cannot be empty');
              }
            },
            'health_condition' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Health Condition cannot be empty');
              }
            },
        ];
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}