<?php

namespace App\Imports;

use Carbon\Carbon;
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
use Yagiten\Nepalicalendar\Calendar;

use App\User;

class CasesPaymentImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable;

    public function __construct(User $importedBy)
    {
        $this->importedBy = $importedBy;
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
        $enums = array(
          'gender'=> array( 'Male' => 1, 'Female' => 2, 'Other' => 3 ),
          'health_condition' => array ('No Symptoms'=> 1, 'Mild' => 2, 'Moderate & HDU' => 3, 'Severe - ICU' => 4, 'Severe - Ventilator'),
          'paid_free' => array ('Paid' => "1", 'Free' => "2"),
          'method_of_diagnosis' => array ('PCR' => 1, 'Antigen' => 2, 'Clinical Diagnosis' => 3, 'Others' => 10),
          'age_unit' => array ('Year' => 0, 'Month' => 1, 'Day' => 2),
        );
        $date_en = Carbon::now();
        $date_np = Calendar::eng_to_nep($date_en->year,$date_en->month,$date_en->day)->getYearMonthDay();
        // echo ($row['parentguardian_name']);
        // die;
        return new PaymentCase([
            'hospital_register_id' => $row['hospital_id'] ,
            'name' => $row['full_name_of_patient'],
            'register_date_en'=> $date_en->isoFormat("Y-M-D"),
            'register_date_np' => $date_np,
            'lab_name' => $row['lab_name']??'No Lab Found',
            'lab_id' => $row['lab_id']??'0123456789',
            'age' => $row['age'],
            'age_unit' => $enums['age_unit'][$row['age_unit']] ?? 0,
            'gender' => $enums['gender'][$row['gender']],
            'phone' => $row['mobile_number'],
            'address' => $row['current_address_of_patient'],
            'guardian_name' => $row['parentguardian_name'],
            'complete_vaccination' => null,
            'health_condition' => $enums['health_condition'][$row['health_condition']] ?? null,
            'self_free' => $enums['paid_free'][$row['paid_free']],
            'remark' => $row['remark'],
            'is_death' => null,
            'is_in_imu' => 0,
            'method_of_diagnosis' => $enums['method_of_diagnosis'][$row['method_of_diagnosis']],
            'hp_code' => \App\Models\Organization::where('token', auth()->user()->token)->first()->hp_code

        ]);
    }

    public function rules(): array
    {
        return [
            'full_name_of_patient' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Patient Name cannot be empty');
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
            'method_of_diagnosis' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Method of Diagnosis cannot be empty');
              }
            },
            'paid_free' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Self/Paid cannot be empty');
              }
            }
        ];
    }

    public function chunkSize(): int
    {
        return 2000;
    }
}