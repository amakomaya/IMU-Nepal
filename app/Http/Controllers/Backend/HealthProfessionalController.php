<?php

namespace App\Http\Controllers\Backend;

use App\Exports\HealthProfessionalsExport;
use App\Models\District;
use App\Models\HealthProfessional;
use App\Models\Municipality;
use App\Models\MunicipalityInfo;
use App\Models\Organization;
use App\Models\province;
use App\Models\VaccinationRecord;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;


class HealthProfessionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        if (User::checkAuthForIndexShowHealthpost() === false) {
            return redirect('/admin');
        }
        if (Auth::user()->role === "municipality") {
            $token = Auth::user()->token;
            $municipality_id = MunicipalityInfo::where('token', $token)->first()->municipality_id;
            $organization = Organization::where('municipality_id', $municipality_id)->pluck('token');
            $organizations = Organization::where('municipality_id', $municipality_id)->get();
            $checked_by_tokens = collect($organization)->merge($token)->toArray();

            $data = HealthProfessional::whereIn('checked_by', $checked_by_tokens)
                ->whereNull('vaccinated_status')
                ->latest()->paginate(1000);

            return view('health-professional.index', compact('data','organizations'));
        } else {
            $data = HealthProfessional::where('checked_by', Auth::user()->token)
                ->whereNull('vaccinated_status')
                ->latest()->paginate(1000);
        }
        return view('health-professional.index', compact('data'));
    }

    public function immunized()
    {
        if (User::checkAuthForIndexShowHealthpost() === false) {
            return redirect('/admin');
        }

        if (Auth::user()->role === "municipality") {
            $token = Auth::user()->token;
            $municipality_id = MunicipalityInfo::where('token', $token)->first()->municipality_id;
            $organization = Organization::where('municipality_id', $municipality_id)->pluck('token');
            $checked_by_tokens = collect($organization)->merge($token)->toArray();

            $data_having_null = HealthProfessional::whereIn('checked_by', $checked_by_tokens)
                ->whereNull('vaccinated_status')
                ->pluck('id');
            if (count($data_having_null) > 0) {
                $vaccinated_id_check = VaccinationRecord::whereIn('vaccinated_id', $data_having_null)->pluck('vaccinated_id');
                if (count($vaccinated_id_check) > 0) {
                    HealthProfessional::whereIn('id', $vaccinated_id_check)->update(['vaccinated_status' => '1']);
                }
            }

            $data = HealthProfessional::
                where('vaccinated_status', '1')
                ->whereIn('checked_by', $checked_by_tokens)
                ->latest()
                ->paginate(1000);
        } else {

            $data_having_null = HealthProfessional::where('checked_by', Auth::user()->token)
                ->whereNull('vaccinated_status')
                ->pluck('id');
            if (count($data_having_null) > 0) {
                $vaccinated_id_check = VaccinationRecord::whereIn('vaccinated_id', $data_having_null)->pluck('vaccinated_id');
                if (count($data_having_null) > 0) {
                    HealthProfessional::whereIn('id', $vaccinated_id_check)->update(['vaccinated_status' => '1']);
                }
            }
            $data = HealthProfessional::where('checked_by', Auth::user()->token)
                ->where('vaccinated_status', '1')
                ->latest()
                ->paginate(1000);
        }
        return view('health-professional.immunized', compact('data'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
//        $request->validate([
//            'phone' => 'required|unique:health_professional,phone'
//        ]);
        $row = $request->all();
        $row['token'] = md5(microtime(true) . mt_Rand());
        $row['status'] = 1;
        if (array_key_exists("disease", $row)) {
            $row['disease'] = "[" . implode(', ', $row['disease']) . "]";
        } else {
            $row['disease'] = "[]";
        }
        $row['serial_no'] = 1;
        $row['checked_by'] = Auth::user()->token;
        $id = HealthProfessional::create($row)->id;
        $id = str_pad($id, 6, "0", STR_PAD_LEFT);
        return redirect()->back()->with('message', "Your information is successfully submitted. Your serial Number is : $id  And Please carefully note your Serial Numbers and your registered Ph number : " . $row['phone']);
    }

    public function show($id)
    {
        $data = HealthProfessional::where('id', $id)->first();
        $muni = Municipality::getMunicipality($data->municipality_id);
        $muni_perm = Municipality::getMunicipality($data->perm_municipality_id);
        $province = province::getProvince($data->province_id);
        $province_perm = province::getProvince($data->perm_province_id);
        return view('health-professional.show', compact('data', 'province', 'province_perm', 'muni', 'muni_perm'));
    }

    public function edit($id)
    {
        $data = HealthProfessional::where('id', $id)->first();
        return view('health-professional.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
//        $request->validate([
//            'phone' => "required|unique:health_professional,phone,$id"
//        ]);
        $data = HealthProfessional::find($id);
        $row = $request->all();
        if (array_key_exists("disease", $row)) {
            $row['disease'] = "[" . implode(', ', $row['disease']) . "]";
        } else {
            $row['disease'] = "[]";
        }
        $data->update($row);
        $request->session()->flash('message', 'Data Updated successfully');
        return redirect()->back();
    }

    public function destroy(HealthProfessional $healthProfessional)
    {
        //
    }

    public function export(Request $request)
    {
        $request = $request->all();
        return Excel::download(new HealthProfessionalsExport($request), auth()->user()->username . '-health-professionals-data-' . date('Y-m-d H:i:s') . '.xlsx');
    }
}
