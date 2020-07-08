<div id="myModal" class="modal fade" role="dialog">

    <div class="modal-dialog" style="width:1124px;">
        <!-- Modal content-->

        <form method="post" action="{{ url('api/hmis/safe-maternity-program') }}">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title text-center">सुरक्षित मातृत्व कार्यक्रम</h4>
                    <p>
                        डाटाहरुलाई सच्चाई HMIS ( DHIS2 ) Server मा पठाउनुहोस्
                    </p>
                    <div class="form-group">
                        <label for="period" class="col-md-2">Select Period:</label>
                        <select class="form-control" style="margin-top: 20px; width: 30%" name="period">
                            @if(!isset($select_year))
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $now_in_nepali = \Yagiten\Nepalicalendar\Calendar::eng_to_nep($now->year, $now->month, $now->day)->getYearMonthDay();
                                @endphp
                                <option value="{{ explode('-' , $now_in_nepali)[0].explode('-' ,$now_in_nepali)[1] }}"
                                        selected> {{ explode('-' ,$now_in_nepali)[0].'/'.explode('-' ,$now_in_nepali)[1] }} </option>
                            @else
                                <option value="{{ $select_year.sprintf("%02d", $select_month) }}"
                                        selected> {{ $select_year.'/'.sprintf("%02d", $select_month) }} </option>
                            @endif
                        </select>
                    </div>
                </div>
                <div class="container">
                    <div class="row">
                        <div class="modal-body">


                            <div class="page" id="mainPage"> <!-- Do not set style attr -->

                                <style type="text/css">


                                    .cde table {
                                        border-collapse: collapse;
                                    }

                                    .cde-COMPACT td, .cde-COMPACT th {
                                        padding: 5px;
                                        border: 1px solid #c0c0c0;
                                    }

                                    div#loaderDiv {
                                        color: #1c425c;
                                        font-size: 14px;
                                        border: 1px solid #9aaab5;
                                        margin-bottom: 15px;
                                        width: 500px;
                                        height: 230px;
                                        text-align: center;
                                        display: none;
                                        border-radius: 3px;
                                    }

                                    td {
                                        padding: 7px;
                                        padding: 5px;
                                        border: 1px solid #c0c0c0;
                                    }

                                    input {
                                        text-align: center;
                                        background-color: rgb(255, 255, 255);
                                        border: 1px solid rgb(170, 170, 170);
                                        padding: 5px;

                                    }

                                    .page {
                                        padding-left: 3%;
                                    }

                                    .footerleft{
                                        padding-left: 4%;
                                    }
                                </style>

                                <table>
                                    <tbody>
                                    <tr>
                                        <td colspan="2"
                                            style="border: 1px solid rgb(0, 0, 0); vertical-align: middle; height: 20px; text-align: center; width: 80px; background-color: rgb(198, 239, 206);">
                                    <span style="font-size:14px;"><b><font
                                                    color="#006100">५. सुरक्षित मातृत्व कार्यक्रम </font></b></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="vertical-align:top;">
                                            <table border="1" cellpadding="0" cellspacing="0" style="width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226)"
                                                        width="123"><strong>गर्भवती सेवा</strong></td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);"
                                                        width="144">
                                                        <div align="center"><strong>&lt; २० बर्ष</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);"
                                                        width="163">
                                                        <div align="center"><strong>≥ २० बर्ष</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">पहिलो पटक गर्भवती जाँच
                                                        गरेका
                                                        महिला
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="C6ubEqEew8X-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['firstTimeAncVistedAgeLess20']}}"
                                                                                   type="text"
                                                                                   name="firstTimeAncVistedAgeLess20"
                                                                                   class="entryfield"
                                                                                   tabindex="1"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="C6ubEqEew8X-dataelement" style="display:none">Safe Motherhood Program-Antenatal Checkup-First ANC Visit (any time)&lt; 20 Years</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="kSnqP4GPOsQ-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['firstTimeAncVistedAgeGrater20']}}"
                                                                                   type="text"
                                                                                   name="firstTimeAncVistedAgeGraterOrEqual20"
                                                                                   class="entryfield"
                                                                                   tabindex="2"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="kSnqP4GPOsQ-dataelement" style="display:none">Safe Motherhood Program-Antenatal Checkup-First ANC Visit (any time)≥ 20 Years</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">चौथो महिनामा गर्भवती जाँच
                                                        गरेका
                                                        महिला
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="vH9Mm6o3LKn-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['AncVisitedAgeLess20FourthMonth']}}"
                                                                                   type="text"
                                                                                   name="AncVisitedAgeLess20FourthMonth"
                                                                                   class="entryfield"
                                                                                   tabindex="3"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="vH9Mm6o3LKn-dataelement" style="display:none">Safe Motherhood Program-Antenatal Checkup-First ANC Visit as per Protocol&lt; 20 Years</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="Bf3ebOgYXFL-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['AncVisitedAgeGrater20FourthMonth']}}"
                                                                                   type="text"
                                                                                   name="AncVisitedAgeGrater20FourthMonth"
                                                                                   class="entryfield"
                                                                                   tabindex="4"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="Bf3ebOgYXFL-dataelement" style="display:none">Safe Motherhood Program-Antenatal Checkup-First ANC Visit as per Protocol≥ 20 Years</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">प्रोटोकल अनुसार ४ पटक
                                                        गर्भवती
                                                        जाँच
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="RtidqFs7NRc-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['completedAllAncVisitLess20']}}"
                                                                                   type="text"
                                                                                   name="completedAllAncVisitLess20"
                                                                                   class="entryfield"
                                                                                   tabindex="5"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="RtidqFs7NRc-dataelement" style="display:none">Safe Motherhood Program-Antenatal Checkup-Four ANC Visits as per Protocol&lt; 20 Years</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="gNcAqChA90J-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['completedAllAncVisitGrater20']}}"
                                                                                   type="text"
                                                                                   name="completedAllAncVisitGrater20"
                                                                                   class="entryfield"
                                                                                   tabindex="6"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="gNcAqChA90J-dataelement" style="display:none">Safe Motherhood Program-Antenatal Checkup-Four ANC Visits as per Protocol≥ 20 Years</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table border="1" cellpadding="0" cellspacing="0" style="width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);"
                                                        width="387"><strong>प्रसूति सेवा</strong></td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);"
                                                        width="75">
                                                        <div align="center"><strong>स्वा. सं.&nbsp;</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);"
                                                        width="30">
                                                        <div align="center"><strong>घर</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">दक्ष प्रसुतिकर्मीबाट</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="T9SHzXzpwnJ-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['womenDeliveriedWithDoctorAtHealthFacility']}}"
                                                                                   type="text"
                                                                                   name="womenDeliveriedWithDoctorAtHealthFacility"
                                                                                   class="entryfield"
                                                                                   tabindex="7"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="T9SHzXzpwnJ-dataelement" style="display:none">Safe Motherhood Program-Delivery Service-Skilled Birth Attendants (SBA)Facility</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="GmSYhnEFEPr-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['womenDeliveriedWithDoctorAtHome']}}"
                                                                                   type="text"
                                                                                   name="womenDeliveriedWithDoctorAtHome"
                                                                                   class="entryfield"
                                                                                   tabindex="8"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="GmSYhnEFEPr-dataelement" style="display:none">Safe Motherhood Program-Delivery Service-Skilled Birth Attendants (SBA)Home</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">अन्य स्वास्थ्यकर्मीबाट
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="WyIabPquKV4-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['womenDeliveriedWithFchvAtHealthFacility']}}"
                                                                                   type="text"
                                                                                   name="womenDeliveriedWithFchvAtHealthFacility"
                                                                                   class="entryfield"
                                                                                   tabindex="9"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="WyIabPquKV4-dataelement" style="display:none">Safe Motherhood Program-Delivery Service-Non-SBA Health Workers Facility</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="CddyVd7FKmr-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['womenDeliveriedWithFchvAtHome']}}"
                                                                                   type="text"
                                                                                   name="womenDeliveriedWithFchvAtHome"
                                                                                   class="entryfield"
                                                                                   tabindex="10"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="CddyVd7FKmr-dataelement" style="display:none">Safe Motherhood Program-Delivery Service-Non-SBA Health Workers Home</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table border="1" cellpadding="0" cellspacing="0" style="width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td rowspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>प्रसूतिको किसिम</strong></td>
                                                    <td colspan="3"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>Presentation</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>Cephalic</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>Shoulder</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>Breech</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">सामान्य</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="VdZyJrLrSg5-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['cephalicNormal']}}"
                                                                                   type="text"
                                                                                   name="cephalicNormal"
                                                                                   class="entryfield"
                                                                                   tabindex="11"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="VdZyJrLrSg5-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-Spontaneous Cephalic</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="rqny3AJuymt-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['shoulderNormal']}}"
                                                                                   type="text"
                                                                                   name="shoulderNormal"
                                                                                   class="entryfield"
                                                                                   tabindex="12"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="rqny3AJuymt-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-Spontaneous Shoulder</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="c6dLz049UWw-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['breechNormal']}}"
                                                                                   type="text"
                                                                                   name="breechNormal"
                                                                                   class="entryfield"
                                                                                   tabindex="13"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="c6dLz049UWw-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-Spontaneous Breech</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">भ्याकुम/फोरसेप</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="CtX7O3pbtL3-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['cephalicVacuum_forcep']}}"
                                                                                   type="text"
                                                                                   name="cephalicVacuum_forcep"
                                                                                   class="entryfield"
                                                                                   tabindex="14"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="CtX7O3pbtL3-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-Vaccum/Forcep Cephalic</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="fQU35FONgPq-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['shoulderVacuum_forcep']}}"
                                                                                   type="text"
                                                                                   name="shoulderVacuum_forcep"
                                                                                   class="entryfield"
                                                                                   tabindex="15"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="fQU35FONgPq-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-Vaccum/Forcep Shoulder</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="NdY1V8YqJDd-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['breechVacuum_forcep']}}"
                                                                                   type="text"
                                                                                   name="breechVacuum_forcep"
                                                                                   class="entryfield"
                                                                                   tabindex="16"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="NdY1V8YqJDd-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-Vaccum/Forcep Breech</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">सल्यक्रिया</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="BoPXsSKiPVx-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['cephalicCS']}}"
                                                                                   type="text"
                                                                                   name="cephalicCS" class="entryfield"
                                                                                   tabindex="17"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="BoPXsSKiPVx-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-C/S Cephalic</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="xAulrqfseew-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['shoulderCS']}}"
                                                                                   type="text"
                                                                                   name="shoulderCS" class="entryfield"
                                                                                   tabindex="18"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="xAulrqfseew-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-C/S Shoulder</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="zgNUMf4qP2Z-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['breechCS']}}"
                                                                                   type="text"
                                                                                   name="breechCS" class="entryfield"
                                                                                   tabindex="19"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="zgNUMf4qP2Z-dataelement" style="display:none">Safe Motherhood Program-Type of Delivery-C/S Breech</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table border="1" cellpadding="0" cellspacing="0" style="width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2" rowspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>प्रसूतिको परिणाम</strong></td>
                                                    <td rowspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>एकल बच्चा</strong></div>
                                                    </td>
                                                    <td colspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>बहु बच्चा</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>जुम्ल्याहा</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>≥ तिम्ल्याहा</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="border-color: rgb(85, 85, 85);">आमाहरुको
                                                        संख्या&nbsp;
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="UQTYGjWr7wz-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['singleChildMother']}}"
                                                                                   type="text"
                                                                                   name="singleChildMother"
                                                                                   class="entryfield"
                                                                                   tabindex="20"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="UQTYGjWr7wz-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-Mother Single</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="cMo5gmSt1yV-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['doubleChildMother']}}"
                                                                                   type="text"
                                                                                   name="doubleChildMother"
                                                                                   class="entryfield"
                                                                                   tabindex="21"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="cMo5gmSt1yV-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-Mother Twin</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="pP3HfFu4Bte-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['tripleMoreChildMother']}}"
                                                                                   type="text"
                                                                                   name="tripleMoreChildMother"
                                                                                   class="entryfield"
                                                                                   tabindex="22"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="pP3HfFu4Bte-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-Mother≥ Triplet</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="2" style="border-color: rgb(85, 85, 85);">जम्मा जीवित
                                                        जन्म
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">महिला</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="Yjwho2iruhu-ye1QuAMRG5Z-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['singleFemaleChild']}}"
                                                                                   type="text"
                                                                                   name="singleFemaleChild"
                                                                                   class="entryfield"
                                                                                   tabindex="23"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="Yjwho2iruhu-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-live Births Single</span><span
                                                                    id="ye1QuAMRG5Z-optioncombo"
                                                                    style="display:none">Female</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="w0fvdvU1yuF-ye1QuAMRG5Z-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['doubleFemaleChild']}}"
                                                                                   type="text"
                                                                                   name="doubleFemaleChild"
                                                                                   class="entryfield"
                                                                                   tabindex="24"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="w0fvdvU1yuF-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-live Births Twin</span><span
                                                                    id="ye1QuAMRG5Z-optioncombo"
                                                                    style="display:none">Female</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="ZtwhYCiEQ7w-ye1QuAMRG5Z-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['tripleMoreFemaleChild']}}"
                                                                                   type="text"
                                                                                   name="tripleMoreFemaleChild"
                                                                                   class="entryfield"
                                                                                   tabindex="25"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="ZtwhYCiEQ7w-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-live Births ≥ Triplet</span><span
                                                                    id="ye1QuAMRG5Z-optioncombo"
                                                                    style="display:none">Female</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">पुरूष</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="Yjwho2iruhu-PflKpozpO7b-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['singleMaleChild']}}"
                                                                                   type="text"
                                                                                   name="singleMaleChild"
                                                                                   class="entryfield"
                                                                                   tabindex="26"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="Yjwho2iruhu-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-live Births Single</span><span
                                                                    id="PflKpozpO7b-optioncombo"
                                                                    style="display:none">Male</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="w0fvdvU1yuF-PflKpozpO7b-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['doubleMaleChild']}}"
                                                                                   type="text"
                                                                                   name="doubleMaleChild"
                                                                                   class="entryfield"
                                                                                   tabindex="27"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="w0fvdvU1yuF-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-live Births Twin</span><span
                                                                    id="PflKpozpO7b-optioncombo"
                                                                    style="display:none">Male</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="ZtwhYCiEQ7w-PflKpozpO7b-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['tripleMoreMaleChild']}}"
                                                                                   type="text"
                                                                                   name="tripleMoreMaleChild"
                                                                                   class="entryfield"
                                                                                   tabindex="28"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="ZtwhYCiEQ7w-dataelement" style="display:none">Safe Motherhood Program-Delivery Outcome-live Births ≥ Triplet</span><span
                                                                    id="PflKpozpO7b-optioncombo"
                                                                    style="display:none">Male</span></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table border="1" cellpadding="0" cellspacing="0" style="width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td rowspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>जन्म तौल</strong></td>
                                                    <td colspan="3"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>जिवित जन्म</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>जम्मा संख्या</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>निसासिएको</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>बिकलांग</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">सामान्य (≥ २.५ के.जी.)
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="uzX1NG6xDwr-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['weightMore250gmBaby']}}"
                                                                                   type="text"
                                                                                   name="weightMore2500gmBaby"
                                                                                   class="entryfield"
                                                                                   tabindex="29"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="uzX1NG6xDwr-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Normal (≥ 2.5 kg)</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="DjglomjSiAu-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="weightMore2500gmBabyAsphyxia"
                                                                                   class="entryfield"
                                                                                   tabindex="30"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="DjglomjSiAu-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Normal (≥ 2.5 kg)Asphyxia</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="vIuy5zKUj82-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="weightMore2500gmBabyDefect"
                                                                                   class="entryfield"
                                                                                   tabindex="31"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="vIuy5zKUj82-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Normal (≥ 2.5 kg)Defect</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">कम (<span
                                                                style="line-height: 20.8px;">१.५</span> - &lt; २.५
                                                        के.जी.)
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="r1x2daA3pwt-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{$data['weightLess200to250gmBaby']}}"
                                                                                   type="text"
                                                                                   name="weightLess2000to2500gmBaby"
                                                                                   class="entryfield"
                                                                                   tabindex="32"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="r1x2daA3pwt-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Low (1.5 to &lt; 2.5 kg)</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="stOtnhrYX9J-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="weightLess2000to2500gmBabyAsphyxia"
                                                                                   class="entryfield"
                                                                                   tabindex="33"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="stOtnhrYX9J-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Low (1.5 to &lt; 2.5 kg)Asphyxia</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="ssjLchkxrC3-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="weightLess2000to2500gmBabyDefect"
                                                                                   class="entryfield"
                                                                                   tabindex="34"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="ssjLchkxrC3-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Low (1.5 to &lt; 2.5 kg)Defect</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">धेरै कम (&lt; १.५
                                                        के.जी.)
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="uGteAfUfOBk-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title=""
                                                                                   value="{{ $data['weightLess200gmBaby'] }}"
                                                                                   type="text"
                                                                                   name="weightLess2000gmBaby"
                                                                                   class="entryfield"
                                                                                   tabindex="35"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="uGteAfUfOBk-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Very low (&lt; 1.5 kg)</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="DFEHOcOqW5a-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="weightLess2000gmBabyAsphyxia"
                                                                                   class="entryfield"
                                                                                   tabindex="36"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="DFEHOcOqW5a-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Very low (&lt; 1.5 kg)Asphyxia</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="zaFinXSR0UY-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="weightLess2000gmBabyDefect"
                                                                                   class="entryfield"
                                                                                   tabindex="37"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="zaFinXSR0UY-dataelement" style="display:none">Safe Motherhood Program-Birth Weight-Very low (&lt; 1.5 kg)Defect</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table>
                                                <tbody>
                                                <tr>
                                                    <td style="vertical-align:top;">
                                                        <table border="1" cellpadding="0" cellspacing="0" width="200">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2"
                                                                    style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                                    <strong>मृत जन्म संख्या</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border-color: rgb(85, 85, 85);">Fresh</td>
                                                                <td style="border-color: rgb(85, 85, 85);"><input
                                                                            id="k2StfxOzXpx-kdsirVNKdhm-val" size="5"
                                                                            title=""
                                                                            value="{{$data['deadFresh']}}" type="text"
                                                                            name="deadFresh"
                                                                            class="entryfield" tabindex="38"
                                                                            style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                            id="k2StfxOzXpx-dataelement"
                                                                            style="display:none">Safe Motherhood Program-Number of Still Births-Fresh</span><span
                                                                            id="kdsirVNKdhm-optioncombo"
                                                                            style="display:none">default</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border-color: rgb(85, 85, 85);">Macerated
                                                                </td>
                                                                <td style="border-color: rgb(85, 85, 85);"><input
                                                                            id="FlyYBxnmKup-kdsirVNKdhm-val" size="5"
                                                                            title=""
                                                                            value="{{$data['deadMacerated']}}"
                                                                            type="text" name="deadMacerated"
                                                                            class="entryfield" tabindex="39"
                                                                            style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                            id="FlyYBxnmKup-dataelement"
                                                                            style="display:none">Safe Motherhood Program-Number of Still Births-Macerated</span><span
                                                                            id="kdsirVNKdhm-optioncombo"
                                                                            style="display:none">default</span>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <table border="1" width="100">
                                                            <tbody>
                                                            <tr>
                                                                <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                                    <strong>नाभी मलम लगाएको</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border-color: rgb(85, 85, 85);"><input
                                                                            id="XxGpVsZfVPL-kdsirVNKdhm-val" size="5"
                                                                            title=""
                                                                            value="0" type="text"
                                                                            name="CHX_appliedInCord"
                                                                            class="entryfield" tabindex="40"
                                                                            style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                            id="XxGpVsZfVPL-dataelement"
                                                                            style="display:none">Safe Motherhood Program-CHX applied in Cord</span><span
                                                                            id="kdsirVNKdhm-optioncombo"
                                                                            style="display:none">default</span>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                    <td style="vertical-align:top;">
                                                        <table border="1" width="165">
                                                            <tbody>
                                                            <tr>
                                                                <td colspan="2"
                                                                    style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                                    <strong>रक्त संचार गरिएका</strong></td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border-color: rgb(85, 85, 85);">महिला</td>
                                                                <td style="border-color: rgb(85, 85, 85);"><input
                                                                            id="UUZw688ThWK-kdsirVNKdhm-val" size="5"
                                                                            title=""
                                                                            value="0" type="text"
                                                                            name="bloodTransfusionNumber"
                                                                            class="entryfield" tabindex="41"
                                                                            style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                            id="UUZw688ThWK-dataelement"
                                                                            style="display:none">Safe Motherhood Program-Blood Transfusion-Number</span><span
                                                                            id="kdsirVNKdhm-optioncombo"
                                                                            style="display:none">default</span>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border-color: rgb(85, 85, 85);">पिन्ट</td>
                                                                <td style="border-color: rgb(85, 85, 85);"><input
                                                                            id="r0HLdqmLwFB-kdsirVNKdhm-val" size="5"
                                                                            title=""
                                                                            value="0" type="text"
                                                                            name="bloodTransfusionUnit"
                                                                            class="entryfield" tabindex="42"
                                                                            style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                            id="r0HLdqmLwFB-dataelement"
                                                                            style="display:none">Safe Motherhood Program-Blood Transfusion-Unit</span><span
                                                                            id="kdsirVNKdhm-optioncombo"
                                                                            style="display:none">default</span>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="3" style="vertical-align:top;">
                                                        <table border="1" cellpadding="0" cellspacing="0"
                                                               style="width: 494px;">
                                                            <tbody>
                                                            <tr>
                                                                <td rowspan="2" style="border-color: rgb(85, 85, 85);">
                                                                    सुत्केरी
                                                                    जाँच
                                                                </td>
                                                                <td style="border-color: rgb(85, 85, 85);">
                                                                    <div align="left">२४ घण्टा भित्र&nbsp;</div>
                                                                </td>
                                                                <td style="border-color: rgb(85, 85, 85);">
                                                                    <div align="center"><input
                                                                                id="b8jGBrMfBbP-kdsirVNKdhm-val"
                                                                                size="5" title=""
                                                                                value="{{$data['checkIn24hour']}}"
                                                                                type="text" name="checkIn24hour"
                                                                                class="entryfield" tabindex="43"
                                                                                style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                                id="b8jGBrMfBbP-dataelement"
                                                                                style="display:none">Safe Motherhood Program-PNC Visits within 24 hours</span><span
                                                                                id="kdsirVNKdhm-optioncombo"
                                                                                style="display:none">default</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td style="border-color: rgb(85, 85, 85);">प्रोटोकल
                                                                    अनुसार ३
                                                                    पटक
                                                                </td>
                                                                <td style="border-color: rgb(85, 85, 85);">
                                                                    <div align="center"><input
                                                                                id="Aw24Ejbp63F-kdsirVNKdhm-val"
                                                                                size="5" title=""
                                                                                value="{{$data['pncAll']}}"
                                                                                type="text" name="pncAll"
                                                                                class="entryfield" tabindex="44"
                                                                                style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                                id="Aw24Ejbp63F-dataelement"
                                                                                style="display:none">Safe Motherhood Program-3 PNC Visits as per Protocol </span><span
                                                                                id="kdsirVNKdhm-optioncombo"
                                                                                style="display:none">default</span>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                        <td style="vertical-align: top;">
                                            <table border="1" cellpadding="0" cellspacing="0" style="width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>Obstetric Complications</strong></td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>ICD Code</strong></td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>Number</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Ectopic pregnancy</td>
                                                    <td style="border-color: rgb(85, 85, 85);">O00</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="f4ueiBTKF1T-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsEctopicPregnancy"
                                                                                   class="entryfield"
                                                                                   tabindex="45"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="f4ueiBTKF1T-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Ectopic Pregnancy</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Abortion complication
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">O08</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="S4CFTXyEFVc-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsAbortionComplication"
                                                                                   class="entryfield"
                                                                                   tabindex="46"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="S4CFTXyEFVc-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Abortion Complication</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Preg.-induced
                                                        hypertension
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">O13</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="JLKFv7K2i7Y-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsPregInducedHypertension"
                                                                                   class="entryfield"
                                                                                   tabindex="47"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="JLKFv7K2i7Y-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Preg.-Induced Hypertension</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Severe/Pre-eclampsia</td>
                                                    <td style="border-color: rgb(85, 85, 85);">O14</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="v3zKVR5wJt3-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsSeverePreEclampsia"
                                                                                   class="entryfield"
                                                                                   tabindex="48"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="v3zKVR5wJt3-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Severe/Pre-Eclampsia</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Eclampsia</td>
                                                    <td style="border-color: rgb(85, 85, 85);">O15</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="PELChUNfW2C-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsEclampsia"
                                                                                   class="entryfield"
                                                                                   tabindex="49"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="PELChUNfW2C-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Eclampsia</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Hyperemesis grivadarum
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">O21.0</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="LIo3j809JrK-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsHyperemesisGrivadarum"
                                                                                   class="entryfield"
                                                                                   tabindex="50"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="LIo3j809JrK-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Hyperemesis Grivadarum</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Antepartum haemorrhage
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">O46</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="ueBJTsv0PXr-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsAntepartumHaemorrhage"
                                                                                   class="entryfield"
                                                                                   tabindex="51"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="ueBJTsv0PXr-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Antepartum Haemorrhage</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Prolonged labour</td>
                                                    <td style="border-color: rgb(85, 85, 85);">O63</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="wsncKYDcmqr-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsProlongedlabour"
                                                                                   class="entryfield"
                                                                                   tabindex="52"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="wsncKYDcmqr-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Prolonged labour</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Obstructed Labor</td>
                                                    <td style="border-color: rgb(85, 85, 85);">O64-O66</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="Dior21jcQCe-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsObstructedLabor"
                                                                                   class="entryfield"
                                                                                   tabindex="53"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="Dior21jcQCe-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Obstructed Labor</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Ruptured uterus</td>
                                                    <td style="border-color: rgb(85, 85, 85);">S37.6</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="nwQoDUhFR1z-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsRupturedUterus"
                                                                                   class="entryfield"
                                                                                   tabindex="54"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="nwQoDUhFR1z-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Ruptured Uterus</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Postpartum haemorrhage
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">O72</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="PGNd6jcxJX4-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsPostpartumHaemorrhage"
                                                                                   class="entryfield"
                                                                                   tabindex="55"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="PGNd6jcxJX4-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Postpartum Haemorrhage</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Retained placenta</td>
                                                    <td style="border-color: rgb(85, 85, 85);">O73</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="kV3JhKJWMR8-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsRetainedPlacenta"
                                                                                   class="entryfield"
                                                                                   tabindex="56"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="kV3JhKJWMR8-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Retained Placenta</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Pueperal sepsis</td>
                                                    <td style="border-color: rgb(85, 85, 85);">O85</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="dHet49ySm06-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsPuerperalSepsis"
                                                                                   class="entryfield"
                                                                                   tabindex="57"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="dHet49ySm06-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Puerperal Sepsis</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">Other complications</td>
                                                    <td style="border-color: rgb(85, 85, 85);">O75</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="XAzAbTRbJve-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="obstetricComplicationsOtherComplications"
                                                                                   class="entryfield"
                                                                                   tabindex="58"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="XAzAbTRbJve-dataelement" style="display:none">Safe Motherhood Program-Obstetric Complications-Other Complications</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table border="1" cellpadding="0" cellspacing="0" style="width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td rowspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>स्वास्थ्य संस्थामा भएकाे मातृ मृत्यु</strong></td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>गर्भावस्था&nbsp;</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>प्रसूति अवस्था</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>सुत्केरी अवस्था</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>स्वास्थ्य संस्थामा भएकाे नवशिशु मृत्यु</strong></td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="YZfwWNZzfg0-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="maternalDeathAntepartum"
                                                                                   class="entryfield"
                                                                                   tabindex="59"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="YZfwWNZzfg0-dataelement" style="display:none">Safe Motherhood Program-Maternal Death-Antepartum</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="BL5XaUOJ6aw-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="maternalDeathIntrapartum"
                                                                                   class="entryfield"
                                                                                   tabindex="60"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="BL5XaUOJ6aw-dataelement" style="display:none">Safe Motherhood Program-Maternal Death-Intrapartum</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="IMFPhLtATaw-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="maternalDeathPostpartum"
                                                                                   class="entryfield"
                                                                                   tabindex="61"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="IMFPhLtATaw-dataelement" style="display:none">Safe Motherhood Program-Maternal Death-Postpartum</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="bu8MSulaBxg-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="maternalDeathNeonataldeathAtHealthFacility"
                                                                                   class="entryfield"
                                                                                   tabindex="62"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="bu8MSulaBxg-dataelement" style="display:none">Safe Motherhood Program-Maternal Death-Neonatal death at Health Facility</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table border="1" cellpadding="0" cellspacing="0" style="width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2" rowspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>आमा सुरक्षा कार्यक्रम</strong></div>
                                                    </td>
                                                    <td colspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>महिला संख्या</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>पाउनुपर्ने</strong></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <div align="center"><strong>पाएका</strong></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="2" style="border-color: rgb(85, 85, 85);">प्रोत्साहन
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">यातायात खर्च</td>
                                                    <td style="text-align: center; border-color: rgb(85, 85, 85);">
                                                        <input
                                                                id="dZo1PDWPAO1-kdsirVNKdhm-val" size="5" title=""
                                                                value="0"
                                                                type="text"
                                                                name="AamaProgramIncentiveTransportNoofWomenEligible"
                                                                class="entryfield" tabindex="63"
                                                                style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                id="dZo1PDWPAO1-dataelement" style="display:none">Safe Motherhood Program-Aama Program-Incentive-Transport-No of Women Eligible</span><span
                                                                id="kdsirVNKdhm-optioncombo" style="display:none">default</span>
                                                    </td>
                                                    <td style="text-align: center; border-color: rgb(85, 85, 85);">
                                                        <input
                                                                id="ysTJteUVzO1-kdsirVNKdhm-val" size="5" title=""
                                                                value="0"
                                                                type="text"
                                                                name="AamaProgramPregnantWomenReceivedIncentiveOnTransportation"
                                                                class="entryfield" tabindex="64"
                                                                style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                id="ysTJteUVzO1-dataelement" style="display:none">Safe Motherhood Program-Aama Program-Pregnant Women Received Incentive on Transportation</span><span
                                                                id="kdsirVNKdhm-optioncombo" style="display:none">default</span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">गर्भवती उत्प्रेरणा</td>
                                                    <td style="text-align: center; border-color: rgb(85, 85, 85);">
                                                        <input
                                                                id="MO4VQOLebuG-kdsirVNKdhm-val" size="5" title=""
                                                                value="0"
                                                                type="text"
                                                                name="AamaProgramIncentiveANCNoofWomenEligible"
                                                                class="entryfield" tabindex="65"
                                                                style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                id="MO4VQOLebuG-dataelement" style="display:none">Safe Motherhood Program-Aama Program-Incentive-ANC-No of Women Eligible</span><span
                                                                id="kdsirVNKdhm-optioncombo" style="display:none">default</span>
                                                    </td>
                                                    <td style="text-align: center; border-color: rgb(85, 85, 85);">
                                                        <input
                                                                id="ua0pnAingEU-kdsirVNKdhm-val" size="5" title=""
                                                                value="0"
                                                                type="text"
                                                                name="AamaProgramIncentiveANCNumberofWomenReceive"
                                                                class="entryfield" tabindex="66"
                                                                style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                id="ua0pnAingEU-dataelement" style="display:none">Safe Motherhood Program-Aama Program-Incentive-ANC-Number of Women Receive</span><span
                                                                id="kdsirVNKdhm-optioncombo" style="display:none">default</span>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>

                                            <table border="1" cellpadding="0" cellspacing="0"
                                                   style="line-height: 20.8px; width: 500px;">
                                                <tbody>
                                                <tr>
                                                    <td colspan="2"
                                                        style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>सुरक्षित गर्भपतन सेवा</strong></td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <b>मेडिकल</b></td>
                                                    <td style="border-color: rgb(85, 85, 85); background-color: rgb(226, 226, 226);">
                                                        <strong>सर्जिकल</strong></td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="2" style="border-color: rgb(85, 85, 85);"><span
                                                                style="line-height: 20.8px;">गर्भपतन&nbsp;</span>सेवा
                                                        पाएका
                                                        जम्मा महिला
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">&lt; २० वर्ष</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="KsMJg5sdGOS-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServiceNumberOfWomen20YearsMedical"
                                                                                   class="entryfield"
                                                                                   tabindex="67"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="KsMJg5sdGOS-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Number of Women &lt; 20 Years-Medical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="KkSEy2pfgCD-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServiceNumberOfWomen20YearsSurgical"
                                                                                   class="entryfield"
                                                                                   tabindex="68"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="KkSEy2pfgCD-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Number of Women &lt; 20 Years-Surgical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">≥ २० वर्ष</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="wxIeWrnnZxe-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServiceNumberofWomen≥20YearsMedical"
                                                                                   class="entryfield"
                                                                                   tabindex="69"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="wxIeWrnnZxe-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Number of Women ≥ 20 Years-Medical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="ymw1agzYyF3-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServiceNumberOfWomen≥20YearsSurgical"
                                                                                   class="entryfield"
                                                                                   tabindex="70"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="ymw1agzYyF3-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Number of Women ≥ 20 Years-Surgical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td rowspan="2" style="border-color: rgb(85, 85, 85);">गर्भपतन
                                                        पश्चात प. नि.
                                                        साधन अपनाएका
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">छोटो अवधिको</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="BIjA8u1VMF3-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServicePostAbortionFPMethodsShortTermMedical"
                                                                                   class="entryfield"
                                                                                   tabindex="71"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="BIjA8u1VMF3-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Post Abortion FP Methods Short Term-Medical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="spH6ZRldV3F-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServicePostAbortionFPMethodsShorttermSurgical"
                                                                                   class="entryfield"
                                                                                   tabindex="72"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="spH6ZRldV3F-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Post Abortion FP Methods Short term-Surgical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-color: rgb(85, 85, 85);">लामो अवधिको</td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="AP5k6dsqGPI-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServicePostAbortionFPMethodsLongtermMedical"
                                                                                   class="entryfield"
                                                                                   tabindex="73"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="AP5k6dsqGPI-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Post Abortion FP Methods Long term-Medical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="WAxqda0KkYt-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServicePostAbortionFPMethodsLongtermSurgical"
                                                                                   class="entryfield"
                                                                                   tabindex="74"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="WAxqda0KkYt-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Post Abortion FP Methods Long term-Surgical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="border-color: rgb(85, 85, 85);">गर्भपतन
                                                        पश्चात्
                                                        जटिलता भएका
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="CWlOpEYqaC1-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServicePostAbortionComplicationMedical"
                                                                                   class="entryfield"
                                                                                   tabindex="75"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="CWlOpEYqaC1-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Post Abortion Complication Medical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                    <td style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="FKPsRSUnnob-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServicePostAbortionComplicationSurgical"
                                                                                   class="entryfield"
                                                                                   tabindex="76"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="FKPsRSUnnob-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Post Abortion Complication Surgical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="border-color: rgb(85, 85, 85);">PAC सेवा
                                                        पाएका
                                                    </td>
                                                    <td colspan="2" rowspan="1" style="border-color: rgb(85, 85, 85);">
                                                        <div align="center"><input id="Jz96CS4ZOn7-kdsirVNKdhm-val"
                                                                                   size="5"
                                                                                   title="" value="0" type="text"
                                                                                   name="SafeAbortionServicePostAbortionCare(PAC)ThisfacilityMedical"
                                                                                   class="entryfield"
                                                                                   tabindex="77"
                                                                                   style="background-color: rgb(255, 255, 255); border: 1px solid rgb(170, 170, 170);"><span
                                                                    id="Jz96CS4ZOn7-dataelement" style="display:none">Safe Motherhood Program-Safe Abortion Service-Post Abortion Care (PAC) This facility-Medical</span><span
                                                                    id="kdsirVNKdhm-optioncombo"
                                                                    style="display:none">default</span></div>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                    </tbody>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="modal-footer footerleft">
                    <label for="" class="pull-left">HMIS ( DHIS2 ) को Username र Password राख्नुहोस</label><br>
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="usernamehmis">Username</label>
                            <input type="text" name="hmisUsername" placeholder="Username">
                        </div>
                        <div class="form-group col-md-4">
                            <label for="password">Password</label>
                            <input type="password" name="hmisPassword" placeholder="Password">
                        </div>
                    </div>
                    <br>
                    <input type="text" name="hp_code" value="{{ $hp_code }}" hidden>

                    {{--                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancle</button>--}}
                    <button type="submit" class="btn btn-success">Confirm & Send</button>
                </div>
            </div>
        </form>
    </div>
</div>
