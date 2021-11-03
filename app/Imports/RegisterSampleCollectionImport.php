<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\SampleCollection;
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
use Illuminate\Support\Facades\Cache;

use App\User;

class RegisterSampleCollectionImport implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
{
    use Importable, RemembersRowNumber;

    public static $importedRowCount = 0;
    public function __construct(User $importedBy)
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
        $userToken = auth()->user()->token;
        $healthWorker = \App\Models\OrganizationMember::where('token', $userToken)->first();
        $hpCode = $healthWorker->org_code;

        $this->importedBy = $importedBy;
        $this->userToken =  $userToken;
        $this->hpCode = $hpCode;
        $this->healthWorker = $healthWorker;
        $this->enums = array(
          'test_type'=> array( 'pcr swab collection' => '1', 'antigen test' => '2' ),
          'sample_type' => array ('nasopharyngeal'=> '[1]', 'oropharyngeal' => '[2]', 'both' => '[1, 2]' ),
          'service_type' => array ('paid service' => "1", 'free of cost service' => "2"),
          'infection_type' => array ('symptomatic' => '1', 'asymptomatic' => '2'),
          'age_unit' => array ('year' => '0', 'month' => '1', 'day' => '2'),
          'gender'=> array( 'male' => '1', 'female' => '2', 'other' => '3' ),
          'ethnicity' => array( "don't know"=> 7, 'dalit'=> 1, 'janajati'=> 2, 'madheshi'=> 3, 'muslim'=> 4, 'brahmin/chhetri'=> 5, 'other'=> 6),
          'province' => $provinces,
          'district' => $districts,
          'municipality' => $municipalities,
        );
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

        $suspectedCase = SuspectedCase::create([
          'name' => $row['person_name'],
          'age' => $row['age'],
          'age_unit' => $row['age_unit']??'0',
          'province_id' => $row['province'],
          'district_id' => $row['district'],
          'municipality_id' => $row['municipality'],
          'org_code'=> $this->hpCode,
          'tole' => $row['tole'],
          'ward' => $row['ward'],
          'caste' => $row['ethnicity'],
          'created_by' => $this->userToken,
          'registered_device' => 'excel',
          'status' => 1,
          'token' => 'e-' . md5(microtime(true) . mt_Rand()),
          'sex' => $row['gender'],
          'emergency_contact_one' => $row['emergency_contact_one'],
          'emergency_contact_two' => $row['emergency_contact_two'],
          'swab_collection_conformation' => '1',
          'cases' => '0',
          'case_type' => '1',
          'case_id' => $this->healthWorker->id . '-' . Carbon::now()->format('ymd') . '-' . strtoupper(bin2hex(random_bytes(3))),
          'register_date_en' => $this->todayDateEn,
          'register_date_np' => $this->todayDateNp
        ]);
        $sampleCollectionData = [
          'service_for' => $row['test_type'],
          'checked_by' => $this->userToken,
          'org_code'=> $this->hpCode,
          'status' => 1,
          'checked_by_name'=> $this->healthWorker->name,
          'sample_identification_type' => 'unique_id',
          'service_type' => $row['service_type'],
          'result' => 2,
          'registered_device' => 'excel',
          'woman_token' => $suspectedCase->token,
          'infection_type' => $row['infection_type'],
          'collection_date_en' => $this->todayDateEn,
          'collection_date_np' => $this->todayDateNp
        ];
        $id = $this->healthWorker->id;
        $swabId = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon::now()->format('ymd') . '-' . $this->convertTimeToSecond(Carbon::now()->addSeconds($currentRowNumber)->format('H:i:s'));
        $swabId = generate_unique_sid($swabId);
        $sampleCollectionData['token'] = $swabId;
        if ($sampleCollectionData['service_for'] === '1')
            $sampleCollectionData['sample_type'] = $row['sample_type'];
        SampleCollection::create($sampleCollectionData);
        return;
    }
  
    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }
  
    private function filterEmptyRow($data) {
      $required_row = ['test_type', 'sample_type', 'age_unit', 'gender', 'ethnicity', 'province' , 'district', 'municipality', 'service_type', 'infection_type']; //added to solve teplate throwing wierd default values
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
        $data['test_type'] = $this->enums['test_type'][strtolower(trim($data['test_type']))] ?? null;
        $data['sample_type'] = $this->enums['sample_type'][strtolower(trim($data['sample_type']))] ?? null;
        $data['age_unit'] = $this->enums['age_unit'][strtolower(trim($data['age_unit']))] ?? 0;
        $data['gender'] = $this->enums['gender'][strtolower(trim($data['gender']))] ?? null;
        $data['ethnicity'] = $this->enums['ethnicity'][strtolower(trim($data['ethnicity']))] ?? null;
        $data['province'] = $this->enums['province'][strtolower(trim($data['province']))] ?? null;
        $data['district'] = $this->enums['district'][strtolower(trim($data['district']))] ?? null;
        $data['municipality'] = $this->enums['municipality'][strtolower(trim($data['municipality']))] ?? null;
        $data['service_type'] = $this->enums['service_type'][strtolower(trim($data['service_type']))] ?? null;
        $data['infection_type'] = $this->enums['infection_type'][strtolower(trim($data['infection_type']))] ?? null;
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
            'test_type' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Test Type');
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
            'service_type' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Service Type');
              }
            },
            'infection_type' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Infection Type');
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