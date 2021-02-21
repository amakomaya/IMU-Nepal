<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Imports\HealthProfessionalImport;
use App\Imports\HealthProfessionalVaccinatedImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;


class BackupRestoreController extends Controller
{
    public function index()
    {
        return view('backend.backup-restore.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_path' => 'required | mimes:xls,xlsx',
        ]);
        $pathname = $request->file_path->getRealPath();

        if ($request->import_type === '1'){
            Excel::import(new HealthProfessionalImport, $pathname);
        }

        if ($request->import_type === '2'){
            Excel::import(new HealthProfessionalVaccinatedImport, $pathname);
        }

        return redirect()->back()->with('success', 'All Excel Data Uploaded!');

    }
}