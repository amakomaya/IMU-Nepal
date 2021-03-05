<?php

namespace App\Http\Controllers\Backend;

use App\Models\HealthProfessional;
use App\Models\Organization;
use App\Models\OrganizationMember;
use App\Models\VaccinationRecord;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VaccinationController extends Controller
{
    public function store(Request $request)
    {
        $d = $request->all();
        $data['token'] = md5(microtime(true) . mt_Rand());
        $data['vaccinated_id'] = $d['vaccinated_id'];
        $data['hp_code'] = OrganizationMember::where('token', auth()->user()->token)->first()->hp_code;
        $data['vaccine_name'] = 'Covi Shield';
        $data['vaccine_period'] = '1M';
        $data['vaccinated_date_en'] = $d['vaccinated_date_en'];
        $data['vaccinated_date_np'] = $d['vaccinated_date_en'];
        $data['vaccinated_address'] = $d['vaccinated_address'];
        $data['status'] = 1;

        HealthProfessional::where('id', $data['vaccinated_id'])->update(['vaccinated_status' => 1]);
        VaccinationRecord::create($data);

        return redirect()->back()->with('Vaccination Data Added successfully');
    }
    public function storeOrgLogin(Request $request)
    {
        $d = $request->all();
        $data['token'] = md5(microtime(true) . mt_Rand());
        $data['vaccinated_id'] = str_pad($d['vaccinated_id'], 6, "0", STR_PAD_LEFT);
        $data['hp_code'] = Organization::where('token', auth()->user()->token)->first()->hp_code;
        $data['vaccine_name'] = 'Covi Shield';
        $data['vaccine_period'] = '1M';
        $data['vaccinated_date_en'] = $d['vaccinated_date_en'];
        $data['vaccinated_date_np'] = $d['vaccinated_date_en'];
        $data['vaccinated_address'] = $d['vaccinated_address'];
        $data['status'] = 1;

        HealthProfessional::where('id', $data['vaccinated_id'])->update(['vaccinated_status' => 1]);
        VaccinationRecord::create($data);

        return redirect()->back()->with('Vaccination Data Added successfully');
    }
}
