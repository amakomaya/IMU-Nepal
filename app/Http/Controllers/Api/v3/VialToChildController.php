<?php

namespace App\Http\Controllers\Api\v3;

use App\Http\Controllers\Controller;
use App\Models\Aefi;
use App\Models\BabyDetail;
use App\Models\BabyWeight;
use App\Models\District;
use App\Models\Healthpost;
use App\Models\Municipality;
use App\Models\OutReachClinic;
use App\Models\province;
use App\Models\VaccinationRecord;
use App\Models\VaccineVialStock;
use App\Models\VialDetail\VialDetail;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VialToChildController extends Controller
{
    public function login(Request $request){
        $json = json_decode($request->getContent(), true);

        $user = User::where([
            ['username', $json[0]['username']],
            ['password', md5($json[0]['password'])]
        ])
            ->get()
            ->first();
        if (!empty($user)){
            if (!empty($healthpost = Healthpost::where('token', $user->token)->get()->first())) {
                $userInfo = [
                    'message' => "Successfully Logged in !!!",
                    'healthpost' => $healthpost,
                ];
                return response()->json($userInfo);
            }
        }
        $response = ['message' => 'The requested usename or password doesn\'t exist'];
        return response()->json($response);
    }

    public function index(Request $request)
    {
        $hp_code = $request->hp_code;
        $baby_token = BabyDetail::where('hp_code', $hp_code)->withInDobTwoYears()->isAlive()->active()->pluck('token');
        $data_ids = VaccineVialStock::select(DB::raw('max(id) as id'))->where('hp_code', $hp_code)
            ->groupBy('name')
            ->get();

        $response = [
            'babies'=>BabyDetail::wherein('token', $baby_token)->get(),
            'vaccinations'=>VaccinationRecord::whereIn('baby_token', $baby_token)->active()->get(),
            'weights'=>BabyWeight::whereIn('baby_token', $baby_token)->active()->get(),
            'aefis'=>Aefi::whereIn('baby_token', $baby_token)->active()->get(),
            'vial_details'=>VialDetail::where('hp_code', $hp_code)->latest()->get(),
            'vial_stock' => VaccineVialStock::whereIn('id', $data_ids)->get(),
            'provinces' => province::all(),
            'districts' => District::all(),
            'municipalities' => Municipality::all(),
            'orcs' => OutReachClinic::where('hp_code', $hp_code)->active()->get(),
        ];
        return response()->json($response);
    }

    public function store(Request $request){
        $data = json_decode($request->getContent(), true);

        $babies_data = $data['babies'] ?? [];
        $vaccinations_data = $data['vaccinations'] ?? [];
        $weights_data = $data['weights'] ?? [];
        $aefis_data = $data['aefis'] ?? [];
        $vial_details_data = $data['vial_details'] ?? [];
        $vial_stock_data = $data['vial_stock'] ?? [];

        DB::beginTransaction();

        try{
            $response['babies'] = $this->storeData($babies_data, BabyDetail::class);
            $response['vaccinations'] = $this->storeData($vaccinations_data, VaccinationRecord::class);
            $response['weights'] = $this->storeData($weights_data, BabyWeight::class);
            $response['aefis'] = $this->storeData($aefis_data, Aefi::class);
            $response['vial_details'] = $this->storeData($vial_details_data, VialDetail::class);
            $response['vial_stock'] = $this->storeData($vial_stock_data, VaccineVialStock::class);
            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['message' => "Opp's Something went wrong. Please try again"]);
        }
        return response()->json($response);
    }

    public function update(Request $request){
        $data = json_decode($request->getContent(), true);

        $babies_data = $data['babies'] ?? [];
        $vaccinations_data = $data['vaccinations'] ?? [];
        $weights_data = $data['weights'] ?? [];
        $aefis_data = $data['aefis'] ?? [];
        $vial_details_data = $data['vial_details'] ?? [];
        $vial_stock_data = $data['vial_stock'] ?? [];

        DB::beginTransaction();

        try{
            $response['babies'] = $this->updateData($babies_data, BabyDetail::class);
            $response['vaccinations'] = $this->updateData($vaccinations_data, VaccinationRecord::class);
            $response['weights'] = $this->updateData($weights_data, BabyWeight::class);
            $response['aefis'] = $this->updateData($aefis_data, Aefi::class);
            $response['vial_details'] = $this->updateData($vial_details_data, VialDetail::class);
            $response['vial_stock'] = $this->updateData($vial_stock_data, VaccineVialStock::class);

            DB::commit();
        }catch(Exception $e){
            DB::rollback();
            return response()->json(['message' => "Opp's Something went wrong. Please try again"]);
        }
        return response()->json($response);
    }

    private function storeData(array $data, string $class)
    {
        $errors = [];
        if (isset($data)){
            foreach ($data as $datum){
                try{

                    $class::create($datum);

                }catch (Exception $e){
                    $error = [
                        'token' => $datum['token'],
                        'error' => [
                            'message' => $e->getMessage(),
                            'type' => $e->getCode()
                        ]
                    ];
                    array_push($errors, $error);
                }
            }
        }
        return $errors;
    }

    private function updateData(array $data, string $model)
    {
        $errors = [];
        if (isset($data)){
            foreach ($data as $datum){
                try{

                    $model::updateOrCreate(['token', $datum['token']], $datum);

                }catch (Exception $e){
                    $error = [
                        'token' => $datum['token'],
                        'error' => [
                            'message' => $e->getMessage(),
                            'type' => $e->getCode()
                        ]
                    ];
                    array_push($errors, $error);
                }
            }
        }
        return $errors;
    }

    public function quickDataBaby(Request $request)
    {
        $date = $request->get('date');
        $hp_code = $request->get('hp_code');

        return response()->json(
            BabyDetail::where('hp_code', '=', $hp_code)
                ->where(function ($query) use ($date) {
                    $query->where('created_at','>',$date)->orWhere('updated_at','>', $date);
                })->get()
        );
    }

    public function quickDataVaccination(Request $request)
    {
        $date = $request->get('date');
        $hp_code = $request->get('hp_code');

        return response()->json(
            VaccinationRecord::where('hp_code', '=', $hp_code)
                ->where(function ($query) use ($date) {
                    $query->where('created_at','>',$date)->orWhere('updated_at','>', $date);
                })->get()
        );
    }
}