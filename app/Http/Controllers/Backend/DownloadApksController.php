<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Str;
use App\Models\ApkManagement;

class DownloadApksController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $data = ApkManagement::latest()->get();
        return view('backend.download-apks.index', compact('data'));
    }

    public function store(Request $request)
    {
        $filename = Str::replaceFirst(" ","-", $request->name)."-".time().".".$request->apk_path->extension();
        $request->apk_path->storeAs('public/apks', $filename);
        ApkManagement::create([
            'name'            => $request->name,
            'is_in_google_play'      => $request->status,
            'apk_path'               => $filename,
        ]);
        $request->session()->flash('message', 'Data Inserted successfully');
        return redirect()->back();
    }

    public function destroy($id, Request $request)
    {
        $data = ApkManagement::findOrFail($id);
        if($data->apk_path!=""){
            unlink(storage_path('app/public/apks/'.$data->apk_path));
        }
        $data->delete();
        $request->session()->flash('message', 'Data Deleted successfully');
        return redirect()->back();
    }
}
