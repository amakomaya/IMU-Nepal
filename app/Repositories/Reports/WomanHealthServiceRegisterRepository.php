<?php

namespace App\Repositories\Reports;

use App\Helpers\GetHealthpostCodes;
use App\Models\VaccinationRecord;
use App\Reports\DateFromToRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yagiten\Nepalicalendar\Calendar;
use App\Models\Woman;


class WomanHealthServiceRegisterRepository
{
    public function all($request)
    {
        $hpCodes = GetHealthpostCodes::filter($request);

        $data = $this->data($hpCodes, $request);
        return $data;
    }

    private function data($hpCodes, $request)
    {
        return Woman::with('ancs', 'pncs', 'delivery', 'babyDetails', 'lab_tests', 'vaccinations')->whereIn('hp_code',$hpCodes)->get();
    }
}