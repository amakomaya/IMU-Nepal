<?php

namespace App\Http\Controllers;

use App\Helpers\GetHealthpostCodes;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\PaymentCase;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\CasesPaymentImport;

class CasesPaymentController extends Controller
{

    public function report(Request $request){

        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $organization = Organization::whereIn('hp_code', $hpCodes)->get();

        if ($request->has('selected_date')){
            $check_date = Carbon::parse($request->selected_date);
        }
        else{
            $check_date = Carbon::today();
        }
        if ($request->has('selected_date')){
            $period = Carbon::parse($request->selected_date)->format('Ymd');
            $total = PaymentCase::whereIn('hp_code', $hpCodes)
                ->where(function($q) use ($request) {
                    $q->whereNull('date_of_outcome_en')
                        ->orWhereDate('date_of_outcome_en','>=' ,Carbon::parse($request->selected_date))
                        ->whereDate('register_date_en', '<=' ,Carbon::parse($request->selected_date));
                })
                ->get();
        }else{
            $period = date('Ymd');
            $total = PaymentCase::whereIn('hp_code', $hpCodes)
                ->where(function($q){
                    $q->whereNull('date_of_outcome_en')
                        ->orWhereDate('date_of_outcome_en',Carbon::today());
                })
                ->get();
        }

        $total_beds_allocated_general = $organization->sum('no_of_beds');
        $total_daily_consumption_of_oxygen = $organization->whereIn('is_oxygen_facility', [1,2])->sum('daily_consumption_of_oxygen');
        $total_beds_allocated_icu = $organization->sum('no_of_icu');
        $total_beds_allocated_hdu = $organization->sum('no_of_hdu');
        $total_beds_allocated_ventilators_among_icu = $organization->sum('no_of_ventilators');

        $total_patients_without_symptoms = 0;
        $total_patients_with_mild_symptoms = 0;
        $total_patients_with_moderate_symptoms = 0;
        $total_patients_with_severe_symptoms_in_icu = 0;
        $total_patients_with_severe_symptoms_in_ventilator = 0;

        $free_patients_without_symptoms = 0;
        $free_patients_with_mild_symptoms = 0;
        $free_patients_with_moderate_symptoms = 0;
        $free_patients_with_severe_symptoms_in_icu = 0;
        $free_patients_with_severe_symptoms_in_ventilator = 0;

        $total_admissions = 0;
        $total_discharge = 0;
        $total_deaths = 0;

        $free_admissions = 0;
        $free_discharge = 0;
        $free_deaths = 0;

        foreach ($total as $item) {
            if ($item->health_condition_update != null){
                $health_condition_update_array = json_decode($item->health_condition_update, true);
                foreach ($health_condition_update_array as $arr){
                    if (Carbon::parse($arr['date'])->between(Carbon::parse($item->register_date_en), $check_date)){
                        $item->health_condition = $arr['id'];
                    }
                }
            }
            if (
                $item->date_of_outcome_en == null &&
                Carbon::parse($item->register_date_en)->lessThanOrEqualTo($check_date)
            ) {
                switch ($item->health_condition) {
                    case 1:
                        $total_patients_without_symptoms++;
                        if ($item->self_free == 2) {
                            $free_patients_without_symptoms++;
                        }
                        break;
                    case 2:
                        $total_patients_with_mild_symptoms++;
                        if ($item->self_free == 2) {
                            $free_patients_with_mild_symptoms++;
                        }
                        break;
                    case 3:
                        $total_patients_with_moderate_symptoms++;
                        if ($item->self_free == 2) {
                            $free_patients_with_moderate_symptoms++;
                        }
                        break;
                    case 4:
                        $total_patients_with_severe_symptoms_in_icu++;
                        if ($item->self_free == 2) {
                            $free_patients_with_severe_symptoms_in_icu++;
                        }
                        break;
                    case 5:
                        $total_patients_with_severe_symptoms_in_ventilator++;
                        if ($item->self_free == 2) {
                            $free_patients_with_severe_symptoms_in_ventilator++;
                        }
                        break;
                }
            }

            if (Carbon::parse($item->register_date_en)->equalTo(Carbon::parse($item->date_of_outcome_en))){
                switch ($item->is_death){
                    case 1:
                        $total_discharge++;
                        if ($item->self_free == 2){
                            $free_discharge++;
                        }
                        break;
                    case 2:
                        $total_deaths++;
                        if ($item->self_free == 2){
                            $free_deaths++;
                        }
                        break;
                }
            }

            if (Carbon::parse($item->register_date_en)->equalTo($check_date)){
                $total_admissions++;
                if ($item->self_free == 2){
                    $free_admissions++;
                }
            }elseif (
                Carbon::parse($item->date_of_outcome_en)->equalTo($check_date)
            ){
                switch ($item->is_death){
                    case 1:
                        $total_discharge++;
                        if ($item->self_free == 2){
                            $free_discharge++;
                        }
                        break;
                    case 2:
                        $total_deaths++;
                        if ($item->self_free == 2){
                            $free_deaths++;
                        }
                        break;
                    default:
                        $total_admissions++;
                        if ($item->self_free == 2){
                            $free_admissions++;
                        }
                        break;
                }

            }

            if(
                Carbon::parse($item->register_date_en)->lessThanOrEqualTo($check_date)
                &&
                Carbon::parse($item->date_of_outcome_en)->greaterThan($check_date)
                &&
                $item->date_of_outcome_en !== null
            ){
                switch ($item->health_condition) {
                    case 1:
                        $total_patients_without_symptoms++;
                        if ($item->self_free == 2) {
                            $free_patients_without_symptoms++;
                        }
                        break;
                    case 2:
                        $total_patients_with_mild_symptoms++;
                        if ($item->self_free == 2) {
                            $free_patients_with_mild_symptoms++;
                        }
                        break;
                    case 3:
                        $total_patients_with_moderate_symptoms++;
                        if ($item->self_free == 2) {
                            $free_patients_with_moderate_symptoms++;
                        }
                        break;
                    case 4:
                        $total_patients_with_severe_symptoms_in_icu++;
                        if ($item->self_free == 2) {
                            $free_patients_with_severe_symptoms_in_icu++;
                        }
                        break;
                    case 5:
                        $total_patients_with_severe_symptoms_in_ventilator++;
                        if ($item->self_free == 2) {
                            $free_patients_with_severe_symptoms_in_ventilator++;
                        }
                        break;
                }

            }

        }

        if (auth()->user()->role === 'healthpost' || auth()->user()->role === 'healthworker'){
            $organization_is_oxygen_facility = Organization::where('hp_code', $hpCodes)->first()->is_oxygen_facility;
        }else{
            $organization_is_oxygen_facility = '';
        }
        $data = [
            'is_oxygen_facility' => $organization_is_oxygen_facility,
            'total_daily_consumption_of_oxygen' => $total_daily_consumption_of_oxygen,
            'total_beds_allocated_general' => $total_beds_allocated_general,
            'total_beds_allocated_icu' => $total_beds_allocated_icu,
            'total_beds_allocated_hdu' => $total_beds_allocated_hdu,
            'total_beds_allocated_ventilators_among_icu' => $total_beds_allocated_ventilators_among_icu,

            'total_patients_without_symptoms' => $total_patients_without_symptoms,
            'total_patients_with_mild_symptoms' => $total_patients_with_mild_symptoms,
            'total_patients_with_moderate_symptoms' => $total_patients_with_moderate_symptoms,
            'total_patients_with_severe_symptoms_in_icu' => $total_patients_with_severe_symptoms_in_icu,
            'total_patients_with_severe_symptoms_in_ventilator' =>$total_patients_with_severe_symptoms_in_ventilator,

            'free_patients_without_symptoms' => $free_patients_without_symptoms,
            'free_patients_with_mild_symptoms' => $free_patients_with_mild_symptoms,
            'free_patients_with_moderate_symptoms' => $free_patients_with_moderate_symptoms,
            'free_patients_with_severe_symptoms_in_icu' => $free_patients_with_severe_symptoms_in_icu,
            'free_patients_with_severe_symptoms_in_ventilator' => $free_patients_with_severe_symptoms_in_ventilator,

            'total_admissions' => $total_admissions,
            'total_discharge' => $total_discharge,
            'total_deaths' => $total_deaths,
            'free_admissions' => $free_admissions,
            'free_discharge' => $free_discharge,
            'free_deaths' => $free_deaths,

        ];

        return view('backend.cases.payment.report', compact('data', 'period'));
    }

    public function sendToDhis(Request $request){
        if (auth()->user()->role == 'healthworker'){
            $organization_hp_code = OrganizationMember::where('token', auth()->user()->token)->first()->hp_code;
            $organization = Organization::where('hp_code', $organization_hp_code)->first();
        }else{
            $organization = Organization::where('token', \auth()->user()->token)->first();
        }

        $req = $request->all();

        $organization->update($req);

        $orgUnit = $organization->hmis_uid;

        $json = json_encode([
            'dataSet' => 'EZA8TZsaRMA',
            'completeDate' => Carbon::now()->format(('Y-m-d')),
            'period' => $req['period'],
            'orgUnit' => $orgUnit,
            'dataValues' => $this->dataValues($req)
        ]);

        $username = 'imu.user';
        $password = 'Hmis@1234';
        $url = env('HMIS_BASE_URL', 'http://hmis.gov.np/hmisadditional').'/api/dataValueSets';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json))
        );

        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        $result = curl_exec($ch);

        if (!curl_errno($ch)) {
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        if (!empty($status_code)) {
            if ($status_code == 401) {
                $request->session()->flash('error', "Authentication Failed");
                return redirect()->back();
            }
        }

        curl_close($ch);
        $data = json_decode($result, true);

        if(isset($data['importConflicts'])){
            return "Conflict"."<br/>";
        }elseif(isset($data['conflicts'])){
            $request->session()->flash('error', "Organisation unit not found or not accessible");
            return redirect()->back();
        }elseif(empty($data)){
            $request->session()->flash('error', "तपाईको अनुरोध पुरा भएन, कृपया फेरि प्रयास गर्नुहोस");
            return redirect()->back();
        }else {
            $request->session()->flash('success', "तपाईको डाटा HMIS ( DHIS2 ) Server मा सुरक्षित भएको छ।");
            return redirect()->back();
        }
    }

    public function allByOrganization(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = \DB::table('payment_cases')
            ->whereIn('payment_cases.hp_code', $hpCodes)
            ->join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->join('municipalities', 'healthposts.municipality_id', '=', 'municipalities.id')
            ->select(['healthposts.name as organiation_name',
                'healthposts.no_of_beds',
                'healthposts.no_of_ventilators',
                'healthposts.no_of_icu',
                'healthposts.no_of_hdu',
                'healthposts.daily_consumption_of_oxygen',
                'healthposts.is_oxygen_facility',
                'municipalities.municipality_name as municipality',
                'payment_cases.health_condition', 'payment_cases.health_condition_update',
                'payment_cases.self_free', 'payment_cases.is_death'
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get();

        $mapped_data = $data->map(function ($value) {
            $return = [];
            $return['organiation_name'] = $value->organiation_name.', '.$value->municipality;
            $return['no_of_beds'] = $value->no_of_beds;
            $return['no_of_ventilators'] = $value->no_of_ventilators;
            $return['no_of_icu'] = $value->no_of_icu;
            $return['no_of_hdu'] = $value->no_of_hdu;
            $return['daily_consumption_of_oxygen'] = $value->daily_consumption_of_oxygen;
            $return['is_oxygen_facility'] = $value->is_oxygen_facility;
            $return['self_free'] = $value->self_free;
            $return['is_death'] = $value->is_death;

            if ($value->health_condition_update == null){
                $return['health_condition'] = $value->health_condition;
            }else{
                $array_health_condition = json_decode($value->health_condition_update, true);
                $return['health_condition'] = collect($array_health_condition)->last()['id'];
            }
            return $return;
        })->groupBy(function($item) {
                return $item['organiation_name'];
            });

        $mapped_data_second = $mapped_data->map(function ($value){
            $return = [];
            $value = collect($value);
            $return['total_no_of_beds'] = collect($value->first())['no_of_beds'];
            $return['total_no_of_ventilators'] = collect($value->first())['no_of_ventilators'];
            $return['total_no_of_icu'] = collect($value->first())['no_of_icu'];
            $return['total_no_of_hdu'] = collect($value->first())['no_of_hdu'];
            $return['daily_consumption_of_oxygen'] = collect($value->first())['daily_consumption_of_oxygen'];
            $return['is_oxygen_facility'] = collect($value->first())['is_oxygen_facility'];

            $return['used_total_no_of_beds'] = collect($value)->where('is_death', null)->whereIn('health_condition', [1,2])->count();
            $return['used_total_no_of_hdu'] = collect($value)->where('is_death', null)->where('health_condition', 3)->count();
            $return['used_total_no_of_icu'] = collect($value)->where('is_death', null)->where('health_condition', 4)->count();
            $return['used_total_no_of_ventilators'] = collect($value)->where('is_death', null)->where('health_condition', 5)->count();

            $return['total_cases'] = $value->count();
            $value['total_under_treatment'] = $value->where('is_death', null)->count();
            return $return;
        });
        return view('backend.cases.payment.all-by-organization', ['data' => $mapped_data_second]);
    }

    public function byInstitutional(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = \DB::table('payment_cases')
            ->whereIn('payment_cases.hp_code', $hpCodes)
            ->where('healthposts.hospital_type', 5)
            ->join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->join('municipalities', 'healthposts.municipality_id', '=', 'municipalities.id')
            ->select(['healthposts.name as organiation_name',
                'healthposts.no_of_beds',
                'healthposts.no_of_ventilators',
                'healthposts.no_of_icu',
                'healthposts.no_of_hdu',
                'healthposts.daily_consumption_of_oxygen',
                'healthposts.is_oxygen_facility',
                'municipalities.municipality_name as municipality',
                'payment_cases.health_condition', 'payment_cases.health_condition_update',
                'payment_cases.self_free', 'payment_cases.is_death'
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get();

        $mapped_data = $data->map(function ($value) {
            $return = [];
            $return['organiation_name'] = $value->organiation_name.', '.$value->municipality;
            $return['no_of_beds'] = $value->no_of_beds;
            $return['no_of_ventilators'] = $value->no_of_ventilators;
            $return['no_of_icu'] = $value->no_of_icu;
            $return['no_of_hdu'] = $value->no_of_hdu;
            $return['daily_consumption_of_oxygen'] = $value->daily_consumption_of_oxygen;
            $return['is_oxygen_facility'] = $value->is_oxygen_facility;
            $return['self_free'] = $value->self_free;
            $return['is_death'] = $value->is_death;

            if ($value->health_condition_update == null){
                $return['health_condition'] = $value->health_condition;
            }else{
                $array_health_condition = json_decode($value->health_condition_update, true);
                $return['health_condition'] = collect($array_health_condition)->last()['id'];
            }
            return $return;
        })->groupBy(function($item) {
            return $item['organiation_name'];
        });

        $mapped_data_second = $mapped_data->map(function ($value){
            $return = [];
            $value = collect($value);
            $return['total_no_of_beds'] = collect($value->first())['no_of_beds'];
            $return['total_no_of_ventilators'] = collect($value->first())['no_of_ventilators'];
            $return['total_no_of_icu'] = collect($value->first())['no_of_icu'];
            $return['total_no_of_hdu'] = collect($value->first())['no_of_hdu'];
            $return['daily_consumption_of_oxygen'] = collect($value->first())['daily_consumption_of_oxygen'];
            $return['is_oxygen_facility'] = collect($value->first())['is_oxygen_facility'];

            $return['used_total_no_of_beds'] = collect($value)->where('is_death', null)->whereIn('health_condition', [1,2])->count();
            $return['used_total_no_of_hdu'] = collect($value)->where('is_death', null)->where('health_condition', 3)->count();
            $return['used_total_no_of_icu'] = collect($value)->where('is_death', null)->where('health_condition', 4)->count();
            $return['used_total_no_of_ventilators'] = collect($value)->where('is_death', null)->where('health_condition', 5)->count();

            $return['total_cases'] = $value->count();
            $value['total_under_treatment'] = $value->where('is_death', null)->count();
            return $return;
        });
        return view('backend.cases.payment.all-by-organization', ['data' => $mapped_data_second, 'heading' => 'Institutional Isolation']);
    }

    public function byLabAndTreatment(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $data = \DB::table('payment_cases')
            ->where('healthposts.hospital_type', 3)
            ->whereIn('payment_cases.hp_code', $hpCodes)
            ->join('healthposts', 'payment_cases.hp_code', '=', 'healthposts.hp_code')
            ->join('municipalities', 'healthposts.municipality_id', '=', 'municipalities.id')
            ->select(['healthposts.name as organiation_name',
                'healthposts.no_of_beds',
                'healthposts.no_of_ventilators',
                'healthposts.no_of_icu',
                'healthposts.no_of_hdu',
                'healthposts.daily_consumption_of_oxygen',
                'healthposts.is_oxygen_facility',
                'municipalities.municipality_name as municipality',
                'payment_cases.health_condition', 'payment_cases.health_condition_update',
                'payment_cases.self_free', 'payment_cases.is_death'
            ])
            ->orderBy('healthposts.name', 'asc')
            ->get();

        $mapped_data = $data->map(function ($value) {
            $return = [];
            $return['organiation_name'] = $value->organiation_name.', '.$value->municipality;
            $return['no_of_beds'] = $value->no_of_beds;
            $return['no_of_ventilators'] = $value->no_of_ventilators;
            $return['no_of_icu'] = $value->no_of_icu;
            $return['no_of_hdu'] = $value->no_of_hdu;
            $return['daily_consumption_of_oxygen'] = $value->daily_consumption_of_oxygen;
            $return['is_oxygen_facility'] = $value->is_oxygen_facility;
            $return['self_free'] = $value->self_free;
            $return['is_death'] = $value->is_death;

            if ($value->health_condition_update == null){
                $return['health_condition'] = $value->health_condition;
            }else{
                $array_health_condition = json_decode($value->health_condition_update, true);
                $return['health_condition'] = collect($array_health_condition)->last()['id'];
            }
            return $return;
        })->groupBy(function($item) {
            return $item['organiation_name'];
        });

        $mapped_data_second = $mapped_data->map(function ($value){
            $return = [];
            $value = collect($value);
            $return['total_no_of_beds'] = collect($value->first())['no_of_beds'];
            $return['total_no_of_ventilators'] = collect($value->first())['no_of_ventilators'];
            $return['total_no_of_icu'] = collect($value->first())['no_of_icu'];
            $return['total_no_of_hdu'] = collect($value->first())['no_of_hdu'];
            $return['daily_consumption_of_oxygen'] = collect($value->first())['daily_consumption_of_oxygen'];
            $return['is_oxygen_facility'] = collect($value->first())['is_oxygen_facility'];

            $return['used_total_no_of_beds'] = collect($value)->where('is_death', null)->whereIn('health_condition', [1,2])->count();
            $return['used_total_no_of_hdu'] = collect($value)->where('is_death', null)->where('health_condition', 3)->count();
            $return['used_total_no_of_icu'] = collect($value)->where('is_death', null)->where('health_condition', 4)->count();
            $return['used_total_no_of_ventilators'] = collect($value)->where('is_death', null)->where('health_condition', 5)->count();

            $return['total_cases'] = $value->count();
            $value['total_under_treatment'] = $value->where('is_death', null)->count();
            return $return;
        });
        return view('backend.cases.payment.all-by-organization', ['data' => $mapped_data_second, 'heading' => 'Lab And Treatment']);
    }


    private function dataValues(array $data)
    {
        return [
            ['dataElement' => 'xsKRc9OwBof', 'value' => $data['total_beds_allocated_general']],
            ['dataElement' => 'TmxN1Fi6AO8', 'value' => $data['total_beds_allocated_icu']],
            ['dataElement' => 'oHAzqKL4JRN', 'value' => $data['total_beds_allocated_ventilators_among_icu']],
            ['dataElement' => 'ukRwct4JOlH', 'value' => $data['total_beds_allocated_general'] + $data['total_beds_allocated_icu']],

            ['dataElement' => 'N2OMXZJ68XO', 'value' => $data['total_patients_without_symptoms'] +
                                                        $data['total_patients_with_mild_symptoms'] +
                                                        $data['total_patients_with_moderate_symptoms'] +
                                                        $data['total_patients_with_severe_symptoms_in_icu'] +
                                                        $data['total_patients_with_severe_symptoms_in_ventilator']],
            ['dataElement' => 'Uy86LpDFshY', 'value' => $data['total_patients_without_symptoms']],
            ['dataElement' => 'p9wLvWHjSmr', 'value' => $data['total_patients_with_mild_symptoms']],
            ['dataElement' => 'RpwIOThjK2h', 'value' => $data['total_patients_with_moderate_symptoms']],
            ['dataElement' => 'aVLYA6O4XF2', 'value' => $data['total_patients_with_severe_symptoms_in_icu']],
            ['dataElement' => 'Wt7cbwJG5Hm', 'value' => $data['total_patients_with_severe_symptoms_in_ventilator']],

            ['dataElement' => 'Zbj14a4QxlU', 'value' => $data['free_patients_without_symptoms'] +
                                                        $data['free_patients_with_mild_symptoms'] +
                                                        $data['free_patients_with_moderate_symptoms'] +
                                                        $data['free_patients_with_severe_symptoms_in_icu'] +
                                                        $data['free_patients_with_severe_symptoms_in_ventilator']],
            ['dataElement' => 'y7nnQwco1hi', 'value' => $data['free_patients_without_symptoms']],
            ['dataElement' => 'jheH7yk9jRb', 'value' => $data['free_patients_with_mild_symptoms']],
            ['dataElement' => 'OZOXfHR8nhF', 'value' => $data['free_patients_with_moderate_symptoms']],
            ['dataElement' => 'EEPlqfl2o01', 'value' => $data['free_patients_with_severe_symptoms_in_icu']],
            ['dataElement' => 'zhewkaBacjC', 'value' => $data['free_patients_with_severe_symptoms_in_ventilator']],

            ['dataElement' => 'OxJq8yRfB2I', 'value' => $data['total_admissions']],
            ['dataElement' => 'Kb0V8ZusyfB', 'value' => $data['total_discharge']],
            ['dataElement' => 'B9C4XJrCji2', 'value' => $data['total_deaths']],

            ['dataElement' => 'JkiE01qR3RE', 'value' => $data['free_admissions']],
            ['dataElement' => 'lJdBm4JQbxu', 'value' => $data['free_discharge']],
            ['dataElement' => 'qj1x5V3Zc7d', 'value' => $data['free_deaths']]
        ];
    }

    public function bulkUpload (Request $request) {
      if ($request->hasFile('bulk_file')) {
        $bulk_file = $request->file('bulk_file');
        try {
          Excel::queueImport(new CasesPaymentImport(auth()->user()), $bulk_file);
          return response()->json(['message' => 'success',
            'message' => 'Case Payment Data uploaded successfully',
          ]);

        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
          $errors = [];
          $failures = $e->failures();
          $error_msg = '';
          foreach ($failures as $key=>$failure) {
              $error_msg .= ($key+1).'. Row: '.$failure->row().', Column: '.$failure->attribute().', Error: '.join(",", $failure->errors()).'<br />';
              $errors[$key]['row'] = $failure->row(); // row that went wrong
              $errors[$key]['column'] = $failure->attribute(); // either heading key (if using heading row concern) or column index
              $errors[$key]['error'] = $failure->errors(); // Actual error messages from Laravel validator
              $errors[$key]['values'] = $failure->values(); // The values of the row that has failed.
          }
          
          return response()->json([
            'status' => 'fail',
            'message' => $errors
            ], 422
          );

        }
        return response()->json(['status' => 'success',
            'message' => "Uploading"
          ]);
      }
    }
}
