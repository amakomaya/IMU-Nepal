<?php

namespace App\Exports;

use App\Models\HealthProfessional;
use App\Models\MunicipalityInfo;
use App\Models\Organization;
use App\Models\VaccinationRecord;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UnVaccinatedExport implements FromCollection, WithHeadings
{
    public function __construct($arg)
    {
        $this->arg = $arg;
    }

    public function collection()
    {
            switch($this->arg['key']){
                case 'province':
                    $organization_token = Organization::where('province_id', $this->arg['id'])->pluck('token');
                    $municipality_token = MunicipalityInfo::where('province_id', $this->arg['id'])->pluck('token');
                    $tokens = collect($organization_token)->merge($municipality_token);
                    break;
                case 'district':
                    $organization_token = Organization::where('district_id', $this->arg['id'])->pluck('token');
                    $municipality_token = MunicipalityInfo::where('district_id', $this->arg['id'])->pluck('token');
                    $tokens = collect($organization_token)->merge($municipality_token);
                    break;
                case 'municipality':
                    $organization_token = Organization::where('municipality_id', $this->arg['id'])->pluck('token');
                    $municipality_token = MunicipalityInfo::where('municipality_id', $this->arg['id'])->pluck('token');
                    $tokens = collect($organization_token)->merge($municipality_token);
                    break;
                default:
                    $tokens = [];
            }

            $data = HealthProfessional::whereIn('checked_by', $tokens)
                ->whereNull('vaccinated_status')
                ->with('district','municipality')
                ->get();

        if (count($data->count()) > 0) {
            $ids = collect($data)->pluck('id');
            $vaccinated_id_check = VaccinationRecord::whereIn('vaccinated_id', $ids)->pluck('vaccinated_id');
            if (count($vaccinated_id_check) > 0) {
                HealthProfessional::whereIn('id', $vaccinated_id_check)->update(['vaccinated_status' => '1']);
                $data = HealthProfessional::whereIn('checked_by', $tokens)
                    ->whereNull('vaccinated_status')
                    ->with('district','municipality')
                    ->get();
            }
        }


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
