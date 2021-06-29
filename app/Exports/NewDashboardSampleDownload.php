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

        $tokens = SampleCollection::whereIn('hp_code', $hpCodes)->where('service_for', $service_for_chosen)->where('result', $result_chosen)
            ->whereDate('reporting_date_en', $date_chosen)->active()->get()->pluck('woman_token');

        $data = SuspectedCase::whereIn('token', $tokens)->with('district', 'municipality', 'latestAnc')->get();

        if($tokens->count() > $data->count()){
            $dump_data = SuspectedCaseOld::whereIn('token', $tokens)->with('district', 'municipality', 'latestAnc')->get();
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
