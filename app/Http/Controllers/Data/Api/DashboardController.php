<?php

namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
use App\Models\Anc;
use App\Models\LabTest;
use App\Models\Woman;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);

        $user = auth()->user();
        $sample_token = LabTest::where('checked_by', $user->token)->pluck('sample_token');
        $token = Anc::whereIn('token', $sample_token)->pluck('woman_token');
        $cases = collect(Woman::whereIn('hp_code', $hpCodes)->active()->with('latestAnc')->get());
        $registered_in_24_hrs = $cases->filter(function ($value){
            return  $value->created_at >= Carbon::now()->subDay()->toDateTimeString();
        });
        $sample_collection = $cases->filter(function ($value){
            return $value->latestAnc !== null;
        });
        $sample_collection_in_24_hrs = $sample_collection->filter(function ($value){
            return  $value->created_at >= Carbon::now()->subDay()->toDateTimeString();
        });
        $sample_received_in_lab = [];
//        $sample_received_in_lab = $sample_collection->filter(function ($value){
//                return $value->latestAnc->labReport !== null;
//        });
//        $sample_received_in_lab_in_24_hrs = $sample_received_in_lab->filter(function ($value){
//            return  $value->latestAnc->labReport->created_at >= Carbon::now()->subDay()->toDateTimeString();
//        });


//        return $query->with('latestAnc')->whereHas('latestAnc', function ($latest_anc_query) {
//            $latest_anc_query->whereHas('labReport');
//        });
        $data = [
            'registered' => $cases->count(),
            'registered_in_24_hrs' => $registered_in_24_hrs->count(),
            'sample_collection' => $sample_collection->count(),
            'sample_collection_in_24_hrs' => $sample_collection_in_24_hrs->count(),
            'sample_received_in_lab' => $sample_received_in_lab->count(),
//            'sample_received_in_lab_in_24_hrs' => $sample_received_in_lab_in_24_hrs->count(),
//            'lab_result_positive' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardLabReceivedPositive()->count(),
//            'lab_result_positive_in_24_hrs' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardLabReceivedPositiveIn24hrs()->count(),
//            'lab_result_negative' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardLabReceivedNegative()->count(),
//            'lab_result_negative_in_24_hrs' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardLabReceivedNegativeIn24hrs()->count(),

            'in_lab_received' => Woman::whereIn('token', $token)->active()->dashboardLabAddReceived()->count(),
            'in_lab_received_in_24_hrs' => Woman::whereIn('token', $token)->active()->dashboardLabAddReceivedIn24hrs()->count(),
            'in_lab_received_positive' => Woman::whereIn('token', $token)->active()->dashboardLabAddReceivedPositive()->count(),
            'in_lab_received_positive_in_24_hrs' => Woman::whereIn('token', $token)->active()->dashboardLabAddReceivedPositiveIn24hrs()->count(),
            'in_lab_received_negative' => Woman::whereIn('token', $token)->active()->dashboardLabAddReceivedNegative()->count(),
            'in_lab_received_negative_in_24_hrs' => Woman::whereIn('token', $token)->active()->dashboardLabAddReceivedNegativeIn24hrs()->count(),

        ];
        return response()->json($data);
    }
}
