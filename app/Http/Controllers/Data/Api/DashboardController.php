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

        $data = [
            'registered' => Woman::whereIn('hp_code', $hpCodes)->active()->count(),
            'registered_in_24_hrs' => Woman::whereIn('hp_code', $hpCodes)->active()->where('created_at', '>=', Carbon::now()->subDay()->toDateTimeString())->count(),
            'sample_collection' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardSampleCollection()->count(),
            'sample_collection_in_24_hrs' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardSampleCollectionIn24hrs()->count(),
            'sample_received_in_lab' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardSampleReceivedInLab()->count(),
            'sample_received_in_lab_in_24_hrs' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardSampleReceivedInLabIn24hrs()->count(),
            'lab_result_positive' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardLabReceivedPositive()->count(),
            'lab_result_positive_in_24_hrs' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardLabReceivedPositiveIn24hrs()->count(),
            'lab_result_negative' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardLabReceivedNegative()->count(),
            'lab_result_negative_in_24_hrs' => Woman::whereIn('hp_code', $hpCodes)->active()->dashboardLabReceivedNegativeIn24hrs()->count(),

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
