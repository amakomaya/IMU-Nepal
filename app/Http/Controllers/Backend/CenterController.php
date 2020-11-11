<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Center;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Auth;

class CenterController extends Controller
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        User::checkAuthForCenter();
        $centers = Center::all();
        return view('backend.center.index',compact('centers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        User::checkAuthForCenter();

        return view('backend.center.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        User::checkAuthForCenter();

    	$this->validateForm($request, $scenario="create");

         $province =Center::create([
            'name'            => $request->get('name'),
            'token'               => uniqid().time(),
            'phone'               => $request->get('phone'),
            'office_address'      => $request->get('office_address'),
            'office_longitude'    => $request->get('office_longitude'),
            'office_lattitude'    => $request->get('office_lattitude'),
            'status'              => $request->get('status'),
        ]);

         User::create([
            'token'               => $province->token,
            'username'               => $request->get('username'),
            'email'               => $request->get('email'),
            'password'               => md5($request->get('password')),
            'role'               => "center",
        ]);



        $request->session()->flash('message', 'Data Inserted successfully');

        return redirect()->route('center.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        User::checkAuthForCenter();

        $data = Center::findOrFail($id);
        $user = $this->findModelUser($data->token);        
        return view('backend.center.show',compact('data', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        User::checkAuthForCenter();

        $data = $this->findModelCenter($id);
        $user = $this->findModelUser($data->token);
        return view('backend.center.edit', compact('data','user'));
	}

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        User::checkAuthForCenter();

        $this->validateForm($request, $scenario="update"); 

        $province = $this->findModelCenter($id);        
        
    	$province->update([
            'name'               => $request->get('name'),
            'phone'               => $request->get('phone'),
            'office_address'               => $request->get('office_address'),
            'office_longitude'               => $request->get('office_longitude'),
            'office_lattitude'               => $request->get('office_lattitude'),
            'status'               => $request->get('status'),
		]);


        $user = $this->findModelUser($province->token);

        $user->update([
            'email'               => $request->get('email'),
        ]);

		$request->session()->flash('message', 'Data Updated successfully');

        return redirect()->route('center.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        User::checkAuthForCenter();

        $center = $this->findModelCenter($id);

        $center->delete();

        $user = $this->findModelUser($center->token);

        $user->delete();

        $request->session()->flash('message', 'Data Deleted successfully');

        return redirect()->route('center.index');
    }
	
	protected function validateForm(Request $request, $scenario){

        if($scenario=="create"){
            $attributes = array(
                'name' => 'Center',
                'office_address' => 'office address',
                'status' => 'status',
                'username' => 'Username',
                'password' => 'Password',
                're_password' => 'Confrim Password',
                'email' => 'Email'
            );
            
            $this->validate($request, [
                'name' => 'required|string',
                'office_address' => 'required|string',
                'status' => 'required|string',
                'username' => 'required|string|unique:users',
                'password' => 'required|string',
                're_password' => 'required|same:password',
                'email'=>'nullable|string|email|'
            ],array(), $attributes);

        }else{
            $attributes = array(
                'name' => 'Center',
                'office_address' => 'office address',
                'status' => 'status',
                'email' => 'Email'
            );
            
            $this->validate($request, [
                'name' => 'required|string',
                'office_address' => 'required|string',
                'status' => 'required|string',
                'email'=>'nullable|string|email|'
            ],array(), $attributes);
        }
    }
    
    protected function findModelCenter($id){
        if(Center::find($id)===null){
            abort(404);
        }else{
            return $model = Center::find($id);
        }
    }

    protected function findModelUser($token){
        if(User::where('token', $token)->get()->first()===null){
            abort(404);
        }else{
            return $model = User::where('token', $token)->get()->first();
        }
    }


}
