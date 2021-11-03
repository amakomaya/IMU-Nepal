<?php
use App\Models\LabTest;
use App\Models\SampleCollection;
use App\Models\OrganizationMember;

/*TODO All these checks are giving load to server. Remove after proper integrety check is added to anc table. */
if (! function_exists('organization_get_sample_by_lab_id')) {
    function organization_get_sample_by_lab_id($labId, $sId = null){
        $userToken = auth()->user()->token;
        $healthWorker = OrganizationMember::where('token', $userToken)->first();
        $hpCode = $healthWorker->org_code;
        $organiation_member_tokens = OrganizationMember::where('org_code', $hpCode)->pluck('token');
        $labTokens = [];
        foreach ($organiation_member_tokens as $item) {
            array_push($labTokens, $item."-".$labId);
        }
        if($sId) {
          $find_tests = SampleCollection::whereIn('lab_token', $labTokens)->where('token', $sId)->latest();
        } else {
          $find_tests = SampleCollection::whereIn('lab_token', $labTokens)->latest();
        }
        return $find_tests;
    }
}

