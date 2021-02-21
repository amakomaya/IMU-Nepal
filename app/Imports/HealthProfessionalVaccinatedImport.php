<?php

namespace App\Imports;

use App\Models\HealthProfessional;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithColumnLimit;

class HealthProfessionalVaccinatedImport implements ToCollection, WithColumnLimit
{

    public function collection(Collection $rows)
    {
        $token = auth()->user()->token;
        $now_date = Carbon::now();
        $data = $rows->map(function ($row) use ($now_date, $token) {
            $data = [
                'vaccinated_id'         => $row[0],
                'vaccinated_date_en'     => $row[1],
                'vaccinated_date_np'     => $row[2],
                'vaccinated_address'     => $row[3]
            ];
          return $data;
        })->toArray();

        dd($data);
    }

    public function endColumn(): string
    {
        return 'T';
    }
}
