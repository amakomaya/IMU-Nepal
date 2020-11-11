<?php

namespace App\Http\Controllers;

use App\Models\ForgetPassword;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ForgetPasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $rows = ForgetPassword::all()->sortByDesc('created_at')->map(function ($data){
            $data['data'] = json_decode($data->data, true);
            return $data;
        });
        return view('auth.passwords.forget_password_list',compact('rows'));
    }
    public function store(Request $request){
        ForgetPassword::create(['data' => json_encode($request->all())]);
        Session::flash('forget_password_message', 'Messages');
        return redirect()->back();
    }
    public function updateUserPassword(Request $request){
        $user = User::where('username', $request->username)->first();
        if (!$user){
            Session::flash('message', 'Username not found ||');
            return redirect()->back();
        }
        $user->update([
            'password' => md5($request->password),
        ]);
        ForgetPassword::where('id', $request->forgetPasswordId)->first()->update(['read_at' => Carbon::now()]);
        Session::flash('message', 'Password successfully Changed');
        return redirect()->back();
    }
}
