<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Healthpost;
use App\Models\Woman;
use GMaps;

class MapController extends Controller
{
    public function map()
    {
        $woman = Woman::where('delivery_status','=',0)
                        ->groupBy(['longitude','latitude','hp_code'])
                        ->get(['longitude','latitude','hp_code']);
        // dd($woman);
        $woman_count = array();
        foreach ($woman as $w){
            $value = Woman::where('longitude', $w->longitude)->count();
            array_push($woman_count, $value);            
        }

        $i = 0;
        foreach ($woman as $women) {
            $circle['center'] = $women->latitude.','.$women->longitude;
            $circle['radius'] = '50';
            $marker['position'] = $women->latitude.','.$women->longitude;
            $marker['infowindow_content'] = $women->getHealthpost($women->hp_code).'<br> Total Pregnant Woman '.$woman_count[$i];
            GMaps::add_circle($circle);
            GMaps::add_marker($marker);
        $i++;
        }
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