@extends('layouts.backend.app')
@section('style')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        .earning {
            display: none;
        }

        .lab-form {
            background: #ecf5fc;
            padding: 20px 50px 45px;
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
                @if (Request::session()->has('message'))
                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        {!! Request::session()->get('message') !!}
                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading">
                       <h3 class="text-center"><span id="form_title"></span>POE Registration Form</h3>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form class="form-group lab-form" role="form" action="{{route('woman.store')}}" enctype="multipart/form-data" method="POST" name="createCase" id="createCase" novalidate="novalidate" onsubmit="disableSubmit()">
                          @csrf
                        <div class="panel-body">
                            <div class="form-group {{ $errors->has('register_date_np') ? 'has-error' : '' }}">
                                <label for="register_date_np">Date of persons entering Nepal through land-crossings (LC)/Point of Entry (POE)</label>
                                <input type="text" id="register_date_np" class="form-control" value="{{ old('register_date_np') }}" name="register_date_np"
                                       aria-describedby="help" placeholder="Enter Date of POE" readonly>
                                @if ($errors->has('register_date_np'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('register_date_np') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" class="form-control" value="{{ old('name') }}" name="name"
                                       aria-describedby="help" placeholder="Enter Full Name">
                                @if ($errors->has('name'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                                <label for="age">Age</label>
                                <input type="text" id="age" value="{{ old('age') }}" class="form-control col-xs-9"
                                       name="age" placeholder="Enter Age"
                                ><br>
                                <input type="radio" name="age_unit"
                                       {{ old('age_unit') == "0" ? 'checked' : '' }} value="0" data-rel="earning"
                                       checked>Years
                                <input type="radio" name="age_unit"
                                       {{ old('age_unit') == "1" ? 'checked' : '' }} value="1" data-rel="earning">Months
                                <input type="radio" name="age_unit"
                                       {{ old('age_unit') == "2" ? 'checked' : '' }} value="2" data-rel="earning">Days
                                <br>
                                @if ($errors->has('age'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('age') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="caste">Ethnicity</label>
                                <select name="caste" class="form-control">
                                    <option {{ old('caste') == '7' ? "selected" : "" }} value="7">Don't Know</option>
                                    <option {{ old('caste') == '1' ? "selected" : "" }} value="1">Dalit</option>
                                    <option {{ old('caste') == '2' ? "selected" : "" }} value="2">Janajati</option>
                                    <option {{ old('caste') == '3' ? "selected" : "" }} value="3">Madheshi</option>
                                    <option {{ old('caste') == '4' ? "selected" : "" }} value="4">Muslim</option>
                                    <option {{ old('caste') == '5' ? "selected" : "" }} value="5">Brahmin/Chhetri
                                    </option>
                                    <option {{ old('caste') == '6' ? "selected" : "" }} value="6">Other</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="company">Gender</label>
                                <select name="sex" class="form-control">
                                    <option {{ old('sex') == '' ? "selected" : "" }} value="">-- Select Gender --</option>
                                    <option {{ old('sex') == '1' ? "selected" : "" }} value="1">Male</option>
                                    <option {{ old('sex') == '2' ? "selected" : "" }} value="2">Female</option>
                                    <option {{ old('sex') == '3' ? "selected" : "" }}  value="3">Other</option>
                                </select>
                                @if ($errors->has('sex'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('sex') }}</small>
                                @endif
                            </div>
                            <div class="form-group">
                                <label class="control-label" for="occupation">Occupation</label>
                                <select name="occupation" class="form-control" id="occupation">
                                    <option {{ old('occupation') == '' ? "selected" : "" }} value="">-- Select Occupation --</option>
                                    <option {{ old('occupation') == '1' ? "selected" : "" }} value="1">Front Line Health Worker</option>
                                    <option {{ old('occupation') == '2' ? "selected" : "" }} value="2">Doctor</option>
                                    <option {{ old('occupation') == '3' ? "selected" : "" }} value="3">Nurse</option>
                                    <option {{ old('occupation') == '4' ? "selected" : "" }} value="4">Police/Army</option>
                                    <option {{ old('occupation') == '5' ? "selected" : "" }} value="5">Business/Industry</option>
                                    <option {{ old('occupation') == '6' ? "selected" : "" }} value="6">Teacher/Student/Education</option>
                                    <option {{ old('occupation') == '7' ? "selected" : "" }} value="7">Civil Servant</option>
                                    <option {{ old('occupation') == '8' ? "selected" : "" }} value="8">Journalist</option>
                                    <option {{ old('occupation') == '9' ? "selected" : "" }} value="9">Agriculture</option>
                                    <option {{ old('occupation') == '10' ? "selected" : "" }} value="10">Transport/Delivery</option>
                                    <option {{ old('occupation') == '12' ? "selected" : "" }} value="12">Tourist</option>
                                    <option {{ old('occupation') == '13' ? "selected" : "" }} value="13">Migrant Worker</option>
                                    <option {{ old('occupation') == '11' ? "selected" : "" }} value="11">Other</option>
                                </select>
                                @if ($errors->has('occupation'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('nationality') ? 'has-error' : '' }}">
                                <label for="nationality">Nationality</label>
                                <select name="nationality" class="form-control" id="nationality">
                                    <option {{ old('nationality') == '' ? "selected" : "" }} value="">-- Select Nationality --</option>
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->country_id }}" 
                                            {{ old('nationality') == $country->country_id ? "selected" : "" }}>{{ $country->name }}</option>
                                    @endforeach
                                    <option {{ old('nationality') == '300' ? "selected" : "" }} value="300">Other</option>
                                </select>
                                @if ($errors->has('nationality'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('nationality') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('id_card_type') ? 'has-error' : '' }}">
                                <label for="id_card_type">ID Card Type</label>
                                <select name="id_card_type" class="form-control id_card_type">
                                    <option {{ old('id_card_type') == '' ? "selected" : "" }} value="">-- Select ID Card Type --</option>
                                    <option {{ old('id_card_type') == '1' ? "selected" : "" }} value="1">Citizenship</option>
                                    <option {{ old('id_card_type') == '2' ? "selected" : "" }} value="2">License</option>
                                    <option {{ old('id_card_type') == '3' ? "selected" : "" }} value="3">Voter Card</option>
                                    <option {{ old('id_card_type') == '4' ? "selected" : "" }} value="4">Passport</option>
                                    <option {{ old('id_card_type') == '0' ? "selected" : "" }} value="0">Other</option>
                                </select>
                                @if ($errors->has('id_card_type'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('id_card_type') }}</small>
                                @endif
                            </div>
                            <div class="form-group id_card_type_other {{ $errors->has('id_card_type_other') ? 'has-error' : '' }}">
                                <label for="id_card_type_other">Other ID Card Type</label>
                                <input type="text" id="id_card_type_other" class="form-control" value="{{ old('id_card_type_other') }}" name="id_card_type_other"
                                       aria-describedby="help" placeholder="Enter Other ID Card Type">
                                @if ($errors->has('id_card_type_other'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('id_card_type_other') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('id_card_detail') ? 'has-error' : '' }}">
                                <label for="id_card_detail">ID Card No.</label>
                                <input type="text" id="id_card_detail" class="form-control" value="{{ old('id_card_detail') }}" name="id_card_detail"
                                       aria-describedby="help" placeholder="Enter ID Card No.">
                                @if ($errors->has('id_card_detail'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('id_card_detail') }}</small>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('emergency_contact_one') ? 'has-error' : '' }}">
                                <label for="name">Contact phone number in Nepal</label>
                                <input type="text" class="form-control" value="{{ old('emergency_contact_one') }}"
                                       name="emergency_contact_one" aria-describedby="help"
                                       placeholder="Contact phone number in Nepal"
                                >
                                @if ($errors->has('emergency_contact_one'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                                @endif
                            </div>
                            <div class="card" style="background: white">
                                <div class="" style="padding: 20px;">
                                    <h4>Travelled From</h4>
                                    <div class="row">
                                        <div class="form-group col-md-6 {{ $errors->has('travelled_where') ? 'has-error' : '' }}">
                                            <label for="travelled_where">Country</label>
                                            <select name="travelled_where" class="form-control" id="travelled_where">
                                                <option {{ old('travelled_where') == '' ? "selected" : "" }} value="">-- Select Travelled From (Country) --</option>
                                                @foreach ($countries as $country)
                                                    <option value="{{ $country->country_id }}" 
                                                        {{ old('travelled_where') == $country->country_id ? "selected" : "" }}>{{ $country->name }}</option>
                                                @endforeach
                                                <option {{ old('travelled_where') == '300' ? "selected" : "" }} value="300">Other</option>
                                            </select>
                                            @if ($errors->has('travelled_where'))
                                                <small id="help" class="form-text text-danger">{{ $errors->first('travelled_where') }}</small>
                                            @endif
                                        </div>
                                        <div class="form-group col-md-6 {{ $errors->has('travelled_city') ? 'has-error' : '' }}">
                                            <label for="travelled_city">City</label>
                                            <input type="text" id="travelled_city" class="form-control" value="{{ old('travelled_city') }}" name="travelled_city"
                                                aria-describedby="help" placeholder="Travelled From (City)">
                                            @if ($errors->has('travelled_city'))
                                                <small id="help" class="form-text text-danger">{{ $errors->first('travelled_city') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card" style="background: white; margin-top:10px;">
                                <div class="" style="padding: 20px;">
                                    <h4>Place of stay in Nepal</h4>
                                    <div class="form-group">
                                        <div class="row">
                                            <div class="form-group col-sm-4" id="province">
                                                <select name="province_id" class="form-control"
                                                        onchange="provinceOnchange($(this).val())">
                                                    @if(Auth::user()->role!="province" && Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                        <option value="">Select All Provinces</option>
                                                    @endif
                                                    @foreach(\Illuminate\Support\Facades\Cache::remember('province-list', 48*60*60, function () {
                                              return App\Models\Province::select(['id', 'province_name'])->get();
                                            }) as $province)
                                                        @if($province_id==$province->id || old('province_id')==$province->id)
                                                            @php($selectedProvince = "selected")
                                                        @else
                                                            @php($selectedProvince = "")
                                                        @endif
                                                        <option value="{{$province->id}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('province_id'))
                                                    <small id="help"
                                                        class="form-text text-danger">{{ $errors->first('province_id') }}</small>
                                                @endif
                                            </div>
                                            <div class="form-group col-sm-4" id="district">
                                                <select name="district_id" class="form-control"
                                                        onchange="districtOnchange($(this).val())">
                                                    @if(Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                        <option value="">Select All Districts</option>
                                                    @endif
                                                    @foreach(App\Models\District::where('province_id', $province_id)->get() as $district)
                                                        @if($district_id==$district->id || old('district_id')==$district->id)
                                                            @php($selectedDistrict = "selected")
                                                        @else
                                                            @php($selectedDistrict = "")
                                                        @endif
                                                        <option value="{{$district->id}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('district_id'))
                                                    <small id="help"
                                                        class="form-text text-danger">{{ $errors->first('district_id') }}</small>
                                                @endif
                                            </div>
                                            <div class="form-group col-sm-4" id="municipality">
                                                <select name="municipality_id" class="form-control"
                                                        onchange="municipalityOnchange($(this).val())"
                                                        id="municipality_id">
                                                    @if(Auth::user()->role!="municipality" && Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                        <option value="">Select All Municipalities</option>
                                                    @endif
                                                    @foreach(\App\Models\Municipality::where('district_id', $district_id)->get() as $municipality)
                                                        @if($municipality_id==$municipality->id  || old('municipality_id')==$municipality->id)
                                                            @php($selectedMunicipality = "selected")
                                                        @else
                                                            @php($selectedMunicipality = "")
                                                        @endif
                                                        <option value="{{$municipality->id}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
                                                    @endforeach
                                                </select>
                                                @if ($errors->has('municipality_id'))
                                                    <small id="help"
                                                        class="form-text text-danger">{{ $errors->first('municipality_id') }}</small>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="form-group col-md-6 {{ $errors->has('ward') ? 'has-error' : '' }}">
                                                <label for="ward">Ward No</label>
                                                <input type="text" class="form-control" value="{{ old('ward') }}" name="ward"
                                                    aria-describedby="help" placeholder="Enter Ward No"
                                                >
                                                @if ($errors->has('ward'))
                                                    <small id="help" class="form-text text-danger">{{ $errors->first('ward') }}</small>
                                                @endif
                                            </div>
                                            <div class="form-group col-md-6 {{ $errors->has('tole') ? 'has-error' : '' }}">
                                                <label for="tole">Tole</label>
                                                <input type="text" class="form-control" value="{{ old('tole') }}" name="tole"
                                                    aria-describedby="help" placeholder="Enter Tole"
                                                >
                                                @if ($errors->has('tole'))
                                                    <small id="help" class="form-text text-danger">{{ $errors->first('tole') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card" style="background: white; margin-top:10px;">
                                <div class="" style="padding: 20px;">
                                    <h4>Nearest Contact person in Nepal</h4>
                                    <div class="row">
                                        <div class="form-group col-md-6 {{ $errors->has('nearest_contact') ? 'has-error' : '' }}">
                                            <label for="name">Full Name</label>
                                            <input type="text" class="form-control" value="{{ old('nearest_contact') }}"
                                                name="nearest_contact" aria-describedby="help"
                                                placeholder="Full Name"
                                            >
                                            @if ($errors->has('nearest_contact'))
                                                <small id="help"
                                                    class="form-text text-danger">{{ $errors->first('nearest_contact') }}</small>
                                            @endif
                                        </div>
                                        
                                        <div class="form-group col-md-6 {{ $errors->has('emergency_contact_two') ? 'has-error' : '' }}">
                                            <label for="name">Phone</label>
                                            <input type="text" class="form-control" value="{{ old('emergency_contact_two') }}"
                                                name="emergency_contact_two" aria-describedby="help"
                                                placeholder="Phone">
                                            @if ($errors->has('emergency_contact_two'))
                                                <small id="help"
                                                    class="form-text text-danger">{{ $errors->first('emergency_contact_two') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group" style="margin-top:15px;">
                                <label class="control-label">Currently symptomatic?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <h5>Symptomatic</h5>
                                        <input type="radio" name="symptoms_recent" value="1" id="symptoms_recent" class="symptoms_recent" style="bottom: 12px;" required>
                                    </label>
                                    <label class="radio-inline">
                                        <h5>Asymptomatic</h5>
                                        <input type="radio" name="symptoms_recent" value="0" class="symptoms_recent" style="bottom: 12px;">
                                    </label>
                                </div>
                                @if ($errors->has('symptoms_recent'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('symptoms_recent') }}</small>
                                @endif
                            </div>
                            
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="{{ $errors->has('temperature') ? 'has-error' : '' }}">
                                            <label for="name">Body Temperature</label><br>
            
                                            <input type="radio" name="temperature_type" class="temperature_type"
                                                {{ old('temperature_type') == "1" ? 'checked' : '' }} value="1" checked> Fahrenheit 
                                            <input type="radio" name="temperature_type" class="temperature_type"
                                                {{ old('temperature_type') == "2" ? 'checked' : '' }} value="2"> Celsius<br>
            
                                            <input type="number" class="form-control temperature" value="{{ old('temperature') }}"
                                                    name="temperature" aria-describedby="help"
                                                    placeholder="Body Temperature" style="margin-top: 10px;">
                                            @if ($errors->has('temperature'))
                                            <small id="help"
                                            class="form-text text-danger">{{ $errors->first('temperature') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3 asymptomatic">
                                        <div class="{{ $errors->has('fever') ? 'has-error' : '' }}">
                                            <label for="name">Fever (>38 C/100.4F)</label>
                                            <div class="control-group">
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                        {{ old('fever') == "0" ? 'checked' : '' }} name="fever"
                                                        value="0" class="fever" id="fever_no">No
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio"
                                                        {{ old('fever') == "1" ? 'checked' : '' }} name="fever"
                                                        value="1" class="fever" id="fever_yes">Yes
                                                </label>
                                            </div>
                                            @if ($errors->has('fever'))
                                                <small id="help"
                                                    class="form-text text-danger">{{ $errors->first('fever') }}</small>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="asymptomatic">
                                            <div class="fever-status">
                                                <div class="{{ $errors->has('malaria') ? 'has-error' : '' }}">
                                                    <label for="name">RDT-Malaria Test performed?</label>
                                                    <div class="control-group">
                                                        <label class="radio-inline">
                                                            <input type="radio"
                                                                {{ old('malaria_test_status') == "0" ? 'checked' : '' }} name="malaria_test_status"
                                                                value="0" class="malaria_test_status">No
                                                        </label>
                                                        <label class="radio-inline">
                                                            <input type="radio"
                                                                {{ old('malaria_test_status') == "1" ? 'checked' : '' }} name="malaria_test_status"
                                                                value="1" class="malaria_test_status">Yes
                                                        </label>
                                                    </div>
                                                    @if ($errors->has('malaria_test_status'))
                                                        <small id="help"
                                                            class="form-text text-danger">{{ $errors->first('malaria') }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="asymptomatic">
                                            <div class="fever-status">
                                                <div class="malaria-status">
                                                    <div class="{{ $errors->has('malaria_result') ? 'has-error' : '' }}">
                                                        <label for="name">Malaria Test Result</label>
                                                        <div class="control-group">
                                                            <label class="radio-inline">
                                                                <input type="radio"
                                                                    {{ old('malaria_result') == "0" ? 'checked' : '' }} name="malaria_result"
                                                                    value="0" class="malaria_result">Negative
                                                            </label>
                                                            <label class="radio-inline">
                                                                <input type="radio"
                                                                    {{ old('malaria_result') == "1" ? 'checked' : '' }} name="malaria_result"
                                                                    value="1" class="malaria_result">Positive
                                                            </label>
                                                        </div>
                                                        @if ($errors->has('malaria_result'))
                                                            <small id="help"
                                                                class="form-text text-danger">{{ $errors->first('malaria_result') }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="asymptomatic">
                                <div class="form-group" id="symptomatic-patient">
                                    <label class="control-label" for="symptoms">Covid-19 Symptoms:</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="checkbox" name="symptoms[]" value="1"> Pneumonia<br>
                                            <input type="checkbox" name="symptoms[]" value="2"> ARDS<br>
                                            <input type="checkbox" name="symptoms[]" value="3"> Influenza-like illness<br>
                                            <input type="checkbox" name="symptoms[]" value="4"> History of fever/chills<br>
                                            <input type="checkbox" name="symptoms[]" value="5"> General weakness<br>
                                            <input type="checkbox" name="symptoms[]" value="6"> Cough<br>
                                            <input type="checkbox" name="symptoms[]" value="7"> Sore Throat<br>
                                            <input type="checkbox" name="symptoms[]" value="8"> Running nose<br>
                                            <input type="checkbox" name="symptoms[]" value="9"> Shortness of breath<br>
                                            <input type="checkbox" name="symptoms[]" value="10"> Irritability/Confusion<br>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="checkbox" name="symptoms[]" value="11"> Loss of taste<br>
                                            <input type="checkbox" name="symptoms[]" value="12"> Loss of smell<br>
                                            <input type="checkbox" name="symptoms[]" value="17"> Diarrhea<br>
                                            <input type="checkbox" name="symptoms[]" value="18"> Nausea/vomiting<br>
                                            <input type="checkbox" name="symptoms[]" value="19"> Headache<br>
                                            <input type="checkbox" name="symptoms[]" value="20"> Pharyngeal exudate<br>
                                            <input type="checkbox" name="symptoms[]" value="21"> Conjunctival injection(eye)<br>
                                            <input type="checkbox" name="symptoms[]" value="22"> Seizure<br>
                                            <input type="checkbox" name="symptoms[]" value="23"> Coma<br>
                                            <input type="checkbox" name="symptoms[]" value="24"> Dyspnea/tachynea(DB/Fast breathing)<br>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="checkbox" name="symptoms[]" value="25"> Abnormal lung auscultation<br>
                                            <input type="checkbox" name="symptoms[]" value="26"> Abnormal lung x-ray/CT scan findings<br>
                                            <div style="padding-top: 10px;">
                                                <label>Pain</label><br>
                                                <input type="checkbox" name="symptoms[]" value="13"> Muscular<br>
                                                <input type="checkbox" name="symptoms[]" value="14"> Chest<br>
                                                <input type="checkbox" name="symptoms[]" value="15"> Abdominal<br>
                                                <input type="checkbox" name="symptoms[]" value="16"> Joint<br>
                                            </div>
                                            <div class="{{ $errors->has('symptoms_specific') ? 'has-error' : '' }}" style="padding-top: 10px;">
                                                <label for="symptoms_specific">If other specify</label>
                                                <input type="text" class="form-control" value="{{ old('symptoms_specific') }}" name="symptoms_specific"
                                                        aria-describedby="help" placeholder="Enter other symptoms"
                                                >
                                                @if ($errors->has('symptoms_specific'))
                                                    <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_specific') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($errors->has('symptoms'))
                                        <small id="help"
                                            class="form-text text-danger">{{ $errors->first('symptoms') }}</small>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group " id="symptoms_comorbidity">
                                    <label class="control-label" for="symptoms_comorbidity">Comorbidity:</label>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="1"> Diabetes<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="2"> HTN<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="3"> Hermodialysis<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="4"> Immunocompromised<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="7"> Heart disease, including hypertension<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="8"> Liver disease<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="9"> Nerve related diseases<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="10"> Kidney diseases<br>
                                        </div>
                                        <div class="col-md-4">
                                            <label>Pregnancy(Trimester)</label><br>
                                            <div style="padding-left: 10px; padding-bottom: 10px;">
                                                <input type="radio" name="symptoms_comorbidity_trimester" value="5"> First<br>
                                                <input type="radio" name="symptoms_comorbidity_trimester" value="16"> Second<br>
                                                <input type="radio" name="symptoms_comorbidity_trimester" value="17"> Third<br>
                                                <input type="radio" name="symptoms_comorbidity_trimester" value=""> No<br>
                                            </div>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="6"> Maternity<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="11"> Malnutrition<br>
                                        </div>
                                        <div class="col-md-4">
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="12"> Autoimmune diseases<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="13"> Immunodeficiency, including HIV<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="14"> Malignancy<br>
                                            <input type="checkbox" name="symptoms_comorbidity[]" value="15"> Chric lung disesase/asthma/artery<br>
                                            <div class="{{ $errors->has('symptoms_comorbidity_specific') ? 'has-error' : '' }}" style="padding-top:10px;">
                                                <label for="symptoms_comorbidity_specific">If other specify</label>
                                                <input type="text" class="form-control" value="{{ old('symptoms_comorbidity_specific') }}" name="symptoms_comorbidity_specific"
                                                        aria-describedby="help" placeholder="Enter other comorbidity"
                                                >
                                                @if ($errors->has('symptoms_comorbidity_specific'))
                                                    <small id="help" class="form-text text-danger">{{ $errors->first('symptoms_comorbidity_specific') }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @if ($errors->has('symptoms_comorbidity'))
                                        <small id="help"
                                                class="form-text text-danger">{{ $errors->first('symptoms_comorbidity') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label">Have you ever received Covid-19 vaccine?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccine_status') == "0" ? 'checked' : '' }} name="vaccine_status"
                                               value="0" class="vaccine_status" required>No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccine_status') == "1" ? 'checked' : '' }} name="vaccine_status"
                                               value="1" class="vaccine_status">Yes
                                    </label>
                                </div>
                            </div>

                            {{-- <div class="form-group vaccine-status">
                                <label class="control-label">Vaccination doses complete?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccination_dosage_complete') == "0" ? 'checked' : '' }} name="vaccination_dosage_complete"
                                               value="0" class="vaccination_dosage_complete">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccination_dosage_complete') == "1" ? 'checked' : '' }} name="vaccination_dosage_complete"
                                               value="1" class="vaccination_dosage_complete">Yes
                                    </label>

                                </div>
                            </div> --}}

                            {{-- <div class="form-group vaccine-status">
                                <label class="control-label">How many dosages of vaccine you have received?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccine_dosage_count') == "1" ? 'checked' : '' }} name="vaccine_dosage_count"
                                               value="1" class="vaccine_dosage_count">1st Dose
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('vaccine_dosage_count') == "2" ? 'checked' : '' }} name="vaccine_dosage_count"
                                               value="2" class="vaccine_dosage_count">2nd (Final) Dose
                                    </label>

                                </div>
                            </div> --}}

                            <div class="form-group vaccine-status {{ $errors->has('vaccine_name') ? 'has-error' : '' }}">
                                <label for="vaccine_name">Name of Vaccine</label>
                                <select name="vaccine_name" class="form-control" id="vaccine_name">
                                    <option {{ old('vaccine_name') == '' ? "selected" : "" }} value="">-- Select Name of Vaccine --</option>
                                    @foreach($vaccines as $vaccine)
                                        <option value="{{ $vaccine->id }}" {{ old('vaccine_name') == $vaccine->id ? "selected" : "" }}>{{ $vaccine->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group vaccine-other {{ $errors->has('vaccine_name_other') ? 'has-error' : '' }}">
                                <label for="vaccine_name_other">If other specify</label>
                                <input type="text" class="form-control" value="{{ old('vaccine_name_other') }}" name="vaccine_name_other"
                                        aria-describedby="help" placeholder="Enter other vaccine name"
                                >
                                @if ($errors->has('vaccine_name_other'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('vaccine_name_other') }}</small>
                                @endif
                            </div>
                            <div class="row">
                            <div class="form-group col-md-6 vaccine-status">
                                <table class="table table-bordered">
                                    <tr>
                                        <td></td>
                                        <td>Vaccination Date</td>
                                    </tr>
                                    <tr class="table-sars-cov-tr">
                                        <td>
                                            1st Dose
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="dose_one_date" id="dose_one_date">
                                        </td>
                                    </tr>
                                    <tr class="not-jonson">
                                        <td>
                                            2nd Dose
                                        </td>
                                        <td>
                                            <input type="text" class="form-control" name="dose_two_date" id="dose_two_date">
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            </div>
                            <br>
                            
                            <div class="form-group">
                                <label class="control-label">Are you collecting COVID -19 swab now ?</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('swab_collection_conformation') == "0" ? 'checked' : '' }} name="swab_collection_conformation"
                                               value="0" checked class="swab_collection_conformation">No
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio"
                                               {{ old('swab_collection_conformation') == "1" ? 'checked' : '' }} name="swab_collection_conformation"
                                               value="1" class="swab_collection_conformation">Yes
                                    </label>

                                </div>
                            </div>

                            <input type="hidden" name="case_type" value="3">

                            <button type="submit" id="submit-form" class="btn btn-primary btn-sm btn-block ">SAVE</button>

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
    </div>
    <!-- /#page-wrapper -->
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script type="text/javascript">
        // import DataConverter from "ad-bs-converter";
        // console.log(NepaliFunctions.GetCurrentBsDate())

        $(':radio[data-rel]').change(function () {
            var rel = $("." + $(this).data('rel'));
            if ($(this).val() == 'yes') {
                rel.slideDown();
            } else {
                rel.slideUp();
                rel.find(":text,select").val("");
                rel.find(":radio,:checkbox").prop("checked", false);
            }
        });

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


        var currentDate = NepaliFunctions.ConvertDateFormat(NepaliFunctions.GetCurrentBsDate(), "YYYY-MM-DD");

        $('#register_date_np').nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate,
        });
        $('#dose_one_date').nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });
        $('#dose_two_date').nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });


        symptomaticCheck();
        $('.symptoms_recent').on('change', function() {
            symptomaticCheck();
        });
        function symptomaticCheck() {
            if($('.symptoms_recent:checked').val() == '1'){
                $('.asymptomatic').show();

                $('.temperature').keyup(function(){
                    if($('.temperature_type:checked').val() == '1'){
                        if($('.temperature').val() >= 100.4){
                            $('#fever_yes').prop("checked", true);
                        }else {
                            $('#fever_no').prop("checked", true);
                        }
                    }
                    else if($('.temperature_type:checked').val() == '2'){
                        if($('.temperature').val() >= 38){
                            $('#fever_yes').prop("checked", true);
                        }else {
                            $('#fever_no').prop("checked", true);
                        }
                    }
                    $('input[name="fever"]:not(:checked)').attr('disabled', true);
                    $('input[name="fever"]:checked').attr('disabled', false);
                    feverCheck();
                });
            }
            else {
                $('.asymptomatic').hide();
            }
        }

        
        idCardType();
        $('.id_card_type').on('change', function() {
            idCardType();
        });
        function idCardType() {
            if($('.id_card_type').val() == '0'){
                $('.id_card_type_other').show();
            }
            else {
                $('.id_card_type_other').hide();
            }
        }


        feverCheck();
        $('.fever').on('change', function() {
            feverCheck();
        });
        function feverCheck() {
            if($('.fever:checked').val() == '1'){
                $('.fever-status').show();
            }
            else {
                $('.fever-status').hide();
            }
        }

        malariaCheck();
        $('.malaria_test_status').on('change', function() {
            malariaCheck();
        });
        function malariaCheck() {
            if($('.malaria_test_status:checked').val() == '1'){
                $('.malaria-status').show();
            }
            else {
                $('.malaria-status').hide();
            }
        }

        malariaResultCheck();
        $('.malaria_result').on('change', function() {
            malariaResultCheck();
        });
        function malariaResultCheck() {
            if($('.malaria_result:checked').val() == '1'){
                $('.malaria-result-status').show();
            }
            else {
                $('.malaria-result-status').hide();
            }
        }

        // antigenCheck();
        // $('.antigen_test_status').on('change', function() {
        //     antigenCheck();
        // });
        // function antigenCheck() {
        //     if($('.antigen_test_status:checked').val() == '1'){
        //         $('.antigen-status').show();
        //     }
        //     else {
        //         $('.antigen-status').hide();
        //     }
        // }

        // antigenResultCheck();
        // $('.antigen_result').on('change', function() {
        //     antigenResultCheck();
        // });
        // function antigenResultCheck() {
        //     if($('.antigen_result:checked').val() == '1'){
        //         $('.antigen-result-status').show();
        //     }
        //     else {
        //         $('.antigen-result-status').hide();
        //     }
        // }

        vaccineStatusCheck();
        $('.vaccine_status').on('change', function() {
            vaccineStatusCheck();
        });
        function vaccineStatusCheck() {
            if($('.vaccine_status:checked').val() == '1'){
                $('.vaccine-status').show();
            }
            else {
                $('.vaccine-status').hide();
            }
        }

        vaccineOtherCheck();
        $('#vaccine_name').on('change', function() {
            vaccineOtherCheck();
        });
        function vaccineOtherCheck() {
            if($('#vaccine_name').val() == '10'){
                $('.vaccine-other').show();
                $('.not-jonson').show();
            }else if($('#vaccine_name').val() == '6'){
                $('.vaccine-other').hide();
                $('.not-jonson').hide();
            }
            else {
                $('.vaccine-other').hide();
                $('.not-jonson').show();
            }
        }


        $(function () {
            $.validator.addMethod("nameCustom", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\.\'\-]{2,50}(?: [a-zA-Z\.\'\-]{2,50})+$/i.test(value);
            }, "Please enter a valid name.");

            $.validator.addMethod("ageCustom", function (value, element) {
                return this.optional(element) || /^(12[0-7]|1[01][0-9]|[1-9]?[0-9])$/i.test(value);
            }, "Age is invalid: Please enter a valid age.");

            $.validator.addMethod("phoneCustom", function (value, element) {
                return this.optional(element) || /^((984|985|986|974|975|980|981|982|961|988|972|963)\d{7})|((097|095|081|053|084|083|029|056|096|089|093|010|026|041|068|049|094|064|079|027|046|087|091|076|061|036|025|066|077|099|044|057|023|021|069|055|037|075|024|067|051|086|082|071|033|031|092|047|038|063|035)(4|5|6)\d{5})|(01)(4|5|6)\d{6}$/i.test(value);
            }, "Contact number is invalid: Please enter a valid phone number.");
            $("form[name='createCase']").validate({
                // Define validation rules
                rules: {
                    register_date_np: {
                        required: true,
                    },
                    name: {
                        required: true,
                        nameCustom: true
                    },
                    age: {
                        required: true,
                        ageCustom: true,
                    },
                    caste: {
                        required: true
                    },
                    sex: {
                        required: true,
                    },
                    occupation: {
                        required: true,
                    },
                    nationality: {
                        required: true,
                    },
                    id_card_detail: {
                        maxlength: 30,
                    },
                    travelled_where: {
                        required: true,
                    },
                    travelled_city: {
                        required: true,
                    },
                    travelled_date: {
                        required: true,
                    },
                    province_id: {
                        required: true,
                    },
                    district_id: {
                        required: true
                    },
                    municipality_id: {
                        required: true
                    },
                    ward: {
                        required: true,
                        digits: true
                    },
                    tole: {
                        required: true,
                    },
                    emergency_contact_one: {
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        phoneCustom: true
                    },
                    nearest_contact: {
                        required: true,
                        nameCustom: true
                    },
                    emergency_contact_two: {
                        digits: true,
                        minlength: 7
                    },
                    contact_relationship: {
                        required: true,
                    },
                    temperature: {
                        required: true,
                    },
                    "symptoms[]": {
                        required: function () {
                            return $(".symptoms_recent:checked").val() == "1";
                        }
                    },
                    fever: {
                        required: function () {
                            return $(".symptoms_recent:checked").val() == "1";
                        }
                    },
                    malaria_test_status: {
                        required: function () {
                            return $(".fever:checked").val() == "1";
                        }
                    },
                    malaria_result: {
                        required: function () {
                            return $(".malaria_test_status:checked").val() == "1";
                        }
                    },
                    // antigen_test_status: {
                    //     required: function () {
                    //         return $(".fever:checked").val() == "1";
                    //     }
                    // },
                    // antigen_result: {
                    //     required: function () {
                    //         return $(".antigen_test_status:checked").val() == "1";
                    //     }
                    // },
                    vaccination_card: {
                        required: function () {
                            return $(".vaccine_status:checked").val() == "1";
                        }
                    },
                    vaccination_dosage_complete: {
                        required: function () {
                            return $(".vaccine_status:checked").val() == "1";
                        }
                    },
                    vaccine_dosage_count: {
                        required: function () {
                            return $(".vaccine_status:checked").val() == "1";
                        }
                    },
                    vaccine_name: {
                        required: function () {
                            return $(".vaccine_status:checked").val() == "1";
                        }
                    }
                },
                // Specify validation error messages
                messages: {
                    name: "Please provide a valid name.",
                    age: "Please provide a valid age.",

                },
                submitHandler: function (form) {
                    form.submit();
                },
                errorPlacement: function(error, element) {
                  enableSubmit();
                }
            });
        });
        
        if($('#nationality').val() == ''){
            $('#nationality').val('167');
        }

        function disableSubmit() {
          $("#submit-form").prop('disabled', true);
          $("#submit-form").html("SAVING...");
          return false;
        }

        function enableSubmit() {
          $("#submit-form").prop('disabled', false);
          $("#submit-form").html("SAVE");
          return false;
        }

        function loadSelect2() {
            $('#nationality, #travelled_where').select2({
                // placeholder: "-- Select --",
                allowClear: true
            });
        }

        $(document).ready(function() {
            loadSelect2();
        });
    </script>
@endsection