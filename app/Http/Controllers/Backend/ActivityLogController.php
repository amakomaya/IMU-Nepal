<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function __construct()
    { 
        $this->middleware('auth');
    }

    public function index(){
        $logs = Activity::latest()->paginate(50);
        return view('backend.activity-log.index', compact('logs'));
    }
}
