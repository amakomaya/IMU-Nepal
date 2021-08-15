<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\CictTracing;
use App\Models\CictContact;
use App\Models\CictFollowUp;
use App\Models\CictCloseContact;
use App\Models\SuspectedCase;
use App\Models\SuspectedCaseOld;
use App\Models\SampleCollection;
use App\Models\OrganizationMember;
use App\Models\Vaccine;
use App\Models\ProvinceInfo;
use App\Models\District;
use App\Models\DistrictInfo;
use App\Models\Municipality;
use App\Models\ContactTracing;
use App\Models\ContactTracingOld;
use App\Models\ContactDetail;
use App\Models\ContactDetailOld;
use App\Models\ContactFollowUp;
use App\Models\ContactFollowUpOld;
use App\Models\CaseManagement;

use App\Reports\FilterRequest;
use App\Helpers\GetHealthpostCodes;
use Yagiten\Nepalicalendar\Calendar;
use Carbon\Carbon;

class CictTracingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cict_tracings = CictTracing::orderBy('created_at', 'desc')->get();
        return view('backend.cict-tracing.index', compact('cict_tracings'));
    }

    public function search(Request $request){
        if($request->case_id){
            $check_if_exists = CictTracing::where('case_id', $request->case_id)->first();
            if($check_if_exists) {
                $request->session()->flash('message', 'Case already registered in Cict.');
                return redirect()->route('cict-tracing.search');
            }
            $patient = SuspectedCase::with('province', 'district', 'municipality', 'ancs', 'latestAnc')
                ->where('case_id', $request->case_id)->first();
            if(empty($patient)){
                $patient = SuspectedCaseOld::with('province', 'district', 'municipality', 'ancs', 'latestAnc')
                    ->where('case_id', $request->case_id)->first();
            }

            if($patient){
                $request->session()->flash('message', 'Case Found.');
                return view('backend.cict-tracing.search', compact('patient'));
            }
            else{
                $request->session()->flash('message', 'Patient not found.');
                return redirect()->route('cict-tracing.search');
            }

        }
        return view('backend.cict-tracing.search');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
        try{
            if($request->case_id){
                $check_if_exists = CictTracing::where('case_id', $request->case_id)->first();
                if($check_if_exists) {
                    $request->session()->flash('message', 'Case already registered in Cict.');
                    return redirect()->route('cict-tracing.search');
                }

                $patient = SuspectedCase::with('province', 'district', 'municipality', 'ancs', 'latestAnc')
                    ->where('case_id', $request->case_id)->first();
                if($patient){
                    $data['token'] = md5(microtime(true) . mt_Rand());
                    $data['case_id'] = $request->case_id;
                    $data['woman_token'] = $patient->token;
                    $data['hp_code'] = $healthworker->hp_code;
                    $data['checked_by'] = $healthworker->token;
                    $data['regdev'] = 'web';
                    $data['name'] = $patient->name;
                    $data['age'] = $patient->age;
                    $data['age_unit'] = $patient->age_unit;
                    $data['sex'] = $patient->sex;
                    $data['emergency_contact_one'] = $patient->emergency_contact_one;
                    $data['emergency_contact_two'] = $patient->emergency_contact_two;
                    $data['nationality'] = $patient->nationality;
                    $data['province_id'] = $patient->province_id;
                    $data['district_id'] = $patient->district_id;
                    $data['municipality_id'] = $patient->municipality_id;
                    $data['ward'] = $patient->ward;
                    $data['tole'] = $patient->tole;
                    $data['symptoms_recent'] = $patient->latestAnc ? $patient->latestAnc->infection_type : null;
                    $data['date_of_onset_of_first_symptom_np'] = $patient->date_of_onset_of_first_symptom;
                    $data['symptoms'] = $patient->symptoms;
                    $data['symptoms_specific'] = $patient->symptoms_specific;
                    $data['symptoms_comorbidity'] = $patient->symptoms_comorbidity;
                    $data['symptoms_comorbidity_specific'] = $patient->symptoms_comorbidity_specific;
                    // $data['sample_type'] = $patient->latestAnc ? $patient->latestAnc->sample_type : "[]";
                    // $data['collection_date_np'] = $patient->latestAnc ? $patient->latestAnc->collection_date_np : "[]";

                    $cict_tracing = CictTracing::create($data);

                    $request->session()->flash('message', 'Data auto generated successfully');
                    return redirect()->route('cict-tracing.section-one', ['case_id' => $data['case_id']]);
                } else{
                    $request->session()->flash('message', 'Case Id not found');
                    return redirect()->route('cict-tracing.search');
                }
            }
        }catch(exception $e){

        }
    }

    public function sectionOne(Request $request)
    {
        if($request->case_id){
            $data = CictTracing::with(['suspectedCase' => function($q){
                    $q->with('ancs', 'latestAnc');
                }])->where('case_id', $request->case_id)->first();
            if($data){
                return view('backend.cict-tracing.a-form.section-one', compact('data'));
            } else{
                $request->session()->flash('message', 'Case Id not found');
                return redirect()->route('cict-tracing.search');
            }
        }else{
            $request->session()->flash('message', 'Case Id not found');
            return redirect()->route('cict-tracing.search');
        }
        
    }

    public function sectionOneUpdate(Request $request, $case_id)
    {
        $data = $request->all();
        unset($data['_token']);
        unset($data['_method']);
        $cict_tracing = CictTracing::where('case_id', $case_id)->first();
        $cict_tracing->update($data);
            
        $request->session()->flash('message', 'Case Investigation (A Form) (1 of 3) Inserted successfully');
        return redirect()->route('cict-tracing.section-two', ['case_id' => $case_id]);
    }

    public function sectionTwo(Request $request)
    {
        if($request->case_id){
            $data = CictTracing::with(['suspectedCase' => function($q){
                    $q->with('ancs', 'latestAnc');
                }])->where('case_id', $request->case_id)->first();
            if($data){
                return view('backend.cict-tracing.a-form.section-two', compact('data'));
            } else{
                $request->session()->flash('message', 'Case Id not found');
                return redirect()->route('cict-tracing.search');
            }
        }else{
            $request->session()->flash('message', 'Case Id not found');
            return redirect()->route('cict-tracing.search');
        }
    }

    public function sectionTwoUpdate(Request $request, $case_id)
    {
        try{
            $data = $request->all();
            unset($data['_token']);
            unset($data['_method']);

            $data['symptoms'] = $request->symptoms ? "[" . implode(', ', $request->symptoms) . "]" : "[]";
            
            $data['symptoms_comorbidity'] = $request->symptoms_comorbidity ?? [];
            if($request->symptoms_comorbidity_trimester) {
                array_push($data['symptoms_comorbidity'], $request->symptoms_comorbidity_trimester);
            }
            $data['symptoms_comorbidity'] = $data['symptoms_comorbidity'] ? "[" . implode(', ', $data['symptoms_comorbidity']) . "]" : "[]";
            
            $travelled_14_days_details_array = [];
            for ($i = 0; $i < count($request->travelled_14_days_details_departure_from); $i++) {
                if ($request->travelled_14_days_details_departure_from[$i] != '') {
                    $travelled_14_days_details_data['departure_from'] = $request->travelled_14_days_details_departure_from[$i];
                    $travelled_14_days_details_data['arrival_to'] = $request->travelled_14_days_details_arrival_to[$i];
                    $travelled_14_days_details_data['departure_date'] = $request->travelled_14_days_details_departure_date_np[$i];
                    $travelled_14_days_details_data['arrival_date'] = $request->travelled_14_days_details_arrival_date_np[$i];
                    $travelled_14_days_details_data['travel_mode'] = $request->travelled_14_days_details_travel_mode[$i];
                    $travelled_14_days_details_data['travel_mode_other'] = $request->travelled_14_days_details_travel_mode_other[$i];
                    $travelled_14_days_details_data['vehicle_no'] = $request->travelled_14_days_details_vehicle_no[$i];
                    array_push($travelled_14_days_details_array, $travelled_14_days_details_data);
                }
            }
            $data['travelled_14_days_details'] = json_encode($travelled_14_days_details_array);
            unset($data['travelled_14_days_details_departure_from']);
            unset($data['travelled_14_days_details_arrival_to']);
            unset($data['travelled_14_days_details_departure_date_np']);
            unset($data['travelled_14_days_details_arrival_date_np']);
            unset($data['travelled_14_days_details_travel_mode']);
            unset($data['travelled_14_days_details_travel_mode_other']);
            unset($data['travelled_14_days_details_vehicle_no']);

            $same_household_details_array = [];
            for ($i = 0; $i < count($request->same_household_details_name); $i++) {
                if ($request->same_household_details_name[$i] != '') {
                    $same_household_details['name'] = $request->same_household_details_name[$i];
                    $same_household_details['age'] = $request->same_household_details_age[$i];
                    $same_household_details['age_unit'] = $request->same_household_details_age_unit[$i];
                    $same_household_details['sex'] = $request->same_household_details_sex[$i];
                    $same_household_details['phone'] = $request->same_household_details_phone[$i];
                    array_push($same_household_details_array, $same_household_details);
                }
            }
            $data['same_household_details'] = json_encode($same_household_details_array);
            unset($data['same_household_details_name']);
            unset($data['same_household_details_age']);
            unset($data['same_household_details_age_unit']);
            unset($data['same_household_details_sex']);
            unset($data['same_household_details_phone']);

            $close_contact_details_array = [];
            for ($i = 0; $i < count($request->close_contact_details_name); $i++) {
                if ($request->close_contact_details_name[$i] != '') {
                    $close_contact_details['name'] = $request->close_contact_details_name[$i];
                    $close_contact_details['age'] = $request->close_contact_details_age[$i];
                    $close_contact_details['age_unit'] = $request->close_contact_details_age_unit[$i];
                    $close_contact_details['sex'] = $request->close_contact_details_sex[$i];
                    $close_contact_details['phone'] = $request->close_contact_details_phone[$i];
                    array_push($close_contact_details_array, $close_contact_details);
                }
            }
            $data['close_contact_details'] = json_encode($close_contact_details_array);
            unset($data['close_contact_details_name']);
            unset($data['close_contact_details_age']);
            unset($data['close_contact_details_age_unit']);
            unset($data['close_contact_details_sex']);
            unset($data['close_contact_details_phone']);

            $direct_care_details_array = [];
            for ($i = 0; $i < count($request->direct_care_details_name); $i++) {
                if ($request->direct_care_details_name[$i] != '') {
                    $direct_care_details['name'] = $request->direct_care_details_name[$i];
                    $direct_care_details['age'] = $request->direct_care_details_age[$i];
                    $direct_care_details['age_unit'] = $request->direct_care_details_age_unit[$i];
                    $direct_care_details['sex'] = $request->direct_care_details_sex[$i];
                    $direct_care_details['phone'] = $request->direct_care_details_phone[$i];
                    array_push($direct_care_details_array, $direct_care_details);
                }
            }
            $data['direct_care_details'] = json_encode($direct_care_details_array);
            unset($data['direct_care_details_name']);
            unset($data['direct_care_details_age']);
            unset($data['direct_care_details_age_unit']);
            unset($data['direct_care_details_sex']);
            unset($data['direct_care_details_phone']);

            $attend_social_details_array = [];
            for ($i = 0; $i < count($request->attend_social_details_name); $i++) {
                if ($request->attend_social_details_name[$i] != '') {
                    $attend_social_details['name'] = $request->attend_social_details_name[$i];
                    $attend_social_details['phone'] = $request->attend_social_details_phone[$i];
                    $attend_social_details['remarks'] = $request->attend_social_details_remarks[$i];
                    array_push($attend_social_details_array, $attend_social_details);
                }
            }
            $data['attend_social_details'] = json_encode($attend_social_details_array);
            unset($data['attend_social_details_name']);
            unset($data['attend_social_details_phone']);
            unset($data['attend_social_details_remarks']);

            $cict_tracing = CictTracing::where('case_id', $request->case_id)->first();
            $cict_tracing->update($data);
            
            $request->session()->flash('message', 'Case Investigation (A Form) (2 of 3) Inserted successfully');
            return redirect()->route('cict-tracing.section-three', ['case_id' => $case_id]);
        }catch(exception $e){

        }
    }

    public function sectionThree(Request $request)
    {
        $vaccines = Vaccine::get();
        if($request->case_id){
            $data = CictTracing::with(['closeContacts', 'checkedBy', 'suspectedCase' => function($q){
                    $q->with('ancs', 'latestAnc');
                }])->where('case_id', $request->case_id)->first();
            if($data){
                $org_id = OrganizationMember::where('token', auth()->user()->token)->first()->id;
                return view('backend.cict-tracing.a-form.section-three', compact('data', 'org_id', 'vaccines'));
            } else{
                $request->session()->flash('message', 'Case Id not found');
                return redirect()->route('cict-tracing.search');
            }
        }else{
            $request->session()->flash('message', 'Case Id not found');
            return redirect()->route('cict-tracing.search');
        }
    }

    public function sectionThreeUpdate(Request $request, $case_id)
    {
        try{
            $cict_tracing = CictTracing::where('case_id', $request->case_id)->first();
            $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
            
            $data = $request->all();

            unset($data['household_details']);
            unset($data['travel_vehicle_details']);
            unset($data['other_direct_care_details']);
            
            $other_direct_care_details_array = [];
            for ($i = 0; $i < count($request->other_direct_care_details_name); $i++) {
                if ($request->other_direct_care_details_name[$i] != '') {
                    $other_direct_care_details['name'] = $request->other_direct_care_details_name[$i];
                    $other_direct_care_details['age'] = $request->other_direct_care_details_age[$i];
                    $other_direct_care_details['age_unit'] = $request->other_direct_care_details_age_unit[$i];
                    $other_direct_care_details['sex'] = $request->other_direct_care_details_sex[$i];
                    $other_direct_care_details['relationship'] = $request->other_direct_care_details_relationship[$i];
                    $other_direct_care_details['relationship_others'] = $request->other_direct_care_details_relationship_others[$i];
                    $other_direct_care_details['phone'] = $request->other_direct_care_details_phone[$i];
                    if($request->other_direct_care_details_case_id[$i]){
                        $other_direct_care_details['case_id'] = $request->other_direct_care_details_case_id[$i];
                    }else{
                        $other_direct_care_details['case_id'] = OrganizationMember::where('token', auth()->user()->token)->first()->id . '-' . strtoupper(bin2hex(random_bytes(3)));
                    }
                    array_push($other_direct_care_details_array, $other_direct_care_details);
                }
            }
            $data['other_direct_care_details'] = json_encode($other_direct_care_details_array);
            unset($data['other_direct_care_details_name']);
            unset($data['other_direct_care_details_age']);
            unset($data['other_direct_care_details_age_unit']);
            unset($data['other_direct_care_details_sex']);
            unset($data['other_direct_care_details_relationship']);
            unset($data['other_direct_care_details_relationship_others']);
            unset($data['other_direct_care_details_phone']);
            unset($data['other_direct_care_details_case_id']);

            $other_attend_social_details_array = [];
            for ($i = 0; $i < count($request->other_attend_social_details_name); $i++) {
                if ($request->other_attend_social_details_name[$i] != '') {
                    $other_attend_social_details['name'] = $request->other_attend_social_details_name[$i];
                    $other_attend_social_details['details'] = $request->other_attend_social_details_details[$i];
                    array_push($other_attend_social_details_array, $other_attend_social_details);
                }
            }
            $data['other_attend_social_details'] = json_encode($other_attend_social_details_array);
            unset($data['other_attend_social_details_name']);
            unset($data['other_attend_social_details_details']);

            $cict_tracing->update($data);

            foreach ($request->household_details as $key => $details) {
                if($details['name'] != null){
                    $household['cict_id'] = $cict_tracing->id;
                    $household['case_id'] = $details['case_id'];
                    $household['name'] = $details['name'];
                    $household['age'] = $details['age'];
                    $household['age_unit'] = $details['age_unit'];
                    $household['sex'] = $details['sex'];
                    $household['relationship'] = $details['relationship'];
                    $household['relationship_others'] = $details['relationship_others'];
                    $household['emergency_contact_one'] = $details['emergency_contact_one'];
                    $household['contact_type'] = $details['contact_type'];
                    $household['hp_code'] = $healthworker->hp_code;
                    $household['checked_by'] = $healthworker->token;
                    $household['parent_case_id'] = $cict_tracing->case_id;
                    $close_contact = CictCloseContact::where('case_id', $details['case_id'])->where('cict_id', $cict_tracing->id)
                        ->where('contact_type', '1')->first();
                    if($close_contact){
                        $close_contact->update($household);
                    }else{
                        CictCloseContact::create($household);
                    }
                }
            }

            foreach ($request->travel_vehicle_details as $key => $details) {
                if($details['name'] != null){
                    $travel_vehicle['cict_id'] = $cict_tracing->id;
                    $travel_vehicle['case_id'] = $details['case_id'];
                    $travel_vehicle['name'] = $details['name'];
                    $travel_vehicle['age'] = $details['age'];
                    $travel_vehicle['age_unit'] = $details['age_unit'];
                    $travel_vehicle['sex'] = $details['sex'];
                    $travel_vehicle['relationship'] = $details['relationship'];
                    $travel_vehicle['relationship_others'] = $details['relationship_others'];
                    $travel_vehicle['emergency_contact_one'] = $details['emergency_contact_one'];
                    $travel_vehicle['contact_type'] = $details['contact_type'];
                    $travel_vehicle['hp_code'] = $healthworker->hp_code;
                    $travel_vehicle['checked_by'] = $healthworker->token;
                    $travel_vehicle['parent_case_id'] = $cict_tracing->case_id;
                    $close_contact = CictCloseContact::where('case_id', $details['case_id'])->where('cict_id', $cict_tracing->id)
                        ->where('contact_type', '2')->first();
                    if($close_contact){
                        $close_contact->update($travel_vehicle);
                    }else{
                        CictCloseContact::create($travel_vehicle);
                    }
                }
            }

            foreach ($request->other_direct_care_details as $key => $details) {
                if($details['name'] != null){
                    $other_direct_care['cict_id'] = $cict_tracing->id;
                    $other_direct_care['case_id'] = $details['case_id'];
                    $other_direct_care['name'] = $details['name'];
                    $other_direct_care['age'] = $details['age'];
                    $other_direct_care['age_unit'] = $details['age_unit'];
                    $other_direct_care['sex'] = $details['sex'];
                    $other_direct_care['relationship'] = $details['relationship'];
                    $other_direct_care['relationship_others'] = $details['relationship_others'];
                    $other_direct_care['emergency_contact_one'] = $details['emergency_contact_one'];
                    $other_direct_care['contact_type'] = $details['contact_type'];
                    $other_direct_care['hp_code'] = $healthworker->hp_code;
                    $other_direct_care['checked_by'] = $healthworker->token;
                    $other_direct_care['parent_case_id'] = $cict_tracing->case_id;
                    $close_contact = CictCloseContact::where('case_id', $details['case_id'])->where('cict_id', $cict_tracing->id)
                        ->where('contact_type', '3')->first();
                    if($close_contact){
                        $close_contact->update($other_direct_care);
                    }else{
                        CictCloseContact::create($other_direct_care);
                    }
                }
            }
            
            $request->session()->flash('message', 'Case Investigation (A Form) Inserted successfully');
            return redirect()->route('cict-tracing.index');
        }catch(exception $e){

        }
    }

    public function aFormContactList($case_id){
        $cict_tracing = CictTracing::select('name', 'case_id')->where('case_id', $case_id)->first();
        $contact_list = CictCloseContact::with('contact', 'followUp')->where('parent_case_id', $case_id)->get();
        
        return view('backend.cict-tracing.contact-list', compact('cict_tracing', 'contact_list'));
    }

    public function partOne(Request $request){
        $cict_contact = CictContact::where('case_id', $request->case_id)->first();
        if($cict_contact){
            $contact_tracing = CictTracing::where('case_id', $cict_contact->parent_case_id)->first();
            $data = $cict_contact;
            $data->parent_case_name = $contact_tracing->name;
        }else {
            $close_contact = CictCloseContact::where('case_id', $request->case_id)->first();
            $contact_tracing = CictTracing::where('case_id', $close_contact->parent_case_id)->first();
            $data = $close_contact;
            $data->emergency_contact_two = $close_contact->emergency_contact_one;
            $data->emergency_contact_one = '';
            $data->parent_case_name = $contact_tracing->name;
            $data->cict_token = $contact_tracing->token;
        }
        return view('backend.cict-tracing.b-one-form.part-one', compact('data'));

    }

    public function partOneUpdate(Request $request, $case_id)
    {
        $data = $request->all();
        $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
        $cict_contact = CictContact::where('case_id', $case_id)->first();
        if($cict_contact){
            $cict_contact->update($data);
        }else{
            $data['token'] = md5(microtime(true) . mt_Rand());
            $data['hp_code'] = $healthworker->hp_code;
            $data['checked_by'] = $healthworker->token;
            $data['regdev'] = 'web';
            $cict_contact = CictContact::create($data);
        }
            
        $request->session()->flash('message', 'Contact Interview Form (B1 Form) (1 of 2) Inserted successfully');
        return redirect()->route('b-one-form.part-two', ['case_id' => $case_id]);
    }

    public function partTwo(Request $request)
    {
        $vaccines = Vaccine::get();
        if($request->case_id){
            $data = CictContact::with('checkedBy')->where('case_id', $request->case_id)->first();
            if($data){
                return view('backend.cict-tracing.b-one-form.part-two', compact('data', 'vaccines'));
            } else{
                $request->session()->flash('message', 'Case Id not found');
                return redirect()->route('cict-tracing.search');
            }
        }else{
            $request->session()->flash('message', 'Case Id not found');
            return redirect()->route('cict-tracing.search');
        }
    }

    public function partTwoUpdate(Request $request, $case_id)
    {
        $data = $request->all();

        $data['symptoms'] = $request->symptoms ? "[" . implode(', ', $request->symptoms) . "]" : "[]";

        $data['symptoms_comorbidity'] = $request->symptoms_comorbidity ?? [];
        if($request->symptoms_comorbidity_trimester) {
            array_push($data['symptoms_comorbidity'], $request->symptoms_comorbidity_trimester);
        }
        $data['symptoms_comorbidity'] = $data['symptoms_comorbidity'] ? "[" . implode(', ', $data['symptoms_comorbidity']) . "]" : "[]";
        
        $cict_contact = CictContact::where('case_id', $case_id)->first();
        $cict_contact->update($data);
            
        $request->session()->flash('message', 'Contact Interview Form (B1 Form) Inserted successfully');
        return redirect()->route('cict-tracing.contact-list', $cict_contact->parent_case_id);
    }

    public function followUp(Request $request){
        $cict_contact = CictContact::where('case_id', $request->case_id)->first();
        if(empty($cict_contact)){
            $request->session()->flash('message', 'Please fill B1 form first.');
            return redirect()->back();
        }
        $cict_follow_up = CictFollowUp::with('checkedBy')->where('case_id', $request->case_id)->first();
        $cict_tracing = CictTracing::where('case_id', $cict_contact->parent_case_id)->first();
        if($cict_follow_up){
            $data = $cict_follow_up;
        }else {
            $test['case_id'] = $request->case_id;
            $test['parent_case_id'] = $cict_contact->parent_case_id;
            $data = (object) $test;
        }
        
        return view('backend.cict-tracing.b-two-form.follow-up', compact('cict_contact', 'cict_tracing', 'data'));
    }

    public function followUpUpdate(Request $request, $case_id){
        $data = $request->all();
        $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
        $cict_follow_up = CictFollowUp::where('case_id', $case_id)->first();
        
        for($i=0; $i<11; $i++){
            $data['no_symptoms_' . $i] = $request->{'no_symptoms_'.$i} ?? 0;
            $data['fever_' . $i] = $request->{'fever_'.$i} ?? null;
            $data['runny_nose_' . $i] = $request->{'runny_nose_'.$i} ?? null;
            $data['cough_' . $i] = $request->{'cough_'.$i} ?? null;
            $data['sore_throat_' . $i] = $request->{'sore_throat_'.$i} ?? null;
            $data['breath_' . $i] = $request->{'breath_'.$i} ?? null;
        }
        if($cict_follow_up){
            $cict_follow_up->update($data);
        }else{
            $data['token'] = md5(microtime(true) . mt_Rand());
            $data['hp_code'] = $healthworker->hp_code;
            $data['checked_by'] = $healthworker->token;
            $data['regdev'] = 'web';
            $cict_follow_up = CictFollowUp::create($data);
        }

        $request->session()->flash('message', 'Contact Follow Up Form (B2 Form) Inserted successfully');
        return redirect()->route('cict-tracing.contact-list', $cict_follow_up->parent_case_id);
    }

    public function report($case_id){
        $data = CictTracing::with(['contact' => function($q){
                $q->with('followUp');
            },
            'closeContacts', 'checkedBy', 'vaccine',
            'suspectedCase' => function($q){
                $q->with('ancs', 'latestAnc');
            }])->where('case_id', $case_id)->first();

        // dd($data);
        return view('backend.cict-tracing.reports.report', compact('data'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $cict_tracing = CictTracing::where('token', $id)->first();
            $cict_tracing->delete();
            return response()->json(['message' => 'success']);
        }
        catch (\Exception $e){
            return response()->json(['message' => 'error']);
        }
    }
    
    public function destroyCloseContact($case_id)
    {
        try {
            $close_contact = CictCloseContact::where('case_id', $case_id)->first();
            $close_contact->delete();
            return response()->json(['message' => 'success']);
        }
        catch (Exception $e) {
            return response()->json(['message' => 'error']);
        }
    }

    private function dataFromOnly(Request $request)
    {
        if (!empty($request['from_date'])) {
            $from_date_array = explode("-", $request['from_date']);
            $from_date_eng = Carbon::parse(Calendar::nep_to_eng($from_date_array[0], $from_date_array[1], $from_date_array[2])->getYearMonthDay())->startOfDay();
        }

        return [
            'from_date' =>  $from_date_eng ?? Carbon::now()->startOfDay(),
        ];
    }

    public function provinceReport(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $filter_date = $this->dataFromOnly($request);
        
        $province_id = ProvinceInfo::where('token', auth()->user()->token)->first()->id;
        $locations = District::where('province_id', $province_id)->get();

        $cict_tracings = CictTracing::leftjoin('healthposts', 'healthposts.hp_code', '=', 'cict_tracings.hp_code')
            ->select('cict_tracings.token', 'healthposts.district_id')
            ->whereDate('cict_tracings.created_at', $filter_date['from_date']->toDateString())
            ->whereIn('cict_tracings.hp_code', $hpCodes)->get()->groupBy('district_id');
        $contacts = CictContact::leftjoin('healthposts', 'healthposts.hp_code', '=', 'cict_contacts.hp_code')
            ->select('cict_contacts.token', 'healthposts.district_id')
            ->whereDate('cict_contacts.created_at', $filter_date['from_date']->toDateString())
            ->whereIn('cict_contacts.hp_code', $hpCodes)->get()->groupBy('district_id');
        $follow_ups = CictFollowUp::leftjoin('healthposts', 'healthposts.hp_code', '=', 'cict_follow_ups.hp_code')
            ->select('cict_follow_ups.token', 'healthposts.district_id')
            ->whereDate('cict_follow_ups.created_at', $filter_date['from_date']->toDateString())
            ->whereIn('cict_follow_ups.hp_code', $hpCodes)->get()->groupBy('district_id');

        return view('backend.cict-tracing.reports.province-report', compact('cict_tracings', 'contacts', 'follow_ups', 'locations', 'from_date'));
    }

    public function districtReport(Request $request){
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        foreach ($response as $key => $value) {
            $$key = $value;
        }

        $filter_date = $this->dataFromOnly($request);
        
        $district_id = DistrictInfo::where('token', auth()->user()->token)->first()->id;
        $locations = Municipality::where('district_id', $district_id)->get();

        $cict_tracings = CictTracing::leftjoin('healthposts', 'healthposts.hp_code', '=', 'cict_tracings.hp_code')
            ->select('cict_tracings.token', 'healthposts.municipality_id')
            ->whereDate('cict_tracings.created_at', $filter_date['from_date']->toDateString())
            ->whereIn('cict_tracings.hp_code', $hpCodes)->get()->groupBy('municipality_id');
        $contacts = CictContact::leftjoin('healthposts', 'healthposts.hp_code', '=', 'cict_contacts.hp_code')
            ->select('cict_contacts.token', 'healthposts.municipality_id')
            ->whereDate('cict_contacts.created_at', $filter_date['from_date']->toDateString())
            ->whereIn('cict_contacts.hp_code', $hpCodes)->get()->groupBy('municipality_id');
        $follow_ups = CictFollowUp::leftjoin('healthposts', 'healthposts.hp_code', '=', 'cict_follow_ups.hp_code')
            ->select('cict_follow_ups.token', 'healthposts.municipality_id')
            ->whereDate('cict_follow_ups.created_at', $filter_date['from_date']->toDateString())
            ->whereIn('cict_follow_ups.hp_code', $hpCodes)->get()->groupBy('municipality_id');

        return view('backend.cict-tracing.reports.district-report', compact('cict_tracings', 'contacts', 'follow_ups', 'locations', 'from_date'));
    }

    public function oldCictTotalData()
    {
        $case_mgmt = CaseManagement::count();

        $contact_tracing_current = ContactTracing::count();
        $contact_tracing_dump = ContactTracingOld::count();
        $contact_tracing = $contact_tracing_current + $contact_tracing_dump;

        // $contact_details_current = ContactDetail::count();

        $contact_followup_current = ContactFollowUp::count();
        $contact_followup_dump = ContactFollowUpOld::count();
        $contact_followup = $contact_followup_current + $contact_followup_dump;

        return response()->json([
            'case_mgmt' => $case_mgmt,
            'contact_tracing' => $contact_tracing,
            'contact_followup' => $contact_followup
        ]);
    }

    public function oldCictDatewiseReport(Request $request){
        $data_chosen_from = $request->date_from ?? date('Y-m-d');
        $data_chosen_to = $request->date_to ?? date('Y-m-d');

        $case_mgmt_count = CaseManagement::whereBetween('created_at', [$data_chosen_from, $data_chosen_to])->count();

        $contact_tracing_current_count = ContactTracing::whereBetween('created_at', [$data_chosen_from, $data_chosen_to])->count();
        $contact_tracing_dump_count = ContactTracingOld::whereBetween('created_at', [$data_chosen_from, $data_chosen_to])->count();
        $contact_tracing_count = $contact_tracing_current_count + $contact_tracing_dump_count;

        $contact_followup_current_count = ContactFollowUp::whereBetween('created_at', [$data_chosen_from, $data_chosen_to])->count();
        $contact_followup_dump_count = ContactFollowUpOld::whereBetween('created_at', [$data_chosen_from, $data_chosen_to])->count();
        $contact_followup_count = $contact_followup_current_count + $contact_followup_dump_count;

        return response()->json([
            'case_mgmt_count' => $case_mgmt_count,
            'contact_tracing_count' => $contact_tracing_count,
            'contact_followup_count' => $contact_followup_count
        ]);
    }
}
