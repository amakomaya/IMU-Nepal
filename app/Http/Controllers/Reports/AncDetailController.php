<?php

namespace App\Http\Controllers\Reports;

use App\Models\Anc;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AncDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Anc  $anc
     * @return \Illuminate\Http\Response
     */
    public function show(Anc $anc)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Anc  $anc
     * @return \Illuminate\Http\Response
     */
    public function edit($token)
    {
        $data = Anc::where('status', '1')->where('token', $token)->first();
        return view('backend.sample.edit', compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Anc  $anc
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'token' => 'required',
            'woman_token' => 'required',
            'sample_type' => 'required',
            'infection_type' => 'required',
            'service_type' => 'required',
            'result' => 'required',
        ]);

        $sample = Anc::find($id);
        $sample->token = $request->get('token');
        $sample->woman_token = $request->get('woman_token');
        $sample->sample_type = $request->get('sample_type');
        $sample->sample_type_specific = $request->get('sample_type_specific');
        $sample->infection_type = $request->get('infection_type');
        $sample->service_type = $request->get('service_type');
        $sample->result = $request->get('result');

        $sample->save();

        return view('backend.woman.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Anc  $anc
     * @return \Illuminate\Http\Response
     */
    public function destroy(Anc $anc)
    {
        //
    }
}