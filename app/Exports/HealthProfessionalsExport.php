<?php

namespace App\Exports;

use App\Models\HealthProfessional;
use App\Models\MunicipalityInfo;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class HealthProfessionalsExport implements FromCollection, WithHeadings
{
    public function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        if (Auth::user()->role === "main" || Auth::user()->role === "center") {
            $data = HealthProfessional::whereBetween('created_at', [$this->request['from'], $this->request['to']])
                ->with('district', 'municipality')
                ->get();
        } elseif (Auth::user()->role === "municipality") {
            $token = Auth::user()->token;
            $municipality_id = MunicipalityInfo::where('token', $token)->first()->municipality_id;
            $organization = Organization::where('municipality_id', $municipality_id)->pluck('token');

            $checked_by_tokens = collect($organization)->merge($token)->toArray();

            $data = HealthProfessional::whereIn('checked_by', $checked_by_tokens)
                ->whereBetween('created_at', [$this->request['from'], $this->request['to']])
                ->whereNull('vaccinated_status')
                ->with('district','municipality')
                ->get();
        } else {
            $data = HealthProfessional::where('checked_by', Auth::user()->token)
                ->whereBetween('created_at', [$this->request['from'], $this->request['to']])
                ->whereNull('vaccinated_status')
                ->with('district','municipality')
                ->get();

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
            } catch (\Exception $e) {

            }
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