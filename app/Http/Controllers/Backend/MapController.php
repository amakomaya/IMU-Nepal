<?php

namespace App\Http\Controllers\Backend;

use App\Helpers\GetHealthpostCodes;
use App\Reports\FilterRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Healthpost;
use App\Models\Woman;
use GMaps;

class MapController extends Controller
{
    public function map(Request $request)
    {
//        $response = FilterRequest::filter($request);
//        $hpCodes = GetHealthpostCodes::filter($response);
//        $cases = Woman::whereIn('hp_code', $hpCodes)->where('longitude', '!=', null)->with('latestAnc')->get();
//         dd($hpCodes);

//        $i = 0;
//        dd($cases);
//        foreach ($cases as $case) {
//            $circle['center'] = $case->latitude.','.$case->longitude;
//            $circle['radius'] = '50';
//            $marker['position'] = $case->latitude.','.$case->longitude;
//            $marker['infowindow_content'] = $case->getHealthpost($case->hp_code).'<br> Total COVID-19 Cases '. $i;
//            GMaps::add_circle($circle);
//            GMaps::add_marker($marker);
//        $i++;
//        }
        $config['center'] = 'Kathmandu, Nepal';
        $config['zoom'] = '7';
        $config['map_height'] = '500px';
        $config['scrollwheel'] = false;

        $config['geocodeCaching'] = true;
        GMaps::initialize($config);

        $map = GMaps::create_map();
        return view('backend.woman.maps', compact('map'));
    }
}