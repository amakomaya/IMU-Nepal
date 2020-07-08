<?php

namespace App\Http\Controllers\ContentApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use GMaps;
use App\Models\Woman\WomanRegisterApp;
use App\Models\ContentApp\WomanSurvey;

class WomanMapController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(){
        $config['center'] = 'Kaski, Nepal';
        $config['zoom'] = '8';
        $config['map_height'] = '700px';
        $config['scrollwheel'] = false;

        $config['geocodeCaching'] = true;
        GMaps::initialize($config);

        // Add marker
        $woman = WomanRegisterApp::where('longitude', '!=', '')->get(['longitude', 'latitude', 'name', 'age', 'lmp_date_en', 'phone']);

        foreach ($woman as $women) {
            $circle['center'] = $women->latitude . ',' . $women->longitude;
            $circle['radius'] = '20';
            $marker['position'] = $women->latitude . ',' . $women->longitude;
            $marker['icon'] = 'http://maps.google.com/mapfiles/ms/icons/red-dot.png';
            $marker['infowindow_content'] = '<strong><u>Woman Registration</u></strong><br> '.$women->name.'<br>Phone : '.$women->phone.'<br>LMP Date NP : '.$women->lmp_date_np.'<br>Age : '.$women->age ;
            GMaps::add_circle($circle);
            GMaps::add_marker($marker);
        }

        $self_evaluate_data = WomanSurvey::pluck('data')->unique()->map(function ($data){
            $data_decode = json_decode($data, true);
            try{ 
              if (starts_with($data_decode['women_token'], 'guest')) {
              $woman = \App\Models\Woman\WomanRegisterApp::where('token', $data_decode['women_token'])->get()->first();
              }else{
                 $woman = \App\Models\Woman::where('token', $data_decode['women_token'])->get()->first();
              }
            	$data_decode['woman_info'] = $woman;
            }catch(\Exception $e){}
                return $data_decode;
        })->reject(function ($data_decode) {
            return empty($data_decode['longitude']);
        })->reject(function ($data_decode) {
            return empty($data_decode['woman_info']);
        }); 

        foreach ($self_evaluate_data as $row) {
            $circle['center'] = $row['latitude'] . ',' . $row['longitude'];
            $circle['radius'] = '20';
            $marker['position'] = $row['latitude'] . ',' . $row['longitude'];
            $marker['icon'] = 'http://maps.google.com/mapfiles/ms/icons/green-dot.png';
            $marker['infowindow_content'] = '<strong><u>Self Evaluation</u></strong><br> '. $row['woman_info']['name'].'<br>Phone : '.$row['woman_info']['phone'].'<br>LMP Date NP : '.$row['woman_info']['lmp_date_np'].'<br>Age : '.$row['woman_info']['age'];
            GMaps::add_circle($circle);
            GMaps::add_marker($marker);
        }

        $map = GMaps::create_map();

        return view('content-app.woman.map', ['map' => $map]);
    }
}