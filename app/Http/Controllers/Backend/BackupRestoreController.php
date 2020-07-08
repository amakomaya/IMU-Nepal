<?php


namespace App\Http\Controllers\Backend;


use App\Models\Aefi;
use App\Models\Anc;
use App\Models\BabyDetail;
use App\Models\BabyWeight;
use App\Models\Delivery;
use App\Models\LabTest;
use App\Models\Pnc;
use App\Models\VaccinationRecord;
use App\Models\VaccineVialStock;
use App\Models\VialDetail\VialDetail;
use App\Models\Woman;
use App\Models\Woman\Vaccination;
use Exception;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class BackupRestoreController
{
    public function index()
    {
        return view('backend.backup-restore.index');
    }

    public function store(Request $request)
    {

        if (Auth::user()->role == 'main') {
            $request->validate([
                'file_path' => 'required | regex : /.+\.db$/',
                'type' => 'required'
            ]);
            $pathname = public_path() . $request->file_path;
        } else {
            $request->validate([
                'file_path' => 'required',
                'type' => 'required'
            ]);
            $pathname = $request->file_path->getPathname();
            $filename = time() . Auth::user()->name . $request->file_path->getClientOriginalName();
            $path = public_path('files/1/Backup/FromUser/' . $filename);
            if (!\File::isDirectory($path)) {
                \File::makeDirectory($path, 0777, true, true);
            }
            \Storage::disk('local')->putFileAs(
                $path,
                $request->file_path,
                $filename
            );
        }

        $connection = new SQLiteConnection(new \PDO('sqlite:' . $pathname));
        $builder = new Builder($connection);

        switch ($request->type) {
            case 1:
                try {
                    $response = $this->amakomayaCareRestore($builder);
                    if (count($response['errors']) > 0) {
                        $request->session()->flash('error', "Error in " . implode(", ", $response['errors']) . " data");
                    }
                    if (count($response['success']) > 0) {
                        $request->session()->flash('success', implode(", ", $response['success']) . " data successfully uploaded");
                    }
                } catch (Exception $e) {
                    $request->session()->flash('error', "Error on uploading. Please retry !");
                }
                break;
            case 2:
                try {
                    $response = $this->vialToChildRestore($builder);
                    if (count($response['errors']) > 0) {
                        $request->session()->flash('error', "Error in " . implode(", ", $response['errors']) . " data");
                    }
                    if (count($response['success']) > 0) {
                        $request->session()->flash('success', implode(", ", $response['success']) . " data successfully uploaded");
                    }
                } catch (Exception $e) {
                    $request->session()->flash('error', "Error on uploading. Please retry !");
                }
                break;

            default:
                return redirect()->back();
        }

        return redirect()->back();

    }

    private function amakomayaCareRestore($builder)
    {

        //==== current issue ======
        // 1. Health post code not found in delivery table
        // 2. Woman token not found in woman vaccine table


        //=========

        $get_health_post_code = $builder->newQuery()->from('woman')->first()->hp_code;
        $errors = [];
        $success = [];
        $woman = $builder->newQuery()->from('woman')->get();
        $ancs = $builder->newQuery()->from('anc')->get();
        $delivery = $builder->newQuery()->from('delivery')->get();
        $delivery_details = $builder->newQuery()->from('delivery_details')->get();
        $pncs = $builder->newQuery()->from('pnc_visit_details')->get();
        $lab_tests = $builder->newQuery()->from('lab_tests')->get();
        $woman_vaccine = $builder->newQuery()->from('woman_vaccine')->get();

        try {
            $woman->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'sync', 'update_status'])->all();
                Woman::updateOrCreate([
                    'token' => $data['token']
                ], $data);

            });
            array_push($success, "Woman Information");
        } catch (Exception $e) {
            array_push($errors, "Woman Information");
        }

        try {
            $ancs->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();
                Anc::updateOrCreate([
                    'token' => $data['token'],
                    'woman_token' => $data['woman_token'],
                    'visit_date' => $data['visit_date']
                ], $data);
            });
            array_push($success, "Ancs");
        } catch (Exception $e) {
            array_push($errors, "Ancs");
        }

        try {
            $delivery->map(function ($item, $key) use ($get_health_post_code) {
                $item->hp_code = $get_health_post_code;
                $data = collect($item)->except(['_id', 'sync', 'update_status'])->all();
                Delivery::updateOrCreate([
                    'token' => $data['token'],
                    'woman_token' => $data['woman_token']
                ], $data);
            });
            array_push($success, "Delivery");
        } catch (Exception $e) {
            array_push($errors, "Delivery");
        }


        try {
            $delivery_details->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();
                BabyDetail::updateOrCreate(
                    ['token' => $data['token']], $data);
            });
            array_push($success, "Delivery details");
        } catch (Exception $e) {
            array_push($errors, "Delivery details");
        }

        try {
            $pncs->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'regdev', 'sync', 'update_status'])->all();
                Pnc::updateOrCreate([
                    'token' => $data['token'],
                    'woman_token' => $data['woman_token']
                ], $data);
            });
            array_push($success, "Pncs");
        } catch (Exception $e) {
            array_push($errors, "Pncs");
        }

        try {
            $lab_tests->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'regdev', 'sync_status', 'update_status'])->all();
                LabTest::updateOrCreate([
                    'token' => $data['token'],
                    'woman_token' => $data['woman_token']
                ], $data);
            });
            array_push($success, "Lab test");
        } catch (Exception $e) {
            array_push($errors, "Lab test");
        }

        try {
            $woman_vaccine->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'vaccine_id', 'sync_status', 'update_status'])->all();
                if (!array_key_exists('token', $data)) {
                    $data['token'] = "restore-token-error" . uniqid();
                }
                Vaccination::updateOrCreate([
                    'token' => $data['token'],
                    'woman_token' => $data['woman_token']
                ], $data);
            });
            array_push($success, "Woman vaccine info");
        } catch (Exception $e) {
            array_push($errors, "Woman vaccine info");
        }

        return [
            'success' => $success,
            'errors' => $errors
        ];
    }

    private function vialToChildRestore($builder)
    {
        $errors = [];
        $success = [];
        $baby = $builder->newQuery()->from('baby')->get();
        $vial_details = $builder->newQuery()->from('vial_details')->get();
        $vaccination = $builder->newQuery()->from('vaccination')->get();
        $vaccine_stock = $builder->newQuery()->from('vaccine_vial_stock')->get();
        $baby_aefi = $builder->newQuery()->from('baby_aefi')->get();
        $baby_weight = $builder->newQuery()->from('baby_weight')->get();






        try {
            $baby->map(function ($item, $key) {
                $item->baby_name = $item->name;
                $item->token = $item->baby_token;
                $data = collect($item)->except(['_id', 'name', 'baby_token', 'sync_status', 'update_status'])->all();
                // BabyDetail::updateOrCreate(['token' => $data['token']], $data);
                // $baby = BabyDetail::where('token', '=', $data['token'])->first();
                // if (count($baby) > 0) {
                //     BabyDetail::where('token', $data['token'])->update($baby->only((new BabyDetail())->getFillable()));    
                // }else{
                //     BabyDetail::create($data);
                // }
                DB::table('baby_details')
                    ->updateOrInsert(
                        ['token' => $data['token']],
                        $data
                    );
            });
            array_push($success, "Baby");
        } catch (Exception $e) {
            array_push($errors, "Baby");
        }

        try {
            $vial_details->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'sync_status', 'update_status'])->all();
                VialDetail::updateOrCreate([
                    'vial_image' => $data['vial_image'],
                    'hp_code' => $data['hp_code'],
                    'vaccine_name' => $data['vaccine_name']
                ], $data);
            });
            array_push($success, "Vial Details");
        } catch (Exception $e) {
            array_push($errors, "Vial Details");
        }

        try {
            $vaccination->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'sync_status', 'update_status'])->all();

                VaccinationRecord::updateOrCreate(
                    ['baby_token' => $data['baby_token'], 'vaccine_name' => $data['vaccine_name'], 'vaccine_period' => $data['vaccine_period']], $data);
            });
            array_push($success, "Vaccination Record");
        } catch (Exception $e) {
            array_push($errors, "Vaccination Record");
        }

        try {
            $vaccine_stock->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'update_status'])->all();
                VaccineVialStock::updateOrCreate([
                    'token' => $data['token'],
                    'name' => $data['name']
                ], $data);
            });
            array_push($success, "Vaccine stock");

        } catch (Exception $e) {
            array_push($errors, "Vaccine stock");
        }

        try {
            $baby_aefi->map(function ($item, $key) {
                $data = collect($item)->except(['_id', 'sync_status', 'update_status'])->all();

                Aefi::updateOrCreate(
                    ['token' => $data['token'],
                        'baby_token' => $data['baby_token']
                    ], $data);
            });
            array_push($success, "AEFI");
        } catch (Exception $e) {
            array_push($errors, "AEFI");
        }

        try {
            $baby_weight->map(function ($item, $kecy) {
                $data = collect($item)->except(['_id', 'sync_status', 'update_status'])->all();

                BabyWeight::updateOrCreate(
                    ['token' => $data['token'], 'baby_token' => $data['baby_token']], $data);
            });
            array_push($success, "Baby weight");

        } catch (Exception $e) {
            array_push($errors, "Baby weight");
        }

        return [
            'success' => $success,
            'errors' => $errors
        ];
    }
}