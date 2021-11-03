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
                            <select name="org_code" class="form-control"  >
                            	@if(Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                	<option value="">Select All Organizations</option>
                                @endif
                                @foreach($organizations as $healthpost)
                                    @if($org_code==$healthpost->org_code)
                                        @php($selectedHealthpost = "selected")
                                    @else
                                        @php($selectedHealthpost = "")
                                    @endif
                                    <option value="{{$healthpost->org_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>
                                @endforeach
                            </select>
                        </div>                                
                        <div id ="from_to"></div>
                        <div class="form-group col-sm-3">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                	<!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['registered']}}</div>
	                                    <div>Woman Registered</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=registered&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['anc']}}</div>
	                                    <div>Woman completed at least One Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=anc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['firstAnc']}}</div>
	                                    <div>Woman completed First Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=firstAnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['notFirstAnc']}}</div>
	                                    <div>Woman Not completed First Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=notFirstAnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->

	                <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['secondAnc']}}</div>
	                                    <div>Woman completed Second Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=secondAnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['notSecondAnc']}}</div>
	                                    <div>Woman Not completed Second Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=notSecondAnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['thirdAnc']}}</div>
	                                    <div>Woman completed Third Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=thirdAnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['notThirdAnc']}}</div>
	                                    <div>Woman Not completed Third Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=notThirdAnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['forthAnc']}}</div>
	                                    <div>Woman completed Fourth Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=forthAnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['notForthAnc']}}</div>
	                                    <div>Woman Not completed Fourth Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=notForthAnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['anc_all']}}</div>
	                                    <div>Woman completed All First, Second, Third & Fourth Anc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=anc_all&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['delivery']}}</div>
	                                    <div>Woman have Successful Delivery</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=delivery&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                <div class="clearfix"></div>
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['misccarige']}}</div>
	                                    <div>Woman have Miscarriage</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=misccarige&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['baby']}}</div>
	                                    <div>Child Birth</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/baby?list=baby&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
	                 <!--/.information-->
                    <div class="col-lg-6">
	                    <div class="panel panel-primary">
	                        <div class="panel-heading">
	                            <div class="row">
	                                <div class="col-xs-3">
	                                    <i class="fa fa-user fa-5x"></i>
	                                </div>
	                                <div class="col-xs-9 text-right">
	                                    <div class="huge">{{$data['pnc']}}</div>
	                                    <div>Completed at least one Pnc Visit</div>
	                                </div>
	                            </div>
	                        </div>
	                        <a href="{{URL::to('/')}}/admin/woman?list=pnc&province_id={{$province_id}}&district_id={{$district_id}}&municipality_id={{$municipality_id}}&ward_id={{$ward_id}}&org_code={{$org_code}}&from_date={{$from_date}}&to_date={{$to_date}}">
	                            <div class="panel-footer">
	                                <span class="pull-left">View Details</span>
	                                <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
	                                <div class="clearfix"></div>
	                            </div>
	                        </a>
	                    </div>
	                </div>
	                <!--/.information-->
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

