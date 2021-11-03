<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Auth;
use Carbon\Carbon;
use App\Reports\FilterRequest;
use App\Helpers\GetHealthpostCodes;

use App\Models\OrganizationObservationCases;
use App\Models\OrganizationMember;

class ObservationCasesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(Auth::user()->role == 'healthpost') {
            $organization_hp_code = DB::table('organizations')->where('token', Auth::user()->token)->first()->hp_code;
            $observation_cases = OrganizationObservationCases::where('hp_code', $organization_hp_code)->orderBy('created_at', 'desc')->get();
            $add_sum = $observation_cases->sum('add');
            $transfer_to_bed_sum = $observation_cases->sum('transfer_to_bed');
            $return_to_home_sum = $observation_cases->sum('return_to_home');

            $observation_cases_today = OrganizationObservationCases::where('hp_code', $organization_hp_code)
                ->whereDate('created_at', date('Y-m-d'))
                ->orderBy('created_at', 'desc')
                ->get();
            $add_today_sum = $observation_cases_today->sum('add');
            $transfer_to_bed_today_sum = $observation_cases_today->sum('transfer_to_bed');
            $return_to_home_today_sum = $observation_cases_today->sum('return_to_home');

            return view('backend.observation-cases.organization', compact('observation_cases', 'add_sum', 'transfer_to_bed_sum', 'return_to_home_sum', 'observation_cases_today', 'add_today_sum', 'transfer_to_bed_today_sum', 'return_to_home_today_sum'));
        } else {
            $response = FilterRequest::filter($request);
            $hpCodes = GetHealthpostCodes::filter($response);
    
            $observation_cases = OrganizationObservationCases::whereIn('organization_observation_cases.hp_code', $hpCodes)
                ->leftjoin('organizations', 'organization_observation_cases.hp_code', '=', 'organizations.hp_code')
                ->select('organization_observation_cases.*', 'organizations.name')
                ->orderBy('organization_observation_cases.created_at', 'desc')
                ->get()
                ->groupBy('hp_code');
            $add_sum = $transfer_to_bed_sum = $return_to_home_sum = $add_today_sum = $transfer_to_bed_today_sum = $return_to_home_today_sum = 0;
            foreach($observation_cases as $case) {
                $add_sum += $case->sum('add');
                $transfer_to_bed_sum += $case->sum('transfer_to_bed');
                $return_to_home_sum += $case->sum('return_to_home');
            }

            $observation_cases_today = OrganizationObservationCases::whereIn('organization_observation_cases.hp_code', $hpCodes)
                ->leftjoin('organizations', 'organization_observation_cases.hp_code', '=', 'organizations.hp_code')
                ->select('organization_observation_cases.*', 'organizations.name')
                ->whereDate('organization_observation_cases.created_at', date('Y-m-d'))
                ->orderBy('organization_observation_cases.created_at', 'desc')
                ->get()
                ->groupBy('hp_code');
            foreach($observation_cases_today as $cases) {
                $add_today_sum += $cases->sum('add');
                $transfer_to_bed_today_sum += $cases->sum('transfer_to_bed');
                $return_to_home_today_sum += $cases->sum('return_to_home');
            }
            
            return view('backend.observation-cases.index', compact('observation_cases', 'add_sum', 'transfer_to_bed_sum', 'return_to_home_sum', 'observation_cases_today', 'add_today_sum', 'transfer_to_bed_today_sum', 'return_to_home_today_sum'));

        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $organization_hp_code = DB::table('organizations')->where('token', auth()->user()->token)->first()->hp_code;
        return view('backend.observation-cases.create', compact('organization_hp_code'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->add == null && $request->transfer_to_bed == null && $request->return_to_home == null){
            $request->session()->flash('error', "Error on data insert. Please retry !");
            return redirect()->back();
        }
        try{
            // $organization_hp_code = DB::table('organizations')->where('token', auth()->user()->token)->first()->hp_code;
            // $request['hp_code'] = $organization_hp_code;
            OrganizationObservationCases::create($request->all());

            $request->session()->flash('message', 'Data Inserted successfully');
        } catch(Exception $e) {
            $request->session()->flash('error', "Error on data insert. Please retry !");
            return redirect()->back();
        }

        return redirect()->route('observation-cases.index');
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
        try{
            $observation_cases = OrganizationObservationCases::findOrFail($id);
            $observation_cases->update($request->all());

            $request->session()->flash('message', 'Data Updated successfully');
        } catch(Exception $e) {
            $request->session()->flash('error', "Error on data update. Please retry !");
        }
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
