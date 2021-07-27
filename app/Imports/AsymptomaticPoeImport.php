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

class AsymptomaticPoeImport  implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
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
        $hpCode = $healthWorker->hp_code;

        $this->importedBy = $importedBy;
        $this->userToken =  $userToken;
        $this->hpCode = $hpCode;
        $this->healthWorker = $healthWorker;
        $this->enums = array(
          'gender'=> array( 'male' => '1', 'female' => '2', 'other' => '3' ),
          'occupation' => array(),
          'destination_in_nepal_province' => $provinces,
          'destination_in_nepal_district' => $districts,
          'destination_in_nepal_municipality' => $municipalities,
          'countries' => ['nepal'=>167, 'india'=>104, 'china'=>47, 'other'=>300],
          'yes_no' => ['yes'=>1, 'no'=>0],
          'how_many_dosages_of_vaccine_you_have_received' => ['1st dose' => 1,'2nd (final) dose'=>2],
          // 'name_of_vaccine' => ['verocell (sinopharm)'=> '1', 'covishield (the serum institute of india)'=>'2', 'pfizer' => '3', 'moderna' => '4', 'astrazeneca' => '5', 'other' => '10'],
          'occupation' => ['1' =>'front line health worker', '2' =>'doctor','3' => 'nurse','4' =>'police/army', '5' =>'business/industry', '6' =>'teacher/student(education)', '7' =>'civil servant', '8' =>'journalist', '9' =>'agriculture', '10' =>'transport/delivery', '11' =>'Tourist', '12' =>'migrant worker'],
          'relationship_with_the_contact_person' => ['family'=>0,'friend'=>1,'neighbour'=>2,'relative'=>3,'other'=>4]
          
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
          'name' => $row['full_name'],
          'age' => $row['age'],
          'age_unit' => '0',
          'sex' => $row['gender'],
          'occupation' => $row['occupation'],
          'nationality' => $row['nationality'],
          'id_card_detail' => $row['passportnationality_number'],
          'travelled' => '1',
          'travelled_date' => $row['travel_date'],
          'travelled_where' => '['.$row['travel_from_country'].','.$row['travelled_from_city'].']',
          'province_id' => $row['destination_in_nepal_province'],
          'district_id' => $row['destination_in_nepal_district'],
          'municipality_id' => $row['destination_in_nepal_municipality'],
          'hp_code' => $this->hpCode,
          'tole' => $row['destination_in_nepal_tole'],
          'ward' => $row['destination_in_nepal_ward_no'],
          'created_by' => $this->userToken,
          'registered_device' => 'excel',
          'nearest_contact' => '['.$row['nearest_contact_person_in_nepal'].','.$row['relationship_with_the_contact_person'].','.$row['contact_of_nearest_person_phone'].']',
          'temprature' => $row['body_temperaturein_fahrenheit'],
          'covid_vaccination_details' => '['.$row['have_you_ever_received_covid_19_vaccine'].','.$row['do_you_have_a_vaccination_card'].','.$row['vaccination_doses_complete'].','.$row['how_many_dosages_of_vaccine_you_have_received'].','.$row['name_of_vaccine'].']',
          'status' => 1,
          'token' => 'e-' . md5(microtime(true) . mt_Rand()),
          'emergency_contact_one' => $row['contact_of_nearest_person_phone'],
          'swab_collection_conformation' => '1',
          'cases' => '0',
          'case_type' => '3',
          'case_id' => $this->healthWorker->id .'-' . Carbon::now()->format('ymd') . '-' . strtoupper(bin2hex(random_bytes(3))),
          'register_date_en' => $this->todayDateEn,
          'register_date_np' => $this->todayDateNp,
          'symptoms_recent' => '0',
          'symptoms_within_four_week' => '0'
        ]);
        return;
    }
  
    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }
  
    private function filterEmptyRow($data) {
      $required_row = ['full_name', 'age', 'destination_in_nepal_province', 'destination_in_nepal_district', 'destination_in_nepal_municipality', 'gender' , 'contact_of_nearest_person_phone']; //added to solve teplate throwing wierd default values
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
        $data['gender'] = $this->enums['gender'][strtolower(trim($data['gender']))] ?? null;
        $data['destination_in_nepal_province'] = $this->enums['destination_in_nepal_province'][strtolower(trim($data['destination_in_nepal_province']))] ?? null;
        $data['destination_in_nepal_district'] = $this->enums['destination_in_nepal_district'][strtolower(trim($data['destination_in_nepal_district']))] ?? null;
        $data['destination_in_nepal_municipality'] = $this->enums['destination_in_nepal_municipality'][strtolower(trim($data['destination_in_nepal_municipality']))] ?? null;
        $data['occupation'] = $this->enums['occupation'][strtolower(trim($data['occupation']))] ?? null;
        $data['nationality'] = $this->enums['countries'][strtolower(trim($data['nationality']))] ?? null;
        $data['have_you_ever_received_covid_19_vaccine'] = $this->enums['yes_no'][strtolower(trim($data['have_you_ever_received_covid_19_vaccine']))] ?? null;
        $data['do_you_have_a_vaccination_card'] = $this->enums['yes_no'][strtolower(trim($data['do_you_have_a_vaccination_card']))] ?? null;
        $data['vaccination_doses_complete'] = $this->enums['yes_no'][strtolower(trim($data['vaccination_doses_complete']))] ?? null;
        $data['how_many_dosages_of_vaccine_you_have_received'] = $this->enums['how_many_dosages_of_vaccine_you_have_received'][strtolower(trim($data['how_many_dosages_of_vaccine_you_have_received']))] ?? null;
        // $data['name_of_vaccine'] = $this->enums['name_of_vaccine'][strtolower(trim($data['name_of_vaccine']))] ?? null;
        $data['travel_from_country'] = $this->enums['countries'][strtolower(trim($data['travel_from_country']))] ?? null;
        $data['relationship_with_the_contact_person'] = $this->enums['relationship_with_the_contact_person'][strtolower(trim($data['relationship_with_the_contact_person']))] ?? null;
        // $data[] = 
      }
      return $data;
    }
  

    public function rules(): array
    {
        return [
            'full_name' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Name');
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
            'destination_in_nepal_province' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Province');
              }
            },
            'destination_in_nepal_district' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid District');
              }
            },
            'destination_in_nepal_municipality' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Municipality');
              }
            },
            'destination_in_nepal_tole' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Tole');
              }
            },
            // 'destination_in_nepal_ward' => function($attribute, $value, $onFailure) {
            //   if ($value === '' || $value === null) {
            //        $onFailure('Invalid Ward');
            //   }
            // },
            'passportnationality_number' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Id No.');
              }
            },
            'contact_of_nearest_person_phone' => function($attribute, $value, $onFailure) {
               if ($value === '' || $value === null) {
                   $onFailure('Invalid Mobile No.');
              }
            },
            'travel_date' => function($attribute, $value, $onFailure) {
              //TODO: check travel date english or nepali
              if ($value === '' || $value === null) {
                  $onFailure('Invalid Travel Date');
              }
            },
            'travel_from_country' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                  $onFailure('Invalid Travel Country');
              }
            },
            'travelled_from_city' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                  $onFailure('Invalid Travel City');
              }
            },
            'contact_of_nearest_person_phone' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                  $onFailure('Invalid Contact Phone');
              }
            },
           'body_temperaturein_fahrenheit' => function($attribute, $value, $onFailure) {
            if ($value === '' || $value === null) {
                $onFailure('Temprature must be entered in valid Fahrenheit unit.');
            }
            if($value && ((float)$value>110 || (float)$value<80)){
              $onFailure('Temprature must be entered in valid Fahrenheit unit.');
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