<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\SampleCollection;
use App\Models\Country;
use App\Models\LabTest;
use App\Models\Province;
use App\Models\District;
use App\Models\Municipality;
use App\Models\SuspectedCase;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Events\ImportFailed;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Yagiten\Nepalicalendar\Calendar;
use Maatwebsite\Excel\Validators\ValidationException;
use Maatwebsite\Excel\Validators\Failure;
use Illuminate\Support\Facades\Cache;


use App\User;

class PoeImport  implements ToModel, WithChunkReading, WithValidation, WithHeadingRow, ShouldQueue
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
        $countriesList = Cache::remember('country-list', 48*60*60, function () {
          return Country::get();
        });
        $provinces = $districts = $municipalities = $countries = [];
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
        $countriesList->map(function ($country) use (&$countries) {
          $countries[utf8_encode(strtolower(trim($country->name)))] = $country->country_id;
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
          'gender'=> array( 'male' => '1', 'female' => '2', 'other' => '3' ),
          'destination_in_nepal_province' => $provinces,
          'destination_in_nepal_district' => $districts,
          'destination_in_nepal_municipality' => $municipalities,
          'countries' => $countries,
          'yes_no' => ['yes'=>'1', 'no'=>'0'],
          'how_many_dosages_of_vaccine_you_have_received' => ['1st dose' => 1,'2nd (final) dose'=>2],
          // 'name_of_vaccine' => ['verocell (sinopharm)'=> '1', 'covishield (the serum institute of india)'=>'2', 'pfizer' => '3', 'moderna' => '4', 'astrazeneca' => '5', 'other' => '10'],
          'occupation' => [ 'front line health worker' =>'1', 'doctor' =>'2','nurse' => '3','police/army' =>'4', 'business/industry' =>'5', 'teacher/student(education)' =>'6', 'civil servant' =>'7', 'journalist' =>'8', 'agriculture' =>'9', 'transport/delivery' =>'10', 'tourist' =>'11', 'migrant worker' =>'12'],
          'relationship_with_the_contact_person' => ['family'=>0,'friend'=>1,'neighbour'=>2,'relative'=>3,'other'=>4],
          'result' => ['positive'=>3, 'negative'=>4],
          'comorbidity' => [
            'diabetes' => '1', 'htn' => '2', 'hermodialysis' => '3','immunocompromised' => '4','maternity' => '6','heart disease, including hypertension' => '7',
            'liver disease' => '8', 'nerve related diseases' => '9', 'kidney diseases' => '10', 'malnutrition' => '11', 'autoimmune diseases' => '12', 'immunodeficiency, including hiv' => '13', 'malignancy' => '14', 'chric lung disesase/asthma/artery' => '15'
          ],
          'id_card_type' => [
            'citizenship'  => '1', 'license' => '2', 'voter card' => '3', 'passport', 'other' => '0'
          ]

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
        $regDateEn = $row['date_of_entering_poe_yyyy_mm_dd_ad'];
        if(!$regDateEn) { //If empty automatic today reg date
          $regDateEn = $this->todayDateEn->format('Y-m-d');
        }
        $regDateEn = $this->returnValidEnDate($regDateEn);
        if (!$regDateEn) {
          $error = ['date_of_entering_poe_yyyy_mm_dd_ad' => 'Invalid Entry Date. Date must be in AD & YYYY-MM-DD Format'];
            $failures[] = new Failure(1, 'date_of_entering_poe_yyyy_mm_dd_ad', $error, $row);
            throw new ValidationException(
                \Illuminate\Validation\ValidationException::withMessages($error),
                $failures
            );
            return;
        }
        $date_of_first_vacc = $row['date_of_first_vaccination_yyyy_mm_dd_ad'];
        $date_of_second_vacc = $row['date_of_second_vaccination_yyyy_mm_dd_ad'];
        if($date_of_first_vacc) {
          $date_of_first_vacc = $this->returnValidEnDate($date_of_first_vacc);
          if (!$date_of_first_vacc) {
            $error = ['date_of_first_vaccination_yyyy_mm_dd_ad' => 'Invalid Date. Date must be in AD & YYYY-MM-DD Format'];
              $failures[] = new Failure(1, 'date_of_first_vaccination_yyyy_mm_dd_ad', $error, $row);
              throw new ValidationException(
                  \Illuminate\Validation\ValidationException::withMessages($error),
                  $failures
              );
              return;
          }
          $dateArray = explode("-", $date_of_first_vacc);
          $date_of_first_vacc = Calendar::eng_to_nep($dateArray[0],$dateArray[1],$dateArray[2])->getYearMonthDay();
        }
        if($date_of_second_vacc) {
          $date_of_second_vacc = $this->returnValidEnDate($date_of_second_vacc);
          if (!$date_of_second_vacc) {
            $error = ['date_of_second_vaccination_yyyy_mm_dd_ad' => 'Invalid Date. Date must be in AD & YYYY-MM-DD Format'];
              $failures[] = new Failure(1, 'date_of_second_vaccination_yyyy_mm_dd_ad', $error, $row);
              throw new ValidationException(
                  \Illuminate\Validation\ValidationException::withMessages($error),
                  $failures
              );
              return;
          }
          $dateArray = explode("-", $date_of_second_vacc);
          $date_of_second_vacc = Calendar::eng_to_nep($dateArray[0],$dateArray[1],$dateArray[2])->getYearMonthDay();
        }
        $dateArray = explode("-", $regDateEn);
        $regDateNp = Calendar::eng_to_nep($dateArray[0],$dateArray[1],$dateArray[2])->getYearMonthDay();
       
        $suspectedCase = SuspectedCase::create([
          'name' => $row['full_name'],
          'age' => $row['age'],
          'age_unit' => '0',
          'sex' => $row['gender'],
          'occupation' => $row['occupation'],
          'nationality' => $row['nationality'],
          'id_card_type' => $row['id_card_type'],
          'id_card_type_other' => $row['if_other_id_card_type'],
          'id_card_detail' => $row['id_card_no'],
          'travelled' => '1',
          // 'travelled_date' => $row['travel_date'],
          'travelled_where' => '['.$row['travel_from_country'].','.$row['travelled_from_city'].']',
          'province_id' => $row['destination_in_nepal_province'],
          'district_id' => $row['destination_in_nepal_district'],
          'municipality_id' => $row['destination_in_nepal_municipality'],
          'org_code' => $this->hpCode,
          'tole' => $row['destination_in_nepal_tole'],
          'ward' => $row['destination_in_nepal_ward_no'],
          'created_by' => $this->userToken,
          'registered_device' => 'excel',
          'nearest_contact' => '['.$row['nearest_contact_person_in_nepal'].',null,'.$row['contact_of_nearest_person_phone'].']',
          'temperature' => (int)$row['body_temperaturein_fahrenheit'],
          'covid_vaccination_details' => '['.$row['have_you_ever_received_covid_19_vaccine'].',null,null,null,'.$row['name_of_vaccine'].','.$row['if_other_name_of_vaccine'].']',
          'dose_details' => '[{"type":"1","date":"' . $date_of_first_vacc??'' . '"},{"type":"2","date":"' . $date_of_second_vacc??'' . '"}]',
          'status' => 1,
          'token' => 'e-' . md5(microtime(true) . mt_Rand()),
          'emergency_contact_one' => $row['contact_phone_number_in_nepal'],
          'emergency_contact_two' => $row['contact_of_nearest_person_phone'],
          'caste' => 7,
          'swab_collection_conformation' => '1',
          'cases' => '0',
          'case_type' => '3',
          'case_id' => $this->healthWorker->id . '-' . Carbon::now()->format('ymd') . '-' . strtoupper(bin2hex(random_bytes(3))),
          'register_date_en' => $regDateEn,
          'register_date_np' => $regDateNp,
          'symptoms_recent' => $row['covid_19_symptoms'],
          'symptoms_within_four_week' => $row['covid_19_symptoms'],
          'malaria' => '['.$row['if_fever_malaria_test_done'].','.$row['malaria_test_result'].','.$row['if_malaria_positive_isolation_center_referred_to'].']',
          'symptoms_comorbidity' => '['.$row['comorbidity'].']',
          'case_reason' => '['.$row['if_fever_covid_19_antigen_test_done'].','.$row['antigen_result'].','.$row['if_antigen_positive_isolation_center_referred_to'].']',
        ]);
        // dd($row['antigen_result']);
        if($row['antigen_result']) {
          $sampleTestTime = $this->todayDateEn->format('g : i A');
          $labResult = $row['antigen_result'];
          $sampleCollectionData = [
            'service_for' => '2',
            'checked_by' => $this->userToken,
            'org_code' => $this->hpCode,
            'status' => 1,
            'checked_by_name'=> $this->healthWorker->name,
            'sample_identification_type' => 'unique_id',
            // 'service_type' => $row['service_type'], //TODO verify service type paid or free
            'result' => $labResult,
            'registered_device' => 'excel',
            'case_token' => $suspectedCase->token,
            'infection_type' => '1',
            'sample_test_date_en' => $regDateEn,
            'sample_test_date_np' => $regDateNp,
            'sample_test_time' => $sampleTestTime,
            'received_by' => $this->userToken,
            'received_by_org_code' => $this->hpCode,
            'received_date_en' => $regDateEn,
            'received_date_np' => $regDateNp,
            'collection_date_en' => $regDateEn,
            'collection_date_np' => $regDateNp,
            'reporting_date_en' => $this->todayDateEn,
            'reporting_date_np' => $this->todayDateNp
          ];
          $id = $this->healthWorker->id;
          $patientLabId = Carbon::now()->format('ymd'). '-'. $this->convertTimeToSecond(Carbon::now()->addSeconds($currentRowNumber+1)->format('H:i:s'));
          $swabId = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon::now()->format('ymd') . '-' . $this->convertTimeToSecond(Carbon::now()->addSeconds($currentRowNumber+1)->format('H:i:s'));
          $swabId = generate_unique_sid($swabId);
          $sampleCollectionData['token'] = $swabId;
          $sampleCollectionData['lab_token'] = $this->userToken.'-'.$patientLabId;
          $uniqueLabId = generate_unique_lab_id_excel($sampleCollectionData['lab_token']);
          $sampleCollectionData['lab_token'] = $uniqueLabId;
          
          $sampleCollection = SampleCollection::create($sampleCollectionData);
          LabTest::create([
            'token' => $sampleCollectionData['lab_token'],
            'org_code' => $this->hpCode,
            'status' => 1,
            'sample_recv_date' =>  $regDateEn,
            'sample_test_date' => $regDateNp,
            'sample_test_time' => $sampleTestTime,
            'sample_test_result' => $labResult,
            'checked_by' => $this->userToken,
            'checked_by_name' => $this->healthWorker->name,
            'sample_token' => $sampleCollection->token,
            'registered_device' => 'excel'
          ]);
        }
        return;
    }
  
    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }
  
    private function filterEmptyRow($data) {
      $required_row = ['full_name', 'age', 'destination_in_nepal_province', 'destination_in_nepal_district', 'destination_in_nepal_municipality', 'gender' , 'contact_of_nearest_person_phone', 'contact_phone_number_in_nepal']; //added to solve teplate throwing wierd default values
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

    private function returnValidEnDate($date){
      try{
        //TODO: stop future date
        if($date) {
          if (preg_match ("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
          {
            $year = $parts[1];
            $month = $parts[2];
            $day = $parts[3];
            if (checkdate($month ,$day, $year)) {
              if((int)$year <= Carbon::now()->year) {
                return $date;
              }
            }
          } else {
            $parsedDate = Date::excelToDateTimeObject($date);
            $carbonDate = Carbon::instance($parsedDate);
            $year = $carbonDate->year;
            $month = $carbonDate->month;
            $day = $carbonDate->day;
            if($year>2000 && $year <= Carbon::now()->year) {
              return $carbonDate->format('Y-m-d');
            }
          }
        }
      } catch(\Exception $e){
        return false;
      } 
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
        
        $data['travel_from_country'] = $this->enums['countries'][strtolower(trim($data['travel_from_country']))] ?? null;
        $data['covid_19_symptoms'] = $this->enums['yes_no'][strtolower(trim($data['covid_19_symptoms']))] ?? null;
        $data['if_fever_malaria_test_done'] = $this->enums['yes_no'][strtolower(trim($data['if_fever_malaria_test_done']))] ?? null;
        $data['malaria_test_result'] = $this->enums['result'][strtolower(trim($data['malaria_test_result']))] ?? null;
        $data['comorbidity'] = $this->enums['comorbidity'][strtolower(trim($data['comorbidity']))] ?? null;
        $data['if_fever_covid_19_antigen_test_done'] = $this->enums['yes_no'][strtolower(trim($data['if_fever_covid_19_antigen_test_done']))] ?? null;
        $data['antigen_result'] = $this->enums['result'][strtolower(trim($data['antigen_result']))] ?? null;
        $data['id_card_type'] = $this->enums['id_card_type'][strtolower(trim($data['id_card_type']))] ?? null;
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
            'id_card_type' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Id Card Type');
              }
            },
            'id_card_detail' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                   $onFailure('Invalid Id Card No.');
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
            'contact_phone_number_in_nepal' => function($attribute, $value, $onFailure) {
              if ($value === '' || $value === null) {
                  $onFailure('Invalid Contact Phone');
              }
            },
           'body_temperaturein_fahrenheit' => function($attribute, $value, $onFailure) {
            if($value && ((float)$value>110 || (float)$value<80)){
              $onFailure('Temeprature must be entered in valid Fahrenheit unit.');
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