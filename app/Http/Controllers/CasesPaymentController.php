<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CasesPaymentController extends Controller
{
    public function sendToDhis(Request $request){

        $orgUnit = \App\Models\Organization::where('token', \auth()->user()->token)->first()->hmis_uid;

        $req = $request->all();

        $json = json_encode([
            'dataSet' => 'EZA8TZsaRMA',
            'completeDate' => \Carbon\Carbon::now()->format(('Y-m-d')),
            'period' => $req['period'],
            'orgUnit' => $orgUnit,
            'dataValues' => $this->dataValues($req)
        ]);

        $username = 'imu.user';
        $password = 'Hmis@1234';
        $url = env('HMIS_BASE_URL', 'http://hmis.gov.np/hmisadditional').'/api/dataValueSets';

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
                $request->session()->flash('error', "Authentication Failed");
                return redirect()->back();
            }
        }

        curl_close($ch);
        $data = json_decode($result, true);

        if(isset($data['importConflicts'])){
            return "Conflict"."<br/>";
        }elseif(isset($data['conflicts'])){
            $request->session()->flash('error', "Organisation unit not found or not accessible");
            return redirect()->back();
        }elseif(empty($data)){
            $request->session()->flash('error', "तपाईको अनुरोध पुरा भएन, कृपया फेरि प्रयास गर्नुहोस");
            return redirect()->back();
        }else {
            $request->session()->flash('success', "तपाईको डाटा HMIS ( DHIS2 ) Server मा सुरक्षित भएको छ।");
            return redirect()->back();
        }
    }

    private function dataValues(array $data)
    {
        return [
            ['dataElement' => 'xsKRc9OwBof', 'value' => $data['total_beds_allocated_general']],
            ['dataElement' => 'TmxN1Fi6AO8', 'value' => $data['total_beds_allocated_icu']],
            ['dataElement' => 'oHAzqKL4JRN', 'value' => $data['total_beds_allocated_ventilators_among_icu']],
            ['dataElement' => 'ukRwct4JOlH', 'value' => $data['total_beds_allocated_general'] + $data['total_beds_allocated_icu']],

            ['dataElement' => 'N2OMXZJ68XO', 'value' => $data['total_patients_without_symptoms'] +
                                                        $data['total_patients_with_mild_symptoms'] +
                                                        $data['total_patients_with_moderate_symptoms'] +
                                                        $data['total_patients_with_severe_symptoms_in_icu'] +
                                                        $data['total_patients_with_severe_symptoms_in_ventilator']],
            ['dataElement' => 'Uy86LpDFshY', 'value' => $data['total_patients_without_symptoms']],
            ['dataElement' => 'p9wLvWHjSmr', 'value' => $data['total_patients_with_mild_symptoms']],
            ['dataElement' => 'RpwIOThjK2h', 'value' => $data['total_patients_with_moderate_symptoms']],
            ['dataElement' => 'aVLYA6O4XF2', 'value' => $data['total_patients_with_severe_symptoms_in_icu']],
            ['dataElement' => 'Wt7cbwJG5Hm', 'value' => $data['total_patients_with_severe_symptoms_in_ventilator']],

            ['dataElement' => 'Zbj14a4QxlU', 'value' => $data['free_patients_without_symptoms'] +
                                                        $data['free_patients_with_mild_symptoms'] +
                                                        $data['free_patients_with_moderate_symptoms'] +
                                                        $data['free_patients_with_severe_symptoms_in_icu'] +
                                                        $data['free_patients_with_severe_symptoms_in_ventilator']],
            ['dataElement' => 'y7nnQwco1hi', 'value' => $data['free_patients_without_symptoms']],
            ['dataElement' => 'jheH7yk9jRb', 'value' => $data['free_patients_with_mild_symptoms']],
            ['dataElement' => 'OZOXfHR8nhF', 'value' => $data['free_patients_with_moderate_symptoms']],
            ['dataElement' => 'EEPlqfl2o01', 'value' => $data['free_patients_with_severe_symptoms_in_icu']],
            ['dataElement' => 'zhewkaBacjC', 'value' => $data['free_patients_with_severe_symptoms_in_ventilator']],

            ['dataElement' => 'OxJq8yRfB2I', 'value' => $data['total_admissions']],
            ['dataElement' => 'Kb0V8ZusyfB', 'value' => $data['total_discharge']],
            ['dataElement' => 'B9C4XJrCji2', 'value' => $data['total_deaths']],

            ['dataElement' => 'JkiE01qR3RE', 'value' => $data['free_admissions']],
            ['dataElement' => 'lJdBm4JQbxu', 'value' => $data['free_discharge']],
            ['dataElement' => 'qj1x5V3Zc7d', 'value' => $data['free_deaths']]
        ];
    }
}
