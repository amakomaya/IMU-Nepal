<?php

namespace App\Http\Controllers\Hmis;

use App\Models\Healthpost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VaccinationProgramController extends Controller
{
    public function send(Request $appRequest)
    {
        if (empty($hp_code = $appRequest->get('hp_code'))) {
            $appRequest->session()->flash('error', "Please Select Health Post");
            return redirect()->back();
        }

        $orgUnit = Healthpost::where('hp_code', $hp_code)->first()->hmis_uid;

        $json = json_encode([
            'dataSet' => 'padSuQDK1pQ',
            'completeDate' => Carbon::now()->addYears(56)->addMonths(8)->addDays(15)->format(('Y-m-d')),
            'period' => $appRequest['period'],
            'orgUnit' => $orgUnit,
            'dataValues' => $this->dataValues($appRequest->all())
        ]);

        $username = $appRequest['hmisUsername'];
        $password = $appRequest['hmisPassword'];
        $url = env('HMIS_BASE_URL', 'http://192.168.50.55:8080').'/api/dataValueSets';

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json))
        );
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        $result = curl_exec($ch);

        if (!curl_errno($ch)) {
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        if (!empty($status_code)) {
            if ($status_code == 401) {
                $appRequest->session()->flash('error', "Authentication Failed");
                return redirect()->back();
            }
        }

        curl_close($ch);
        $data = json_decode($result, true);

        if(isset($data['importConflicts'])){
            return "Conflict"."<br/>";
        }elseif(isset($data['conflicts'])){
            $appRequest->session()->flash('error', "Organisation unit not found or not accessible");
            return redirect()->back();
        }elseif(empty($data)){
            $appRequest->session()->flash('error', "तपाईको अनुरोध पुरा भएन, कृपया फेरि प्रयास गर्नुहोस");
            return redirect()->back();
        }else {
            $appRequest->session()->flash('success', "तपाईको डाटा HMIS ( DHIS2 ) Server मा सुरक्षित भएको छ।");
            return redirect()->back();
        }
    }

    private function dataValues($data)
    {

        $dataValues = [

            ['dataElement' => 'w84aFW1LESM', 'value' => $data['bcgFirst']],
            ['dataElement' => 'S4NHzUtxuFr', 'value' => $data['pvFirst']],
            ['dataElement' => 'EWloIcvmV6J', 'value' => $data['pvSecond']],
            ['dataElement' => 'BKLqlVqwTX9', 'value' => $data['pvThird']],
            ['dataElement' => 'j0Bqqi6vw0a', 'value' => $data['opvFirst']],
            ['dataElement' => 'iIBI3R4j7rR', 'value' => $data['opvSecond']],
            ['dataElement' => 'SZXbOZ7nCcT', 'value' => $data['opvThird']],
            ['dataElement' => 'F3fBb33oh1c', 'value' => $data['pcvFirst']],
            ['dataElement' => 'TkfynUxlyEA', 'value' => $data['pcvSecond']],
            ['dataElement' => 'PZet2gxoVk4', 'value' => $data['pcvThird']],
            ['dataElement' => 'XZZ0Lbe79KT', 'value' => $data['rvFirst']],
            ['dataElement' => 'a6M1cxcfKOC', 'value' => $data['rvSecond']],
            ['dataElement' => 'W4MXIx0utSp', 'value' => $data['fipvFirst']],
            ['dataElement' => 'HHg41AN9x3a', 'value' => $data['fipvSecond']],
            ['dataElement' => 'ZOssey90Zzq', 'value' => $data['mrFirst']],
            ['dataElement' => 'LLqzacIY1IW', 'value' => $data['mrSecond']],
            ['dataElement' => 'ZVZtO6UfQ85', 'value' => $data['jeFirst']],
            ['dataElement' => 'irb2bKCrM2r', 'value' => $data['pvThirdAndOpvThridAfterOneYear']],
            ['dataElement' => 'vvjFCmI0DBV', 'value' => $data['tdFirst']],
            ['dataElement' => 'T6HLuCqpOMn', 'value' => $data['tdSecond']],
            ['dataElement' => 'q0XkNvBOqZc', 'value' => $data['tdThrid']],

            ['dataElement' => 't9R5Y3uzJTx', 'value' => $data['bcgReceived']],
            ['dataElement' => 'QtwhOIOxPwA', 'value' => $data['pentavalentReceived']],
            ['dataElement' => 'rGLfRwhOjlQ', 'value' => $data['opvReceived']],
            ['dataElement' => 'DcEuBApISkR', 'value' => $data['pcvReceived']],
            ['dataElement' => 'KamjT3aFCc4', 'value' => $data['rotaReceived']],
            ['dataElement' => 'hiGYtGNLJtD', 'value' => $data['fipvReceived']],
            ['dataElement' => 'J4hFbjdq6JD', 'value' => $data['mrReceived']],
            ['dataElement' => 'Gbg5LIipiRD', 'value' => $data['jeReceived']],
            ['dataElement' => 'f9FaQ3GKgX0', 'value' => $data['tdReceived']],

            ['dataElement' => 'kmZ5oht6i4H', 'value' => $data['bcgExpense']],
            ['dataElement' => 'iAXJGGhHbT2', 'value' => $data['pentavalentExpense']],
            ['dataElement' => 'rLMrwKa4ke1', 'value' => $data['opvExpense']],
            ['dataElement' => 'BtKyLr9sKvr', 'value' => $data['pcvExpense']],
            ['dataElement' => 'QbmVzD1DUAy', 'value' => $data['rotaExpense']],
            ['dataElement' => 'NqcErbLQZBP', 'value' => $data['fipvExpense']],
            ['dataElement' => 'lEu3B3Ta0Tr', 'value' => $data['mrExpense']],
            ['dataElement' => 's0JU9IPFO5W', 'value' => $data['jeExpense']],
            ['dataElement' => 'HlEQhPWaIMB', 'value' => $data['tdExpense']],

            ['dataElement' => 'JICTjqC14FH', 'value' => $data['aefi_bcg']],
            ['dataElement' => 'RXrEBVQx9CI', 'value' => $data['aefi_pentavalent']],
            ['dataElement' => 'LSqtugKnf3R', 'value' => $data['aefi_opv']],
            ['dataElement' => 'ARIOZ27POtn', 'value' => $data['aefi_pcv']],
            ['dataElement' => 'h1E7AJzatMg', 'value' => $data['aefi_rota']],
            ['dataElement' => 'dsMF4jwaRpG', 'value' => $data['aefi_fipv']],
            ['dataElement' => 'fmSYcMOvGr9', 'value' => $data['aefi_mr']],
            ['dataElement' => 'aCd3Rmq4zLp', 'value' => $data['aefi_je']],
            ['dataElement' => 'N5pk8EuYqeZ', 'value' => $data['aefi_td']]

        ];
        return $dataValues;
    }
}
