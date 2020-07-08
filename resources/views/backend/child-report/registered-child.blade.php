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
                       <strong>Registered Child</strong>
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


<?php if($monthlyreport != ""){ 
    // from date
    $fromNepali = $fiscal_year."-".'04'."-".'01';
    $from_date = \App\Helpers\ViewHelper::convertNepaliToEnglish($fromNepali); 
    $months = ['Shrawn','Bhadra','Aswin','Kartik','Mansir','Poush','Magh', 'Falgun', 'Chaitra', 'Baishakh', 'Jestha', 'Asad'];
    ?>

    <table class="table table-bordered">
    <tr>
            <th style="vertical-align:middle;" class="text-center">Month</th>
            <th style="vertical-align:middle;" class="text-center">Child Registerd</th>
    </tr>
    <?php foreach ($monthlyreport as $key => $registerd) {
            if($key!=0){
                $from_date = $to_date;
            }
            $to_date = date('Y-m-d', strtotime("+1 month", strtotime($from_date)));
    ?>
    <tr>
        <td><?=$months[$key]?></td>
        <td>
            
                <?=$registerd['total_child']?>
        </td>
    </tr>
    <?php
        }
    ?>
    </table>

        

    <?php } ?>




    
</div>



            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

@endsection