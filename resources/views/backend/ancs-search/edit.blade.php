@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <style>
        #all_form {
            background: #ecf5fc;
            padding: 40px 50px 45px;
        }
    </style>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                @if (Request::session()->has('message'))
                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        {!! Request::session()->get('message') !!}

                    </div>
                @endif
                <div class="panel">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <form action="" method="GET">
                                <div class="form-group">
                                    <h3>Search case by SID :</h3>
                                    <div class="row">
                                        <div class="container">
                                            <div class="input-group">
                                                <input type="text" name="sid" value="{{ request()->get('sid') }}" class="form-control" placeholder="Enter SID"/>
                                                <div class="input-group-btn">
                                                    <button class="btn btn-primary">
                                                        <span class="glyphicon glyphicon-search"></span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="panel-body">
                        <form action="{{ route('admin.ancs.update') }}" method="POST" id="all_form">
                        @csrf
                        <h2>Women Data</h2>
                        <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                            <label for="name">Full Name</label>
                            <input type="text" id="name" class="form-control" value="{{ $ancs ? $ancs->name : '' }}" name="name"
                                   aria-describedby="help" placeholder="Enter Full Name">
                            @if ($errors->has('name'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('name') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                            <label for="age">Age</label>
                            <input type="text" id="age" value="{{ $ancs ? $ancs->age : '' }}" class="form-control col-xs-9"
                                   name="age" placeholder="Enter Age"
                            ><br>
                            <input type="radio" name="age_unit"
                                   {{ $ancs && $ancs->age_unit == "0" ? 'checked' : '' }} value="0" data-rel="earning"
                                   checked>Years
                            <input type="radio" name="age_unit"
                                   {{ $ancs && $ancs->age_unit == "1" ? 'checked' : '' }} value="1" data-rel="earning">Months
                            <input type="radio" name="age_unit"
                                   {{ $ancs && $ancs->age_unit == "2" ? 'checked' : '' }} value="2" data-rel="earning">Days
                            <br>
                            @if ($errors->has('age'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('age') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="caste">Caste</label>
                            <select name="caste" class="form-control">
                                <option {{ $ancs && $ancs->caste == '6' ? "selected" : "" }} value="6">Don't Know</option>
                                <option {{ $ancs && $ancs->caste == '0' ? "selected" : "" }} value="0">Dalit</option>
                                <option {{ $ancs && $ancs->caste == '1' ? "selected" : "" }} value="1">Janajati</option>
                                <option {{ $ancs && $ancs->caste == '2' ? "selected" : "" }} value="2">Madheshi</option>
                                <option {{ $ancs && $ancs->caste == '3' ? "selected" : "" }} value="3">Muslim</option>
                                <option {{ $ancs && $ancs->caste == '4' ? "selected" : "" }} value="4">Brahmin/Chhetrai
                                </option>
                                <option {{ $ancs && $ancs->caste == '5' ? "selected" : "" }} value="5">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="company">Gender</label>
                            <select name="sex" class="form-control">
                                <option value="" disabled selected>Select Gender</option>
                                <option {{ $ancs && $ancs->sex == '1' ? "selected" : "" }} value="1">Male</option>
                                <option {{ $ancs && $ancs->sex == '2' ? "selected" : "" }} value="2">Female</option>
                                <option {{ $ancs && $ancs->sex == '3' ? "selected" : "" }}  value="3">Other</option>
                            </select>
                            @if ($errors->has('sex'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sex') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('ward') ? 'has-error' : '' }}">
                            <label for="ward">Ward No</label>
                            <input type="text" class="form-control" value="{{ $ancs ? $ancs->ward : '' }}" name="ward"
                                   aria-describedby="help" placeholder="Enter Ward No"
                            >
                            @if ($errors->has('ward'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('ward') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }}">
                            <label for="tole">Tole</label>
                            <input type="text" class="form-control" value="{{ $ancs ? $ancs->tole : '' }}" name="tole"
                                   aria-describedby="help" placeholder="Enter Tole"
                            >
                            @if ($errors->has('tole'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('tole') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('emergency_contact_one') ? 'has-error' : '' }}">
                            <label for="name">Emergency Contact One</label>
                            <input type="text" class="form-control" value="{{ $ancs ? $ancs->emergency_contact_one : '' }}"
                                   name="emergency_contact_one" aria-describedby="help"
                                   placeholder="Enter Emergency Contact One"
                            >
                            @if ($errors->has('emergency_contact_one'))
                                <small id="help"
                                       class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('emergency_contact_two') ? 'has-error' : '' }}">
                            <label for="name">Emergency Contact Two</label>
                            <input type="text" class="form-control" value="{{  $ancs ? $ancs->emergency_contact_two : '' }}"
                                   name="emergency_contact_two" aria-describedby="help"
                                   placeholder="Enter Emergency Contact Two">
                            @if ($errors->has('emergency_contact_one'))
                                <small id="help"
                                       class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('date_of_onset_of_first_symptom') ? 'has-error' : '' }}">
                            <label for="date_of_onset_of_first_symptom">Date of onset of first symptom:</label>
                            <input type="text" class="form-control" value="{{ $ancs ? $ancs->date_of_onset_of_first_symptom : '' }}"
                                   name="date_of_onset_of_first_symptom" aria-describedby="help">
                            @if ($errors->has('date_of_onset_of_first_symptom'))
                                <small id="help"
                                       class="form-text text-danger">{{ $errors->first('date_of_onset_of_first_symptom') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label" for="caste">Occupation</label>
                            <select name="occupation" class="form-control">
                                <option {{ $ancs && $ancs->occupation == '' ? "selected" : "" }} value="">Select Occupation
                                </option>
                                <option {{ $ancs && $ancs->occupation == '1' ? "selected" : "" }} value="1">Front Line Health
                                    Worker
                                </option>
                                <option {{ $ancs && $ancs->occupation == '2' ? "selected" : "" }} value="2">Doctor</option>
                                <option {{ $ancs && $ancs->occupation == '3' ? "selected" : "" }} value="3">Nurse</option>
                                <option {{ $ancs && $ancs->occupation == '4' ? "selected" : "" }} value="4">Police/Army
                                </option>
                                <option {{ $ancs && $ancs->occupation == '5' ? "selected" : "" }} value="5">
                                    Business/Industry
                                </option>
                                <option {{ $ancs && $ancs->occupation == '6' ? "selected" : "" }} value="6">
                                    Teacher/Student/Education
                                </option>
                                <option {{ $ancs && $ancs->occupation == '7' ? "selected" : "" }} value="7">Journalist
                                </option>
                                <option {{ $ancs && $ancs->occupation == '8' ? "selected" : "" }} value="8">Agriculture
                                </option>
                                <option {{ $ancs && $ancs->occupation == '9' ? "selected" : "" }} value="9">
                                    Transport/Delivery
                                </option>
                                <option {{ $ancs && $ancs->occupation == '10' ? "selected" : "" }} value="10">Other</option>
                            </select>
                            @if ($errors->has('occupation'))
                                <small id="help"
                                       class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label class="control-label">Have you traveled anywhere till 15 days ago?</label>
                            <div class="control-group">
                                <label class="radio-inline">
                                    <input type="radio"
                                           {{ $ancs && $ancs->travelled == "0" ? 'checked' : '' }} name="travelled" value="0"
                                           checked>No
                                </label>
                                <label class="radio-inline">
                                    <input type="radio"
                                           {{ $ancs && $ancs->travelled == "1" ? 'checked' : '' }} name="travelled"
                                           value="1">Yes
                                </label>
                            </div>
                        </div>
                        <div class="form-group" id="case_reason">
                            @php $reasons = json_decode($ancs->reson_for_testing); @endphp
                            <label class="control-label" for="reson_for_testing">Reason for testing:</label><br>
                            <input type="checkbox" name="reson_for_testing[]" value="1" @if(in_array(1, $reasons)) checked @endif>Planned travel<br>
                            <input type="checkbox" name="reson_for_testing[]" value="2" @if(in_array(2, $reasons)) checked @endif>Mandatory requirement<br>
                            <input type="checkbox" name="reson_for_testing[]" value="3" @if(in_array(3, $reasons)) checked @endif>Returnee/Migrant worker<br>
                            <input type="checkbox" name="reson_for_testing[]" value="4" @if(in_array(4, $reasons)) checked @endif>Pre-medical/surgical procedure<br>
                            <input type="checkbox" name="reson_for_testing[]" value="5" @if(in_array(5, $reasons)) checked @endif>Pregnancy complications/Pre-delivery<br>
                            <input type="checkbox" name="reson_for_testing[]" value="6" @if(in_array(6, $reasons)) checked @endif>Testing by Government authority for other purpose<br>
                            <input type="checkbox" name="reson_for_testing[]" value="7" @if(in_array(7, $reasons)) checked @endif>Test on demand by person<br>
                            @if ($errors->has('reson_for_testing'))
                                <small id="help"
                                       class="form-text text-danger">{{ $errors->first('reson_for_testing') }}</small>
                            @endif
                        </div>

                        <h2>Lab Data</h2>
                        <div class="form-group">
                            <label for="name">Sample Received Date</label>
                            <input type="text" id="sample_recv_date" class="form-control" value="{{ $ancs ? $ancs->sample_recv_date : '' }}" name="sample_recv_date"
                                   aria-describedby="help" placeholder="Enter Full Name">
                            @if ($errors->has('sample_recv_date'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_recv_date') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Sample Test Date</label>
                            <input type="text" id="sample_test_date" class="form-control" value="{{ $ancs ? $ancs->sample_test_date : '' }}" name="sample_test_date"
                                   aria-describedby="help" placeholder="Enter Full Name">
                            @if ($errors->has('sample_test_date'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_test_date') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Sample Test Time</label>
                            <input type="text" id="sample_test_time" class="form-control" value="{{ $ancs ? $ancs->sample_test_time : '' }}" name="sample_test_time"
                                   aria-describedby="help" placeholder="Enter Full Name">
                            @if ($errors->has('sample_test_time'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_test_time') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Sample Test Result</label>
                            <select name="sample_test_result" class="form-control">
                                <option value="" disabled selected>Select Sample Test Result</option>
                                <option {{ $ancs && $ancs->sample_test_result == '3' ? "selected" : "" }} value="3">Positive</option>
                                <option {{ $ancs && $ancs->sample_test_result == '4' ? "selected" : "" }} value="4">Negative</option>
                                <option {{ $ancs && $ancs->sample_test_result == '9' ? "selected" : "" }}  value="9">Received</option>
                                @php if($ancs->sample_test_result != 3 && $ancs->sample_test_result != 4 && $ancs->sample_test_result != 9) {
                                    $is_select = 'selected';
                                    $is_value = $ancs->sample_test_result;
                                } else {
                                    $is_select = '';
                                    $is_value = '10';
                                }
                                @endphp
                                <option {{ $is_select }} value="{{ $is_value }}">Don't Know</option>
                            </select>
                            @if ($errors->has('sample_test_result'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_test_result') }}</small>
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="name">Sample Token</label>
                            <input type="text" id="sample_token" class="form-control" value="{{ $ancs ? $ancs->sample_token : '' }}" name="sample_token"
                                   aria-describedby="help" placeholder="Enter Full Name">
                            @if ($errors->has('sample_token'))
                                <small id="help" class="form-text text-danger">{{ $errors->first('sample_token') }}</small>
                            @endif
                        </div>

                        <input type="hidden" name="woman_token" value="{{ $ancs ? $ancs->woman_token : '' }}">
                        <input type="hidden" name="sid" value="{{ request()->get('sid') }}">

                        {!! rcForm::close('post') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    {{-- <script type="text/javascript" src="{{ mix('js/app.js') }}"></script> --}}
@endsection
