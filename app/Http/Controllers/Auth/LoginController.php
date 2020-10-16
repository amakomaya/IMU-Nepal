<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use Carbon\Carbon;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */

     /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Handle a login request to the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        $user = User::where('username', $request->username)
                  ->where('password',md5($request->password))
                  ->first();
        $position = \Location::get();
        $detect = \Browser::detect();
        $details_log = ['location'=> $position, 'browser' => $detect];

        if(count($user)>0){
            $request->session()->put('user_token', $user->token);
            $request->session()->put('user_show', false);
            Auth::login($user);
            activity('login')
                ->causedBy($user)
                ->performedOn(new User())
                ->withProperties($request)
                ->log(json_encode($details_log));

            $update_profile_expiration = Carbon::parse($user->updated_at)->addMonth();

            if (auth()->user()->role != 'main' && $update_profile_expiration < Carbon::now() ) {
                $request->session()->flash('message', 'Update your account\'s information ! <a href="/admin/profile">Edit Profile</a>');
                return redirect('/admin');
            }
            return redirect('/admin');
        }else{
            $request->session()->flash('error_message', 'Username or Password Incorrect!');
            return redirect()->route('login');
        }
         
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}