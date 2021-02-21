<?php

namespace App\Imports;

use App\Models\HealthProfessional;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnLimit;

class HealthProfessionalImport implements ToCollection, WithColumnLimit
{

    public function collection(Collection $rows)
    {
        $token = auth()->user()->token;
        $now_date = Carbon::now();
        $data = $rows->map(function ($row) use ($now_date, $token) {
          $data = [
            'health_worker'         => $row[0],
            'organization_type'     => $row[1],
            'organization_name'     => $row[2],
            'organization_phn'      => $row[3],
            'organization_address'  => $row[4],
            'designation'           => $row[5],
            'level'                 => $row[6],
            'service_date'          => $row[7],
            'name'                  => $row[8],
            'gender'                => $row[9],
            'age'                   => $row[10],
            'phone'                 => $row[11],
            'province_id'           => $row[12],
            'district_id'           => $row[13],
            'municipality_id'       => $row[14],
            'ward'                  => $row[15],
            'tole'                  => $row[16],
            'citizenship_no'        => $row[17],
            'issue_district'        => $row[18],
            'covid_status'          => $row[19],
            'created_at'            => $now_date,
            'updated_at'            => $now_date,
            'checked_by'            => $token,
            'status'                => 1
        ];
          return $data;
        })->toArray();

        return HealthProfessional::insert($data);

    }

    public function endColumn(): string
    {
        return 'Z';
    }
}
