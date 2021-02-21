<?php

namespace App\Imports;

use App\Models\HealthProfessional;
use App\Models\OrganizationMember;
use App\Models\VaccinationRecord;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnLimit;

class HealthProfessionalVaccinatedImport implements ToCollection, WithColumnLimit
{

    public function collection(Collection $rows)
    {
        $token = auth()->user()->token;
        $hp_code = OrganizationMember::where('token', $token)->first()->hp_code;
        $now_date = Carbon::now();
        $data = $rows->map(function ($row) use ($now_date, $hp_code) {
            $data = [
                'vaccinated_id'         => $row[0],
                'vaccinated_date_en'     => $row[1],
                'vaccinated_date_np'     => $row[2],
                'vaccinated_address'     => $row[3],
                'created_at' => $now_date,
                'updated_at' => $now_date,
                'status' => 1,
                'vaccine_period' => '1M',
                'vaccine_name' => 'Covi Shield',
                'hp_code' => $hp_code
            ];
          return $data;
        })->toArray();

        return VaccinationRecord::insert($data);
    }

    public function endColumn(): string
    {
        return 'H';
    }
}
