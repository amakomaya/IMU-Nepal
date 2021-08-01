<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class CCMCAPIController extends Controller
{
    public function getRecoveredCases()
    {
        if (request()->query->get('ccmcApiKey') != env('CCMCAPIKEY', 'eJj7DU8ctQy6qeap'))
            abort('401', 'Incorrect api key');

        $startDate = \request('startDate') ?? date('Y-m-d');
        $endDate = \request('endDate') ?? date('Y-m-d');

        $data = DB::table('payment_cases')->selectRaw('count(payment_cases.id) as total_cases')
            ->selectRaw('d.district_name')
            ->selectRaw('age')
            ->selectRaw('date(payment_cases.created_at) as created_at')
            ->selectRaw('CASE
         WHEN is_death = 1 THEN "recovered"
         WHEN is_death = 2 THEN "Death"
         ELSE "active"
        END as case_type')
            ->selectRaw('CASE
         WHEN gender = 1 THEN "male"
         WHEN gender = 2 THEN "female"
         ELSE "other"
        END as gender')
            ->leftJoin('healthposts as h', 'payment_cases.hp_code', 'h.hp_code')
            ->leftJoin('districts as d', 'd.id', 'h.district_id')
            ->where(function ($q) {
                $q->orWhere("is_death", 1)
                    ->orWhere("is_death", 2)
                    ->orWhereNull('is_death');
            })
            ->whereNotNull('d.district_name')
            ->where('payment_cases.created_at', '>=', $startDate)
            ->where('payment_cases.created_at', '<=', $endDate)
            ->groupBy(DB::raw('d.district_name ,age,is_death,gender,date(payment_cases.created_at)'))
            ->simplePaginate(1000);
        return response(['data' => $data]);
    }

    public function vaccinationData()
    {
        if (request()->query->get('ccmcApiKey') != env('CCMCAPIKEY', 'eJj7DU8ctQy6qeap'))
            abort('401', 'Incorrect api key');

        $startDate = \request('startDate') ?? date('Y-m-d');
        $endDate = \request('endDate') ?? date('Y-m-d');

        $data = DB::table('ancs')
            ->selectRaw('count(ancs.id) as total')
            ->selectRaw('district_name')
            ->selectRaw('sample_test_date_en')
            ->selectRaw('case
           when ancs.result = 3 then "Positive"
           when ancs.result = 4 then "Negative"
       END as test_result')
            ->selectRaw('case when ancs.service_for = 1 then "PCR"
            when ancs.service_for = 2 then "Antigen"
       END  as test_type')
            ->leftJoin('healthposts as h', 'ancs.hp_code', 'h.hp_code')
            ->leftJoin('districts as d', 'd.id', 'h.district_id')
            ->whereRaw('(result = 3 or result = 4) and (service_for = 1 or service_for = 2)')
            ->groupBy(DB::raw('district_name,ancs.result,ancs.service_for,sample_test_date_en'))
            ->where('sample_test_date_en', '>=', $startDate)
            ->where('sample_test_date_en', '<=', $endDate)
            ->simplePaginate(1000);
        return response(['data' => $data]);
    }

    public function hospitalData()
    {
        if (request()->query->get('ccmcApiKey') != env('CCMCAPIKEY', 'eJj7DU8ctQy6qeap'))
            abort('401', 'Incorrect api key');
        $startDate = \request('startDate') ?? date('Y-m-d');
        $endDate = \request('endDate') ?? date('Y-m-d');

        $data = DB::table('healthposts')
            ->selectRaw('
          healthposts.name,
          healthposts.hp_code,
          healthposts.no_of_beds as no_of_beds,
          ifnull(hp_bed_status.occupied_beds,0) as bed_occupied,
          healthposts.no_of_hdu as no_of_hdu,
          ifnull(hp_bed_status.hdu_occuplied,0) as hdu_occupied,
          healthposts.no_of_icu as no_of_icu,
          ifnull(hp_bed_status.icu_occuplied,0) as icu_occupied,
          healthposts.no_of_ventilators as no_of_ventilators,
          healthposts.hospital_type as hospital_type,
          healthposts.is_oxygen_facility as is_oxygen_facility,
          healthposts.phone as phone,
          ifnull(hp_bed_status.venilator_occupied,0) as ventilator_occupied,
          provinces.province_name,
          districts.district_name,
          municipalities.municipality_name,
          healthposts.ward_no
          '
            )
            ->leftJoin(
                DB::raw("( select
                  count(IF(health_condition = 1 or health_condition = 2, 1, null)) as occupied_beds,
                  count(IF(health_condition = 3, 1, null)) as hdu_occuplied,
                  count(IF(health_condition = 4, 1, null)) as icu_occuplied,
                  count(IF(health_condition = 5, 1, null)) as venilator_occupied,
                  hp_code from payment_cases where payment_cases.created_at >=$startDate AND payment_cases.created_at <= $endDate group by hp_code) AS hp_bed_status"), 'hp_bed_status.hp_code', 'healthposts.hp_code'
            )
            ->leftJoin('provinces', 'provinces.id', 'healthposts.province_id')
            ->leftJoin('districts', 'districts.id', 'healthposts.district_id')
            ->leftJoin('municipalities', 'municipalities.id', 'healthposts.municipality_id')
            ->simplePaginate(1000);
        return response(['data' => $data]);
    }
}