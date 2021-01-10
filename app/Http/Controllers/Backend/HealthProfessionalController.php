<?php

namespace App\Http\Controllers\Backend;

use App\Models\HealthProfessional;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HealthProfessionalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = HealthProfessional::all();
        return view('health-professional.index', compact('data'));
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
Your serial Number is : ' . $id . ' And Please carefully note your Serial Numbers and your registered Ph number : ' . $row['phone'] . '');
    }

    public function show($id)
    {
        $data = HealthProfessional::where('id', $id)->first();
        return view('health-professional.show', compact('data'));
    }

    public function edit($id)
    {
        $data = HealthProfessional::where('id', $id)->first();
        return view('health-professional.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $data = HealthProfessional::find($id);
        $row = $request->all();
        $row['disease'] = "[" . implode(', ', $row['disease']) . "]";
        $data->update($row);
        $request->session()->flash('message', 'Data Updated successfully');
        return redirect(route('health-professional.index'));
    }

    public function destroy(HealthProfessional $healthProfessional)
    {
        //
    }
}
