<?php


namespace App\Http\Controllers\Data\Api;


use App\Helpers\GetHealthpostCodes;
use App\Helpers\ViewHelper;
use App\Http\Controllers\Controller;
use App\Models\BabyDetail;
use App\Models\VaccinationRecord;
use App\Reports\FilterRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BabyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $response = FilterRequest::filter($request);
        $hpCodes = GetHealthpostCodes::filter($response);
        $babies = BabyDetail::whereIn('org_code', $hpCodes)->withAll()->active()->isAlive();
        return response()->json([
            'collection' => $babies->advancedFilter()
        ]);
    }

    public function destroy($id)
    {
        BabyDetail::withAll()->find($id)->delete();
        return response()->json();
    }

    public function show($token){
        return response()->json([
            'record' => BabyDetail::withAll()->where('token', $token)->first()
        ]);
    }

    public function update(Request $request, $token) {
        $request['dob_en'] = ViewHelper::convertNepaliToEnglish($request['dob_np']);
        BabyDetail::where('token', $token)->update($request->only((new BabyDetail())->getFillable()));
        return response()->json();
    }

    public function updateVaccination(Request $request, $id){
        $request['vaccinated_date_en'] = ViewHelper::convertNepaliToEnglish($request['vaccinated_date_np']);
        VaccinationRecord::find($id)->update($request->only((new VaccinationRecord())->getFillable()));
        return response()->json();
    }

    public function deleteVaccination($id){
        VaccinationRecord::findOrFail($id)->delete();
        return response()->json();
    }
}