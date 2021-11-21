<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\GetHealthpostCodes;
use App\Reports\FilterRequest;
use App\Models\MunicipalityInfo;
use App\Models\FrontPage;
use App\Models\ZeroReport;

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
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $zero_report = ZeroReport::whereIn('org_code', $hpCodes)->whereDate('date', date('Y-m-d'))->get();
        if(auth()->user()->role == 'municipality'){
            $municipality_id = $response['municipality_id'];
            $m_info = MunicipalityInfo::where('municipality_id', $municipality_id)->where('center_type', 2)->first();
            if($m_info){
                $is_user = 1;
            }
        }
        $data = FrontPage::first();
        return view('backend.dashboard.index', compact('data', 'is_user', 'zero_report'));
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

    public function zeroReport(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        try{
            if($request['check_status'] == 1){
                $zero_report = ZeroReport::where('org_code', $hpCodes[0])
                    ->where('type', $request['type'])
                    ->where('date', date('Y-m-d'))
                    ->first();
                if(!$zero_report){
                    ZeroReport::create([
                        'province_id' => $response['province_id'],
                        'district_id' => $response['district_id'],
                        'municipality_id' => $response['municipality_id'],
                        'org_code' => $hpCodes[0],
                        'type' => $request['type'],
                        'status' => $request['check_status'],
                        'date' => date('Y-m-d')
                    ]);
                }
            }else {
                ZeroReport::where('org_code', $hpCodes[0])
                    ->where('type', $request['type'])
                    ->where('date', date('Y-m-d'))
                    ->delete();
            }
            return response()->json(['message' => 'Successful']);
        }catch(Exception $e){
            return response()->json(['message' => $e]);
        }
    }
}
