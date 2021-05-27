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
            $.get( "{{route("admin.organization-select")}}?id="+id,function(data){
                $("#ward").html(data);
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
                    <div class="panel-heading">
                        Woman Info
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

                        {{ $data }}
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /#page-wrapper -->
@endsection

