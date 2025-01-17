<?php

namespace App\Exports;

use App\Helpers\GetHealthpostCodes;
use App\Models\OrganizationMember;
use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class DownloadablePositiveListPoe implements FromCollection, WithHeadings
{
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        {
            $response = FilterRequest::filter($this->request);
            $hpCodes = GetHealthpostCodes::filter($response);

            $date_from = Carbon::today()->startOfDay();
            $date_to = Carbon::now();

            $tokens = SampleCollection::whereIn('hp_code', $hpCodes)->where(function ($q) {
                    $q->where('result', 3)->orWhere('result', '0');
                })
//                ->whereDate('updated_at', Carbon::today())
                ->whereBetween('sample_test_date_en', array($date_from->toDateTimeString(), $date_to->toDateTimeString()) )
                ->active()
                ->pluck('woman_token');

            $data = SuspectedCase::whereIn('token', $tokens)->with('district', 'municipality', 'latestAnc')->get();

            return $data->map(function ($item, $key) {
                try {
                    $record = [];
                    $record['sn'] = $key + 1;
                    $record['name'] = $item->name;
                    $record['gender'] = $item->formated_gender;
                    $record['age'] = $item->age;
                    $record['age_unit'] = $item->formated_age_unit;
                    // $record['travel_where'] = $item->travel_where;
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
    }

    public function headings(): array
    {
        return [
            'S.N.',
            'Full Name',
            'Gender',
            'Age',
            'Age Unit',
            // 'From Country',
            'Dest. District',
            'Dest. Municipality',
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
