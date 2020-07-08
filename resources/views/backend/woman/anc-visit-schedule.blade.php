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
                        Woman ANC  Visit Schedule
                    </div>
                    <!-- /.panel-heading -->
                <div class="panel-body">
                <form method="get" name="info">
                    <div class="form-group col-sm-3" id="province">
                        <select name="province_id" class="form-control" onchange="provinceOnchange($(this).val())">
                            @if(Auth::user()->role!="province" && Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                <option value="">Select All Provinces</option>
                            @endif
                            @foreach($provinces as $province)
                                @if($province_id==$province->id)
                                    @php($selectedProvince = "selected")
                                @else
                                    @php($selectedProvince = "")
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
                                    @php($selectedDistrict = "selected")
                                @else
                                    @php($selectedDistrict = "")
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
                                    @php($selectedMunicipality = "selected")
                                @else
                                    @php($selectedMunicipality = "")
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
                                    @php($selectedWard = "selected")
                                @else
                                    @php($selectedWard = "")
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
                                    @php($selectedHealthpost = "selected")
                                @else
                                    @php($selectedHealthpost = "")
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

                    <div class="clearfix"></div>
                    <div class="print" id="printable">
                        <style type="text/css">
                        		@media print{@page {size: landscape}}

                            body{
                                margin: 0;
                                padding: 0;
                            }
                            table{		
                                width: 100%;
                                font-family: arial;
                                border-collapse: collapse;
                            }
                            th{
                                text-align: center;
                                background-color: #eee;
                            }
                            th,td{
                                font-size: 14px;
                                border: 1px solid #dfdfdf;
                                padding:5px 10px;
                            }
                        </style>
                        <table>
                            <tr>
                                <th width="2%" rowspan="2">S.N</th>
                                <th width="20%" rowspan="2" >Full Name</th>
                                <th width="2%" rowspan="2" >Age</th>
                                <th width="10%" rowspan="2" >LMP</th>
                                <th width="10%" rowspan="2" >EDD</th>
                                <th colspan="4" >Visit Status</th>
                                <th rowspan="2" >Coming for</th>
                                <th width="10%" rowspan="2" > Remarks</th>
                                
                            </tr>

                            <tr>
                                <th width="1%" align="center">1st</th>
                                <th width="1%" align="center">2nd</th>
                                <th width="1%" align="center">3rd</th>
                                <th width="1%" align="center">4th</th>
                            </tr>
                        @forelse($data as $record)

                            <tr>
                                <td>{{ $loop->iteration	 }}</td>
                                <td>{{ ucwords($record['name']) }}</td>
                                <td>{{ $record['age'] }}</td>
                                <td>{{$record['lmp_date_np_day']}}-{{$record['lmp_date_np_month']}}-{{$record['lmp_date_np_year']}}</td>
                                <td>{{$record['edd_date_np_day']}}-{{$record['edd_date_np_month']}}-{{$record['edd_date_np_year']}}</td>
                                                               
                                <td>@if( $record['fourthMonthAnc'] == '1') <span class="fa fa-check"></span> @endif</td>
                                <td>@if( $record['seventhMonth'] == '1') <span class="fa fa-check"></span> @endif</td>
                                <td>@if( $record['eightMonthAnc'] == '1') <span class="fa fa-check"></span> @endif</td>
                                <td>@if( $record['nineMonthAnc'] == '1') <span class="fa fa-check"></span> @endif</td>
                                <td> </td>
                                <td></td>			
                            </tr>
                        @empty    
                            <p> No Record Found. </p>
                        @endforelse                           
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