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
                       <strong>Dropout Child</strong>
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

                        <div class="form-group  col-sm-3">
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

                        <div class="form-group  col-sm-3">
                            <div class="form-group col-sm-6"><strong>Exceed<font color="white">.</font>Time</strong> </div>
                            <div class="form-group col-sm-6">
                                @php
                                    $exceed_time_1_month = "";
                                    $exceed_time_2_month = "";
                                    $exceed_time_3_month = "";
                                @endphp

                                @if($exceed_time=='1')
                                    @php
                                        $exceed_time_1_month = "selected";
                                    @endphp
                                @endif
                                @if($exceed_time=='2')
                                    @php
                                        $exceed_time_2_month = "selected";
                                    @endphp
                                @endif
                                @if($exceed_time=='3')
                                    @php
                                        $exceed_time_3_month = "selected";
                                    @endphp
                                @endif

                                <select name="exceed_time" class="form-control">
                                        <option value="1" {{$exceed_time_1_month}}>1 month</option>
                                        <option value="2" {{$exceed_time_2_month}}>2 months</option>
                                        <option value="3" {{$exceed_time_3_month}}>3 months</option>
                                </select>
                            </div>
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
    @media  print{@page  {size: landscape}}
</style>


@php

        $fromNepali = $fiscal_year."-".'04'."-".'01';
        $from_date = \App\Helpers\ViewHelper::convertNepaliToEnglish($fromNepali); 
        list($year,$month,$day) = explode('-', $from_date);
        $to_date = $year."-07-01";

@endphp


        <table class="table table-bordered">
        <tr>
            <th rowspan="2" style="vertical-align:middle;" class="text-center">Month\Type of Vaccine</th>
            <th rowspan="2" style="vertical-align:middle;" class="text-center">BCG</th>
            <th colspan="3" class="text-center">Pentavalent</th>
            <th colspan="3" class="text-center">OPV</th>
            <th colspan="3" class="text-center">PCV</th>
            <th colspan="2" class="text-center">*FIPV</th>
            <th colspan="2" class="text-center">MR</th>
            <th rowspan="2" style="vertical-align:middle;" class="text-center">JE</th>
            <th colspan="2" class="text-center">*RV</th>        
        </tr>

        <tr class="text-center">
            <td>1st</td>
            <td>2nd</td>
            <td>3rd</td>
            
            <td>1st</td>
            <td>2nd</td>
            <td>3rd</td>

            <td>1st</td>
            <td>2nd</td>
            <td>3rd</td>

            <td>1st</td>
            <td>2nd</td>
            
            <td>1st</td>
            <td>2nd</td>

            <td>1st</td>
            <td>2nd</td>

        </tr>
        @php
        $months = ['Shrawn','Bhadra','Aswin','Kartik','Mansir','Poush','Magh', 'Falgun', 'Chaitra', 'Baishakh', 'Jestha', 'Asad'];
        @endphp
        @foreach ($monthlyreport as $key => $dropoutRecord) 
        @php
            $to_date = date('Y-m-d', strtotime("+1 month", strtotime($to_date)));
        @endphp
        <tr class="text-center">
            <td>{{$months[$key] }}</td>
            <td> </td>
            <td>
                
                    {{$dropoutRecord['pvFirst']}}
            </td>
            <td>
                    {{$dropoutRecord['pvSecond']}}
            </td>
            <td>
                    {{$dropoutRecord['pvThird']}}
            </td>

            <td>
                    {{$dropoutRecord['opvFirst']}}
            </td>
            <td>
                    {{$dropoutRecord['opvSecond']}}
            </td>
            <td>
                    {{$dropoutRecord['opvThird']}}
            </td>

            <td>
                    {{$dropoutRecord['pcvFirst']}}
            </td>
            <td>
                    {{$dropoutRecord['pcvSecond']}}
            </td>
            <td>
                    {{$dropoutRecord['pcvThird']}}
            </td>

            <td>
                    {{$dropoutRecord['fipvFirst']}}
            </td>
            <td>
                    {{$dropoutRecord['fipvSecond']}}
            </td>

            <td>
                    {{$dropoutRecord['mrFirst']}}
            </td>
            <td>
                    {{$dropoutRecord['mrSecond']}}
            </td>

            <td>
                    {{$dropoutRecord['jeFirst']}}
            </td>

            <td>
                    {{$dropoutRecord['rvFirst']}}
            </td>
            <td>
                    {{$dropoutRecord['rvSecond']}}
            </td>
        </tr>
        @endforeach
        
    </table>

    <div class="form-group">
        Note* : FIPV and RV are new Vaccine
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