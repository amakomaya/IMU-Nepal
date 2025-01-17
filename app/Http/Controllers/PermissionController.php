<?php
namespace App\Http\Controllers;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $permissions = Permission::all();
        return view('permissions.index',compact('permissions'));
    }

    public function userByPermission(){
        $users = User::whereIn('role', ['healthpost', 'healthworker'])->get();
        return view('permissions.user-by-permission', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::get(); //Get all roles
        return view('permissions.create',compact('roles'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name'=>'required|max:64',
        ]);
        $permission = new Permission();
        $permission->name = $request->name;
        $permission->save();
        return redirect()->route('permissions.index')->with('success','Permission added successfully');
    }

    public function edit(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Permission $permission)
    {
        $this->validate($request, [
            'name'=>'required',
        ]);
        $permission->name=$request->name;
        $permission->save();
        return redirect()->route('permissions.index')
            ->with('success',
                'Permission '. $permission->name.' updated!');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Permission  $permission
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('permissions.index')
            ->with('success',
                'Permission deleted successfully!');
    }
}