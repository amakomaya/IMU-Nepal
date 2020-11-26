@extends('layouts.backend.app')
@section('style')
    <style>
        .earning {
            display: none;
        }

        form {
            background: #ecf5fc;
            padding: 40px 50px 45px;
        }

        .form-control:focus {
            border-color: #000;
            box-shadow: none;
        }

        label {
            font-weight: 600;
        }

        .error {
            color: red;
            font-weight: 400;
            display: block;
            padding: 6px 0;
            font-size: 14px;
        }

        .form-control.error {
            border-color: red;
            padding: .375rem .75rem;
        }
    </style>
@endsection
@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Sample Update
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form action="{{ route('sample.update',$data->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="panel-body">
                                <div class="form-group {{ $errors->has('token') ? 'has-error' : '' }}">
                                    <label for="token">Token</label>
                                    <input type="text" id="token" class="form-control" name="token"
                                           aria-describedby="help" placeholder="Enter Token" value="{{ $data->token }}"
                                    >
                                    @if ($errors->has('token'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('token') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('woman_token') ? 'has-error' : '' }}">
                                    <label for="woman_token">Woman Token</label>
                                    <input type="text" id="woman_token" class="form-control" name="woman_token"
                                           aria-describedby="help" placeholder="Enter Woman Token"
                                           value="{{ $data->woman_token }}"
                                    >
                                    @if ($errors->has('woman_token'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('woman_token') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('sample_type') ? 'has-error' : '' }}">
                                    <label for="sample_type">Sample Type</label><br>
                                    <input type="radio" name="sample_type"
                                           {{ $data->sample_type == "1" ? 'checked' : '' }} value="1" data-rel="earning"
                                           checked>Nasopharyngeal
                                    <input type="radio" name="age_unit"
                                           {{ $data->sample_type == "2" ? 'checked' : '' }} value="2" data-rel="earning">Oropharyngeal
                                    <br>
                                    @if ($errors->has('sample_type'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('sample_type') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('sample_type_specific') ? 'has-error' : '' }}">
                                    <label for="sample_type_specific">Sample Type Specific</label>
                                    <input type="text" id="sample_type_specific" class="form-control" name="sample_type_specific"
                                           aria-describedby="help" placeholder="Enter Sample Type Specific"
                                           value="{{ $data->sample_type_specific }}"
                                    >
                                    @if ($errors->has('sample_type_specific'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('sample_type_specific') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('infection_type') ? 'has-error' : '' }}">
                                    <label for="infection_type">Infection Type</label><br>
                                    <input type="radio" name="infection_type"
                                           {{ $data->infection_type == "1" ? 'checked' : '' }} value="1" data-rel="earning"
                                           checked>Symptomatic
                                    <input type="radio" name="infection_type"
                                           {{ $data->infection_type == "2" ? 'checked' : '' }} value="2" data-rel="earning">Asymptomatic
                                    <br>
                                    @if ($errors->has('infection_type'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('infection_type') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('service_type') ? 'has-error' : '' }}">
                                    <label for="service_type">Service Type</label><br>
                                    <input type="radio" name="service_type"
                                           {{ $data->service_type == "1" ? 'checked' : '' }} value="1" data-rel="earning"
                                           checked>Paid service
                                    <input type="radio" name="infection_type"
                                           {{ $data->service_type == "2" ? 'checked' : '' }} value="2" data-rel="earning">Free of cost
                                    <br>
                                    @if ($errors->has('service_type'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('service_type') }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="result">Result</label>
                                    <select name="result" class="form-control">
                                        <option {{ $data->result == '2' ? "selected" : "" }} value="2">Pending</option>
                                        <option {{ $data->result == '3' ? "selected" : "" }} value="3">Positive</option>
                                        <option {{ $data->result == '4' ? "selected" : "" }} value="4">Negative</option>
                                        <option {{ $data->result == '5' ? "selected" : "" }} value="5">Don't Know</option>
                                        <option {{ $data->result == '6' ? "selected" : "" }} value="6">Rejected</option>
                                    </select>
                                </div>

                                {!! rcForm::close('post') !!}
                            </div>
                        </form>
                        <!-- /.panel-body -->
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
        </div>
    </div>
@endsection