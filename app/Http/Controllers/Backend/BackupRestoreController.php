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
use App\Models\Symptoms;
use App\Models\ClinicalParameter;
use App\Models\LaboratoryParameter;
use App\Models\ContactTracing;
use App\Models\CaseManagement;
use App\Models\ContactDetail;
use App\VialDetail;


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
            $symptoms = $builder->newQuery()->from('symptoms')->get();
            $clinical_parameters = $builder->newQuery()->from('clinical_parameter')->get();
            $laboratory_parameters = $builder->newQuery()->from('laboratory_parameter')->get();
            $contact_tracings = $builder->newQuery()->from('contact_tracing')->get();
            $case_mgmts = $builder->newQuery()->from('case_mgmt')->get();
            $contact_details = $builder->newQuery()->from('contact_detail')->get();
            $vial_details = $builder->newQuery()->from('vial_details')->get();

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

            try {
                $symptoms->map(function ($item, $key) {
                    $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();

                    $symptom = Symptoms::where('token', $data['token'])->first();

                    if ($symptom !== null) {
                        $symptom->update($data);
                    } else {
                        Symptoms::create($data);
                    }
                });
                array_push($success, "Symptoms");
            } catch (\Exception $e) {
                array_push($errors, "Symptoms");
            }

            try {
                $clinical_parameters->map(function ($item, $key) {
                    $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();

                    $clinical_parameter = ClinicalParameter::where('token', $data['token'])->first();

                    if ($clinical_parameter !== null) {
                        $clinical_parameter->update($data);
                    } else {
                        ClinicalParameter::create($data);
                    }
                });
                array_push($success, "Clinical Parameter");
            } catch (\Exception $e) {
                array_push($errors, "Clinical Parameter");
            }

            try {
                $laboratory_parameters->map(function ($item, $key) {
                    $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();

                    $laboratory_parameter = LaboratoryParameter::where('token', $data['token'])->first();

                    if ($laboratory_parameter !== null) {
                        $laboratory_parameter->update($data);
                    } else {
                        LaboratoryParameter::create($data);
                    }
                });
                array_push($success, "Laboratory Parameter");
            } catch (\Exception $e) {
                array_push($errors, "Laboratory Parameter");
            }

            try {
                $contact_tracings->map(function ($item, $key) {
                    $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();

                    $contact_tracing = ContactTracing::where('token', $data['token'])->first();

                    if ($contact_tracing !== null) {
                        $contact_tracing->update($data);
                    } else {
                        ContactTracing::create($data);
                    }
                });
                array_push($success, "Contact Tracing");
            } catch (\Exception $e) {
                array_push($errors, "Contact Tracing");
            }

            try {
                $case_mgmts->map(function ($item, $key) {
                    $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();

                    $case_mgmt = CaseManagement::where('token', $data['token'])->first();

                    if ($case_mgmt !== null) {
                        $case_mgmt->update($data);
                    } else {
                        CaseManagement::create($data);
                    }
                });
                array_push($success, "Case Management");
            } catch (\Exception $e) {
                array_push($errors, "Case Management");
            }

            try {
                $contact_details->map(function ($item, $key) {
                    $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();

                    $contact_detail = ContactDetail::where('token', $data['token'])->first();

                    if ($contact_detail !== null) {
                        $contact_detail->update($data);
                    } else {
                        ContactDetail::create($data);
                    }
                });
                array_push($success, "Contact Detail");
            } catch (\Exception $e) {
                array_push($errors, "Contact Detail");
            }

            try {
                $vial_details->map(function ($item, $key) {
                    $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();
                    VialDetail::create($data);
                });
                array_push($success, "Vial Details");
            } catch (\Exception $e) {
                array_push($errors, "Vial Details");
            }

            return redirect()->back()->with('success', 'All Data Uploaded!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error on uploading. Please retry !');
        }
    }
}