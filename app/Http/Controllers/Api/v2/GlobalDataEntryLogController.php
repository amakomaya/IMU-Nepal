<?php

namespace App\Http\Controllers\Api\v2;

use App\Http\Controllers\Controller;
use App\Models\GlobalDataEntryLog;
use App\Models\Woman;
use Illuminate\Http\Request;

class GlobalDataEntryLogController extends Controller
{
    public function index(Request $request)
    {
        $hpCode = $request->hp_code;
        $records = GlobalDataEntryLog::where([['hp_code', $hpCode]])->get();
        return response()->json($records);
    }

    public function store(Request $request)
    {
        $json = json_decode($request->getContent(), true);
        try {
            collect($json)->each(function (array $row) {
                if ($row['type'] > 0 && $row['type'] < 5) {
                    Woman::where('token', $row['token'])->update([$this->updateTypeStatus($row['type']) => 1]);
                }
            });
            GlobalDataEntryLog::insert($json);
            return response()->json(1);
        } catch (\Exception $e) {
            return response()->json(0);
        }
    }

    function updateTypeStatus($type)
    {
        switch ($type) {
            case 1:
                return 'anc_status';
                break;
            case 2:
                return 'delivery_status';
                break;
            case 3:
                return 'pnc_status';
                break;
            case 4:
                return 'labtest_status';
                break;
        }
    }
}
