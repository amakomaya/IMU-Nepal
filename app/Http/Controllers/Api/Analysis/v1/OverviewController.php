<?php

namespace App\Http\Controllers\Api\Analysis\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Woman;
use App\Models\BabyDetail;
use App\Models\MunicipalityInfo;
use App\Models\Healthpost;
use App\Models\HealthWorker;
use Carbon\Carbon;

class OverviewController extends Controller
{ 
    public function get(Request $request)
    {
        $response = [ 
                        'woman'=>$this->getWomanInformation(),
                        'babies'=>$this->getBabydetailInformation(),
                        'municipalities'=>$this->getMunicipalityInformation(),
                        'healthposts'=>$this->getHealthpostInformation(),
                        'healthworkers'=>$this->getHealthworkerInformation(),
                        'performance_data' => $this->performanceMunicipalities()
                    ];

        return response()->json($response);
    }

    public function getWomanInformation()
    {
        $woman = Woman::all();
        $graph_data = $woman->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m'); // grouping by months
        })->sortBy(function($col, $key){
            return $key;
        })->map(function ($item, $key) {
            return collect($item)->count();
        });

        $labels = [];
        $values = [];
        foreach ($graph_data as $key => $value) {
            $labels[] = Carbon::parse($key)->format('F, Y');
            $values[] = $value;
        }

        $data = [
            'labels' => $labels,
            'values' => $values

        ];
        // dd($data);


        $woman_infomation = [
            'count' => $woman->count(),
            'graph_data' => $data
        ];
        return $woman_infomation;
    }

    public function getBabydetailInformation()
    {
        $babies = BabyDetail::all();
        $graph_data = $babies->groupBy(function($date) {
            return Carbon::parse($date->created_at)->format('Y-m'); // grouping by months
        })->sortBy(function($col, $key){
            return $key;
        })->map(function ($item, $key) {
            return collect($item)->count();
        });
        dd($graph_data);

        $labels = [];
        $values = [];
        foreach ($graph_data as $key => $value) {
            $labels[] = Carbon::parse($key)->format('F, Y');
            $values[] = $value;
        }
        $data = [
            'labels' => $labels,
            'values' => $values

        ];
        // dd($data);

        $babies_infomation = [
            'count' => $babies->count(),
            'graph_data' => $data
        ];
        return $babies_infomation;
    }

    public function getMunicipalityInformation()
    {
        $municipality = MunicipalityInfo::all();
        $municipality_infomation = [
            'count' => $municipality->count()
        ];
        return $municipality_infomation;
    }

    public function getHealthpostInformation()
    {
        $healthpost = Healthpost::all();
        $healthpost_infomation = [
            'count' => $healthpost->count()
        ];
        return $healthpost_infomation;
    }

    public function getHealthworkerInformation()
    {
        $healthworker = HealthWorker::all();
        $healthworker_infomation = [
            'count' => $healthworker->count()
        ];
        return $healthworker_infomation;
    }

    public function performanceMunicipalities()
    {
        $woman = Woman::all();
        $babies = BabyDetail::all();
        $healthpost = Healthpost::select('hp_code')->distinct()->get()->toArray();

        $woman_graph_data = $woman->groupBy(function($query) {
            return $query->hp_code; // grouping by months
        })->map(function ($item, $key) {
            return collect($item)->count();
        })->toArray();

        $babies_graph_data = $babies->groupBy(function($query) {
            return $query->hp_code; // grouping by months
        })->map(function ($item, $key) {
            return collect($item)->count();
        })->toArray();

        // dd($woman_graph_data);
        $records = [];
        foreach ($healthpost as $value) {
            $data = [];
            $data['Healthpost Name'] = Healthpost::getHealthpost($value['hp_code']);

            try {
                if (in_array($value['hp_code'], array_keys($woman_graph_data))) {
                    $data['AMC Records'] = (string)$woman_graph_data[$value['hp_code']];
                 }else{
                     $data['AMC Records'] = '0';
                 }
            } catch (\Throwable $th) {
                $data['AMC Records'] = '0';
            }

            try {
                if (in_array($value['hp_code'], array_keys($babies_graph_data))) {
                    $data['VTC Records'] = (string)$babies_graph_data[$value['hp_code']];
                 }else{
                     $data['VTC Records'] = '0';
                 }
            } catch (\Throwable $th) {
                $data['VTC Records'] = '0';
            }
            
           
            $records[] = $data;
        }

        $values = [];
        $values[] = array_keys($data);
        foreach ($records as $key => $value) {
            $values[] = array_values($value);
        }

        return $values;
    }
}