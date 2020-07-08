<?php

namespace App\Http\Controllers\Backend\Woman;


use App\Models\Pnc;
use App\Models\Woman;
use App\Models\Anc;
use App\Models\LabTest;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use PhpParser\Node\Stmt\Return_;

class HealthCardController extends Controller
{
    public  function view($token)
    {
        return view('woman.view');
    }
    public function woman_destroy(Request $request, $token)
    {

        $data= Woman::with('ancs', 'pncs', 'deliveries', 'lab_tests')->where('token',$token)->firstOrfail();

        if ($data->delete()){
        return response()->json([
            'success' => $data
        ]);
        }
    }

    public function woman_edit()
    {
        return view('backend.woman.edit-view');
    }
    public  function woman_update(Request $request, $id)

    {
        //return $request->all();
        $woman = Woman::findOrfail($id);
        $data = $request->all();
        $woman-> fill($data)->save();
        return redirect('admin/woman');
    }

    public function ancs_edit($token)
    {

        $data['ancs'] = Anc::where('token',$token)->firstOrfail();
        //return $data['ancs'];
        return view('backend.woman.edit_ancs')->with($data);
    }

    public  function ancs_update(Request $request, $id)
    {
        //return $request->all();
        $ancs = Anc::findOrfail($id);
        $data = $request->all();
        $ancs-> fill($data)->save();
        return redirect('admin/woman');
    }
    public function ancs_destroy($token)
    {
        $data= Anc::where('token',$token)->firstOrfail();
        $data->delete();
        return redirect()->back();
    }

    public function lab_test_edit($token)
    {
        $data['test'] = LabTest::where('token',$token)->firstOrfail();
        return view('backend.woman.edit_labtest')->with($data);
    }

    public function lab_test_update(Request $request, $id)
    {
        $test = LabTest::findOrFail($id);

        $data = $request->all();

        $test->fill($data)->save();

        return redirect('admin/woman');
    }

    public function labtest_destroy($token)
    {
        $data = LabTest::where('token',$token)->firstOrfail();
        $data->delete();
        return redirect()->back();
    }


    public function delivery_update(Request $request, $id)
    {
        //return $request->all();
        $delivery = Delivery::findOrfail($id);

        //return $delivery;
        $data= $request->all();

        //return $data;

        $delivery ->fill($data)->save();

        return redirect('admin/woman');
    }
    public function delivery_edit($token)
    {
        $data['deliveries'] = Delivery::where('token',$token)->firstOrfail();
       //return $data['deliveries'];
        return view('backend.woman.edit_delivery')->with($data);
    }
    public function delivery_desctory($token)
    {
        $data = Delivery::where('token',$token)->firstOrfail();
        $data->delete();
        return redirect()->back();
    }

    public function pncs_update(Request $request, $id)
    {
        $pncs = Pnc::findOrfail($id);
        $data = $request->all();
        $pncs->fill($data)->save();

        return redirect('admin/woman');
    }
    public function pncs_edit($token)
    {
        $data['pncs'] = Pnc::where('token',$token)->firstOrfail();
        //return $data['pncs'];
        return view('backend.woman.edit_pncs')->with($data);
    }
    public function pncs_destroy($token)
    {
        $data = Pnc::where('token',$token)->firstOrfail();

        $data->delete();

        return redirect()->back();
    }


    public function deleteAll(Request $request)
    {
        $ids = $request->ids;

        Woman::with('ancs', 'pncs', 'deliveries', 'lab_tests')->whereIn('token',explode(",",$ids))->delete();
        return response()->json(['status'=>true,'message'=>"Women deleted successfully."]);

    }

}
