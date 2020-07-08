<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VaccinationRecord;
use App\Models\SyncLogs;
use Carbon\Carbon;

class VaccinationRecordController extends Controller
{
    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $heathpost = VaccinationRecord::where([['hp_code', $hpCode],['status', 1]])->get();
        return response()->json($heathpost);
    }

    public function batchStore(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        $log = SyncLogs::create(['data'=> json_encode($json)]);
        try {
            VaccinationRecord::insert($json);
            SyncLogs::where('id', $log->id)->update([
                'completed_at' => Carbon::now()
                ]);
            return response()->json(['success' => 'Vaccination record sucessfully Synced'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to Sync Vaccination record, Please contact 01-4428090'], 409);
        }
    }

    public function batchUpdate(Request $request)
    { 
        $json = json_decode($request->getContent(), true);
        $log = SyncLogs::create(['data'=> json_encode($json)]);
        try {
            $updated_ids = array();
            foreach($json as $data){
                $record = VaccinationRecord::where('token', $data['token'])->update($data);
                $updated_ids[] = $data['token'];
            }
            SyncLogs::where('id', $log->id)->update([
                'completed_at' => Carbon::now()
                ]);
            return response()->json(['success' => 'Vaccination record sucessfully updated', 'updated_ids' => $updated_ids], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to update Vaccination record, Please contact 01-4428090'], 409);
        }
    }
}
