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



        $(document).ready(function(){
            $.get( "{{route("admin.select-from-to")}}?from_date={{$from_date}}&to_date={{$to_date}}",function(data){
                $("#from_to").html(data);
            });
        });

        function validateform(){
            var from_date = document.forms["info"]["from_date"].value;
            var to_date = document.forms["info"]["to_date"].value;
            
            if(from_date=="" && to_date!=""){
                alert('Both From Date and To Date is rerquired');
                return false;
            }

            if(to_date=="" && from_date!=""){
                alert('Both From Date and To Date is rerquired');
                return false;
            }

            if(from_date>to_date){
                alert("From Date must be smaller than To Date");
                return false;
            }
            return true;
        }

</script>
<div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                       <strong> खोप कार्यक्रम </strong>
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
                                        @php $selectedProvince = "selected" @endphp
                                    @else
                                        @php $selectedProvince = "" @endphp
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
                                        @php $selectedDistrict = "selected" @endphp
                                    @else
                                        @php $selectedDistrict = "" @endphp
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
                                        @php $selectedMunicipality = "selected" @endphp
                                    @else
                                        @php $selectedMunicipality = "" @endphp
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
                                        @php $selectedWard = "selected" @endphp
                                    @else
                                        @php $selectedWard = "" @endphp
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
                                        @php $selectedHealthpost = "selected" @endphp
                                    @else
                                        @php $selectedHealthpost = "" @endphp
                                    @endif
                                    <option value="{{$healthpost->hp_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>
                                @endforeach
                            </select>
                        </div>                                
                        <div id ="from_to"></div>
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
    

    
@if(!empty($vaccineRecord))       
    <div class="clearfix"></div>
    @php
    $j=0;
    $k = 0;
    $x = 0;
    $mainLoopUpto = count($vaccineRecord)/16;
    @endphp

    @for ($i=0; $i <=$mainLoopUpto ; $i++)
    <div id="printable">
        <style type="text/css">
            .report{
                border-collapse: collapse;
                text-align: center;
                font-size: 10px;
                width: 100%;
            }

            #table1{
                margin-bottom: 40px;
            }

            .report tr,.report td,.report th{
                height: 30px;
            }

             .report td, .report th{
                width:100px;
            }

            span.ward_no{
                font-size: 10px;
                float: right;
            }

            .circle {
      border-radius: 50%;
      border: 1px solid #000;
      width: 18px;
      height: 18px; 
      /* width and height can be anything, as long as they're equal */
    }

    @media print{@page {size: landscape}}
    .pagebreak { page-break-before: always; }

        </style>
    <h1 style="float: right; font-size: 20px;">
            खोप
        </h1>



        <table border="1" id="table1" class="report">
            <tr>
                <td rowspan="3">सेवा दर्ता नं.</td>

                <td rowspan="3">बच्चाको नाम,थर</td>

                <td rowspan="3">जाति कोड</td>

                <td rowspan="2" colspan="2" width="34px">लिङ्ग</td>

                <td rowspan="3" width="400px">आमा/वुवाको नाम,थर</td>


                <td rowspan="3" width="200px">गाऊँ/टोल</td>


                <td rowspan="3" width="400px">सम्पर्क फोन नं.</td>


                <td rowspan="2" colspan="3">जन्म मिति</td>

                <td rowspan="2" colspan="3">वि.सि.जी. (BCG)</td>

                <td colspan="6">डि.पि.टी./हेप वि/हिब</td>
            </tr>

            <tr>
                <td colspan="3">१</td>

                <td colspan="3">२</td>
            </tr>

            <tr>

                <td width="17px">म</td>

                <td width="17px">पु</td>

                <td>ग</td>

                <td>म</td>

                <td>सा</td>

                <td>ग</td>

                <td>म</td>

                <td>सा</td>

                <td>ग</td>

                <td>म</td>

                <td>सा</td>

                <td>ग</td>

                <td>म</td>

                <td>सा</td>


            </tr>
            <tr style="font-size: 10px; height: 7px !important;">
                <th style="height: 7px !important;">१</th>
                <th style="height: 7px !important; width: 300px;">२,३</th>
                <th style="height: 7px !important;">४</th>
                <th style="height: 7px !important; width: 17px;">५</th>
                <th style="height: 7px !important; width: 17px;">६</th>
                <th style="height: 7px !important; width: 400px;">७</th>
                <th style="height: 7px !important; width: 200px">८</th>
                <th style="height: 7px !important; width: 400px">९</th>
                <th style="height: 7px !important;">१०</th>
                <th style="height: 7px !important;">११</th>
                <th style="height: 7px !important;">१२</th>
                <th style="height: 7px !important;">१३</th>
                <th style="height: 7px !important;">१४</th>
                <th style="height: 7px !important;">१५</th>
                <th style="height: 7px !important;">१६</th>
                <th style="height: 7px !important;">१७</th>
                <th style="height: 7px !important;">१८</th>
                <th style="height: 7px !important;">१९</th>
                <th style="height: 7px !important;">२०</th>
                <th style="height: 7px !important;">२१</th>
            </tr>

    @php
    $m=0;
    @endphp

    @foreach($vaccineRecord as $report)


        @if ($m>=$j && $m<$j+16 )
            @php
                ##gender
                $maleClass = "";
                $femaleClass = "";

                ##dob
                $day_dob_np = "";
                $month_dob_np = "";
                $year_dob_np = "";
                $dob_np= "";

                ##bcgFirst          
                $day_bcgFirst = "";
                $month_bcgFirst = "";
                $year_bcgFirst = "";
                $bcgFirst= "";

                ##pvFirst           
                $day_pvFirst = "";
                $month_pvFirst = "";
                $year_pvFirst = "";
                $pvFirst= "";

                ##pvSecond          
                $day_pvSecond = "";
                $month_pvSecond = "";
                $year_pvSecond = "";
                $pvSecond= "";

            @endphp



                @if($report['gender']=='Male')
                    @php $maleClass = "circle"; @endphp
                @elseif ($report['gender']=='Female')
                    @php $femaleClass = "circle"; @endphp
                @endif

                @if($report['dob_en']!="")
                    @php
                        $dob_np = App\Helpers\ViewHelper::convertEnglishToNepali($report['dob_en']);
                        list($year_dob_np, $month_dob_np, $day_dob_np) = explode("-", $dob_np);
                    @endphp
                @endif

                @if($report['bcgFirst']!="")
                    @php
                        $bcgFirst = App\Helpers\ViewHelper::convertEnglishToNepali($report['bcgFirst']);
                        list($year_bcgFirst, $month_bcgFirst, $day_bcgFirst) = explode("-", $bcgFirst);
                    @endphp
                @endif

                @if($report['pvFirst']!="")
                    @php
                        $pvFirst = App\Helpers\ViewHelper::convertEnglishToNepali($report['pvFirst']);
                        list($year_pvFirst, $month_pvFirst, $day_pvFirst) = explode("-", $pvFirst);
                    @endphp
                @endif

                @if($report['pvSecond']!="")
                    @php
                        $pvSecond = App\Helpers\ViewHelper::convertEnglishToNepali($report['pvSecond']);
                        list($year_pvSecond, $month_pvSecond, $day_pvSecond) = explode("-", $pvSecond);
                    @endphp
                @endif
                
            
            <tr>
                <td></td>
                <td width="300px">{{$report['baby_name']}}</td>
                <td>{{$report['caste']}}</td>
                <td width="17px"><div class="{{$maleClass}}">१</div></td>
                <td width="17px"><div class="{{$femaleClass}}">२</div></td>
                <td>{{$report['mother_name']}}/{{$report['father_name']}}</td>
                <td width="200px">{{$report['tole']}}</td>
                <td width="400px">{{$report['contact_no']}}</td>
                <td>{{$day_dob_np}}</td>
                <td>{{$month_dob_np}}</td>
                <td>{{$year_dob_np}}</td>
                <td>{{$day_bcgFirst}}</td>
                <td>{{$month_bcgFirst}}</td>
                <td>{{$year_bcgFirst}}</td>
                <td>{{$day_pvFirst}}</td>
                <td>{{$month_pvFirst}}</td>
                <td>{{$year_pvFirst}}</td>
                <td>{{$day_pvSecond}}</td>
                <td>{{$month_pvSecond}}</td>
                <td>{{$year_pvSecond}}</td>
            </tr>
        @endif
    @php
    $m++;
    @endphp
    @endforeach
    @php
    $j += 16;
    @endphp
    </table>

     <div class="pagebreak"> </div>
        <span class="ward_no">वडा नं. {{$selectedWard}}</span>

        <h1 style="float: left; font-size: 20px;">
            खोप
        </h1>
        <table border="1" class="report">
            <tr>
                <td colspan="3">(DPT-HepB-Hib)</td>
                <td colspan="9">पोलियो (OPV)</td>
                <td colspan="9">पि.सि.भि (PCV)</td>
                <td colspan="3" rowspan="2">दादुरा/रुबेला (९ देखि ११ महिना)</td>
                <td colspan="3" rowspan="2">पूर्ण खोप लगाएको</td>
                <td colspan="3" rowspan="2">दादुरा/रुबेला (१२ देखि २३ महिना)</td>
                <td colspan="3" rowspan="2">जे.ई. (१२ देखि २३ महिना)</td>
                <td colspan="3" rowspan="2">१२ महिना पछि डि.पि.टि/हेप वि/हिब, पोलियो ३ मात्र पुरा गरेको</td>
            </tr>
            <tr>
                <td colspan="3">३</td>
                <td colspan="3">१</td>
                <td colspan="3">२</td>
                <td colspan="3">३</td>
                <td colspan="3">१</td>
                <td colspan="3">२</td>
                <td colspan="3">३</td>
            </tr>
            <tr style="height: 15px !important;">
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
                <td style="height: 15px !important;">ग</td>
                <td style="height: 15px !important;">म</td>
                <td style="height: 15px !important;">सा</td>
            </tr>
            <tr style="height: 15px !important;">
                <td style="height: 15px !important;">२२</td>
                <td style="height: 15px !important;">२३</td>
                <td style="height: 15px !important;">२४</td>
                <td style="height: 15px !important;">२५</td>
                <td style="height: 15px !important;">२६</td>
                <td style="height: 15px !important;">२७</td>
                <td style="height: 15px !important;">२८</td>
                <td style="height: 15px !important;">२९</td>
                <td style="height: 15px !important;">३०</td>
                <td style="height: 15px !important;">३१</td>
                <td style="height: 15px !important;">३२</td>
                <td style="height: 15px !important;">३३</td>
                <td style="height: 15px !important;">३४</td>
                <td style="height: 15px !important;">३५</td>
                <td style="height: 15px !important;">३६</td>
                <td style="height: 15px !important;">३७</td>
                <td style="height: 15px !important;">३८</td>
                <td style="height: 15px !important;">३९</td>
                <td style="height: 15px !important;">४०</td>
                <td style="height: 15px !important;">४१</td>
                <td style="height: 15px !important;">४२</td>
                <td style="height: 15px !important;">४३</td>
                <td style="height: 15px !important;">४४</td>
                <td style="height: 15px !important;">४५</td>
                <td style="height: 15px !important;">४६</td>
                <td style="height: 15px !important;">४७</td>
                <td style="height: 15px !important;">४८</td>
                <td style="height: 15px !important;">४९</td>
                <td style="height: 15px !important;">५०</td>
                <td style="height: 15px !important;">५१</td>
                <td style="height: 15px !important;">५२</td>
                <td style="height: 15px !important;">५३</td>
                <td style="height: 15px !important;">५४</td>
                <td style="height: 15px !important;">५५</td>
                <td style="height: 15px !important;">५६</td>
                <td style="height: 15px !important;">५७</td>
            </tr>
    @php
    $m = 0;
    @endphp

    @foreach($vaccineRecord as $report)
        @if ($m>=$k && $m<$k+16 ) 
            @php

                ##pvThird           
                $day_pvThird = "";
                $month_pvThird = "";
                $year_pvThird = "";
                $pvThird= "";

                ##opvFirst          
                $day_opvFirst = "";
                $month_opvFirst = "";
                $year_opvFirst = "";
                $opvFirst= "";

                ##opvSecond         
                $day_opvSecond = "";
                $month_opvSecond = "";
                $year_opvSecond = "";
                $opvSecond= "";

                ##opvThird          
                $day_opvThird = "";
                $month_opvThird = "";
                $year_opvThird = "";
                $opvThird= "";

                ##pcvFirst          
                $day_pcvFirst = "";
                $month_pcvFirst = "";
                $year_pcvFirst = "";
                $pcvFirst= "";

                ##pcvSecond         
                $day_pcvSecond = "";
                $month_pcvSecond = "";
                $year_pcvSecond = "";
                $pcvSecond= "";

                ##pcvThird          
                $day_pcvThird = "";
                $month_pcvThird = "";
                $year_pcvThird = "";
                $pcvThird= "";

                ##mrFirst           
                $day_mrFirst = "";
                $month_mrFirst = "";
                $year_mrFirst = "";
                $mrFirst= "";

                ##mrSecond          
                $day_mrSecond = "";
                $month_mrSecond = "";
                $year_mrSecond = "";
                $mrSecond= "";

                ##jeFirst           
                $day_jeFirst = "";
                $month_jeFirst = "";
                $year_jeFirst = "";
                $jeFirst= "";

                ##pvThirdAndOpvThirdAfeterOneYear           
                $day_pvThirdAndOpvThirdAfeterOneYear = "";
                $month_pvThirdAndOpvThirdAfeterOneYear = "";
                $year_pvThirdAndOpvThirdAfeterOneYear = "";
                $pvThirdAndOpvThirdAfeterOneYear= "";

            @endphp

            

                @if($report['pvThird']!="")
                    @php
                        $pvThird = App\Helpers\ViewHelper::convertEnglishToNepali($report['pvThird']);
                        list($year_pvThird, $month_pvThird, $day_pvThird) = explode("-", $pvThird);
                    @endphp
                @endif

                @if($report['opvFirst']!="")
                    @php
                        $opvFirst = App\Helpers\ViewHelper::convertEnglishToNepali($report['opvFirst']);
                        list($year_opvFirst, $month_opvFirst, $day_opvFirst) = explode("-", $opvFirst);
                    @endphp
                @endif

                @if($report['opvSecond']!="")
                    @php
                        $opvSecond = App\Helpers\ViewHelper::convertEnglishToNepali($report['opvSecond']);
                        list($year_opvSecond, $month_opvSecond, $day_opvSecond) = explode("-", $opvSecond);
                    @endphp
                @endif

                @if($report['opvThird']!="")
                    @php
                        $opvThird = App\Helpers\ViewHelper::convertEnglishToNepali($report['opvThird']);
                        list($year_opvThird, $month_opvThird, $day_opvThird) = explode("-", $opvThird);
                    @endphp
                @endif

                @if($report['pcvFirst']!="")
                    @php
                        $pcvFirst = App\Helpers\ViewHelper::convertEnglishToNepali($report['pcvFirst']);
                        list($year_pcvFirst, $month_pcvFirst, $day_pcvFirst) = explode("-", $pcvFirst);
                    @endphp
                @endif

                @if($report['pcvSecond']!="")
                    @php
                        $pcvSecond = App\Helpers\ViewHelper::convertEnglishToNepali($report['pcvSecond']);
                        list($year_pcvSecond, $month_pcvSecond, $day_pcvSecond) = explode("-", $pcvSecond);
                    @endphp
                @endif

                @if($report['pcvThird']!="")
                    @php
                        $pcvThird = App\Helpers\ViewHelper::convertEnglishToNepali($report['pcvThird']);
                        list($year_pcvThird, $month_pcvThird, $day_pcvThird) = explode("-", $pcvThird);
                    @endphp
                @endif

                @if($report['mrFirst']!="")
                    @php
                        $mrFirst = App\Helpers\ViewHelper::convertEnglishToNepali($report['mrFirst']);
                        list($year_mrFirst, $month_mrFirst, $day_mrFirst) = explode("-", $mrFirst);
                    @endphp
                @endif

                @if($report['mrSecond']!="")
                    @php
                        $mrSecond = App\Helpers\ViewHelper::convertEnglishToNepali($report['mrSecond']);
                        list($year_mrSecond, $month_mrSecond, $day_mrSecond) = explode("-", $mrSecond);
                    @endphp
                @endif

                @if($report['jeFirst']!="")
                    @php
                        $jeFirst = App\Helpers\ViewHelper::convertEnglishToNepali($report['jeFirst']);
                        list($year_jeFirst, $month_jeFirst, $day_jeFirst) = explode("-", $jeFirst);
                    @endphp
                @endif

                @if($report['pvThirdAndOpvThirdAfeterOneYear']!="")
                    @php
                        $pvThirdAndOpvThirdAfeterOneYear = App\Helpers\ViewHelper::convertEnglishToNepali($report['pvThirdAndOpvThirdAfeterOneYear']);
                        list($year_pvThirdAndOpvThirdAfeterOneYear, $month_pvThirdAndOpvThirdAfeterOneYear, $day_pvThirdAndOpvThirdAfeterOneYear) = explode("-", $pvThirdAndOpvThirdAfeterOneYear);
                    @endphp
                @endif

            
            <tr>
                <td>{{$day_pvThird}}</td>
                <td>{{$month_pvThird}}</td>
                <td>{{$year_pvThird}}</td>
                <td>{{$day_opvFirst}}</td>
                <td>{{$month_opvFirst}}</td>
                <td>{{$year_opvFirst}}</td>
                <td>{{$day_opvSecond}}</td>
                <td>{{$month_opvSecond}}</td>
                <td>{{$year_opvSecond}}</td>
                <td>{{$day_opvThird}}</td>
                <td>{{$month_opvThird}}</td>
                <td>{{$year_opvThird}}</td>
                <td>{{$day_pcvFirst}}</td>
                <td>{{$month_pcvFirst}}</td>
                <td>{{$year_pcvFirst}}</td>
                <td>{{$day_pcvSecond}}</td>
                <td>{{$month_pcvSecond}}</td>
                <td>{{$year_pcvSecond}}</td>
                <td>{{$day_pcvThird}}</td>
                <td>{{$month_pcvThird}}</td>
                <td>{{$year_pcvThird}}</td>
                <td>{{$day_mrFirst}}</td>
                <td>{{$month_mrFirst}}</td>
                <td>{{$year_mrFirst}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$day_mrSecond}}</td>
                <td>{{$month_mrSecond}}</td>
                <td>{{$year_mrSecond}}</td>
                <td>{{$day_jeFirst}}</td>
                <td>{{$month_jeFirst}}</td>
                <td>{{$year_jeFirst}}</td>
                <td>{{$day_pvThirdAndOpvThirdAfeterOneYear}}</td>
                <td>{{$month_pvThirdAndOpvThirdAfeterOneYear}}</td>
                <td>{{$year_pvThirdAndOpvThirdAfeterOneYear}}</td>
            </tr>
        @endif
        @php
        $m++;
        @endphp
    @endforeach
    @php
        $k +=16;
    @endphp
    </table>

     <div class="pagebreak"> </div>
    @endfor
@else
    <div class="alert alert-danger" role="alert">
            No Records Available
    </div>
@endif
</body>

</html>
    




            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

@endsection