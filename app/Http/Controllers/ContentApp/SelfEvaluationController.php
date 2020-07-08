<?php

namespace App\Http\Controllers\ContentApp;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ContentApp\WomanSurvey;
use Carbon\Carbon;

class SelfEvaluationController extends Controller
{
   public function __construct(){
      $this->middleware('auth');
  }

  public function report(){
    $data = WomanSurvey::pluck('data')->unique()->map(function ($data){
        $data_decode = json_decode($data, true);

        if (starts_with($data_decode['women_token'], 'guest')) {
          $woman = \App\Models\Woman\WomanRegisterApp::where('token', $data_decode['women_token'])->get()->first();
        }else{
           $woman = \App\Models\Woman::where('token', $data_decode['women_token'])->get()->first();
        }

        $data_decode['woman_info'] = $woman;

        return $data_decode;

      }); 

      return view('content-app.self-evaluation.report', compact('data'));

  }
}