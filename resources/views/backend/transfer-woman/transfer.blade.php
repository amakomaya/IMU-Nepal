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
        $("#healthpost").text("Loading...").fadeIn("slow");
        $.get( "{{route("admin.healthpost-select")}}?id="+id,function(data){
            $("#healthpost").html(data);
        });
    }
</script>
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Transfer Woman : {{$woman->name}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                            <form class="form-horizontal" role="form" method="POST" action="{{route('transfer-woman.transfer-store')}}" >
                                {{ csrf_field() }}

                                

                                <div class="form-group{{ $errors->has('province_id') ? ' has-error' : '' }}">
                                    <label for="province_id" class="col-md-3 control-label">Province</label>
                                    
                                    <div class="col-md-7">
                                    <select id="province_id" class="form-control" name="province_id" onchange="provinceOnchange($(this).val())">
                                            <option value="">Select Province</option>
                                            @foreach ($provinces as $province )
                                               <option value="{{ $province->id }}" @if(old('province_id')=="$province->id") {{ 'selected' }} @endif >{{ $province->province_name }}</option>
                                            @endforeach
                                    </select>

                                        @if ($errors->has('province_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('province_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('district_id') ? ' has-error' : '' }}">
                                    <label for="district_id" class="col-md-3 control-label">District</label>
                                    
                                    <div class="col-md-7">
                                        <div id="district" >
                                            <select id="district_id" class="form-control" name="district_id" onchange="districtOnchange($(this).val())">
                                                    <option value="">Select District</option>
                                                    @foreach ($districts as $district )
                                                       <option value="{{ $district->id }}" @if(old('district_id')=="$district->id") {{ 'selected' }} @endif >{{ $district->district_name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>

                                        @if ($errors->has('district_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('district_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('municipality_id') ? ' has-error' : '' }}">
                                    <label for="municipality_id" class="col-md-3 control-label">Municipality</label>
                                    
                                    <div class="col-md-7">
                                        <div id="municipality">
                                            <select id="municipality_id" class="form-control" name="municipality_id" onchange="municipalityOnchange($(this).val())">
                                                    <option value="">Select Municipality</option>
                                                    @foreach ($municipalities as $municipality )
                                                       <option value="{{ $municipality->id }}" @if(old('municipality_id')=="$municipality->id") {{ 'selected' }} @endif >{{ $municipality->municipality_name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>

                                        @if ($errors->has('municipality_id'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('municipality_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('hp_code') ? ' has-error' : '' }}">
                                    <label for="hp_code" class="col-md-3 control-label">Healthpost</label>
                                    
                                    <div class="col-md-7">
                                        <div id="healthpost">
                                            <select id="hp_code" class="form-control" name="hp_code" >
                                                    <option value="">Select Healthpost</option>
                                                    @foreach ($healthposts as $healthpost )
                                                       <option value="{{ $healthpost->hp_code }}" @if(old('hp_code')=="$healthpost->hp_code") {{ 'selected' }} @endif >{{ $healthpost->name }}</option>
                                                    @endforeach
                                            </select>
                                        </div>

                                        @if ($errors->has('hp_code'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('hp_code') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('message') ? ' has-error' : '' }}">
                                    <label for="message" class="col-md-3 control-label">Message</label>
                                    
                                    <div class="col-md-7">
                                        <div id="healthpost">
                                            <textarea name="message" class="form-control">Please admit {{$woman->name}} to your healthpost.  Additional Information:</textarea>
                                        </div>

                                        @if ($errors->has('message'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('message') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input type="hidden" name="from_hp_code" value="{{$from_hp_code}}">

                                    <input type="hidden" name="woman_token" value="{{$woman->token}}">
                                    
                                </div>



                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
                                        <button type="submit" class="btn btn-success">
                                            Submit
                                        </button>
                                    </div>
                                </div>
                            </form>
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
                    