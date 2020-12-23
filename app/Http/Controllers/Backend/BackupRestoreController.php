<?php


namespace App\Http\Controllers\Backend;


use App\Models\Aefi;
use App\Models\SampleCollection;
use App\Models\BabyDetail;
use App\Models\BabyWeight;
use App\Models\Delivery;
use App\Models\LabTest;
use App\Models\Pnc;
use App\Models\VaccinationRecord;
use App\Models\VaccineVialStock;
use App\Models\VialDetail\VialDetail;
use App\Models\SuspectedCase;
use App\Models\Woman\Vaccination;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class BackupRestoreController
{
    public function index()
    {
        return view('backend.backup-restore.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file_path' => 'required',
        ]);
        $pathname = $request->file_path->getPathname();

        $connection = new SQLiteConnection(new \PDO('sqlite:' . $pathname));
        $builder = new Builder($connection);

        try {
            $response = $this->restore($builder);
            if (count($response['errors']) > 0) {
                $request->session()->flash('error', "Error in " . implode(", ", $response['errors']) . " data");
            }
            if (count($response['success']) > 0) {
                $request->session()->flash('success', implode(", ", $response['success']) . " data successfully uploaded");
            }
        } catch (Exception $e) {
            $request->session()->flash('error', "Error on uploading. Please retry !");
        }
        return redirect()->back();
    }

    private function restore($builder)
    {
        $errors = [];
        $success = [];
        $cases = $builder->newQuery()->from('patient')->get();
        $sample_collection = $builder->newQuery()->from('sample_collection')->get();
        $lab_test = $builder->newQuery()->from('lab_test')->get();

        try {
            $cases->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'sync', 'update_status'])->all();
                SuspectedCase::updateOrCreate([
                    'token' => $data['token']
                ], $data);

            });
            array_push($success, "Case Information");
        } catch (Exception $e) {
            array_push($errors, "Case Information");
        }

        try {
            $sample_collection->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();
                SampleCollection::updateOrCreate([
                    'token' => $data['token'],
                    'woman_token' => $data['woman_token'],
                ], $data);
            });
            array_push($success, "Swab Collection");
        } catch (Exception $e) {
            array_push($errors, "Swab Collection");
        }

        try {
            $lab_test->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();
                LabTest::updateOrCreate([
                    'token' => $data['token'],
                    'sample_token' => $data['sample_token'],
                ], $data);
            });
            array_push($success, "Lab Test");
        } catch (Exception $e) {
            array_push($errors, "Lab Test");
        }

        return [
            'success' => $success,
            'errors' => $errors
        ];
    }
}