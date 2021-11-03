<?php

namespace App\Exports;

use App\Helpers\GetHealthpostCodes;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Models\SuspectedCaseOld;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class NewDashboardSampleDownload implements FromCollection, WithHeadings
{
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $response = FilterRequest::filter($this->request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $date_chosen = $this->request->date_chosen;
        $result_chosen = $this->request->result_chosen;
        $service_for_chosen = $this->request->service_for_chosen;

        if($date_chosen == 'Today'){
            $date_chosen = date('Y-m-d');
        }elseif($date_chosen == 'Yesterday'){
            $date_chosen = Carbon::now()->subDays(1)->format('Y-m-d');
        }

        // $data = DB::raw("
        // SELECT suspected_cases.name, suspected_cases.age, genders.name as gender, suspected_cases.province_id, districts.district_name, municipalities.municipality_name, suspected_cases.ward, suspected_cases.tole, suspected_cases.emergency_contact_one, suspected_cases.emergency_contact_two , sample_collection.token SID, sample_collection.created_at, sample_collection.reporting_date_en FROM `sample_collection` LEFT JOIN suspected_cases on suspected_cases.token = sample_collection.woman_token LEFT JOIN districts ON suspected_cases.district_id = districts.id LEFT JOIN municipalities on suspected_cases.municipality_id = municipalities.id LEFT JOIN genders on suspected_cases.sex = genders.id LEFT JOIN organizations h on h.hp_code = sample_collection.hp_code WHERE ((sample_collection.created_at like '2021-06-29%' and sample_collection.received_date_en is null) or sample_collection.reporting_date_en LIKE '2021-06-29%') and sample_collection.service_for = 2 AND sample_collection.result = 4 AND sample_collection.status = 1 and suspected_cases.name is not null ORDER BY `sample_collection`.`created_at` ASC
        // ")

        $tokens = SampleCollection::whereIn('hp_code', $hpCodes)
            ->where('service_for', $service_for_chosen)->where('result', $result_chosen)
            ->where(function($q) use($date_chosen){
                $q->where(function($q2) use($date_chosen) {
                    $q2->whereDate('created_at', $date_chosen)
                    ->whereNull('received_date_en');
                })->orWhereDate('reporting_date_en', $date_chosen);
            })
            ->active()->get()->pluck('woman_token');
            

        $data = SuspectedCase::with('district', 'municipality', 'latestAnc');
            if(auth()->user()->can('poe-registration')){
                $data = $data->where('case_type', '3');
            }
        $data = $data->whereIn('token', $tokens)->get();

        if($tokens->count() > $data->count()){
            $dump_data = SuspectedCaseOld::with('district', 'municipality', 'latestAnc');
            if(auth()->user()->can('poe-registration')){
                $data = $data->where('case_type', '3');
            }
        $data = $data->whereIn('token', $tokens)->get();
            $data = $dump_data->merge($data);
        }


        return $data->map(function ($item, $key) {
            try {
                $record = [];
                $record['sn'] = $key + 1;
                $record['name'] = $item->name;
                $record['gender'] = $item->formated_gender;
                $record['age'] = $item->age;
                $record['age_unit'] = $item->formated_age_unit;
                $record['district'] = $item->district->district_name ?? '';
                $record['municipality'] = $item->municipality->municipality_name ?? '';
                $record['ward'] = $item->ward;
                $record['phone'] = $item->emergency_contact_one;
                $record['phone_two'] = $item->emergency_contact_two;
                $record['swab_id'] = $item->latestAnc->token;
                $record['method_of_diagnosis'] = $this->checkServiceFor($item->latestAnc->service_for);
                $record['lab_id'] = $item->latestAnc->labreport->formated_token;
                $record['lab_name'] = $item->latestAnc->labreport->checked_by_name;

                return $record;
            } catch (\Exception $e) {
                return [];
            }
        });
    }

    public function headings(): array
    {
        return [
            'S.N.',
            'Full Name',
            'Gender',
            'Age',
            'Age Unit',
            'District',
            'Municipality',
            'Ward',
            'Phone',
            'Phone Two',
            'Swab ID',
            'Method of Diagnosis',
            'Lab ID',
            'Tested by Lab Name'
        ];
    }

    private function checkServiceFor($service_for)
    {
        switch ($service_for){
            case "2":
                return "Antigen";
            default:
                return "PCR";
        }
    }
}
