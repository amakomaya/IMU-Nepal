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
        // SELECT women.name, women.age, genders.name as gender, women.province_id, districts.district_name, municipalities.municipality_name, women.ward, women.tole, women.emergency_contact_one, women.emergency_contact_two , ancs.token SID, ancs.created_at, ancs.reporting_date_en FROM `ancs` LEFT JOIN women on women.token = ancs.woman_token LEFT JOIN districts ON women.district_id = districts.id LEFT JOIN municipalities on women.municipality_id = municipalities.id LEFT JOIN genders on women.sex = genders.id LEFT JOIN healthposts h on h.hp_code = ancs.hp_code WHERE ((ancs.created_at like '2021-06-29%' and ancs.received_date_en is null) or ancs.reporting_date_en LIKE '2021-06-29%') and ancs.service_for = 2 AND ancs.result = 4 AND ancs.status = 1 and women.name is not null ORDER BY `ancs`.`created_at` ASC
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
