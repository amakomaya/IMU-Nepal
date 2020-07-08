<?php

namespace App\Http\Controllers\Api\v2;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContentApp\Appointment;
use App\Models\ContentApp\AppointmentPlan;
use Carbon\Carbon;

class ContentAppAppointmentController extends Controller
{
	public function check(Request $request){
	    $input = $request->all();
	    $hp_code = $input['hp_code'];
	    $plan = $input['service_type'];

	    $check_availability = AppointmentPlan::where('hp_code', $hp_code)->where('plan', $plan);

	    if(!$check_availability->exists()){
	    	return response()->json(['message' => 'Appointment not available']);
	    }

		// Check available time if date equal to today 
		// 
		$checked_end_date_time = $check_availability->get()->filter(function($item){
			$item['check_start_date_time'] = $item->from." ".$item->start_time; 
			return Carbon::parse($item->check_start_date_time)->subMinutes(60) >= Carbon::now();
		});


		// Check for seat and time
		$checked_for_seat = $checked_end_date_time->filter(function($item){
			return $item->total_seat > $item->booked_seat;
		});

	    $avavilable_data = $checked_for_seat->sortBy('created_at')->first();
		// $avavilable_data['available_time'] = $item->to." ".$item->end_time; 

		if(!count($avavilable_data) > 0){
	    	return response()->json(['message' => 'Appointment not available']);
	    }

	    $booked_time = $avavilable_data['duration'] * $avavilable_data['booked_seat'];

	    // minutes (Start time to end time) on day divide by duration
	    $no_of_appointment_per_day =  floor(Carbon::parse($avavilable_data['end_time'])->diffInMinutes(Carbon::parse($avavilable_data['start_time'])) / $avavilable_data['duration']);

	    $start_date = Carbon::parse($avavilable_data['from']. $avavilable_data['start_time'])->addMinutes($booked_time);

	    $no_of_days =  floor($avavilable_data['booked_seat'] / $no_of_appointment_per_day);

	    $appointment_time = ($booked_time) - $no_of_days * $no_of_appointment_per_day * $avavilable_data['duration'];

	    // Check appointment time for current time
	    $date = Carbon::parse($avavilable_data['from'])->addDays($no_of_days)->format('Y-m-d');
	    $time = Carbon::parse($avavilable_data['start_time'])->addMinutes($appointment_time)->format('h:i A');

	    if (Carbon::parse($avavilable_data['from']. ''. $avavilable_data['end_time']) < Carbon::parse($date.''.$time)) {
	    	return response()->json(['message' => 'Appointment not available']);
	    }

	    $data = [
	    	'token' => $avavilable_data['id'].'-'.($avavilable_data['booked_seat']+1),
	    	'appointment_purpose' => $avavilable_data['plan'],
	    	'hp_code' => $hp_code,
	    	'appointment_place' => $avavilable_data['place'],
	    	'date' => \App\Helpers\ViewHelper::convertEnglishToNepali($date),
	    	'time' => $time
	    ];
	    return response()->json($data);
	}    

	public function confirm(Request $request){
        $record = json_encode($request->json()->all());
		
		list($id, $booked_no) = preg_split('[-]', $request->all()['token']);
		$appointment_plan = AppointmentPlan::findOrFail($id);

		if ($appointment_plan->total_seat > $appointment_plan->booked_seat && $appointment_plan->booked_seat +1 == $booked_no) {
			$appointment_plan->update(['booked_seat' => $booked_no]);
			Appointment::create(['data' => $record]);
			return response()->json(['message' => 'Data Sussessfully Sync']);
		}
        return response()->json(['message' => 'Something went wrong']);
	}

	public function history(Request $request){
        $input = $request->all();
	    $woman_token = $input['woman_token'];

        $data = Appointment::all()->map(function ($appointment){
                        $data = json_decode($appointment['data'], true);
                        $data['note'] = $appointment['note'] ?? '';
                        $data['completed_at'] = $appointment['completed_at'] ?? '';
                        return $data;
                    })->where('woman_token', $woman_token); 

        $final_array = [];
        foreach ($data as $key => $value) {
        	array_push($final_array, $value);
        }        
        return response()->json($final_array);
	}	
}