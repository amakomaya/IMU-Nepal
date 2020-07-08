@extends('layouts.backend.app')

@section('content')

<script type="text/javascript">

        function provinceOnchange(id){
            $("#district").text("Loading...").fadeIn("slow");
            $.get( "{{route("admin.district-select-province")}}?id="+id,function(data){
                $("#district").html(data);
            });
        }

        function districtOnchange(id){
            $("#municipality").text("Loading...").fadeIn("slow");
            $.get( "{{route("admin.municipality-select-district")}}?id="+id,function(data){
                $("#municipality").html(data);
            });
        }

        function municipalityOnchange(id){
            $("#ward").text("Loading...").fadeIn("slow");
            $.get( "{{route("admin.ward-select-municipality")}}?id="+id,function(data){
                $("#ward").html(data);
            });
        }

        function wardOnchange(id){
            $("#healthpost").text("Loading...").fadeIn("slow");
            municipality_id = $("#municipality_id").val();
            $.get( "{{route("admin.healthpost-select-ward")}}?id="+id+"&municipality_id="+municipality_id,function(data){
                $("#healthpost").html(data);
            });
        }
</script>

<div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                       <strong> भ्याक्सिन प्राप्त, खर्च तथा खेर गएको विवरण (डोजमा)</strong>
                    </div>
                    <!-- /.panel-heading -->
                <div class="panel-body">
                  <!-- <div class="form-group">
                      <a class="btn btn-success pull-right" href="#">
                        Print
                      </a>
                  </div> -->
                  <form method="get" name="info">
                        <div class="form-group col-sm-3" id="province">
                            <select name="province_id" class="form-control" onchange="provinceOnchange($(this).val())">
                                @if(Auth::user()->role!="province" && Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Provinces</option>
                                @endif
                                @foreach($provinces as $province)
                                    @if($province_id==$province->id)
                                        @php 
                                            $selectedProvince = "selected"
                                        @endphp
                                    @else
                                        @php
                                            $selectedProvince = ""
                                        @endphp
                                    @endif
                                    <option value="{{$province->id}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-sm-3" id = "district">
                            <select name="district_id" class="form-control" onchange="districtOnchange($(this).val())">
                                @if(Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Districts</option>
                                @endif
                                @foreach($districts as $district)
                                    @if($district_id==$district->id)
                                        @php
                                            $selectedDistrict = "selected"
                                        @endphp
                                    @else
                                        @php 
                                        $selectedDistrict = ""
                                        @endphp                                        
                                    @endif
                                    <option value="{{$district->id}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-sm-3" id="municipality">
                            <select name="municipality_id" class="form-control" onchange="municipalityOnchange($(this).val())" id="municipality_id">
                                @if(Auth::user()->role!="municipality" && Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Municipalities</option>
                                @endif
                                @foreach($municipalities as $municipality)
                                    @if($municipality_id==$municipality->id)
                                        @php
                                            $selectedMunicipality = "selected"
                                        @endphp
                                    @else
                                        @php
                                            $selectedMunicipality = ""
                                        @endphp
                                    @endif
                                    <option value="{{$municipality->id}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-sm-3" id="ward">
                            <select name="ward_id" class="form-control"  onchange="wardOnchange($(this).val())">
                                @if(Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Wards</option>
                                @endif
                                @foreach($wards as $ward)
                                    @if($ward_id==$ward->id)
                                        @php
                                            $selectedWard = "selected"
                                        @endphp
                                    @else
                                        @php 
                                            $selectedWard = ""
                                        @endphp
                                    @endif
                                    <option value="{{$ward->id}}" {{$selectedWard}}>{{$ward->ward_no}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group  col-sm-3" id="healthpost">
                            <select name="hp_code" class="form-control"  >
                                @if(Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Healthposts</option>
                                @endif
                                @foreach($healthposts as $healthpost)
                                    @if($hp_code==$healthpost->hp_code)
                                        @php
                                            $selectedHealthpost = "selected"
                                        @endphp
                                    @else
                                        @php
                                            $selectedHealthpost = ""
                                        @endphp
                                    @endif
                                    <option value="{{$healthpost->hp_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>
                                @endforeach
                            </select>
                        </div>           

                        <div class="form-group  col-sm-3" id="healthpost">
                            <select name="fiscal_year" class="form-control"  >
                                @foreach($ficalYearList as $year => $fiscal)
                                    @if($year == $fiscal_year){
                                        @php
                                            $selected = 'selected'
                                        @endphp
                                    @else
                                        @php
                                            $selected = ''
                                        @endphp
                                    @endif
                                    <option value="{{$year}}" {{$selected}}>{{$fiscal}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>

                  

                  
                    <div class="clearfix"></div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>

                    <!-- <div id="printable1">
                    <h1 class="btn-success">Print me</h1>
                    </div> -->
                    
                    <div class="clearfix"></div>
    <div id="printable">


    <style type="text/css">
        table{
            border-collapse: collapse;
            font-size: 10px;
            text-align: center;
        }

        #table1{
            margin-bottom: 50px;
        }

        th{
            text-align : center;
        }

        tr,td,th{
            height: 34px;
        }

        td,th{
            width:100px;
        }

        header{
            font-size: 10px;
            text-align: right;
            margin-bottom: 25px
        }

        @media print{@page {size: landscape}}

        .pagebreak { page-break-before: always; } /* page-break-after works, as well */

    </style>
</head>
    
    <div class="clearfix"></div>
    <header>HMIS 2.22</header>

    <h1 style="float: right; font-size: 20px;">
        भ्याक्सिन प्राप्त, खर्च तथा
    </h1>

    <table border="1" width="100%" id="table1">
        <tr>
            <th rowspan="2">महिना</th>
            <th colspan="3">वि.सि.जी. (BCG)</th>
            <th colspan="3">डि.पि.टी./हेप वि/हिब (DPT-HepB-Hib)</th>
            <th colspan="3">पोलियो (OPV)</th>
            <th colspan="2" style="text-align: right;">दादुरा/रुबेला (Measles/</th>
        </tr>
        <tr>
            <td>प्राप्त</td>
            <td>खर्च </td>
            <td>खेरा गएको</td>
            <td>प्राप्त</td>
            <td>खर्च </td>
            <td>खेरा गएको</td>
            <td>प्राप्त</td>
            <td>खर्च </td>
            <td>खेरा गएको</td>
            <td>प्राप्त</td>
            <td>खर्च </td>
        </tr>
        <tr style="font-size: 10px; height: 7px !important;">
            <th style="height: 7px !important;">१</th>
            <th style="height: 7px !important;">२</th>
            <th style="height: 7px !important;">३</th>
            <th style="height: 7px !important;">४</th>
            <th style="height: 7px !important;">५</th>
            <th style="height: 7px !important;">६</th>
            <th style="height: 7px !important;">७</th>
            <th style="height: 7px !important;">८</th>
            <th style="height: 7px !important;">९</th>
            <th style="height: 7px !important;">१०</th>
            <th style="height: 7px !important;">११</th>
            <th style="height: 7px !important;">१२</th>
        </tr>
        @php
        $months = ['श्रावण','भाद्र','आश्विन','कार्तिक','मंशिर','पौष','माघ', 'फाल्गुन', 'चैत्र', 'बैशाख', 'जेष्ठ', 'आषाढ'];
        
        $i=0;
        $new_available_dose_bcg_total = 0;
        $immunized_child_bcg_total = 0;
        $lost_dose_bcg_total = 0;
        $new_available_dose_pentavalent_total = 0;
        $immunized_child_pentavalent_total = 0;
        $lost_dose_pentavalent_total = 0;
        $new_available_dose_opv_total = 0;
        $immunized_child_opv_total = 0;
        $lost_dose_opv_total = 0;
        $new_available_dose_mr_total = 0;
        $immunized_child_mr_total = 0;
        @endphp

        @foreach($monthlyreport as $key => $report)
        @php
            $new_available_dose_bcg_total+=$report['new_available_dose_bcg'];
            $immunized_child_bcg_total+=$report['immunized_child_bcg'];
            $lost_dose_bcg_total+=$report['lost_dose_bcg'];
            $new_available_dose_pentavalent_total+=$report['new_available_dose_pentavalent'];
            $immunized_child_pentavalent_total+=$report['immunized_child_pentavalent'];
            $lost_dose_pentavalent_total+=$report['lost_dose_pentavalent'];
            $new_available_dose_opv_total+=$report['new_available_dose_opv'];
            $immunized_child_opv_total+=$report['immunized_child_opv'];
            $lost_dose_opv_total+=$report['lost_dose_opv'];
            $new_available_dose_mr_total+=$report['new_available_dose_mr'];
            $immunized_child_mr_total+=$report['immunized_child_mr'];
        @endphp
            <tr>
                <th>{{$months[$i]}}</th>
                <td>{{$report['new_available_dose_bcg']}}</td>
                <td>{{$report['immunized_child_bcg']}}</td>
                <td>{{$report['lost_dose_bcg']}}</td>
                <td>{{$report['new_available_dose_pentavalent']}}</td>
                <td>{{$report['immunized_child_pentavalent']}}</td>
                <td>{{$report['lost_dose_pentavalent']}}</td>
                <td>{{$report['new_available_dose_opv']}}</td>
                <td>{{$report['immunized_child_opv']}}</td>
                <td>{{$report['lost_dose_opv']}}</td>
                <td>{{$report['new_available_dose_mr']}}</td>
                <td>{{$report['immunized_child_mr']}}</td>
            </tr>
        @php
            $i++;
        @endphp
        @endforeach
        <tr>
            <th>जाम्मा</th>
            <td>{{$new_available_dose_bcg_total}}</td>
            <td>{{$immunized_child_bcg_total}}</td>
            <td>{{$lost_dose_bcg_total}}</td>
            <td>{{$new_available_dose_pentavalent_total}}</td>
            <td>{{$immunized_child_pentavalent_total}}</td>
            <td>{{$lost_dose_pentavalent_total}}</td>
            <td>{{$new_available_dose_opv_total}}</td>
            <td>{{$immunized_child_opv_total}}</td>
            <td>{{$lost_dose_opv_total}}</td>
            <td>{{$new_available_dose_mr_total}}</td>
            <td>{{$immunized_child_mr_total}}</td>
        </tr>
    </table>

    <header style="float:right;">HMIS 2.22</header>
    <div class="pagebreak"></div>

    <h1 style="float: left; font-size: 20px;">
        खेर गएको विवरण (डोजमा)
    </h1>
    
    <table border="1" width="100%">
        <tr>
            <th style="text-align: left;">Rubella)</th>
            <th colspan="3">पि.सि.भि (PCV)</th>
            <th colspan="3">टी.डी.(TD)</th>
            <th colspan="3">जे.ई. (JE)</th>
            <th rowspan="2" width="200px">कैफियत</th>
        </tr>
        <tr>
            <td>खेरा गएको</td>
            <td>प्राप्त</td>
            <td>खर्च </td>
            <td>खेरा गएको</td>
            <td>प्राप्त</td>
            <td>खर्च </td>
            <td>खेरा गएको</td>
            <td>प्राप्त</td>
            <td>खर्च </td>
            <td width="200px">खेरा गएको</td>
        </tr>
        <tr style="font-size: 10px; height: 7px !important;">
            <th style="height: 7px !important;">१३</th>
            <th style="height: 7px !important;">१४</th>
            <th style="height: 7px !important;">१५</th>
            <th style="height: 7px !important;">१६</th>
            <th style="height: 7px !important;">१७</th>
            <th style="height: 7px !important;">१८</th>
            <th style="height: 7px !important;">१९</th>
            <th style="height: 7px !important;">२०</th>
            <th style="height: 7px !important;">२१</th>
            <th style="height: 7px !important;">२२</th>
            <th style="height: 7px !important; width: 200px;">२३</th>
        </tr>
        @php
            $lost_dose_mr_total = 0;
            $new_available_dose_pcv_total = 0;
            $immunized_child_pcv_total = 0;
            $lost_dose_pcv_total = 0;
            $new_available_dose_je_total = 0;
            $immunized_child_je_total = 0;
            $lost_dose_je_total = 0;
        @endphp
        
        @foreach($monthlyreport as $key => $report)

        @php
            $lost_dose_mr_total += $report['lost_dose_mr'];
            $new_available_dose_pcv_total += $report['new_available_dose_pcv'];
            $immunized_child_pcv_total += $report['immunized_child_pcv'];
            $lost_dose_pcv_total += $report['lost_dose_pcv'];
            $new_available_dose_je_total += $report['new_available_dose_je'];
            $immunized_child_je_total += $report['immunized_child_je'];
            $lost_dose_je_total += $report['lost_dose_je'];
        @endphp

            <tr>
                <td>{{$report['lost_dose_mr']}}</td>
                <td>{{$report['new_available_dose_pcv']}}</td>
                <td>{{$report['immunized_child_pcv']}}</td>
                <td>{{$report['lost_dose_pcv']}}</td>
                <td>{{$report['new_available_dose_td']}}</td>
                <td>{{$report['immunized_child_td']}}</td>
                <td>{{$report['lost_dose_td']}}</td>
                <td>{{$report['new_available_dose_je']}}</td>
                <td>{{$report['immunized_child_je']}}</td>
                <td>{{$report['lost_dose_je']}}</td>
                <td width="200px"></td>
            </tr>
        
        @endforeach
    
        <tr>
            <td>{{$lost_dose_mr_total}}</td>
            <td>{{$new_available_dose_pcv_total}}</td>
            <td>{{$immunized_child_pcv_total}}</td>
            <td>{{$lost_dose_pcv_total}}</td>
                <td></td>
                <td></td>
                <td></td>
            <td>{{$new_available_dose_je_total}}</td>
            <td>{{$immunized_child_je_total}}</td>
            <td>{{$lost_dose_je_total}}</td>
            <td width="200px"></td>
        </tr>
    </table>


    
</div>



            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

@endsection