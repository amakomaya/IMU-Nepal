@extends('layouts.backend.app')

@section('content')

    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        सुरक्षित मातृत्व कार्क्रम
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        @include('reports.layouts.filter')
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                                <i class="fa fa-print"></i> Print
                            </button>
                            <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModal"> <i class="fa fa-send-o"></i> HMIS ( DHIS2 ) मा डाटा पठाउनुहोस् </button>
                            @include('reports.bootstrap-models.safe-maternity-program')
                        </div>
                        <!-- <div id="printable1">
                        <h1 class="btn-success">Print me</h1>
                        </div> -->
                        @include('reports.session')
                        <div class="clearfix"></div>
                        <!-- /.report-->
                        <!-- /.report-->
                        <div class="print" id="printable">
                            <style type="text/css">
                                body {
                                    margin: 0;
                                    padding: 0;
                                }

                                .tableDiv {
                                    width: 100%;
                                    margin: 0 auto;
                                }

                                .report {
                                    margin: 5px;
                                    float: left;
                                    width: 48%;
                                }

                                .titleHeader {
                                    border: 1px solid #000;
                                    text-align: center;
                                    padding: 5px;
                                    color:#006100;
                                    background-color: rgb(198, 239, 206) !important;
                                    font-size: 14px;
                                    margin-right: 3%;
                                    margin-top: 5px !important;
                                }

                                .report, .report td, .report th {
                                    border: 1px solid black;
                                    border-collapse: collapse;
                                }

                                .report th {
                                    font-size: 14px;
                                    padding: 5px;
                                    background-color: #dfdfdf;
                                }

                                .report td {
                                    font-size: 14px;
                                    padding: 10px;
                                    color: #000;
                                }

                                .clearfix {
                                    clear: both;
                                }
                            </style>
                            <div id="print-header">
                                @include('reports.layouts.header-for-print')
                            </div>
                            <div style="float: right; text-align: right; font-size: 12px; margin-right:4%;">HMIS 9.3
                            </div>
                            <div class="clearfix"></div>
                            <div class="tableDiv">
                                <h3 class="titleHeader">६. सुरक्षित मातृत्व कार्क्रम</h3>
                                <table class="report">
                                    <tr>
                                        <th style="text-align:center">गर्भवती सेवा</th>
                                        <th style="text-align:center">< २० बर्ष</th>
                                        <th style="text-align:center">≥ २० बर्ष</th>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">पहिलो पटक गर्भवती जाँच गरेका महिला</td>
                                        <td>{{$data['firstTimeAncVistedAgeLess20']}}</td>
                                        <td>{{$data['firstTimeAncVistedAgeGrater20']}}</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">चाैथाे महिनामा गर्भवती जाँच गरेका महिला</td>
                                        <td>{{$data['AncVisitedAgeLess20FourthMonth']}}</td>
                                        <td>{{$data['AncVisitedAgeGrater20FourthMonth']}}</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">प्राेटाेकल अनुसार ४ पटक गर्भवती जाँच</td>
                                        <td>{{$data['completedAllAncVisitLess20']}}</td>
                                        <td>{{$data['completedAllAncVisitGrater20']}}</td>
                                    </tr>
                                </table>

                                <table class="report">
                                    <tr>
                                        <th rowspan="2" colspan="2" style="text-align:center">प्रसुतीको परिणाम</th>
                                        <th rowspan="2" style="text-align:center">एकल बच्चा</th>
                                        <th colspan="2" style="text-align:center">बहु बच्चा</th>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf" style="text-align:center">जुम्ल्याहा</td>
                                        <td bgcolor="#dfdfdf" style="text-align:center">≥ तिम्ल्याहा</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf" colspan="2">अामाहरुको संख्या</td>
                                        <td>{{$data['singleChildMother']}}</td>
                                        <td>{{$data['doubleChildMother']}}</td>
                                        <td>{{$data['tripleMoreChildMother']}}</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf" rowspan="2">जम्मा जिवित जन्म</td>
                                        <td>महिला</td>
                                        <td>{{$data['singleFemaleChild']}}</td>
                                        <td>{{$data['doubleFemaleChild']}}</td>
                                        <td>{{$data['tripleMoreFemaleChild']}}</td>
                                    </tr>

                                    <tr>
                                        <td>पुरुष</td>
                                        <td>{{$data['singleMaleChild']}}</td>
                                        <td>{{$data['doubleMaleChild']}}</td>
                                        <td>{{$data['tripleMoreMaleChild']}}</td>
                                    </tr>
                                </table>

                                <table class="report">
                                    <tr>
                                        <th style="text-align:center">प्रसुति सेवा</th>
                                        <th style="text-align:center">स्वा. सं.</th>
                                        <th style="text-align:center">घर</th>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">दक्ष प्रसुतिकर्मीबाट</td>
                                        <td>{{$data['womenDeliveriedWithDoctorAtHealthFacility']}}</td>
                                        <td>{{$data['womenDeliveriedWithDoctorAtHome']}}</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">अन्य स्वास्थ्यकर्मीबाट</td>
                                        <td>{{$data['womenDeliveriedWithFchvAtHealthFacility']}}</td>
                                        <td>{{$data['womenDeliveriedWithFchvAtHome']}}</td>
                                    </tr>
                                </table>

                                <table class="report">
                                    <tr>
                                        <th rowspan="2" style="text-align:center">जन्म ताैल</th>
                                        <th colspan="3" style="text-align:center">जिवित जन्म</th>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf" style="text-align:center">जम्मा संख्या</td>
                                        <td bgcolor="#dfdfdf" style="text-align:center">निसासिएको</td>
                                        <td bgcolor="#dfdfdf" style="text-align:center">बिकल्प</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">सामान्य (≥ २.५ के.जी.)</td>
                                        <td>{{$data['weightMore250gmBaby']}}</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">कम (२ - < २.५ के.जी.)</td>
                                        <td>{{$data['weightLess200to250gmBaby']}}</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">धेरै कम (< २ के.जी.)</td>
                                        <td>{{$data['weightLess200gmBaby']}}</td>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>

                                <table class="report">
                                    <tr>
                                        <th rowspan="2" style="text-align:center">प्रसुतीको किसिम</th>
                                        <th colspan="3" style="text-align:center">प्रिजेन्टेशन</th>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf" style="text-align:center">Cephalic</td>
                                        <td bgcolor="#dfdfdf" style="text-align:center">Shoulder</td>
                                        <td bgcolor="#dfdfdf" style="text-align:center">Breech</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">सामान्य</td>
                                        <td>{{$data['cephalicNormal']}}</td>
                                        <td>{{$data['shoulderNormal']}}</td>
                                        <td>{{$data['breechNormal']}}</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">भ्याकुम / फाेरसेप</td>
                                        <td>{{$data['cephalicVacuum_forcep']}}</td>
                                        <td>{{$data['shoulderVacuum_forcep']}}</td>
                                        <td>{{$data['breechVacuum_forcep']}}</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">सल्यक्रिया</td>
                                        <td>{{$data['cephalicCS']}}</td>
                                        <td>{{$data['shoulderCS']}}</td>
                                        <td>{{$data['breechCS']}}</td>
                                    </tr>
                                </table>

                                <table class="report">
                                    <tr>
                                        <th colspan="2" style="text-align:center">मृत जन्म संख्या</th>
                                        <th style="text-align:center">नाभी मलम लगाएको</th>
                                        <th colspan="2" style="text-align:center">रक्त संचार गरिएका</th>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">Fresh</td>
                                        <td>{{$data['deadFresh']}}</td>
                                        <td rowspan="2"></td>
                                        <td bgcolor="#dfdfdf">महिला</td>
                                        <td>&nbsp;</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">Macerated</td>
                                        <td>{{$data['deadMacerated']}}</td>
                                        <td bgcolor="#dfdfdf">पिन्ट</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                </table>

                                <table class="report">
                                    <tr>
                                        <th rowspan="2" style="text-align:center">सुत्केरी जाँच</th>
                                        <th>२४ घण्टा भित्र</th>
                                        <td>{{$data['checkIn24hour']}}</td>
                                    </tr>

                                    <tr>
                                        <td bgcolor="#dfdfdf">प्राेटाेकल अनुसार ३ पटक</td>
                                        <td>{{$data['pncAll']}}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->
@endsection
