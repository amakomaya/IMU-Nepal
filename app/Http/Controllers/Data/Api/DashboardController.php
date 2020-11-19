<?php

namespace App\Http\Controllers\Data\Api;

use App\Helpers\GetHealthpostCodes;
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

        ];
        return response()->json($data);
    }
}
