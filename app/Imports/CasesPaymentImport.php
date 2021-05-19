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

    public $enums = array(
      'gender'=> array( 'Male' => 1, 'Female' => 2, 'Other' => 3 ),
      'health_condition' => array ('No Symptoms'=> 1, 'Mild' => 2, 'Moderate &  HDU' => 3, 'Severe - ICU' => 4, 'Severe - Ventilator'),
      'paid_free' => array ('Paid' => "1", 'Free' => "2"),
      'method_of_diagnosis' => array ('PCR' => 1, 'Antigen' => 2, 'Clinical Diagnosis' => 3, 'Others' => 10),
      'age_unit' => array ('Year' => 0, 'Month' => 1, 'Day' => 2),
    );
  
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
        
        $date_en = Carbon::now();
        $date_np = Calendar::eng_to_nep($date_en->year,$date_en->month,$date_en->day)->getYearMonthDay();

        return new PaymentCase([
            'hospital_register_id' => $row['hospital_id'],
            'name' => $row['full_name_of_patient'],
            'register_date_en'=> $date_en->isoFormat("Y-M-D"),
            'register_date_np' => $date_np,
            'lab_name' => $row['lab_name']??'No Lab Found',
            'lab_id' => $row['lab_id']??'0123456789',
            'age' => $row['age'],
            'age_unit' => $row['age_unit'],
            'gender' => $row['gender'],
            'phone' => $row['mobile_number'],
            'address' => $row['current_address_of_patient'],
            'guardian_name' => $row['parentguardian_name'],
            'complete_vaccination' => null,
            'health_condition' => $row['health_condition'],
            'self_free' =>$row['paid_free'],
            'remark' => $row['remark'],
            'is_death' => null,
            'is_in_imu' => 0,
            'method_of_diagnosis' => $row['method_of_diagnosis'],
            'hp_code' => \App\Models\Organization::where('token', auth()->user()->token)->first()->hp_code
        ]);
    }
    
    public function prepareForValidation($data, $index)
    {
        $data['paid_free'] = $this->enums['paid_free'][$data['paid_free']];
        $data['gender'] = $this->enums['gender'][$data['gender']];
        $data['age_unit'] = $this->enums['age_unit'][$data['age_unit']] ?? 0;
        $data['health_condition'] = $this->enums['health_condition'][$data['health_condition']] ?? null;
        $data['method_of_diagnosis'] = $this->enums['method_of_diagnosis'][$data['method_of_diagnosis']] ?? null;
        
        return $data;
    }
  
    public function rules(): array
    {
        return [
            'full_name_of_patient' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Patient Name');
              }
            },
            'age' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Age');
              }
            },
            'gender' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Gender');
              }
            },
            'health_condition' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Health Condition');
              }
            },
            'method_of_diagnosis' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Method');
              }
            },
            'paid_free' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Self/Paid');
              }
            },
            'mobile_number' => function($attribute, $value, $onFailure) {
              if(!preg_match('/(?:\+977[- ])?\d{2}-?\d{7,8}/i', $value)) {
                $onFailure('Invalid Mobile Number.');
              }
            }
        ];
    }

    public function chunkSize(): int
    {
        return 2000;
    }
}