<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GetHealthpostCodes;
use App\Http\Controllers\Controller;
use App\Http\Requests\WomenRequest;
use App\Models\SampleCollection;
use App\Models\Delivery;
use App\Models\District;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\Municipality;
use App\Models\Province;
use App\Models\VaccineVial;
use App\Models\SuspectedCase;
use App\Reports\FilterRequest;
use App\User;
use Carbon\Carbon;
use Charts;
use GMaps;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yagiten\Nepalicalendar\Calendar;

class WomanController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('backend.woman.index');
    }

    public function negativeIndex()
    {
        return view('backend.woman.index-negative');
    }

    public function positiveIndex()
    {
        return view('backend.woman.index-positive');
    }

    public function labReceivedIndex(){
        return view('backend.woman.index-lab-received');
    }

    public function casesRecoveredIndex(){
        return view('backend.woman.index-cases-recovered');
    }

    public function casesDeathIndex(){
        return view('backend.woman.index-cases-death');
    }

    public function casesInOtherOrganization(){
        return view('backend.woman.index-cases-in-other-organization');
    }

    public function create(Request $request)
    {
        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }
        return view('backend.patient.create', compact('provinces', 'districts', 'municipalities', 'province_id','district_id','municipality_id'));
    }

    public function store(Request $request)
    {
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
        ], $customMessages);
        $response = FilterRequest::filter($request);
        foreach ($response as $key => $value) {
            $$key = $value;
        }
        $row = $request->all();
        $row['token'] = md5(microtime(true).mt_Rand());
        $row['status'] = 1;
        $row['created_by'] = auth()->user()->token;
        $row['symptoms'] = "[]";
        $row['travelled_where'] = "[]";
        $row['hp_code'] = OrganizationMember::where('token', auth()->user()->token)->first()->hp_code;
        $row['symptoms_comorbidity'] = "[]";
        $row['cases'] = 0;
        $row['case_where'] = 0;
        $row['end_case'] = 0;
        $row['payment'] = 0;
        $row['case_id'] = OrganizationMember::where('token', auth()->user()->token)->first()->id.'-'.bin2hex(random_bytes(3));
        $row['registered_device'] = 'web';

        SuspectedCase::create($row);

        $request->session()->flash('message', 'Data Inserted successfully');
        if ($request->swab_collection_conformation == '1'){
            return $this->sampleCollectionCreate($row['token']);
        }
        return redirect()->route('woman.index');
    }

    public function sampleCollectionCreate($token){
        $id = OrganizationMember::where('token', auth()->user()->token)->first()->id;
        $swab_id = str_pad($id, 4, '0', STR_PAD_LEFT).'-'.Carbon::now()->format('ymd').'-'.$this->convertTimeToSecond(Carbon::now()->format('H:i:s'));
        ;
        return view('backend.patient.sample-create', compact('token', 'swab_id'));
    }

    private function convertTimeToSecond(string $time): int
    {
        $d = explode(':', $time);
        return ($d[0] * 3600) + ($d[1] * 60) + $d[2];
    }

    public function sampleCollectionStore(Request $request){
        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];

        $request->validate([
            'sample_type' => 'required',
            'infection_type' => 'required',
            'service_type' => 'required',
        ], $customMessages);
        $row = $request->all();
        $row['created_by'] = auth()->user()->token;
        $row['status'] = 1;
        $row['result'] = 2;
        $row['sample_identification_type'] = 'unique_id';
        switch (auth()->user()->role){
            case 'healthpost':
                $healthpost = Organization::where('token', auth()->user()->token)->first();
                $row['hp_code'] = $healthpost->hp_code;
                $row['created_by_name'] = $healthpost->name;

            case 'healthworker':
                $healthworker = OrganizationMember::where('token', auth()->user()->token)->first();
                $row['hp_code'] = $healthworker->hp_code;
                $row['created_by_name'] = $healthworker->name;

        }
        $row['sample_type'] = "[".implode(', ', $row['sample_type'])."]";
        SampleCollection::create($row);
        $request->session()->flash('message', 'Data Inserted successfully');
        return redirect()->route('woman.index');
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
        //return $token;
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