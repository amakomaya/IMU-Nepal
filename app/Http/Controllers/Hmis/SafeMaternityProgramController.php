<?php

namespace App\Http\Controllers\Hmis;

use App\Helpers\ViewHelper;
use App\Models\Healthpost;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SafeMaternityProgramController extends Controller
{
    public function send(Request $appRequest){

        if ($appRequest->has('hmisChecked')){
            // save on database

            // hmisUsername and hmisPassword for future remember and display on next login
        }

        if (empty($hp_code = $appRequest->get('hp_code'))){
            $appRequest->session()->flash('error', "Please Select Health Post");
            return redirect()->back();
        }

        $orgUnit = Healthpost::where('hp_code', $hp_code)->first()->hmis_uid;

        $username = $appRequest['hmisUsername'];
        $password = $appRequest['hmisPassword'];

        $url = env('HMIS_BASE_URL', 'http://192.168.50.55:8080').'/api/dataValueSets';

        $json = json_encode([
            'dataSet' => 'nPhlpWb5Ekl',
            'completeDate' => ViewHelper::convertEnglishToNepali(Carbon::now()->format('Y-m-d')),
            'period' => $appRequest['period'],
            'orgUnit' => $orgUnit,
            'dataValues' => $this->dataValues($appRequest->all())
        ]);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($json))
        );
        curl_setopt($ch, CURLOPT_USERPWD, "$username:$password");

        $result = curl_exec($ch);

        if(!curl_errno($ch)){
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        }

        if (!empty($status_code)) {
            if ($status_code == 401){
                $appRequest->session()->flash('error', "Authentication Failed");
                return redirect()->back();
            }
        }

        curl_close ($ch);
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

            [ 'dataElement' => 'C6ubEqEew8X', 'value' => $data['firstTimeAncVistedAgeLess20']],
            [ 'dataElement' => 'kSnqP4GPOsQ', 'value' => $data['firstTimeAncVistedAgeGraterOrEqual20']],
            [ 'dataElement' => 'vH9Mm6o3LKn', 'value' => $data['AncVisitedAgeLess20FourthMonth']],
            [ 'dataElement' => 'Bf3ebOgYXFL', 'value' => $data['AncVisitedAgeGrater20FourthMonth']],
            [ 'dataElement' => 'RtidqFs7NRc', 'value' => $data['completedAllAncVisitLess20']],
            [ 'dataElement' => 'gNcAqChA90J', 'value' => $data['completedAllAncVisitGrater20']],

            [ 'dataElement' => 'T9SHzXzpwnJ', 'value' => $data['womenDeliveriedWithDoctorAtHealthFacility']],
            [ 'dataElement' => 'GmSYhnEFEPr', 'value' => $data['womenDeliveriedWithDoctorAtHome']],
            [ 'dataElement' => 'WyIabPquKV4', 'value' => $data['womenDeliveriedWithFchvAtHealthFacility']],
            [ 'dataElement' => 'CddyVd7FKmr', 'value' => $data['womenDeliveriedWithFchvAtHome']],

            [ 'dataElement' => 'VdZyJrLrSg5', 'value' => $data['cephalicNormal']],
            [ 'dataElement' => 'rqny3AJuymt', 'value' => $data['shoulderNormal']],
            [ 'dataElement' => 'c6dLz049UWw', 'value' => $data['breechNormal']],
            [ 'dataElement' => 'CtX7O3pbtL3', 'value' => $data['cephalicVacuum_forcep']],
            [ 'dataElement' => 'fQU35FONgPq', 'value' => $data['shoulderVacuum_forcep']],
            [ 'dataElement' => 'NdY1V8YqJDd', 'value' => $data['breechVacuum_forcep']],
            [ 'dataElement' => 'BoPXsSKiPVx', 'value' => $data['cephalicCS']],
            [ 'dataElement' => 'xAulrqfseew', 'value' => $data['shoulderCS']],
            [ 'dataElement' => 'zgNUMf4qP2Z', 'value' => $data['breechCS']],

            [ 'dataElement' => 'UQTYGjWr7wz', 'value' => $data['singleChildMother']],
            [ 'dataElement' => 'cMo5gmSt1yV', 'value' => $data['doubleChildMother']],
            [ 'dataElement' => 'pP3HfFu4Bte', 'value' => $data['tripleMoreChildMother']],
            [ 'dataElement' => 'Yjwho2iruhu', 'value' => $data['singleFemaleChild']],
            [ 'dataElement' => 'w0fvdvU1yuF', 'value' => $data['doubleFemaleChild']],
            [ 'dataElement' => 'ZtwhYCiEQ7w', 'value' => $data['tripleMoreFemaleChild']],
            [ 'dataElement' => 'Yjwho2iruhu', 'value' => $data['singleMaleChild']],
            [ 'dataElement' => 'w0fvdvU1yuF', 'value' => $data['doubleMaleChild']],
            [ 'dataElement' => 'ZtwhYCiEQ7w', 'value' => $data['tripleMoreMaleChild']],

            [ 'dataElement' => 'uzX1NG6xDwr', 'value' => $data['weightMore2500gmBaby']],
            [ 'dataElement' => 'DjglomjSiAu', 'value' => $data['weightMore2500gmBabyAsphyxia']],
            [ 'dataElement' => 'vIuy5zKUj82', 'value' => $data['weightMore2500gmBabyDefect']],
            [ 'dataElement' => 'r1x2daA3pwt', 'value' => $data['weightLess2000to2500gmBaby']],
            [ 'dataElement' => 'stOtnhrYX9J', 'value' => $data['weightLess2000to2500gmBabyAsphyxia']],
            [ 'dataElement' => 'ssjLchkxrC3', 'value' => $data['weightLess2000to2500gmBabyDefect']],
            [ 'dataElement' => 'uGteAfUfOBk', 'value' => $data['weightLess2000gmBaby']],
            [ 'dataElement' => 'DFEHOcOqW5a', 'value' => $data['weightLess2000gmBabyAsphyxia']],
            [ 'dataElement' => 'zaFinXSR0UY', 'value' => $data['weightLess2000gmBabyDefect']],

            [ 'dataElement' => 'k2StfxOzXpx', 'value' => $data['deadFresh']],
            [ 'dataElement' => 'FlyYBxnmKup', 'value' => $data['deadMacerated']],

            [ 'dataElement' => 'XxGpVsZfVPL', 'value' => $data['CHX_appliedInCord']],
            [ 'dataElement' => 'UUZw688ThWK', 'value' => $data['bloodTransfusionNumber']],
            [ 'dataElement' => 'r0HLdqmLwFB', 'value' => $data['bloodTransfusionUnit']],

            [ 'dataElement' => 'b8jGBrMfBbP', 'value' => $data['checkIn24hour']],
            [ 'dataElement' => 'Aw24Ejbp63F', 'value' => $data['pncAll']],

            [ 'dataElement' => 'f4ueiBTKF1T', 'value' => $data['obstetricComplicationsEctopicPregnancy']],
            [ 'dataElement' => 'S4CFTXyEFVc', 'value' => $data['obstetricComplicationsAbortionComplication']],
            [ 'dataElement' => 'JLKFv7K2i7Y', 'value' => $data['obstetricComplicationsPregInducedHypertension']],
            [ 'dataElement' => 'v3zKVR5wJt3', 'value' => $data['obstetricComplicationsSeverePreEclampsia']],
            [ 'dataElement' => 'PELChUNfW2C', 'value' => $data['obstetricComplicationsEclampsia']],
            [ 'dataElement' => 'LIo3j809JrK', 'value' => $data['obstetricComplicationsHyperemesisGrivadarum']],
            [ 'dataElement' => 'ueBJTsv0PXr', 'value' => $data['obstetricComplicationsAntepartumHaemorrhage']],
            [ 'dataElement' => 'wsncKYDcmqr', 'value' => $data['obstetricComplicationsProlongedlabour']],
            [ 'dataElement' => 'Dior21jcQCe', 'value' => $data['obstetricComplicationsObstructedLabor']],
            [ 'dataElement' => 'nwQoDUhFR1z', 'value' => $data['obstetricComplicationsRupturedUterus']],
            [ 'dataElement' => 'PGNd6jcxJX4', 'value' => $data['obstetricComplicationsPostpartumHaemorrhage']],
            [ 'dataElement' => 'kV3JhKJWMR8', 'value' => $data['obstetricComplicationsRetainedPlacenta']],
            [ 'dataElement' => 'dHet49ySm06', 'value' => $data['obstetricComplicationsPuerperalSepsis']],
            [ 'dataElement' => 'XAzAbTRbJve', 'value' => $data['obstetricComplicationsOtherComplications']],

            [ 'dataElement' => 'YZfwWNZzfg0', 'value' => $data['maternalDeathAntepartum']],
            [ 'dataElement' => 'BL5XaUOJ6aw', 'value' => $data['maternalDeathIntrapartum']],
            [ 'dataElement' => 'IMFPhLtATaw', 'value' => $data['maternalDeathPostpartum']],
            [ 'dataElement' => 'bu8MSulaBxg', 'value' => $data['maternalDeathNeonataldeathAtHealthFacility']],

            [ 'dataElement' => 'dZo1PDWPAO1', 'value' => $data['AamaProgramIncentiveTransportNoofWomenEligible']],
            [ 'dataElement' => 'ysTJteUVzO1', 'value' => $data['AamaProgramPregnantWomenReceivedIncentiveOnTransportation']],
            [ 'dataElement' => 'MO4VQOLebuG', 'value' => $data['AamaProgramIncentiveANCNoofWomenEligible']],
            [ 'dataElement' => 'ua0pnAingEU', 'value' => $data['AamaProgramIncentiveANCNumberofWomenReceive']],
            [ 'dataElement' => 'KsMJg5sdGOS', 'value' => $data['SafeAbortionServiceNumberOfWomen20YearsMedical']],
            [ 'dataElement' => 'KkSEy2pfgCD', 'value' => $data['SafeAbortionServiceNumberOfWomen20YearsSurgical']],
            [ 'dataElement' => 'wxIeWrnnZxe', 'value' => $data['SafeAbortionServiceNumberofWomen≥20YearsMedical']],
            [ 'dataElement' => 'ymw1agzYyF3', 'value' => $data['SafeAbortionServiceNumberOfWomen≥20YearsSurgical']],
            [ 'dataElement' => 'BIjA8u1VMF3', 'value' => $data['SafeAbortionServicePostAbortionFPMethodsShortTermMedical']],
            [ 'dataElement' => 'spH6ZRldV3F', 'value' => $data['SafeAbortionServicePostAbortionFPMethodsShorttermSurgical']],
            [ 'dataElement' => 'AP5k6dsqGPI', 'value' => $data['SafeAbortionServicePostAbortionFPMethodsLongtermMedical']],
            [ 'dataElement' => 'WAxqda0KkYt', 'value' => $data['SafeAbortionServicePostAbortionFPMethodsLongtermSurgical']],
            [ 'dataElement' => 'CWlOpEYqaC1', 'value' => $data['SafeAbortionServicePostAbortionComplicationMedical']],
            [ 'dataElement' => 'FKPsRSUnnob', 'value' => $data['SafeAbortionServicePostAbortionComplicationSurgical']],
            [ 'dataElement' => 'Jz96CS4ZOn7', 'value' => $data['SafeAbortionServicePostAbortionCare(PAC)ThisfacilityMedical']],

        ];
        return $dataValues;
    }
}