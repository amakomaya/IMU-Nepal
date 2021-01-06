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

        $row = $request->all();
        $row['token'] = md5(microtime(true) . mt_Rand());
        $row['status'] = 1;
//        $row['disease'] = "[" . implode(', ', $row['disease']) . "]";

        $row['serial_no'] = 1;
        $id = HealthProfessional::create($row)->id;
        return redirect()->back()->with('message', 'Your information is successfully submitted 
Your serial Number is : '.$id.' And Please carefully note your Serial Numbers and your registered Ph number : '. $row['phone'] .'');
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
