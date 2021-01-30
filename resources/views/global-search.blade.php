<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}"/>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }} Admin</title>

    <!-- Styles -->
    <link href="{{ asset('css/backend.css') }}" rel="stylesheet">

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- MetisMenu CSS -->
    <link href="{{ asset('bower_components/metisMenu/dist/metisMenu.min.css') }}" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="{{ asset('bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css') }}" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ asset('bower_components/datatables-responsive/css/dataTables.responsive.css') }}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ asset('dist/css/sb-admin-2.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/pace.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

    <script src="{{ asset('js/sortable.js') }}"></script>

    <link rel="stylesheet" href="{{ mix('css/app.css') }}">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div id="wrapper">

    <!-- Navigation -->

    <div style="margin: 40px 0px 0px 0px">
        <div class="container panel-body">

            <form method="get" name="info">
                <br>
                <strong class="form-group">Select where you register from ?</strong>
                <hr>
                <div class="form-group col-sm-3" id="province">
                    <select name="province_id" class="form-control" onchange="provinceOnchange($(this).val())">
                        <option value="">Select All Provinces</option>
                        @foreach(\App\Models\province::all() as $province)

                            <option value="{{$province->id}}" @if(request()->province_id == $province->id) selected @endif >{{$province->province_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group  col-sm-3" id = "district">
                    <select name="district_id" class="form-control" onchange="districtOnchange($(this).val())">
                        <option value="">Select All Districts</option>
                        @if(request()->district_id)
                            <option value="{{ request()->district_id }}" selected>{{ \App\Models\District::where('id', request()->district_id)->first()->district_name ?? ''}}
                        @endif
{{--                        @foreach(\App\Models\District::all() as $district)--}}
{{--                            @if(1==$district->id)--}}
{{--                                @php($selectedDistrict = "selected")--}}
{{--                            @else--}}
{{--                                @php($selectedDistrict = "")--}}
{{--                            @endif--}}
{{--                            <option value="{{$district->id}}" {{$selectedDistrict}}>{{$district->district_name}}</option>--}}
{{--                        @endforeach--}}
                    </select>
                </div>
                <div class="form-group  col-sm-3" id="municipality">
                    <select name="municipality_id" class="form-control" onchange="municipalityOnchange($(this).val())" id="municipality_id">
                            <option value="">Select All Municipalities</option>
                        @if(request()->municipality_id)
                            <option value="{{ request()->municipality_id }}" selected>{{ \App\Models\Municipality::where('id', request()->municipality_id)->first()->municipality_name ?? ''}}
                        @endif
{{--                        @foreach(\App\Models\Municipality::all() as $municipality)--}}
{{--                            @if(1==$municipality->id)--}}
{{--                                @php($selectedMunicipality = "selected")--}}
{{--                            @else--}}
{{--                                @php($selectedMunicipality = "")--}}
{{--                            @endif--}}
{{--                            <option value="{{$municipality->id}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>--}}
{{--                        @endforeach--}}
                    </select>
                </div>
                <div class="form-group  col-sm-3" id="healthpost">
                    <select name="hp_code" class="form-control"  >
                        <option value="">Select All Healthposts</option>
                        @if(request()->hp_code)
                            <option value="{{ request()->hp_code }}" selected>{{ \App\Models\Organization::where('hp_code', request()->hp_code)->first()->name ?? ''}}
                        @endif
{{--                        @foreach(\App\Models\Organization::all() as $healthpost)--}}
{{--                            @if(''==$healthpost->hp_code)--}}
{{--                                @php($selectedHealthpost = "selected")--}}
{{--                            @else--}}
{{--                                @php($selectedHealthpost = "")--}}
{{--                            @endif--}}
{{--                            <option value="{{$healthpost->hp_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>--}}
{{--                        @endforeach--}}
                    </select>
                </div>
                <br>
                <strong class="form-group">Advanced Search <br> By your personal Information</strong>
                <hr>
                <div class="form-group  col-sm-3" id="Name">
                    <label for="name">Name</label>
                    <input class="form-control" placeholder="Enter Name" type="text" name="name" value="{{ request()->name }}">
                </div>
                <div class="form-group col-sm-3" id="age">
                    <label for="name">Age</label>
                    <input class="form-control" placeholder="Enter Age" type="text" name="age" value="{{ request()->age }}">
                </div>
                <div class="form-group col-sm-3" id="phone">
                    <label for="name">Phone</label>
                    <input class="form-control" placeholder="Enter Phone" type="text" name="phone" value="{{ request()->phone }}">
                </div>
                <div class="form-group  col-sm-3" id="organization">
                    <label for="organization">Select working Organization</label>
                    <select name="organization" class="form-control"  >
                        <option value="">Select All Organization Name</option>
                        @if(request()->organization)
                            <option value="{{ request()->organization }}" selected>{{ request()->organization}}
                            @endif
                        {{--                        @foreach(\App\Models\Organization::all() as $healthpost)--}}
                        {{--                            @if(''==$healthpost->hp_code)--}}
                        {{--                                @php($selectedHealthpost = "selected")--}}
                        {{--                            @else--}}
                        {{--                                @php($selectedHealthpost = "")--}}
                        {{--                            @endif--}}
                        {{--                            <option value="{{$healthpost->hp_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>--}}
                        {{--                        @endforeach--}}
                    </select>
                </div>

                <div class="form-group col-lg-12">
                    <button type="submit" class="btn btn-block btn-success">Search</button>
                </div>

            </form>
            <div class="clearfix"></div>
        </div>

    </div>

</div>

<!-- Bootstrap Core JavaScript -->
<script src="{{ asset('bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<!-- Metis Menu Plugin JavaScript -->
<script src="{{ asset('bower_components/metisMenu/dist/metisMenu.min.js') }}"></script>

<!-- DataTables JavaScript -->
<script src="{{ asset('bower_components/datatables/media/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js') }}"></script>

<!-- Custom Theme JavaScript -->
<script src="{{ asset('dist/js/sb-admin-2.js') }}"></script>

<script src="{{ asset('js/custom.js') }}"></script>

<script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
<script>
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
        $("#healthpost").text("Loading...").fadeIn("slow");
        $("#organization").text("Loading...").fadeIn("slow");
        municipality_id = $("#municipality_id").val();
        $.get( "{{route("admin.healthpost-select")}}?id="+id,function(data){
            $("#healthpost").html(data);
        });
        $.get( "{{route("admin.organization-select")}}?id="+id,function(data){
            $("#organization").html(data);
        });
    }
</script>
</body>
</html>