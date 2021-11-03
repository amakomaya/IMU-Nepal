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
                        Cases Report
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
                                <select name="org_code" class="form-control"  >
                                    @if(Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                        <option value="">All Organization</option>
                                        @foreach($organizations as $healthpost)
                                            @if($healthpost->hospital_type == 3 || $healthpost->hospital_type == 5 || $healthpost->hospital_type == 6)
                                            @if($org_code==$healthpost->org_code)
                                                @php($selectedHealthpost = "selected")
                                            @else
                                                @php($selectedHealthpost = "")
                                            @endif
                                            <option value="{{$healthpost->org_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>
                                            @endif
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group  col-sm-3" id="service_for">
                                <select name="service_for" class="form-control"  >
                                    <option value="">Test Type</option>
                                    <option value="2" @if(request()->get('service_for') == '2') selected @endif>Antigen</option>
                                    <option value="1" @if(request()->get('service_for') == '1') selected @endif>PCR </option>
                                </select>
                            </div>
                            <div id ="from_to"></div>
                            <div class="form-group  col-sm-3" id="old_new_data">
                                <select name="old_new_data" class="form-control"  >
                                    <option value="1">Latest Data</option>
                                    <option value="2" @if(request()->get('old_new_data') == '2') selected @endif>Older than 15 days </option>
                                </select>
                            </div>
                            <div class="form-group col-sm-3">
                                <button type="submit" class="btn btn-success">Submit</button>
                            </div>
                        </form>
                        <div class="row col-md-12" style="padding-left: 30px;">
                            Reporting Days: {{ $reporting_days }}
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
                            <table class="table table-striped table-bordered table-hover display dataTable" id="dataTable" style="width: 100%";>
                                <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Date</th>
                                        <th>Name</th>
                                        {{-- <th>Province</th>
                                        <th>District</th>
                                        <th>Municipality</th> --}}
                                        <th>Age</th>
                                        <th>Sex</th>
                                        <th>Phone No.</th>
                                        <th>Result</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($final_data as $key => $case)
                                    <?php
                                    switch($case->result){
                                        case 9: $res = 'Received';
                                        break;
                                        case 3: $res = 'Positive';
                                        break;
                                        case 4: $res = 'Negative';
                                        break;
                                        default: $res = "Don't Know";
                                        break;
                                    }
                                    switch($case->sex){
                                        case 1: $sex = 'Male';
                                        break;
                                        case 2: $sex = 'Female';
                                        break;
                                        default: $sex = 'Other';
                                        break;
                                    }
                                    $date_en_array = explode("-", $case->updated_at);
                                    $date_np = Yagiten\Nepalicalendar\Calendar::eng_to_nep((int)$date_en_array[0], (int)$date_en_array[1], (int)$date_en_array[2])->getYearMonthDay();
                                    ?>
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $date_np }}</td>
                                        <td>{{ $case->name }}</td>
                                        {{-- <td>{{ $case->province_name }}</td>
                                        <td>{{ $case->district_name }}</td>
                                        <td>{{ $case->municipality_name }}</td> --}}
                                        <td>{{ $case->age }}</td>
                                        <td>{{ $sex }}</td>
                                        <td>{{ $case->emergency_contact_one }}</td>
                                        <td>{{ $res }} </td>
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

