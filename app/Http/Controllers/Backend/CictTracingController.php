<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Models\CictTracing;
use App\Models\SuspectedCase;
use App\Models\SampleCollection;
use App\Models\OrganizationMember;

class CictTracingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cict_tracings = CictTracing::get();
        return view('backend.cict-tracing.index', compact('cict_tracings'));
    }

    public function search(){
        return view('backend.cict-tracing.search');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if($request->case_id){
            $patient = SuspectedCase::with('province', 'district', 'municipality', 'ancs', 'latestAnc')
                ->where('case_id', $request->case_id)->first();
            if($patient){
                return view('backend.cict-tracing.create', compact('patient'));
            } else{
                $request->session()->flash('message', 'Case Id not found');
                return redirect()->route('cict-tracing.search');
            }
        }else{
            $patient = null;
            return view('backend.cict-tracing.create', compact('patient'));
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
        try{
            if($request->yes == '2'){
                $data['token'] = md5(microtime(true) . mt_Rand());$data['case_id'] = $request->case_id;
                $data['case_id'] = $healthworker->id . '-' . strtoupper(bin2hex(random_bytes(3)));
                $data['hp_code'] = $healthworker->hp_code;
                $data['checked_by'] = $healthworker->token;

                $cict_tracing = CictTracing::create($data);

                // $request->session()->flash('message', 'Data auto generated successfully');
                return redirect()->route('cict-tracing.section-one', ['case_id' => $data['case_id']]);
            }else {
                if($request->case_id){
                    $check_if_exists = CictTracing::where('case_id', $request->case_id)->first();
                    if($check_if_exists) {
                        $request->session()->flash('message', 'Case already registered in Cict.');
                        return redirect()->route('cict-tracing.search');
                    }
                    // dd($request->case_id);

                    $patient = SuspectedCase::with('province', 'district', 'municipality', 'ancs', 'latestAnc')
                        ->where('case_id', $request->case_id)->first();
                        // dd($patient);
                    if($patient){
                        $data['token'] = md5(microtime(true) . mt_Rand());
                        $data['case_id'] = $request->case_id;
                        $data['woman_token'] = $patient->token;
                        $data['hp_code'] = $healthworker->hp_code;
                        $data['checked_by'] = $healthworker->token;
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
                        // dd('sss');
                    } else{
                        // dd('ss');
                        $request->session()->flash('message', 'Case Id not found');
                        return redirect()->route('cict-tracing.search');
                    }
                }
            }



            // dd($data);
            // unset($data['_token']);
            // $data['token'] = uniqid().time();
            // CictTracing::create($data);
            
            // $request->session()->flash('message', 'Data Inserted successfully');
            // return redirect()->route('cict-tracing.section-two', ['token' => $data['token']]);
        }catch(exception $e){

        }
    }

    public function sectionOne(Request $request)
    {
        if($request->case_id){
            $data = CictTracing::where('case_id', $request->case_id)->first();
            if($data){
                return view('backend.cict-tracing.section-one', compact('data'));
            } else{
                $request->session()->flash('message', 'Case Id not found');
                return redirect()->route('cict-tracing.search');
            }
        }else{
            $request->session()->flash('message', 'Case Id not found');
            return redirect()->route('cict-tracing.search');
        }
        
    }

    public function sectionOneUpdate(Request $request)
    {
        $data = $request->all();
        // dd($request->municipality_id);
        unset($data['_token']);
        unset($data['check_token']);
        unset($data['case_id']);
        unset($data['_case_id']);
        $data['municipality_id'] = $request->municipality_id;
        $cict_tracing = CictTracing::where('case_id', $request->case_id)->first();
        $cict_tracing->update($data);
            
        $request->session()->flash('message', 'Data Inserted successfully');
        return redirect()->route('cict-tracing.section-two', ['_case_id' => $cict_tracing->case_id]);
    }

    public function sectionTwo(Request $request)
    {
        if($request->_case_id){
            $data = CictTracing::where('case_id', $request->_case_id)->first();
            if($data){
                return view('backend.cict-tracing.section-two', compact('data'));
            } else{
                $request->session()->flash('message', 'Case Id not found');
                return redirect()->route('cict-tracing.search');
            }
        }else{
            $request->session()->flash('message', 'Case Id not found');
            return redirect()->route('cict-tracing.search');
        }
    }

    public function sectionTwoUpdate(Request $request)
    {
        try{
            $data = $request->all();
            unset($data['_token']);
            unset($data['check_token']);

            $data['symptoms'] = $request->symptoms ? "[" . implode(', ', $request->symptoms) . "]" : "[]";

            $data['symptoms_comorbidity'] = [];
            if($request->symptoms_comorbidity_trimester) {
                array_push($data['symptoms_comorbidity'], $request->symptoms_comorbidity_trimester);
            }
            $data['symptoms_comorbidity'] = $request->symptoms_comorbidity ? "[" . implode(', ', $request->symptoms_comorbidity) . "]" : "[]";

            $data['high_exposure'] = $request->high_exposure ? "[" . implode(', ', $request->high_exposure) . "]" : "[]";
            
            $travelled_14_days_details_array = [];
            for ($i = 0; $i < count($request->travelled_14_days_details_departure_from); $i++) {
                if ($request->travelled_14_days_details_departure_from[$i] != '') {
                    $travelled_14_days_details_data['departure_from'] = $request->travelled_14_days_details_departure_from[$i];
                    $travelled_14_days_details_data['arrival_to'] = $request->travelled_14_days_details_arrival_to[$i];
                    $travelled_14_days_details_data['departure_date'] = $request->travelled_14_days_details_departure_date_np[$i];
                    $travelled_14_days_details_data['arrival_date'] = $request->travelled_14_days_details_arrival_date_np[$i];
                    $travelled_14_days_details_data['travel_mode'] = $request->travelled_14_days_details_travel_mode[$i];
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
            
            $request->session()->flash('message', 'Data Inserted successfully');
            return redirect()->route('cict-tracing.section-three', ['_case_id' => $cict_tracing->case_id]);
        }catch(exception $e){

        }
    }

    public function sectionThree(Request $request)
    {
        if($request->_case_id){
            $data = CictTracing::where('case_id', $request->_case_id)->first();
            if($data){
                return view('backend.cict-tracing.section-three', compact('data'));
            } else{
                $request->session()->flash('message', 'Case Id not found');
                return redirect()->route('cict-tracing.search');
            }
        }else{
            $request->session()->flash('message', 'Case Id not found');
            return redirect()->route('cict-tracing.search');
        }
    }

    public function sectionThreeUpdate(Request $request)
    {
        try{
        
            $data = $request->all();
            unset($data['_token']);
            unset($data['check_token']);
            
            $household_details_array = [];
            for ($i = 0; $i < count($request->household_details_name); $i++) {
                if ($request->household_details_name[$i] != '') {
                    $household_details['name'] = $request->household_details_name[$i];
                    $household_details['age'] = $request->household_details_age[$i];
                    $household_details['age_unit'] = $request->household_details_age_unit[$i];
                    $household_details['sex'] = $request->household_details_sex[$i];
                    $household_details['relationship'] = $request->household_details_relationship[$i];
                    $household_details['phone'] = $request->household_details_phone[$i];
                    array_push($household_details_array, $household_details);
                }
            }
            $data['household_details'] = json_encode($household_details_array);
            unset($data['household_details_name']);
            unset($data['household_details_age']);
            unset($data['household_details_age_unit']);
            unset($data['household_details_sex']);
            unset($data['household_details_relationship']);
            unset($data['household_details_phone']);

            $travel_vehicle_details_array = [];
            for ($i = 0; $i < count($request->travel_vehicle_details_name); $i++) {
                if ($request->travel_vehicle_details_name[$i] != '') {
                    $travel_vehicle_details['name'] = $request->travel_vehicle_details_name[$i];
                    $travel_vehicle_details['age'] = $request->travel_vehicle_details_age[$i];
                    $travel_vehicle_details['age_unit'] = $request->travel_vehicle_details_age_unit[$i];
                    $travel_vehicle_details['sex'] = $request->travel_vehicle_details_sex[$i];
                    $travel_vehicle_details['relationship'] = $request->travel_vehicle_details_relationship[$i];
                    $travel_vehicle_details['phone'] = $request->travel_vehicle_details_phone[$i];
                    array_push($travel_vehicle_details_array, $travel_vehicle_details);
                }
            }
            $data['travel_vehicle_details'] = json_encode($travel_vehicle_details_array);
            unset($data['travel_vehicle_details_name']);
            unset($data['travel_vehicle_details_age']);
            unset($data['travel_vehicle_details_age_unit']);
            unset($data['travel_vehicle_details_sex']);
            unset($data['travel_vehicle_details_relationship']);
            unset($data['travel_vehicle_details_phone']);
            
            $other_direct_care_details_array = [];
            for ($i = 0; $i < count($request->other_direct_care_details_name); $i++) {
                if ($request->other_direct_care_details_name[$i] != '') {
                    $other_direct_care_details['name'] = $request->other_direct_care_details_name[$i];
                    $other_direct_care_details['age'] = $request->other_direct_care_details_age[$i];
                    $other_direct_care_details['age_unit'] = $request->other_direct_care_details_age_unit[$i];
                    $other_direct_care_details['sex'] = $request->other_direct_care_details_sex[$i];
                    $other_direct_care_details['relationship'] = $request->other_direct_care_details_relationship[$i];
                    $other_direct_care_details['phone'] = $request->other_direct_care_details_phone[$i];
                    array_push($other_direct_care_details_array, $other_direct_care_details);
                }
            }
            $data['other_direct_care_details'] = json_encode($other_direct_care_details_array);
            unset($data['other_direct_care_details_name']);
            unset($data['other_direct_care_details_age']);
            unset($data['other_direct_care_details_age_unit']);
            unset($data['other_direct_care_details_sex']);
            unset($data['other_direct_care_details_relationship']);
            unset($data['other_direct_care_details_phone']);

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

            $cict_tracing = CictTracing::where('case_id', $request->case_id)->first();
            $cict_tracing->update($data);
            
            $request->session()->flash('message', 'Data Inserted successfully');
            return redirect()->route('cict-tracing.index');
        }catch(exception $e){

        }

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
        //
    }
}
