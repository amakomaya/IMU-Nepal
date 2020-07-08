@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
@endsection<script type="text/javascript">
    function provinceOnchange(id) {
        $("#district").text("Loading...").fadeIn("slow");
        $.get("{{route("district-select-province")}}?id=" + id, function (data) {
            $("#district").html(data);
        });
    }

    function districtOnchange(id) {
        $("#municipality").text("Loading...").fadeIn("slow");
        $.get("{{route("municipality-select-district")}}?id=" + id, function (data) {
            $("#municipality").html(data);
        });
    }

    function municipalityOnchange(id) {
        $("#ward-or-healthpost").text("Loading...").fadeIn("slow");
        $.get("{{route("ward-or-healthpost-select-municipality")}}?id=" + id, function (data) {
            $("#ward-or-healthpost").html(data);
        });
    }

    function wardOrHealthpostOnchange(id) {
        if (id.startsWith('ward')) {
            $("#ward-healthpost").text("Loading...").fadeIn("slow");
            $.get("{{route("ward-select-municipality")}}?id=" + id, function (data) {
                $("#ward-healthpost").html(data);
                console.log(data);
            });
        } else {
            $("#ward-healthpost").text("Loading...").fadeIn("slow");
            $.get("{{route("healthpost-select-municipality")}}?id=" + id, function (data) {
                $("#ward-healthpost").html(data);
                console.log(data);

            });
        }
    }
</script>
<!-- /.row -->
<div class="row">
    <div class="col-lg-12">
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
            <div class="form-group  col-sm-3" id="district">
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
                <select name="municipality_id" class="form-control" onchange="municipalityOnchange($(this).val())"
                        id="municipality_id">
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
            <div class="from-group  col-sm-3" id="ward-or-healthpost">
                <select name="ward_or_healthpost" class="form-control"
                        onchange="wardOrHealthpostOnchange($(this).val())" id="ward-or-healthpost">
                    @if(Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                        <option value="">Select All Healthpost</option>
                    @endif
                    @foreach($options as $option)
                        @if($ward_or_healthpost==$option.$municipality_id)
                            @php($selectedoption = "selected")
                        @else
                            @php($selectedoption = "")
                        @endif
                        <option value="{{$option.$municipality_id}}" {{$selectedoption}}>By {{$option}}</option>
                    @endforeach
                </select>
            </div>
            <div class="clearfix"></div>
            <div class="form-group  col-sm-3" id="ward-healthpost">
                <select name="{{ (strncmp($ward_or_healthpost, "healthpost", 4) === 0) ? 'hp_code' : 'ward_id' }}"
                        class="form-control">
                    @if(Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                        <option value="">Select All</option>
                    @endif
                    @if(strncmp($ward_or_healthpost, "healthpost", 4) === 0)
                        @foreach($healthposts as $healthpost)
                            @if($hp_code==$healthpost->hp_code)
                                @php($selectedHealthpost = "selected")
                            @else
                                @php($selectedHealthpost = "")
                            @endif
                            <option value="{{$healthpost->hp_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>
                        @endforeach
                    @elseif(strncmp($ward_or_healthpost, "ward", 4) === 0)
                        @foreach($wards as $ward)
                            @if($ward_id==$ward->ward_no)
                                @php($selectedWard = "selected")
                            @else
                                @php($selectedWard = "")
                            @endif
                            <option value="{{$ward->ward_no}}" {{$selectedWard}}>{{$ward->ward_no}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="from-group  row-sm-3" id="select-year-month">
                <div id="app">
                <select-year-month></select-year-month>
                </div>
            </div>
            <div class="form-group col-sm-3">
                <button type="submit" class="btn btn-success">Submit</button>
            </div>
        </form>
        <div class="clearfix"></div>
    </div>
</div>
@section('script')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection