@extends('layouts.backend.app')
@section('style')
    <style type="text/css">
        label.error {
    color: red;
    font-size: 1rem;
    display: block;
    margin-top: 5px;
}

input.error {
    border: 1px dashed red;
    font-weight: 300;
    color: red;
}
    </style>
@endsection
@section('content')
    <div id="page-wrapper">
        <!-- add modal -->
            <div class="modal-content">
                <div class="modal-body">
                    {!! rcForm::open('POST', route('content-app.reg.woman.send-care'), ['id'=>'send-care']) !!}
                    <div class="form-group">
                    <label><h3>Confirm Before Send to CARE</h3></label><br>
                    <label>Select Organization</label>

                    <div class="row">
                          <div class="form-group col-sm-3" id="province">
                        <select name="select_province_id" class="form-control" onchange="provinceOnchange($(this).val())" required>
                            @foreach(\App\Models\Province::all() as $province)
                                <option value="{{$province->id}}"
                                    @if($province->id == 3)
                                        selected
                                    @endif
                                    >{{$province->province_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group  col-sm-3" id = "district">
                        <select name="select_district_id" class="form-control" onchange="districtOnchange($(this).val())" required>
                            @foreach($districts as $district)
                                <option value="{{$district->id}}" 
                                    @if($district->id == 27)
                                        selected
                                    @endif
                                    >{{$district->district_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group  col-sm-3" id="municipality">
                        <select name="select_municipality_id" class="form-control" onchange="municipalityOnchange($(this).val())" id="municipality_id" required>
                            @foreach($municipalities as $municipality)                                
                                <option value="{{$municipality->id}}" 
                                    @if($municipality->id == 331)
                                        selected
                                    @endif
                                    >{{$municipality->municipality_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group  col-sm-3" id="healthpost">
                        <select name="hp_code" class="form-control"  onchange="healthpostOnChange($(this).val())" required>
                            @foreach($healthposts as $healthpost) 
                                <option value="{{$healthpost->hp_code}}"
                                    @if($healthpost->hp_code == '3-27-331-003')
                                        selected
                                    @endif
                                    >{{$healthpost->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group col-sm-offset-9 text-center text-info">Ref : {{ json_decode($row->mis_data, true)['current_healthpost'] ?? ''}} </div>

                <div class="clearfix"></div>
                       
                </div> 
                <hr>
                {!! rcForm::text('Name', 'name', $row->name, ['required' => 'required', 'minlength'=>"2"]) !!}
                {!! rcForm::text('Phone No', 'phone', $row->phone, ['required' =>'required']) !!}
                {!! rcForm::text('Age', 'age', $row->age, ['required' =>'required']) !!}
                {!! rcForm::text('LMP Date EN', 'lmp_date_en', $row->lmp_date_en, ['required' =>'required']) !!}
                {!! rcForm::text('LMP Date NP', 'lmp_date_np', $row->lmp_date_np, ['required' =>'required']) !!}
                <div class="form-group">
                  <label>Select District</label>
                  <select class="form-control" name="district_id">
                    @foreach($districts as $item)
                        <option value="{{$item->id}}"
                            @if($item->id === $row->district_id) selected @endif
                            >{{$item->district_name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label>Select Municipalities</label>
                  <select class="form-control" name="municipality_id">
                    @foreach($municipalities as $item)
                        <option value="{{$item->id}}"
                            @if($item->id === $row->municipality_id) selected @endif
                            >{{$item->municipality_name}}</option>
                    @endforeach
                  </select>
                </div>
                {!! rcForm::text('Ward No', 'ward_no', $row->ward_no, ['required' =>'required']) !!}
                {!! rcForm::text('Tole', 'tole', $row->tole, ['required' =>'required']) !!}
                <div hidden>
                    {!! rcForm::text('', 'longitude', $row->longitude) !!}
                    {!! rcForm::text('', 'latitude', $row->latitude) !!}
                    {!! rcForm::text('', 'username', $row->username) !!}
                    {!! rcForm::text('', 'password', $row->password) !!}
                </div>
                <button class="btn btn-success btn-lg pull-right btn-block" onclick="return confirm('Are you sure you want to Send this on Amakomaya CARE?');"><i class="fa fa-send-o" aria-hidden="true"></i> <strong>Send CARE</strong> </button>
                </form>
            </div>
    </div>

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
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
            $("#healthpost").text("Loading...").fadeIn("slow");
            $.get("{{route("healthpost-select-municipality")}}?id=" + id, function (data) {
                $("#healthpost").html(data);
            });
        }

        $(document).ready(function() {
          $("#send-care").validate();

        });
</script>

@endsection
@endsection