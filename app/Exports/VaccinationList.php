<?php


namespace App\Exports;


use App\Helpers\GetHealthpostCodes;
use App\Models\HealthProfessional;
use App\Models\MunicipalityInfo;
use App\Models\Organization;
use App\Models\VaccinationRecord;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VaccinationList implements FromCollection, WithHeadings
{
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function collection()
    {
        $request = $this->request;
        $response = FilterRequest::filter($this->request);
        $hpCodes = GetHealthpostCodes::filter($response);

        if ($request->has('from')){

            $start = Carbon::parse($request->from)->startOfDay();
            $end = Carbon::parse($request->to)->endOfDay();

            $data_list = VaccinationRecord::whereIn('hp_code', $hpCodes)
                ->whereBetween('created_at',[$start, $end]);
        }else {
            $data_list = VaccinationRecord::whereIn('hp_code', $hpCodes);
        }
        $data_ids = $data_list->pluck('vaccinated_id');

        $data = HealthProfessional::whereIn('id', $data_ids)->get();

        return $data->map(function ($item, $key) {
            try {
                $record = [];
                $record['sn'] = $key + 1;
                $record['register_no'] = $item->id;
                $record['name'] = $item->name;
                $record['gender'] = $this->gender($item->gender);
                $record['age'] = $item->age;
                $record['organization_name'] = $item->organization_name;
                $record['district'] = $item->district->district_name ?? '';
                $record['municipality'] = $item->municipality->municipality_name ?? '';
                $record['ward'] = $item->ward;
                $record['phone'] = $item->phone;
                $record['post'] = $item->designation;
                $record['id_number'] = $item->citizenship_no . ' / ' . $item->issue_district;
                return $record;
            } catch (\Exception $e) { }
        });
    }

    public function headings(): array
    {
        return [
            'S.N.',
            'Register Number',
            'Full Name',
            'Gender',
            'Age',
            'Organization Name',
            'District',
            'Municipality',
            'Ward',
            'Phone',
            'Post',
            'ID No'
        ];
    }

    private function gender($value)
    {
        switch ($value) {
            case '1':
                return 'Male';
            case '2':
                return 'Female';
            default:
                return 'Other';
        }
    }

}