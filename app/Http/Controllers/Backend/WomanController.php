<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\WomenRequest;
use App\Models\SampleCollection;
use App\Models\LabTest;
use App\Models\Delivery;
use App\Models\District;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\VaccineVial;
use App\Models\SuspectedCase;
use App\Models\PaymentCase;
use App\Models\Country;
use App\Models\Vaccine;
use App\Models\CictContact;
use App\Reports\FilterRequest;
use App\User;
use Carbon\Carbon;
use Charts;
use GMaps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yagiten\Nepalicalendar\Calendar;
use DB;

class WomanController extends Controller
{

    protected function roleViewCheck(){
        $role = \auth()->user()->role;
        $valid_roles = ['healthpost', 'healthworker', 'municipality', 'dho', 'province'];
        if (in_array($role, $valid_roles))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index');
        };
        return redirect('/admin');
    }

    public function pendingIndex(){
        if($this->roleViewCheck()){
            return view('backend.woman.pending-index');
        };
        return redirect('/admin');
    }

    public function pendingIndexOld(){
        if($this->roleViewCheck()){
            return view('backend.woman.pending-index-old');
        };
        return redirect('/admin');
    }

    public function antigenPendingIndex(){
        if($this->roleViewCheck()){
            return view('backend.woman.antigen-pending-index');
        };
        return redirect('/admin');
    }

    public function antigenPendingIndexOld(){
        if($this->roleViewCheck()){
            return view('backend.woman.antigen-pending-index-old');
        };
        return redirect('/admin');
    }

    public function addSampleCollection(){
        return view('backend.woman.add-sample');
    }

    public function negativeAntigenIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-negative-antigen');
        };
        return redirect('/admin');
    }

    public function negativeAntigenIndexOld()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-negative-antigen-old');
        };
        return redirect('/admin');
    }

    public function negativeIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-negative');
        };
        return redirect('/admin');
    }

    public function negativeIndexOld()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-negative-old');
        };
        return redirect('/admin');
    }

    public function positiveAntigenIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-positive-antigen');
        };
        return redirect('/admin');
    }

    public function positiveAntigenIndexOld()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-positive-antigen-old');
        };
        return redirect('/admin');
    }

    public function positiveIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-positive');
        };
        return redirect('/admin');
    }

    public function positiveIndexOld()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-positive-old');
        };
        return redirect('/admin');
    }

    public function tracingIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-tracing');
        };
        return redirect('/admin');
    }

    public function labReceivedIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-lab-received');
        };
        return redirect('/admin');
    }

    public function labReceivedIndexOld()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-lab-received-old');
        };
        return redirect('/admin');
    }

    public function labReceivedAntigenIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-lab-received-antigen');
        };
        return redirect('/admin');
    }

    public function labReceivedAntigenIndexOld()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-lab-received-antigen-old');
        };
        return redirect('/admin');
    }

    public function casesRecoveredIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-cases-recovered');
        };
        return redirect('/admin');
    }

    public function casesPaymentIndex()
    {
        return view('backend.cases.payment.index');
    }

    public function casesPaymentDischargeIndex()
    {
        return view('backend.cases.payment.index-discharge');
    }

    public function casesPaymentDeathIndex()
    {
        return view('backend.cases.payment.index-death');
    }

    public function casesPaymentCreate(Request $request)
    {
        if(Auth::user()->role == 'healthpost'){
            $healthposts = Organization::where('token', Auth::user()->token)->first();
        // dd($healthposts);

            $total = $healthposts->no_of_beds + $healthposts->no_of_hdu + $healthposts->no_of_icu + $healthposts->no_of_ventilators;

            if($total > 0) {
                return view('backend.cases.payment.create');
            }
            $request->session()->flash('error', "You don't have any beds available. Please update the no. of beds from your profile.");
            return redirect('/admin/profile');
        }
        if(Auth::user()->role == 'healthworker'){
            $hp_code = OrganizationMember::where('token',Auth::user()->token)->first()->hp_code;
            $healthposts = Organization::where('hp_code', $hp_code)->first();

            $total = $healthposts->no_of_beds + $healthposts->no_of_hdu + $healthposts->no_of_icu + $healthposts->no_of_ventilators;

            if($total > 0) {
                return view('backend.cases.payment.create');
            }
            $request->session()->flash('error', "You don't have any beds available. Please update the no. of beds from your organizational profile.");
            return redirect('/admin/profile');
        }

        return redirect('/admin');
    }

    public function getRemainingBeds(Request $request)
    {
        if(Auth::user()->role == 'healthpost'){
            $healthposts = Organization::where('token', Auth::user()->token)->first();
        }
        if (Auth::user()->role == 'healthworker'){
            $hp_code = OrganizationMember::where('token',Auth::user()->token)->first()->hp_code;
            $healthposts = Organization::where('hp_code', $hp_code)->first();
        }
        $total = $healthposts->no_of_beds + $healthposts->no_of_hdu + $healthposts->no_of_icu + $healthposts->no_of_ventilators;

        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $data = PaymentCase::whereIn('hp_code', $hpCodes)->whereNull('is_death')->get();
        $hdu_count = $icu_count = $venti_count = $general_count = 0;
        foreach($data as $datum) {
            if($datum->health_condition_update == null) {
                if($datum->health_condition == 1 || $datum->health_condition == 2){
                  $general_count++;
                }
                elseif($datum->health_condition == 3) {
                    $hdu_count++;
                }elseif($datum->health_condition == 4) {
                    $icu_count++;
                } elseif($datum->health_condition == 5) {
                    $venti_count++;
                }
            }
            else {
                $condition_array = json_decode($datum->health_condition_update);
                $condition = end($condition_array);
                if($condition->id == 1 || $condition->id == 2){
                  $general_count++;
                }
                elseif($condition->id == 3) {
                    $hdu_count++;
                }elseif($condition->id == 4) {
                    $icu_count++;
                } elseif($condition->id == 5) {
                    $venti_count++;
                }
            }
        }
        $dropdown['general'] = $healthposts->no_of_beds - $general_count;
        $dropdown['hdu'] = $healthposts->no_of_hdu - $hdu_count;
        $dropdown['icu'] = $healthposts->no_of_icu - $icu_count;
        $dropdown['venti'] = $healthposts->no_of_ventilators - $venti_count;
        return response()->json([
            'remaining_beds' => $dropdown
        ]); 
    }

    public function getRemainingBedsWoRequest()
    {
        $user = Auth::user();
        if($user->role == 'healthpost'){
            $healthpost = Organization::where('token', $user->token)->first();
            $hp_code = $healthpost->hp_code;
        }
        if (Auth::user()->role == 'healthworker'){
            $hp_code = OrganizationMember::where('token',Auth::user()->token)->first()->hp_code;
            $healthposts = Organization::where('hp_code', $hp_code)->first();
        }
        $total = $healthposts->no_of_beds + $healthposts->no_of_hdu + $healthposts->no_of_icu + $healthposts->no_of_ventilators;

        $data = PaymentCase::where('hp_code', $hp_code)->whereNull('is_death')->get();
        $hdu_count = $icu_count = $venti_count = $general_count = 0;
        foreach($data as $datum) {
            if($datum->health_condition_update == null) {
                if($datum->health_condition == 1 || $datum->health_condition == 2){
                  $general_count++;
                }
                elseif($datum->health_condition == 3) {
                    $hdu_count++;
                }elseif($datum->health_condition == 4) {
                    $icu_count++;
                } elseif($datum->health_condition == 5) {
                    $venti_count++;
                }
            }
            else {
                $condition_array = json_decode($datum->health_condition_update);
                $condition = end($condition_array);
                if($condition->id == 1 || $condition->id == 2){
                  $general_count++;
                }
                elseif($condition->id == 3) {
                    $hdu_count++;
                }elseif($condition->id == 4) {
                    $icu_count++;
                } elseif($condition->id == 5) {
                    $venti_count++;
                }
            }
        }
        $dropdown['general'] = $healthposts->no_of_beds - $general_count;
        $dropdown['hdu'] = $healthposts->no_of_hdu - $hdu_count;
        $dropdown['icu'] = $healthposts->no_of_icu - $icu_count;
        $dropdown['venti'] = $healthposts->no_of_ventilators - $venti_count;
        return response()->json([
            'remaining_beds' => $dropdown
        ]); 
    }

    public function casesDeathIndex()
    {
        if($this->roleViewCheck()){
            return view('backend.woman.index-cases-death');
        };
        return redirect('/admin');
    }

    public function casesInOtherOrganization()
    {
        return view('backend.woman.index-cases-in-other-organization');
    }

    public function casesPatientDetail(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $date_from = $request->date_from ?: date('Y-m-d',strtotime("-14 days"));
        $date_to = $request->date_to ?: date('Y-m-d');
        $payment_cases = PaymentCase::whereIn('hp_code', $hpCodes)
            ->whereBetween(DB::raw('DATE(register_date_en)'), [$date_from, $date_to])
            ->latest()->with('organization')->paginate(1000);
        return view('backend.cases.payment.patient-detail', compact('payment_cases', 'date_from', 'date_to'));
    }

    public function create(Request $request)
    {
        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        if($request->case_id){
            $data = CictContact::where('case_id', $request->case_id)->first();
            return view('backend.patient.create-cict', compact('provinces', 'districts', 'municipalities', 'province_id', 'district_id', 'municipality_id', 'data'));
        }

        if(Auth::user()->can('poe-registration')){
            $vaccines = Vaccine::get();
            $countries = Country::get();
            return view('backend.patient.create-poe', compact('provinces', 'districts', 'municipalities', 'province_id', 'district_id', 'municipality_id', 'countries', 'vaccines'));
        } else {
            return view('backend.patient.create', compact('provinces', 'districts', 'municipalities', 'province_id', 'district_id', 'municipality_id'));
        }
    }

    public function store(Request $request)
    {
        $uniqueLabId = '';
        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];

        $request->validate([
            'name' => 'required',
            'age' => 'required',
            'ward' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'municipality_id' => 'required',
            'tole' => 'required',
            'emergency_contact_one' => 'required',
            'occupation' => 'required',
            // 'service_for' => 'required',
            // 'infection_type' => 'required',
            // 'service_type' => 'required'
        ], $customMessages);
        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $row = $request->all();
        if(Auth::user()->can('poe-registration')){
            $row['case_type'] = '3';
        } else{
            $row['case_type'] = '1';
        }
        if($request->case_token){
            $row['token'] = $request->case_token;
        }else {
            $row['token'] = md5(microtime(true) . mt_Rand());
        }
        $row['status'] = 1;
        $row['created_by'] = auth()->user()->token;
        $row['symptoms_comorbidity'] = [];
        if($request->symptoms_comorbidity_trimester) {
            array_push($row['symptoms_comorbidity'], $request->symptoms_comorbidity_trimester);
        }
        $row['symptoms'] = isset($row['symptoms']) ? "[" . implode(', ', $row['symptoms']) . "]" : "[]";
        $row['symptoms_comorbidity'] = isset($row['symptoms_comorbidity']) ? "[" . implode(', ', $row['symptoms_comorbidity']) . "]" : "[]";
        $row['travelled_where'] = "[" . $request->travelled_where ."]";
        $row['hp_code'] = OrganizationMember::where('token', auth()->user()->token)->first()->hp_code;
        $row['cases'] = '0';
        $row['case_where'] = '0';
        $row['end_case'] = '0';
        $row['payment'] = '0';
        if($request->case_id){
            $row['case_id'] = $request->case_id;
        }else {
            $row['case_id'] = OrganizationMember::where('token', auth()->user()->token)->first()->id . '-' . Carbon::now()->format('ymd') . '-' . strtoupper(bin2hex(random_bytes(3)));
        }
        $row['registered_device'] = 'web';
        $row['register_date_en'] = Carbon::now()->format('Y-m-d');

        $nep_date_array = explode("-", Carbon::now()->format('Y-m-d'));
        $row['register_date_np'] = Calendar::eng_to_nep($nep_date_array[0], $nep_date_array[1], $nep_date_array[2])->getYearMonthDay();

        $row['reson_for_testing'] = isset($row['reson_for_testing']) ? "[" . implode(', ', $row['reson_for_testing']) . "]" : "[]";
        unset($row['symptoms_comorbidity_trimester']);

        if($request->case_type == '3') {
            if($request->temperature_type == 2){
                $row['temperature'] = ($request->temperature * 9/5) + 32;
            }
            $row['register_date_np'] = $request->register_date_np;
            $register_np_array = explode("-", $request->register_date_np);
            $row['register_date_en'] = Calendar::nep_to_eng($register_np_array[0], $register_np_array[1], $register_np_array[2])->getYearMonthDayNepToEng();
            
            $row['travelled_where'] = "[" . $request->travelled_where . ", " . $request->travelled_city ."]";

            $contact_relationship = $request->contact_relationship ?? '5';
            $row['nearest_contact'] = "[-, " . $contact_relationship . ", " . $request->emergency_contact_two . "]";

            $malaria_test_status = $request->malaria_test_status ?? '0';
            $malaria_result = $request->malaria_result ?? '0';
            $row['malaria'] = "[" . $malaria_test_status . ", " . $malaria_result . "]";

            $symptoms_recent = $request->symptoms_recent ?? '0';
            $antigen_test_status = $request->antigen_test_status ?? '0';
            $antigen_result = $request->antigen_result ?? '0';
    
            $row['case_reason'] = "[" . $antigen_test_status . ", " . $antigen_result .", " . $request->antigen_isolation . "]";
    
            $vaccine_status = $request->vaccine_status ?? '0';
            $vaccination_card = $request->vaccination_card ?? '0';
            if($request->vaccine_name == 6){
                if($request->dose_one_date){
                    $vaccination_dosage_complete = '1';
                }else{
                    $vaccination_dosage_complete = '0';
                }
            }
            else{
                if($request->dose_one_date && $request->dose_two_date){
                    $vaccination_dosage_complete = '1';
                }else{
                    $vaccination_dosage_complete = '0';
                }
            }
            $vaccine_dosage_count = $request->vaccine_dosage_count ?? '0';
            $vaccine_name = $request->vaccine_name ?? '10';
    
            $row['covid_vaccination_details'] = "[" . $vaccine_status . ", " . $vaccination_card . ", " . $vaccination_dosage_complete . ", " . $vaccine_dosage_count . ", " . $vaccine_name . ", " . $request->vaccine_name_other . "]";
            $row['dose_details'] = '[{"type":"1","date":"' . $request->dose_one_date . '"},{"type":"2","date":"' . $request->dose_two_date . '"}]';
        }

        SuspectedCase::create($row);

        if($request->case_type != '3') {
            if($request->swab_collection_conformation == 1) {
                $sample_row['token'] = $request->token;
                $sample_row['woman_token'] = $row['token'];
                $sample_row['checked_by'] = auth()->user()->token;
                $sample_row['status'] = 1;
                $sample_row['result'] = 2;
                $sample_row['regdev'] = 'web';
                $sample_row['service_type'] = $request->service_type;
                $sample_row['service_for'] = $request->service_for;
                $sample_row['infection_type'] = $request->infection_type;
                $sample_row['sample_type_specific'] = $request->sample_type_specific ?? '';
                $sample_row['sample_identification_type'] = 'unique_id';
                $sample_row['collection_date_en'] = Carbon::now()->format('Y-m-d');
                $nep_date_array = explode("-", Carbon::now()->format('Y-m-d'));
                $sample_row['collection_date_np'] = Calendar::eng_to_nep($nep_date_array[0], $nep_date_array[1], $nep_date_array[2])->getYearMonthDay();

                switch (auth()->user()->role) {
                    case 'healthpost':
                        $healthpost = Organization::where('token', auth()->user()->token)->first();
                        $sample_row['hp_code'] = $healthpost->hp_code;
                        $sample_row['checked_by_name'] = $healthpost->name;
                        $sample_row['checked_by'] = $healthpost->token;
                        // $sample_row['received_by_hp_code'] = $healthpost->hp_code;
        
                    case 'healthworker':
                        $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
                        $sample_row['hp_code'] = $healthworker->hp_code;
                        $sample_row['checked_by_name'] = $healthworker->name;
                        $sample_row['checked_by'] = $healthworker->token;
                        // $sample_row['received_by_hp_code'] = $healthworker->hp_code;
        
                }
                if ($request->service_for === '1')
                    $sample_row['sample_type'] = "[" . implode(', ', $request->sample_type) . "]";

                if($request->service_for == '2') {
                    $uniqueLabId = generate_unique_lab_id_web(auth()->user()->token . '-' . Carbon::now()->format('ymd') . '-' . $request->lab_token);
                    $sample_row['sample_type'] = "[]";
                    $sample_row['result'] = 9;
                    $sample_row['lab_token'] = $uniqueLabId;
                    $sample_row['received_date_en'] = $sample_row['collection_date_en'];
                    $sample_row['received_date_np'] = $sample_row['collection_date_np'];
                    $sample_row['received_by'] = $sample_row['checked_by'];
                    $sample_row['received_by_hp_code'] = $sample_row['hp_code'];

                    LabTest::create([
                        'token' => $sample_row['lab_token'],
                        'hp_code' => $sample_row['hp_code'],
                        'sample_test_result' => '9',
                        'status' => 1,
                        'checked_by' => $sample_row['checked_by'],
                        'checked_by_name' => $sample_row['checked_by_name'],
                        'sample_token' => $sample_row['token'],
                        'regdev' => 'web'
                    ]);
                }
                SampleCollection::create($sample_row);
            }
        }
        if($request->service_for == '2') {
            $responseLabId = explode('-', $uniqueLabId);
            array_shift($responseLabId);
            $request->session()->flash('message', 'Data Inserted successfully. Created Lab ID is " ' . join("-",$responseLabId) . ' "');
        } else {
            $request->session()->flash('message', 'Data Inserted successfully');
        }

        if($request->case_type == '3') {
            if ($request->swab_collection_conformation == '1') {
                return $this->sampleCollectionCreate($row['token']);
            }
        }
        return redirect()->back();
    }

     public function sampleCollectionCreate($token)
     {
         $id = OrganizationMember::where('token', auth()->user()->token)->first()->id;
         $swab_id = str_pad($id, 4, '0', STR_PAD_LEFT) . '-' . Carbon::now()->format('ymd') . '-' . $this->convertTimeToSecond(Carbon::now()->format('H:i:s'));
         $swab_id = generate_unique_sid($swab_id);
         return view('backend.patient.sample-create', compact('token', 'swab_id'));
     }

    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }

    public function sampleCollectionStore(Request $request)
    {
        $uniqueLabId = '';
        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];

        $request->validate([
            'service_for' => 'required',
            'infection_type' => 'required',
            'service_type' => 'required'
        ], $customMessages);
        $row = $request->all();
        $row['created_by'] = auth()->user()->token;
        $row['status'] = 1;
        $row['result'] = 2;
        $row['sample_identification_type'] = 'unique_id';
        $row['collection_date_en'] = Carbon::now()->format('Y-m-d');
        $nep_date_array = explode("-", Carbon::now()->format('Y-m-d'));
        $row['collection_date_np'] = Calendar::eng_to_nep($nep_date_array[0], $nep_date_array[1], $nep_date_array[2])->getYearMonthDay();
        $row['sample_type'] = $request->sample_type ? "[" . implode(', ', $request->sample_type) . "]" : "[]";

        switch (auth()->user()->role) {
            case 'healthpost':
                $healthpost = Organization::where('token', auth()->user()->token)->first();
                $row['hp_code'] = $healthpost->hp_code;
                $row['created_by_name'] = $healthpost->name;
                $row['checked_by'] = $healthpost->token;

            case 'healthworker':
                $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
                $row['hp_code'] = $healthworker->hp_code;
                $row['created_by_name'] = $healthworker->name;
                $row['checked_by'] = $healthworker->token;

        }
        
        if($request->service_for == '2') {
            $uniqueLabId = generate_unique_lab_id_web(auth()->user()->token . '-' . Carbon::now()->format('ymd') . '-' . $request->lab_token);

            $row['sample_type'] = "[]";
            $row['result'] = 9;
            $row['lab_token'] = $uniqueLabId;
            $row['received_date_en'] = $row['collection_date_en'];
            $row['received_date_np'] = $row['collection_date_np'];
            $row['received_by'] = $row['hp_code'];
            $row['received_by_hp_code'] = $row['hp_code'];

            LabTest::create([
                'token' => $row['lab_token'],
                'hp_code' => $row['hp_code'],
                'sample_test_result' => '9',
                'status' => 1,
                'checked_by' => $row['checked_by'],
                'checked_by_name' => $row['created_by_name'],
                'sample_token' => $row['token'],
                'regdev' => 'web'
            ]);
        }

        SampleCollection::create($row);
        if($request->service_for == '2') {
            $responseLabId = explode('-', $uniqueLabId);
            array_shift($responseLabId);

            $request->session()->flash('message', 'Data Inserted successfully. Created Lab ID is " ' . join("-",$responseLabId) . ' "');
        } else {
            $request->session()->flash('message', 'Data Inserted successfully');
        }
        return redirect()->route('woman.create');
    }

    public function show($id)
    {

        $data = $this->findModel($id);
        $data = SuspectedCase::with('ancs', 'pncs', 'deliveries', 'lab_tests', 'vaccinations')->where('token', $data->token)->first();

        if (SuspectedCase::checkValidId($id) === false) {
            return redirect('/admin');
        }

        return view('backend.woman.show', compact('data'));
    }

    protected function findModel($id)
    {
        if (SuspectedCase::find($id) === null) {
            abort(404);
        } else {
            return $model = SuspectedCase::find($id);
        }
    }

    public function report(Request $request, $id)
    {
        $data = $this->findModel($id);
        $print = "print";
        return view('backend.woman.report', compact('data', 'print'));
    }

    public function edit($token)
    {
        $data['woman'] = SuspectedCase::with('ancs', 'pncs', 'deliveries', 'lab_tests')->where('token', $token)->first();
        //return $data['woman'];
        return view('backend.woman.edit-view')->with($data);
    }

    public function update(WomenRequest $request, $id)
    {

        $woman = $this->findModel($id);

        if (SuspectedCase::checkValidId($id) === false) {
            return redirect('/admin');
        }

        $lmp_date_en_array = explode("-", $request->get('lmp_date_en'));
        $lmp_date_en = Calendar::nep_to_eng($lmp_date_en_array[0], $lmp_date_en_array[1], $lmp_date_en_array[2])->getYearMonthDay();

        $woman->update([
            'name' => $request->get('name'),
            'mool_darta_no' => $request->get('mool_darta_no'),
            'sewa_darta_no' => $request->get('sewa_darta_no'),
            'orc_darta_no' => $request->get('orc_darta_no'),
            'phone' => $request->get('phone'),
            'height' => $request->get('height'),
            'age' => $request->get('age'),
            'lmp_date_en' => $lmp_date_en,
            'blood_group' => $request->get('blood_group'),
            'province_id' => $request->get('province_id'),
            'district_id' => $request->get('district_id'),
            'municipality_id' => $request->get('municipality_id'),
            'tole' => $request->get('tole'),
            'ward' => $request->get('ward'),
            'husband_name' => $request->get('husband_name'),
            'anc_status' => $request->get('anc_status'),
            'delivery_status' => $request->get('delivery_status'),
            'pnc_status' => $request->get('pnc_status'),
            'labtest_status' => $request->get('labtest_status'),
            'created_by' => Auth::user()->token,
            'longitude' => $request->get('longitude'),
            'latitude' => $request->get('latitude'),
            'status' => $request->get('status'),
        ]);

        $user = $this->findModelUser($woman->token);

        $user->update([
            'email' => $request->get('email'),
        ]);

        $request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('woman.index');
    }

    protected function findModelUser($token)
    {
        if (User::where('token', $token)->get()->first() === null) {
            abort(404);
        } else {
            return $model = User::where('token', $token)->get()->first();
        }
    }

    public function destroy($id)
    {
        //return $id;
        $data = SuspectedCase::with('ancs', 'pncs', 'deliveries', 'lab_tests')->where('token', $id)->firstOrfail();
        //return $data;
        $data->delete();
        return redirect()->back();
    }

    public function womanMaps(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $config['center'] = 'Kaski, Nepal';
        $config['zoom'] = '3';
        $config['map_height'] = '500px';
        $config['scrollwheel'] = false;

        $config['geocodeCaching'] = true;
        GMaps::initialize($config);

        // Add marker
        $woman = SuspectedCase::whereIn('hp_code', $hpCodes)
            ->groupBy(['longitude', 'latitude'])
            ->get(['longitude', 'latitude']);

        //Pregnant SuspectedCase count
        $woman_count = array();
        foreach ($woman as $w) {
            $value = SuspectedCase::where('longitude', $w->longitude)->count();
            array_push($woman_count, $value);
        }

        $i = 0;
        foreach ($woman as $women) {
            $config['center'] = 'Kaski, Nepal';
            $circle['radius'] = '2000';
            $marker['position'] = $women->latitude . ',' . $women->longitude;
            $marker['infowindow_content'] = $woman_count[$i];
            GMaps::add_circle($circle);
            GMaps::add_marker($marker);
            $i++;
        }

        $map = GMaps::create_map();
        return view('backend.woman.maps', compact('map'));
    }

    private function dataFromAndTo(Request $request)
    {
        if (!empty($request['from_date'])) {
            $from_date_array = explode("-", $request['from_date']);
            $from_date_eng = Calendar::nep_to_eng($from_date_array[0], $from_date_array[1], $from_date_array[2])->getYearMonthDay();
        }
        if (!empty($request['to_date'])) {
            $to_date_array = explode("-", $request['to_date']);
            $to_date_eng = Calendar::nep_to_eng($to_date_array[0], $to_date_array[1], $to_date_array[2])->getYearMonthDay();
        }

        return [
            'from_date' => $from_date_eng ?? Carbon::now()->subMonth(1),
            'to_date' => $to_date_eng ?? Carbon::now()
        ];
    }
}