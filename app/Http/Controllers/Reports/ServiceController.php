<?php

namespace App\Http\Controllers\Reports;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Models\SampleCollection;
use App\Models\BabyDetail;
use App\Models\Delivery;
use App\Models\LabTest;
use App\Models\Pnc;
use App\Models\VaccinationRecord;
use App\Models\SuspectedCase;
use App\Reports\DateFromToRequest;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yagiten\Nepalicalendar\Calendar;

class ServiceController extends Controller
{
    public function woman(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $date = $this->dataFromAndTo($request);

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $register = SuspectedCase::whereIn('hp_code', $hpCodes)
                            ->fromToDate($date['from_date'], $date['to_date'])
                            ->active()
                            ->pluck('token')->toArray();

        $ancs = SampleCollection::whereIn('hp_code', $hpCodes)
            ->fromToDate($date['from_date'], $date['to_date'])
            ->active()
            ->pluck('woman_token')->toArray();

        $pncs = Pnc::whereIn('hp_code', $hpCodes)
            ->fromToDate($date['from_date'], $date['to_date'])
            ->active()
            ->pluck('woman_token')->toArray();

        $delivery = Delivery::whereIn('hp_code', $hpCodes)
            ->fromToDate($date['from_date'], $date['to_date'])
            ->active()
            ->pluck('woman_token')->toArray();

        $labtest = LabTest::whereIn('hp_code', $hpCodes)
            ->fromToDate($date['from_date'], $date['to_date'])
            ->active()
            ->pluck('woman_token')->toArray();

        $medication = Woman\Vaccination::whereIn('hp_code', $hpCodes)
            ->fromToDate($date['from_date'], $date['to_date'])
            ->active()
            ->pluck('woman_token')->toArray();

        $woman_tokens = array_unique(array_merge($register, $ancs, $pncs, $delivery, $medication, $labtest));

        $woman = SuspectedCase::withAll()->whereIn('token', $woman_tokens)->active()->get();

        return view('reports.woman-service', compact('woman', 'register','ancs','medication', 'delivery', 'pncs','provinces', 'ward_or_healthpost','districts','municipalities','wards','healthposts','options','province_id','district_id','municipality_id','ward_id','hp_code', 'select_year', 'select_month'));
    }


    public function baby(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $date = $this->dataFromAndTo($request);

        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $register = BabyDetail::whereIn('hp_code', $hpCodes)
            ->fromToDate($date['from_date'], $date['to_date'])
            ->active()
            ->pluck('token')->toArray();

        $vaccination = VaccinationRecord::whereIn('hp_code', $hpCodes)
            ->vaccinatedFromToDate($date['from_date'], $date['to_date'])
            ->hasVialImage()
            ->active()
            ->pluck('baby_token')->toArray();

        $tokens = array_unique(array_merge($register, $vaccination));
        $babies = BabyDetail::withAll()->whereIn('token', $tokens)->active()->get();
        return view('reports.baby-service', compact('babies','vaccination', 'register','provinces', 'ward_or_healthpost','districts','municipalities','wards','healthposts','options','province_id','district_id','municipality_id','ward_id','hp_code', 'select_year', 'select_month'));
    }
    private function dataFromAndTo(Request $request)
    {
        return DateFromToRequest::dateFromTo($request);
    }
}
