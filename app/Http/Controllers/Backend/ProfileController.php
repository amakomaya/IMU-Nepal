<?php

namespace App\Http\Controllers\Backend;

use App\Models\Center;
use App\Models\DistrictInfo;
use App\Models\MunicipalityInfo;
use App\Models\ProvinceInfo;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Organization;

class ProfileController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->role == 'main'){
            return redirect('/admin');
        }
        switch ($user->role) {
            case 'center':
            $data = Center::where('token', $user->token)->first();
            break;
            
            case 'province':
            $data = ProvinceInfo::where('token', $user->token)->first();
            break;
            
            case 'municipality':
            $data = MunicipalityInfo::where('token', $user->token)->first();
            break;

            case 'dho':
            $data = DistrictInfo::where('token', $user->token)->first();
            break;

            case 'healthpost':
            $data = Organization::where('token', $user->token)->first();
            break;

            default:
            $data = [];
            break;
        }
        return view('backend.profile.edit', compact('user', 'data'));
    }

    public function update($id, Request $request)
    {
        $user = auth()->user();
        if ($user->role == 'main'){
            return redirect('/admin');
        }
        $customMessages = [
            'required' => 'The :attribute field is required.',
            'confirmed' => 'The :attribute must equal to confirm new password.'
        ];

        $request->validate([
            'phone' => 'required',
            'password' => 'confirmed|min:4|nullable',
        ], $customMessages);
        switch ($user->role) {
            case 'center':
                $data = Center::where('token', $user->token)->first();
                $data->update([
                'phone' => $request->phone,
                'name' => $request->name,
                'office_address' => $request->tole
                    ]);
                break;
            case 'province':
                $data = ProvinceInfo::where('token', $user->token)->first();
                $data->update([
                    'phone' => $request->phone,
                    'office_address' => $request->tole
                ]);
                break;
            case 'municipality':
                $data = MunicipalityInfo::where('token', $user->token)->first();
                $data->update([
                    'phone' => $request->phone,
                    'office_address' => $request->tole
                ]);
                break;
            case 'dho':
                $data = DistrictInfo::where('token', $user->token)->first();
                $data->update([
                    'phone' => $request->phone,
                    'office_address' => $request->tole
                ]);
                break;
            case 'healthpost':
                $data = Organization::where('token', $user->token)->first();
                $data->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->tole,
                    'no_of_beds' => $request->get('no_of_beds'),
                    'no_of_ventilators' => $request->get('no_of_ventilators'),
                    'no_of_icu' => $request->get('no_of_icu'),
                    'no_of_hdu' => $request->get('no_of_hdu'),
                ]);
                break;
            default:
                break;
        }
        $user = User::where('token', $user->token)->first();
        if (trim($request->password != '')) {
            $user->password = md5($request->password);
        }
        $user->email = $request->email;
        $user->save();
        $request->session()->flash('message', 'Successfully updated your account\'s information !');
        return redirect()->back();
    }
}