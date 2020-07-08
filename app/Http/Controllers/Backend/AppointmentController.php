<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\Healthpost;
use App\Models\ContentApp\AppointmentPlan;
use App\Models\ContentApp\Appointment;
use Carbon\Carbon;

class AppointmentController extends Controller
{
  protected $view_path = 'backend.appointment';
  protected  $base_route = 'appointment.index';
  protected  $base_route_plan = 'appointment.plan.index';

  public function __construct(){
    $this->middleware('auth');
  }

  public function index(){
    $rows = Appointment::all()->map(function ($row){
                        $row['data'] = json_decode($row->data, true);
                        return $row;
                    })->sortByDesc('created_at')->groupBy(function ($item){
                    return Carbon::parse($item['created_at'])->format('Y-m-d'); 
                }); 
    return view($this->view_path.'.index', compact('rows'));
  }

  public function update(Request $request, $id){
    $this->service->update($request->only($this->service->fillable()), $id);
    $request->session()->flash('message', 'Multimedia updated successfully');
    return redirect()->route($this->base_route);
  }

  public function destroy(Request $request, $id){
    $this->service->delete($id);
    $request->session()->flash('message', 'Multimedia deleted');
    return redirect()->route($this->base_route);
  }

  public function indexPlan()
  {
    $rows = AppointmentPlan::where('hp_code', Healthpost::where('token',Auth::user()->token)->get()->first()->hp_code)->get();
    return view($this->view_path.'.plan.index', compact('rows'));  }

  public function createPlan()
  {
    return view($this->view_path.'.plan.create');
  }

  public function storePlan(Request $request)
  {
    $data = $request->all();
    list($start_date_time, $end_date_time) = explode("-", $data['datetimes']);
    $data['from'] = Carbon::parse($start_date_time)->format('Y-m-d');
    $data['to'] = Carbon::parse($end_date_time)->format('Y-m-d');
    $data['start_time'] = Carbon::parse($start_date_time)->format('H:i');
    $data['end_time'] = Carbon::parse($end_date_time)->format('H:i');
    $data['hp_code'] = Healthpost::where('token',Auth::user()->token)->get()->first()->hp_code;
    AppointmentPlan::create($data);        
    $request->session()->flash('message', 'Appointment Plan created successfully');
    return redirect()->route($this->base_route_plan);
  }

  public function editPlan(Request $request, $id)
  {
    $row = AppointmentPlan::findOrFail($id);
    return view($this->view_path.'.plan.edit', compact('row'));
  }

   public function updatePlan(Request $request, $id){
    $record = AppointmentPlan::findOrFail($id);
    $record->update($request->all());
    $request->session()->flash('message', 'Appointment Plan updated successfully');
    return redirect()->route($this->base_route_plan);
  }
}