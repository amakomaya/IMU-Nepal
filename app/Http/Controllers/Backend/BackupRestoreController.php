<?php


namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Imports\HealthProfessionalImport;
use App\Imports\HealthProfessionalVaccinatedImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\SQLiteConnection;
use App\Models\SuspectedCase;
use App\Models\SampleCollection;
use App\Models\LabTest;


class BackupRestoreController extends Controller
{
    public function index()
    {
        return view('backend.backup-restore.index');
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'file_path' => 'required | mimes:xls,xlsx'
            ]);
        }catch(\Exception $e){
            
        }


//        $pathname = $request->file_path->getRealPath();



        $path1 = $request->file('file_path')->store('temp');
        $pathname=storage_path('app').'/'.$path1;

        if ($request->import_type === '1'){
            Excel::import(new HealthProfessionalImport, $pathname);
        }

        if ($request->import_type === '2'){
            Excel::import(new HealthProfessionalVaccinatedImport, $pathname);
        }

        if ($request->import_type === '3'){
            $this->importSampleInfoData($pathname);
        }

        return redirect()->back()->with('success', 'All Data Uploaded!');

    }

    private function importSampleInfoData($pathname)
    {
        $connection = new SQLiteConnection(new \PDO('sqlite:' . $pathname));
        $builder = new Builder($connection);

        try {
        $errors = [];
        $success = [];
        $suspectedCase = $builder->newQuery()->from('patient')->get();
        $sampleCollection = $builder->newQuery()->from('sample_collection')->get();
        $labTest = $builder->newQuery()->from('lab_test')->get();
    
        try {
            $suspectedCase->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'sync', 'update_status'])->all();
                SuspectedCase::updateOrCreate([
                    'token' => $data['token']
                ], $data);
            });
            array_push($success, "Suspected Case");
        } catch (Exception $e) {
            array_push($errors, "Suspected Case");
        }

        try {
            $sampleCollection->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'sync', 'update_status'])->all();
                try{
                    SampleCollection::updateOrCreate([
                        'token' => $data['token'],
                        'woman_token' => $data['woman_token']
                    ], $data);
                }catch(\Exception $e){
                    array_push($errors, "Sample Collection");
                }
            });
            array_push($success, "Sample Collection");
        } catch (Exception $e) {
            array_push($errors, "Sample Collection");
        }

        try {
            $labTest->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();
                LabTest::updateOrCreate([
                    'token' => $data['token'],
                ], $data);
            });
            array_push($success, "Lab Test");
        } catch (Exception $e) {
            array_push($errors, "Lab Test");
        }

        } catch (Exception $e) {
            $request->session()->flash('error', "Error on uploading. Please retry !");
        }

    }
}