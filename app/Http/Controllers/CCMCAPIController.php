<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CCMCAPIController extends Controller
{
    public function getRecoveredCases()
    {
        if (request()->query->get('ccmcApiKey') != env('CCMCAPIKEY', 'jEmUQMWEHI'))
            abort('401', 'Incorrect api key');
        $startDate = \request('startDate') ?? Carbon::now()->toDateString();
        $endDate = \request('endDate') ?? Carbon::tomorrow()->toDateString();

        $data = DB::table('payment_cases')->selectRaw('count(payment_cases.id) as total_cases')
            ->selectRaw('d.district_name')
            ->selectRaw('date(payment_cases.updated_at) as updated_at')
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
            ->leftJoin('organizations as h', 'payment_cases.org_code', 'h.org_code')
            ->leftJoin('districts as d', 'd.id', 'h.district_id')
            ->whereNotNull('d.district_name')
            ->where('payment_cases.updated_at', '>=', $startDate)
            ->where('payment_cases.updated_at', '<', $endDate)
            ->groupBy(DB::raw('d.district_name,is_death,gender,date(payment_cases.updated_at)'))
            ->get();

        return response(['data' => $data]);
    }

    public function testCases()
    {
        if (request()->query->get('ccmcApiKey') != env('CCMCAPIKEY', 'jEmUQMWEHI'))
            abort('401', 'Incorrect api key');
        $startDate = \request('startDate') ?? Carbon::now()->toDateString();
        $endDate = \request('endDate') ?? Carbon::tomorrow()->toDateString();

        $data = DB::table('sample_collection')
            ->selectRaw('count(sample_collection.id) as total')
            ->selectRaw('district_name')
            ->selectRaw('sample_test_date_en')
            ->selectRaw('case
           when sample_collection.result = 3 then "Positive"
           when sample_collection.result = 4 then "Negative"
       END as test_result')
            ->selectRaw('case when sample_collection.service_for = 1 then "PCR"
            when sample_collection.service_for = 2 then "Antigen"
       END  as test_type')
            ->leftJoin('organizations as h', 'sample_collection.org_code', 'h.org_code')
            ->leftJoin('districts as d', 'd.id', 'h.district_id')
            ->whereRaw('(result = 3 or result = 4) and (service_for = 1 or service_for = 2)')
            ->groupBy(DB::raw('district_name,sample_collection.result,sample_collection.service_for,sample_test_date_en'))
            ->where('sample_test_date_en', '>=', $startDate)
            ->where('sample_test_date_en', '<', $endDate)
            ->simplePaginate(1000);
        return response(['data' => $data]);
    }

    public function hospitalData()
    {
        if (request()->query->get('ccmcApiKey') != env('CCMCAPIKEY', 'jEmUQMWEHI'))
            abort('401', 'Incorrect api key');

        $data = DB::table('organizations')
            ->selectRaw('
          organizations.name,
          organizations.org_code,
          organizations.no_of_beds as no_of_beds,
          ifnull(hp_bed_status.occupied_beds,0) as bed_occupied,
          organizations.no_of_hdu as no_of_hdu,
          ifnull(hp_bed_status.hdu_occuplied,0) as hdu_occupied,
          organizations.no_of_icu as no_of_icu,
          ifnull(hp_bed_status.icu_occuplied,0) as icu_occupied,
          organizations.no_of_ventilators as no_of_ventilators,
          organizations.hospital_type as hospital_type,
          organizations.is_oxygen_facility as is_oxygen_facility,
          organizations.phone as phone,
          ifnull(hp_bed_status.venilator_occupied,0) as ventilator_occupied,
          provinces.province_name,
          districts.district_name,
          municipalities.municipality_name,
          organizations.ward_no
          ')
            ->leftJoin(
                DB::raw("(select
                  count(IF(health_condition = 1 or health_condition = 2, 1, null)) as occupied_beds,
                  count(IF(health_condition = 3, 1, null)) as hdu_occuplied,
                  count(IF(health_condition = 4, 1, null)) as icu_occuplied,
                  count(IF(health_condition = 5, 1, null)) as venilator_occupied,
                  org_code from payment_cases where payment_cases.is_death is null group by org_code) AS hp_bed_status"), 'hp_bed_status.org_code', 'organizations.org_code'
            )
            ->leftJoin('provinces', 'provinces.id', 'organizations.province_id')
            ->leftJoin('districts', 'districts.id', 'organizations.district_id')
            ->leftJoin('municipalities', 'municipalities.id', 'organizations.municipality_id')
            ->simplePaginate(1000);
        return response(['data' => $data]);
    }

    public function admittedToday()
    {
        if (request()->query->get('ccmcApiKey') != env('CCMCAPIKEY', 'jEmUQMWEHI'))
            abort('401', 'Incorrect api key');

        $startDate = \request('startDate') ?? Carbon::now()->toDateString();
        $endDate = \request('endDate') ?? Carbon::tomorrow()->toDateString();

        $data = DB::table('payment_cases')->selectRaw('count(payment_cases.id) as total_admitted')
            ->selectRaw('d.district_name')
            ->selectRaw("gender")
            ->selectRaw('date(payment_cases.created_at) as created_at')
            ->leftJoin('organizations as h', 'payment_cases.org_code', 'h.org_code')
            ->leftJoin('districts as d', 'd.id', 'h.district_id')
            ->whereNotNull('d.district_name')
            ->where('payment_cases.created_at', '>=', $startDate)
            ->where('payment_cases.created_at', '<', $endDate)
            ->groupBy(DB::raw('d.district_name,gender,date(payment_cases.created_at)'))
            ->get();

        return response(['data' => $data]);
    }
}
