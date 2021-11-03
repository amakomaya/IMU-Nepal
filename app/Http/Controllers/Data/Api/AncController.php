<?php

namespace App\Http\Controllers\Data\Api;

use App\Models\SampleCollection;
use App\Models\SuspectedCase;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AncController extends Controller
{
    public function checkSID(Request $request)
    {
        $sid = $request->token;
        $record = SampleCollection::where('status', '1')->where('token', $sid)->first();

        if (empty($record))
            return response()->json(['message' => 'Data Not Found']);
        else {
            $woman_data = SuspectedCase::where('token', $record->case_token)->first();

            return response()->json([
                'message' => 'Data Found Successfully',
                'name' => $woman_data->name,
                'age' => $woman_data->age,
                'gender' => $woman_data->formated_gender]);
        }
    }
}
