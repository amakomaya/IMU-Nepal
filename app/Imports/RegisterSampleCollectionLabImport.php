<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\SampleCollection;
use App\Models\LabTest;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
use App\Models\SuspectedCase;
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

class RegisterSampleCollectionLabImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable, RemembersRowNumber;

  
    public function __construct(User $importedBy)
    {
        $provinceList = Province::select(['id', 'province_name'])->get();
        $districtList = District::select(['id', 'district_name'])->get();
        $municipalityList = Municipality::select(['id', 'municipality_name'])->get();
        $provinces = $districts = $municipalities = [];
        $provinceList->map(function ($province) use (&$provinces) {
          $provinces[$province->province_name] = $province->id;
          return;
        });
        $districtList->map(function ($district) use (&$districts) {
          $districts[$district->district_name] = $district->id;
          return;
        });
        $municipalityList->map(function ($municipality) use (&$municipalities) {
          $municipalities[$municipality->municipality_name] = $municipality->id;
          return;
        });
        $userToken = auth()->user()->token;
        $healthWorker = \App\Models\OrganizationMember::where('token', $userToken)->first();
        $hpCode = $healthWorker->hp_code;

        $this->importedBy = $importedBy;
        $this->userToken =  $userToken;
        $this->hpCode = $hpCode;
        $this->organizationType = \App\Models\Organization::where('hp_code', $hpCode)->first()->hospital_type;
        $this->healthWorker = $healthWorker;
        $this->enums = array(
          'test_type'=> array( 'PCR Swab Collection' => '1', 'Antigen Test' => '2' ),
          'sample_type' => array ('Nasopharyngeal'=> '[1]', 'Oropharyngeal' => '[2]', 'Both' => '[1, 2]' ),
          'service_type' => array ('Paid Service' => "1", 'Free of Cost Service' => "2"),
          'infection_type' => array ('Symptomatic' => 1, 'Asymptomatic' => 2),
          'age_unit' => array ('Year' => 0, 'Month' => 1, 'Day' => 2),
          'gender'=> array( 'Male' => 1, 'Female' => 2, 'Other' => 3 ),
          'ethnicity' => array( "Don't Know"=> 6, 'Dalit'=> 0, 'Janajati'=> 1, 'Madheshi'=> 2, 'Muslim'=> 3, 'Brahmin'=> 4, 'Other'=> 5),
          'province' => $provinces,
          'district' => $districts,
          'municipality' => $municipalities,
          'result' => array('Positive' => '3', 'Negative' => '4')
        );
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
        $suspectedCase = SuspectedCase::create([
          'name' => $row['person_name'],
          'age' => $row['age'],
          'age_unit' => $row['age_unit'],
          'province_id' => $row['province'],
          'district_id' => $row['district'],
          'municipality_id' => $row['municipality'],
          'hp_code' => $this->hpCode,
          'token' => 'e-' . md5(microtime(true) . mt_Rand()),
          'tole' => $row['tole'],
          'ward' => $row['ward'],
          'caste' => $row['ethnicity'],
          'created_by' => $this->userToken,
          'registered_device' => 'excel',
          'status' => 1,
          'sex' => $row['gender'],
          'emergency_contact_one' => $row['emergency_contact_one'],
          'emergency_contact_two' => $row['emergency_contact_two'],
          'swab_collection_conformation' => '1',
          'cases' => '0',
          'case_type' => '1',
          'case_id' => $this->healthWorker->id . '-' . bin2hex(random_bytes(3)),
        ]);
        $sampleCollectionData = [
          'service_for' => $row['test_type'],
          'checked_by' => $this->userToken,
          'hp_code' => $this->hpCode,
          'status' => 1,
          'checked_by_name'=> $this->healthWorker->name,
          'sample_identification_type' => 'unique_id',
          'service_type' => $row['service_type'],
          'result' => $row['result'],
          'regdev' => 'excel',
          'woman_token' => $suspectedCase->token,
          'infection_type' => $row['infection_type'],
        ];
        $id = $this->healthWorker->id;
        $swabId = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon::now()->format('ymd') . '-' . $this->convertTimeToSecond(Carbon::now()->addSeconds($currentRowNumber)->format('H:i:s'));
        $sampleCollectionData['token'] = $swabId;
        if ($sampleCollectionData['service_for'] === '1')
            $sampleCollectionData['sample_type'] = $row['sample_type'];

        $sampleCollection = SampleCollection::create($sampleCollectionData);
        
        $date_en = Carbon::now();
        $date_np = Calendar::eng_to_nep($date_en->year,$date_en->month,$date_en->day)->getYearMonthDay();
        $labResult = $row['result'];
        $patientLabId = $row['patient_lab_id'];
        $sampleTestTime = $date_en->format('g : i A');
        try {
          LabTest::create([
            'token' => $this->userToken.'-'.$patientLabId,
            'hp_code' => $this->hpCode,
            'status' => 1,
            'sample_recv_date' =>  $date_np,
            'sample_test_date' => $date_np,
            'sample_test_time' => $sampleTestTime,
            'sample_test_result' => $labResult,
            'checked_by' => $this->userToken,
            'checked_by_name' => $this->healthWorker->name,
            'sample_token' => $sampleCollection->token,
            'regdev' => 'excel'
          ]);
        } catch (\Illuminate\Database\QueryException $e) {
          $error = ['patient_lab_id' => 'The test with the given Patient Lab ID already exists in the system.'];
          $failures[] = new Failure($currentRowNumber, 'patient_lab_id', $error, $row);
          throw new ValidationException(
              \Illuminate\Validation\ValidationException::withMessages($error),
              $failures
          );
          return;
        }
      return;
    }
  
    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }
  
    private function filterEmptyRow($data) {
      $required_row = ['test_type', 'sample_type', 'age_unit', 'gender', 'ethnicity', 'province' , 'district', 'municipality', 'service_type', 'infection_type', 'result']; //added to solve teplate throwing wierd default values
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
        $data['test_type'] = $this->enums['test_type'][$data['test_type']] ?? null;
        $data['sample_type'] = $this->enums['sample_type'][$data['sample_type']] ?? null;
        $data['age_unit'] = $this->enums['age_unit'][$data['age_unit']] ?? 0;
        $data['gender'] = $this->enums['gender'][$data['gender']] ?? null;
        $data['ethnicity'] = $this->enums['ethnicity'][$data['ethnicity']] ?? null;
        $data['province'] = $this->enums['province'][$data['province']] ?? null;
        $data['district'] = $this->enums['district'][$data['district']] ?? null;
        $data['municipality'] = $this->enums['municipality'][$data['municipality']] ?? null;
        $data['service_type'] = $this->enums['service_type'][$data['service_type']] ?? null;
        $data['infection_type'] = $this->enums['infection_type'][$data['infection_type']] ?? null;
        $data['result'] = $this->enums['result'][$data['result']] ?? null;
      }
      return $data;
    }

    public function rules(): array
    {
        return [
            'person_name' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Person Name');
              }
            },
            'age' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null|| !is_numeric($value)) {
                   $onFailure('Invalid Age');
              }
            },
            'gender' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Gender');
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
            // 'tole' => function($attribute, $value, $onFailure) {
            //   if ($value === '' || $value === null) {
            //        $onFailure('Invalid Tole');
            //   }
            // },
            // 'ward' => function($attribute, $value, $onFailure) {
            //   if ($value === '' || $value === null) {
            //        $onFailure('Invalid Ward');
            //   }
            // },
            // 'caste' => function($attribute, $value, $onFailure) {
            //   if ($value === '' || $value === null) {
            //        $onFailure('Invalid Caste');
            //   }
            // },
            'emergency_contact_one' => function($attribute, $value, $onFailure) {
              if(!preg_match('/(?:\+977[- ])?\d{2}-?\d{7,8}/i', $value)) {
                $onFailure('Invalid Mobile Number.');
              }
            },
            'test_type' => function($attribute, $value, $onFailure) {
              $pcrAllowedOrganizationType = ['2', '3'];
              if($value == '1' && !in_array($this->organizationType, $pcrAllowedOrganizationType)) {
                $onFailure('Your organization is not eligible for PCR Lab Result. Please contact IMU support to update your organization type.');
              }
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Test Type');
              }
            },
            'service_type' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Service Type');
              }
            },
            'infection_type' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Infection Type');
              }
            },
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