<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Reports\FilterRequest;
use App\Models\MunicipalityInfo;
use App\Models\FrontPage;

class FrontPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $is_user = 0;
        if(auth()->user()->role == 'municipality'){
            $response = FilterRequest::filter($request);
            $municipality_id = $response['municipality_id'];
            $m_info = MunicipalityInfo::where('municipality_id', $municipality_id)->where('center_type', 2)->first();
            if($m_info){
                $is_user = 1;
            }
        }
        $data = FrontPage::first();
        return view('backend.dashboard.index', compact('data', 'is_user'));
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
        FrontPage::create($request->all());
        $request->session()->flash('message', 'Message Created successfully');
        return redirect()->route('index.index');
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
        $data = FrontPage::findOrfail($id);
        $data->update($request->all());
        $request->session()->flash('message', 'Message Updated successfully');
        return redirect()->route('index.index');
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
