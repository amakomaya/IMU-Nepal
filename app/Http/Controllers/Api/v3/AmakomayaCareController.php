<?php

namespace App\Http\Controllers\Api\v3;

use App\Http\Controllers\Controller;
use App\Models\Anc;
use App\Models\BabyDetail;
use App\Models\Delivery;
use App\Models\District;
use App\Models\HealthWorker;
use App\Models\LabTest;
use App\Models\Municipality;
use App\Models\OutReachClinic;
use App\Models\province;
use App\Models\Woman;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AmakomayaCareController extends Controller
{
    public function login(Request $request){
        $json = json_decode($request->getContent(), true);

        $user = User::where([
            ['username', $json[0]['username']],
            ['password', md5($json[0]['password'])],
            ['role', 'healthworker']])
            ->get()->first();

        if (!empty($user)) {
            $healthworker = HealthWorker::where('token',$user->token)->get()->first();
            $response = [
                'message' => "Successfully Logged in !!!",
                'healthworker' => $healthworker
            ];
            return response()->json($response);
        }
        $response = ['message'=>'The requested username or password doesn\'t exist'];
        return response()->json($response);
    }

    public function index(Request $request){
        $hp_code = $request->hp_code;

        $women_token = Woman::where('hp_code', $hp_code)
                            ->active()
                            ->whereDate('lmp_date_en', '>', Carbon::now()->subWeeks(43))
                            ->pluck('token');

        $women = Woman::whereIn('token', $women_token)->get();
        $ancs = Anc::whereIn('woman_token', $women_token)->active()->get();
        $deliveries = Delivery::whereIn('woman_token', $women_token)->active()->get();
        $delivery_details = BabyDetail::whereIn('delivery_token', $deliveries->pluck('token'))->active()->get();
        $lab_tests = LabTest::whereIn('woman_token', $women_token)->active()->get();
        $vaccinations = Woman\Vaccination::whereIn('woman_token', $women_token)->active()->get();

        $data = [
            'women' => $women,
            'ancs'  => $ancs,
            'deliveries' => $deliveries,
            'delivery_details' => $delivery_details,
            'lab_tests' => $lab_tests,
            'vaccinations' => $vaccinations,
            'provinces' => province::all(),
            'districts' => District::all(),
            'municipalities' => Municipality::all(),
            'orcs' => OutReachClinic::where('hp_code', $hp_code)->active()->get(),
        ];
        return response()->json($data);
    }

    public function ancs(Request $request){
        $hp_code = $request->hp_code;
        $women_token = Woman::where('hp_code', $hp_code)
                            ->active()
                            ->whereDate('lmp_date_en', '>', Carbon::now()->subWeeks(43))
                            ->pluck('token');
        $ancs = Anc::whereIn('woman_token', $women_token)->active()->get()->map(function($item, $key) {
            $healthworker = HealthWorker::where('token',$item->checked_by)->first();
            $item['checked_by_name'] = $healthworker->name;
           return $item;
        });
        return response()->json($ancs);
    }
}