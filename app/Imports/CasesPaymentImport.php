<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\PaymentCase;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
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
use Illuminate\Support\Facades\Cache;

use App\User;

class CasesPaymentImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable, RemembersRowNumber;
    public static $importedRowCount = 0;
    public function __construct(User $importedBy, $bed_status)
    {

        ini_set('max_execution_time', '300');
        $provinceList = Cache::remember('province-list', 48*60*60, function () {
          return Province::select(['id', 'province_name'])->get();
        });
        $districtList = Cache::remember('district-list', 48*60*60, function () {
          return District::select(['id', 'district_name', 'province_id' ])->get();
        });
        $municipalityList = Cache::remember('municipality-list', 48*60*60, function () {
          return Municipality::select(['id', 'municipality_name', 'province_id', 'district_id', 'municipality_name_np', 'type', 'total_no_of_wards'])->get();
        });
        $provinces = $districts = $municipalities = [];
        $provinceList->map(function ($province) use (&$provinces) {
          $provinces[strtolower(trim($province->province_name))] = $province->id;
          return;
        });
        $districtList->map(function ($district) use (&$districts) {
          $districts[strtolower(trim($district->district_name))] = $district->id;
          return;
        });
        $municipalityList->map(function ($municipality) use (&$municipalities) {
          $municipalities[strtolower(trim($municipality->municipality_name))] = $municipality->id;
          return;
        });
        $hpCode = '';
        if($importedBy->role === 'healthworker') {
          $hpCode = \App\Models\OrganizationMember::where('token', $importedBy->token)->first()->hp_code;
        } else {
          $hpCode = \App\Models\Organization::where('token', auth()->user()->token)->first()->hp_code;
        }
        $this->enums = array(
          'gender'=> array( 'male' => 1, 'female' => 2, 'other' => 3 ),
          'health_condition' => array ('no symptoms'=> 1, 'mild' => 2, 'moderate &  hdu' => 3, 'severe - icu' => 4, 'severe - ventilator' => 5),
          'paid_free' => array ('paid' => "1", 'free' => "2"),
          'method_of_diagnosis' => array ('pcr' => 1, 'antigen' => 2, 'clinical diagnosis' => 3, 'others' => 10),
          'age_unit' => array ('year' => 0, 'month' => 1, 'day' => 2),
          'province' => $provinces,
          'district' => $districts,
          'municipality' => $municipalities
        );
        $this->importedBy = $importedBy;
        $this->bed_status = $bed_status;
        $this->totalHduCases = 0;
        $this->totalIcuCases = 0;
        $this->totalVentilatorCases = 0;
        $this->totalGeneralCases = 0;
        $this->hpCode = $hpCode;
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
        $health_condition = $row['health_condition'];
        $bed_status = $this->bed_status;
        if($health_condition === 1 || $health_condition === 2){
          $this->totalGeneralCases++;
        }else if($health_condition === 3) {
          $this->totalHduCases++;
        } else if($health_condition === 4) {
          $this->totalIcuCases++;
        } else if($health_condition === 5) {
          $this->totalVentilatorCases++;
        }
        
        if($this->totalGeneralCases > $bed_status->general) {
          $error = ['health_condition' => 'No. of patient with No Symptoms/Mild condition exeeds the no. of available General bed('.$bed_status->general.'). Please update the data of your existing patient to free up bed & try again.'];
          $failures[] = new Failure(1, 'health_condition', $error, $row);
          throw new ValidationException(
              \Illuminate\Validation\ValidationException::withMessages($error),
              $failures
          );
        }
        if($this->totalHduCases > $bed_status->hdu) {
            $error = ['health_condition' => 'No. of patient with Moderate &  HDU condition exeeds the no. of available HDU bed('.$bed_status->hdu.'). Please update the data of your existing patient to free up bed & try again.'];
            $failures[] = new Failure(1, 'health_condition', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
        }
        if($this->totalIcuCases > $bed_status->icu) {
            $error = ['health_condition' => 'No. of patient with Severe - ICU condition exeeds the no. of available ICU bed('.$bed_status->icu.'). Please update the data of your existing patient to free up bed & try again.'];
            $failures[] = new Failure(1, 'health_condition', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
        }
        if($this->totalVentilatorCases > $bed_status->venti) {
            $error = ['health_condition' => 'No. of patient with Severe - Ventilator condition exeeds the no. of available ventilators('.$bed_status->venti.'). Please update the data of your existing patient to free up bed & try again.'];
            $failures[] = new Failure(1, 'health_condition', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
        } 

        return new PaymentCase([
            'hospital_register_id' => $row['hospital_id'],
            'name' => $row['full_name_of_patient'],
            'register_date_en'=> $this->todayDateEn->isoFormat("Y-M-D"),
            'register_date_np' => $this->todayDateNp,
            'lab_name' => $row['lab_name']??'No Lab Found',
            'lab_id' => $row['lab_id']??'0123456789',
            'age' => $row['age'],
            'age_unit' => $row['age_unit'],
            'gender' => $row['gender'],
            'phone' => $row['mobile_number'],
            'address' => $row['tole'],
            'guardian_name' => null,
            'complete_vaccination' => null,
            'health_condition' => $row['health_condition'],
            'self_free' =>$row['paid_free'],
            'remark' => $row['remark'],
            'is_death' => null,
            'is_in_imu' => 0,
            'method_of_diagnosis' => $row['method_of_diagnosis'],
            'hp_code' => $this->hpCode,
            'reg_dev' => 'excel',
            'province_id' => $row['province'],
            'district_id' => $row['district'],
            'municipality_id' => $row['municipality']
        ]);
    }

    private function filterEmptyRow($data) {
      $required_row = ['paid_free', 'gender', 'age_unit', 'health_condition', 'method_of_diagnosis']; //added to solve teplate throwing wierd default values
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
        if(array_filter($data)) {
          $data['paid_free'] = $this->enums['paid_free'][strtolower(trim($data['paid_free']))]??null;
          $data['gender'] = $this->enums['gender'][strtolower(trim($data['gender']))]??null;
          $data['age_unit'] = $this->enums['age_unit'][strtolower(trim($data['age_unit']))] ?? 0;
          $data['health_condition'] = $this->enums['health_condition'][strtolower(trim($data['health_condition']))] ?? null;
          $data['method_of_diagnosis'] = $this->enums['method_of_diagnosis'][strtolower(trim($data['method_of_diagnosis']))] ?? null;
          $data['province'] = $this->enums['province'][strtolower(trim($data['province']))] ?? null;
          $data['district'] = $this->enums['district'][strtolower(trim($data['district']))] ?? null;
          $data['municipality'] = $this->enums['municipality'][strtolower(trim($data['municipality']))] ?? null;
        }
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
              if ($value === '' || $value === null || !is_numeric($value) ) {
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
            },
            'province' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Province');
              }
            },
            'district' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid District');
              }
            },
            'municipality' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Municipality');
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