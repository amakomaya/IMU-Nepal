@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Create
                    </div>
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('patient.sample.store')) !!}
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label">Test Type</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="service_for" value="1" onclick="toggleLayout(true)"
                                               >PCR Swab Collection
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="service_for" value="2" onclick="toggleLayout(false)">Antigen
                                        Test
                                    </label>
                                </div>
                                @if ($errors->has('service_for'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('service_for') }}</small>
                                @endif
                            </div>
                            <div id="sample">
                                <div class="form-group">
                                    <label class="control-label">Sample Collection Type</label>
                                    <div class="control-group">
                                        <input type="checkbox" name="sample_type[]" value="1"> Nasopharyngeal<br>
                                        <input type="checkbox" name="sample_type[]" value="2"> Oropharyngeal
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('sample_type_specific') ? 'has-error' : '' }} ">
                                    <label for="sample_type_specific">If other specify sample collected type</label>
                                    <input type="text" class="form-control" name="sample_type_specific"
                                           aria-describedby="help"
                                           placeholder="Enter if other specify sample collected type"
                                    >
                                    @if ($errors->has('sample_type_specific'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('sample_type_specific') }}</small>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Infection Type</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="infection_type" value="1">Symptomatic
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="infection_type" value="2">Asymptomatic
                                    </label>
                                </div>
                                @if ($errors->has('infection_type'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('infection_type') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="control-label">Service Type</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" name="service_type" value="1">Paid Service
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="service_type" value="2">Free of cost service
                                    </label>
                                </div>
                                @if ($errors->has('service_type'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('service_type') }}</small>
                                @endif
                            </div>
                            <div class="panel panel-danger">
                                <div class="panel-heading"><strong>Auto Generated Sample ID is :</strong></div>
                                <div class="panel-body text-center"><h3>{{ $swab_id }}</h3></div>
                            </div>
                            <input type="text" name="token" value="{{$swab_id}}" hidden>
                            <input type="text" name="woman_token" value="{{$token}}" hidden>
                            {!! rcForm::close('post') !!}
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
        @section('script')
            <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
            <script type="text/javascript">
                function toggleLayout(sample) {
                    x = document.getElementById("sample");
                    if (sample) {
                        x.style.display = "block";
                    } else {
                        x.style.display = "none";
                    }
                }
            </script>
@endsection