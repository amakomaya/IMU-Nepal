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
                        Monthly Line Listing
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
                                        @foreach($healthposts as $healthpost)
                                            @if($healthpost->hospital_type == 3 || $healthpost->hospital_type == 5 || $healthpost->hospital_type == 6)
                                            @if($hp_code==$healthpost->hp_code)
                                                @php($selectedHealthpost = "selected")
                                            @else
                                                @php($selectedHealthpost = "")
                                            @endif
                                            <option value="{{$healthpost->hp_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div id ="from_to"></div>
                            <div class="form-group col-sm-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                        <div class="row col-md-12" style="padding-left: 30px;">
                            Reporting Days: {{ $reporting_days + 1 }}
                        </div>
                        <div class="clearfix"></div>
                        @if(Request::session()->has('message'))
                        <div class="panel-body">
                            <div class="alert alert-warning" role="alert">
                                <span class="text-danger">{!! Request::session()->get('message') !!}</span>
                            </div>
                        </div>
                        @endif
                        <hr>

                        <div class="dataTable_wrapper">
                            <table class="table table-striped table-bordered table-hover display dataTable" id="dataTable">
                                <thead>
                                <tr>
                                    <th>S.N</th>
                                    <th>District</th>
                                    <th>Municipality</th>
                                    <th>Organization</th>
                                    <th>Name</th>
                                    <th title="Hospital Register ID">H.R. ID</th>
                                    <th>Age</th>
                                    <th>gender</th>
                                    <th>Phone No</th>
                                    <th>Address</th>
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
                                @foreach($final_data as $key => $case)
                                    <?php
                                        if($case['gender'] == '1'){
                                            $gender = 'M';
                                        }elseif($case['gender'] == '2'){
                                            $gender = 'F';
                                        }else {
                                            $gender = 'O';
                                        }

                                        if($case['self_free'] == '1'){
                                            $paid_free = 'Paid';
                                        }elseif($case['self_free'] == '2'){
                                            $paid_free = 'Free';
                                        }else {
                                            $paid_free = '';
                                        }

                                        if($case['outcome_status'] == '1'){
                                            $outcome_status = 'Discharge';
                                        }elseif($case['outcome_status'] == '2'){
                                            $outcome_status = 'Death';
                                        }else {
                                            $outcome_status = 'Under Treatment';
                                        }
                                    ?>
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $case['district_name'] }}</td>
                                        <td>{{ $case['municipality_name'] }}</td>
                                        <td>{{ $case['healthpost_name'] }} </td>
                                        <td>{{ $case['name'] }} </td>
                                        <td>{{ $case['hospital_register_id'] }} </td>
                                        <td>{{ $case['age'] }} </td>
                                        <td>{{ $gender }} </td>
                                        <td>{{ $case['phone'] }} </td>
                                        <td>{{ $case['address'] }} </td>
                                        <td>{{ $case['guardian_name'] }} </td>
                                        <td>{{ $paid_free }} </td>
                                        <td>{{ $case['general_count'] }} </td>
                                        <td>{{ $case['hdu_count'] }} </td>
                                        <td>{{ $case['icu_count'] }} </td>
                                        <td>{{ $case['ventilator_count'] }} </td>
                                        <td>{{ $case['general_count'] + $case['hdu_count'] + $case['icu_count'] + $case['ventilator_count'] }} </td>

                                        <td>{{ $outcome_status }} </td>
                                        <td>{{ $case['register_date'] }} </td>
                                        <td>{{ $case['date_of_outcome'] }} </td>
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
    {{-- <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable/jquery.dataTables.min.css') }}"> --}}
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datatable/buttons.dataTables.min.css') }}">

@endsection
@section('script')
<script src="{{ asset('js/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/datatable/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/datatable/jszip.min.js') }}"></script>
<script src="{{ asset('js/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('js/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('js/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/datatable/buttons.print.min.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            responsive: true,
            scrollX: true,
            pageLength: 50,
            dom : 'Bfrtip',
            buttons: [
                'csv', 'excel',
                {
                    extend: 'pdfHtml5',
                    orientation: 'landscape',
                    pageSize: 'LEGAL'
                }
            ]
        });
    });
</script>
@endsection

