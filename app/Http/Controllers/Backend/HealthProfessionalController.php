<?php

namespace App\Http\Controllers\Backend;

use App\HealthProfessional;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class HealthProfessionalController extends Controller
{
    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $customMessages = [
            'required' => 'The :attribute field is required.',
        ];

        $request->validate([
            'organization_type' => 'required',
            'organization_name' => 'required',
            'organization_phn' => 'required',
            'organization_address' => 'required',
            'designation' => 'required',
            'level' => 'required',
            'service_date' => 'required',
            'name' => 'required',
            'gender' => 'required',
            'age' => 'required',
            'issue_district' => 'required',
            'allergies' => 'required'
        ], $customMessages);
        $row = $request->all();
        $row['token'] = md5(microtime(true) . mt_Rand());
        $row['status'] = 1;
        $row['disease'] = "[" . implode(', ', $row['disease']) . "]";
        $statement = DB::select("SHOW TABLE STATUS LIKE 'health_professional'");
        $nextId = $statement[0]->Auto_increment;
        $row['serial_no'] = str_pad($nextId, 6, '0', STR_PAD_LEFT);
        try {
            HealthProfessional::create($row);
            $message = "Your information is successfully submitted";
            echo "<script type='text/javascript'>confirm('$message');</script>";
            return redirect()->route('health.professional.add');
//            + "\n Your serial number is : " + $row['serial_no'] + "\n Please carefully note your serial numbers and your registered phone numbers."
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    public function show(HealthProfessional $healthProfessional)
    {
        //
    }

    public function edit(HealthProfessional $healthProfessional)
    {
        //
    }

    public function update(Request $request, HealthProfessional $healthProfessional)
    {
        //
    }

    public function destroy(HealthProfessional $healthProfessional)
    {
        //
    }
}
