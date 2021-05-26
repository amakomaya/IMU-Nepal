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
        if($request->file('file_path') == null){
            return redirect()->back()->with('error', 'Error on uploading. Please select file !');
        }

        $path1 = $request->file('file_path')->store('temp');
        $pathname=storage_path('app').'/'.$path1;

        if ($request->import_type === '1'){
            return $this->importSampleInfoData($pathname);
        }
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
                $case = SuspectedCase::where('token', $data['token'])->first();
                    if ($case !== null) {
                        $case->update($data);
                    } else {
                        SuspectedCase::create($data);
                    }
            });
            array_push($success, "Suspected Case");
        } catch (\Exception $e) {
            array_push($errors, "Suspected Case");
        }

        try {
            $sampleCollection->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'sync', 'update_status'])->all();
                try{
                    $samp_collect = SampleCollection::where('token', $data['token'])->first();
                    if ($samp_collect !== null) {
                        $samp_collect->update($data);
                    } else {
                        SampleCollection::create($data);
                    }

                }catch(\Exception $e){
                    // array_push($errors, "Sample Collection");
                }
            });
            array_push($success, "Sample Collection");
        } catch (\Exception $e) {
            array_push($errors, "Sample Collection");
        }

        try {
            $labTest->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();
                $lab = LabTest::where('token', $data['token'])->first();

                if ($lab !== null) {
                    $lab->update($data);
                } else {
                    LabTest::create($data);
                }
            });
            array_push($success, "Lab Test");
        } catch (\Exception $e) {
            array_push($errors, "Lab Test");
        }
            return redirect()->back()->with('success', 'All Data Uploaded!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error on uploading. Please retry !');
        }
    }
}