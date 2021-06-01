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
            $("#organization").text("Loading...").fadeIn("slow");
            $.get( "{{route("admin.organization-select")}}?id="+id,function(data){
                $("#organization").html(data);
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
                        Cases Payment Report Overview
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
                            <div class="form-group  col-sm-3" id="organization">
                                <select name="hp_code" class="form-control"  >
                                    @if(Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                        <option value="">All Organization</option>
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
                        <hr>

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover display dataTable" id="dataTable">
                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>Name</th>
                                    <th>Age</th>
                                    <th>gender</th>
                                    <th>Phone No</th>
                                    <th>Guardian Name</th>
                                    <th>Paid / Free</th>
                                    <th title="No. of days in General Bed">General</th>
                                    <th title="No. days in HDU bed">HDU</th>
                                    <th title="No. of Days in ICU Bed">ICU</th>
                                    <th title="No. of Days in Ventilator">Ventilator</th>
                                    <th title="Total No. of Days">Total</th>
                                    <th title="Outcome Status">Status</th>
                                    <th title="Date of Registry">Reg. Date</th>
                                    <th title="Date of Outcome">Out. Date</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $key => $case)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td >{{ $case['name'] }} </td>
                                        <td>{{ $case['age'] }} </td>
                                        <td>{{ $case['gender'] }} </td>
                                        <td>{{ $case['phone'] }} </td>
                                        <td>{{ $case['guardian_name'] }} </td>
                                        <td>{{ $case['paid_free'] }} </td>
                                        <td>{{ $case['no_of_days_in_general_bed'] }} </td>
                                        <td>{{ $case['no_of_days_in_hdu_bed'] }} </td>
                                        <td>{{ $case['no_of_days_in_icu_bed'] }} </td>
                                        <td>{{ $case['no_of_days_in_ventilator'] }} </td>
                                        <td>{{ $case['total_no_of_days'] }} </td>

                                        <td>{{ $case['outcome_status'] }} </td>
                                        <td>{{ $case['register_date_en'] }} </td>
                                        <td>{{ $case['date_of_outcome_en'] }} </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>



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
@section('Style')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css">

@endsection
@section('script')
    <script>
        $('#dataTable').DataTable( {
            scrollX: true,
            pageLength: 50,
            dom :
                '<"top"f>rt<"button"lp><"clear">',

            buttons: [
                'copyHtml5',
                'excelHtml5',
                'csvHtml5',
                'pdfHtml5'
            ]
        } );
    </script>
@endsection

