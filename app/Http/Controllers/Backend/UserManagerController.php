<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\provinceInfo;
use App\User;
use App\Models\Organization;

class UserManagerController extends Controller
{
	/**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changePassword($id)
    {

        \Session::put('url.intended',\URL::previous());

        $user = $this->findModelUser($id);

        return view('backend.user-manager.change-password', compact('user'));
    }


    public function changePasswordUpdate(Request $request){
    	      
        $this->validateForm($request);

        $user = $this->findModelUser($request->get('id'));

        $user->update([
            'username'               => $request->get('username'),
            'password'				 => md5($request->get('password')),
        ]);

		$request->session()->flash('message', 'Username \ Password changed successfully');

        return redirect()->to(\Session::get('url.intended'));

    }


    public function loginAs($id, Request $request)
    {

        $user = $this->findModelUser($id);

        $request->session()->put('user_show', true);
        
        Auth::login($user);
        
        return redirect('/index');
    }

    public function loginAsFirstLoggedIn(Request $request){

        $token = $request->session()->get('user_token');

        $user = User::where('token', $token)->get()->first();

        $request->session()->put('user_show', false);
        
        Auth::login($user);
        
        return redirect('/index');

    }

    protected function findModelUser($id){

        if(User::find($id)===null)
        {
            abort(404,'Page not found');
        }else{
            return $model = User::find($id);
        }
    }

    protected function validateForm(Request $request){

            $attributes = array(
                'username' => 'Username',
                'password' => 'Password',
                're_password' => 'Confrim Password'
            );

            $this->validate($request, [
                'username' => 'required|string|unique:users,username,'.$request->get('id'),
                'password' => 'required|string',
                're_password' => 'required|same:password'
            ],array(), $attributes);
	}

}