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
                        Update
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <form action="{{ route('patient.update',$data->id) }}" method="POST" name="updateCase">
                            @csrf
                            @method('PUT')
                            <h4>1. Personal Information </h4>

                            <div class="panel-body">
                                <div class="form-group {{ $errors->has('case_id') ? 'has-error' : '' }}">
                                    <label for="case_id">Case Id</label>
                                    <input type="text" id="case_id" class="form-control" name="case_id"
                                           aria-describedby="help" placeholder="Enter Case Id"
                                           value="{{ $data->case_id }}" readonly
                                    >
                                    @if ($errors->has('case_id'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('case_id') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                    <label for="name">Full Name</label>
                                    <input type="text" id="name" class="form-control" name="name"
                                           aria-describedby="help" placeholder="Enter Full Name"
                                           value="{{ $data->name }}"
                                    >
                                    @if ($errors->has('name'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('name') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                                    <label for="age">Age</label>
                                    <input type="text" id="age" value="{{ $data->age }}" class="form-control col-xs-9"
                                           name="age" placeholder="Enter Age"
                                    ><br>
                                    <input type="radio" name="age_unit"
                                           {{ $data->age_unit == "0" ? 'checked' : '' }} value="0" data-rel="earning"
                                           checked>Years
                                    <input type="radio" name="age_unit"
                                           {{ $data->age_unit == "1" ? 'checked' : '' }} value="1" data-rel="earning">Months
                                    <input type="radio" name="age_unit"
                                           {{ $data->age_unit == "2" ? 'checked' : '' }} value="2" data-rel="earning">Days
                                    <br>
                                    @if ($errors->has('age'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('age') }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="caste">Caste</label>
                                    <select name="caste" class="form-control">
                                        <option {{ $data->caste == '6' ? "selected" : "" }} value="6">Don't Know
                                        </option>
                                        <option {{ $data->caste == '0' ? "selected" : "" }} value="0">Dalit</option>
                                        <option {{ $data->caste == '1' ? "selected" : "" }} value="1">Janajati</option>
                                        <option {{ $data->caste == '2' ? "selected" : "" }} value="2">Madheshi</option>
                                        <option {{ $data->caste == '3' ? "selected" : "" }} value="3">Muslim</option>
                                        <option {{ $data->caste == '4' ? "selected" : "" }} value="4">Brahmin/Chhetrai
                                        </option>
                                        <option {{ $data->caste == '5' ? "selected" : "" }} value="5">Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="company">Gender</label>
                                    <select name="sex" class="form-control">
                                        <option value="" disabled selected>Select Gender</option>
                                        <option {{ $data->sex == '1' ? "selected" : "" }} value="1">Male</option>
                                        <option {{ $data->sex == '2' ? "selected" : "" }} value="2">Female</option>
                                        <option {{ $data->sex == '3' ? "selected" : "" }}  value="3">Other</option>
                                    </select>
                                    @if ($errors->has('sex'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('sex') }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="company">Current Address</label>
                                    <div class="row">
                                        <div class="form-group col-sm-3" id="province">
                                            <select name="province_id" class="form-control"
                                                    onchange="provinceOnchange($(this).val())">
                                                @if(Auth::user()->role!="province" && Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                    <option value="">Select All Provinces</option>
                                                @endif
                                                @foreach(App\Models\province::all() as $province)
                                                    @if($data->province_id == $province->id)
                                                        @php($selectedProvince = "selected")
                                                    @else
                                                        @php($selectedProvince = "")
                                                    @endif
                                                    <option value="{{$data->province_id}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group  col-sm-3" id="district">
                                            <select name="district_id" class="form-control"
                                                    onchange="districtOnchange($(this).val())">
                                                @if(Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                    <option value="">Select All Districts</option>
                                                @endif
                                                @foreach(App\Models\District::where('province_id', $data->province_id ?? '')->get() as $district)
                                                    @if($data->district_id==$district->id)
                                                        @php($selectedDistrict = "selected")
                                                    @else
                                                        @php($selectedDistrict = "")
                                                    @endif
                                                    <option value="{{$data->district_id}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group  col-sm-3" id="municipality">
                                            <select name="municipality_id" class="form-control"
                                                    onchange="municipalityOnchange($(this).val())"
                                                    id="municipality_id">
                                                @if(Auth::user()->role!="municipality" && Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                                    <option value="">Select All Municipalities</option>
                                                @endif
                                                @foreach(\App\Models\Municipality::where('district_id', $data->district_id)->get() as $municipality)
                                                    @if($data->municipality_id==$municipality->id)
                                                        @php($selectedMunicipality = "selected")
                                                    @else
                                                        @php($selectedMunicipality = "")
                                                    @endif
                                                    <option value="{{$data->municipality_id}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group {{ $errors->has('ward') ? 'has-error' : '' }}">
                                    <label for="ward">Ward No</label>
                                    <input type="text" class="form-control" name="ward" aria-describedby="help"
                                           placeholder="Enter Ward No" value="{{ $data->ward }}"
                                    >
                                    @if ($errors->has('ward'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('ward') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }}">
                                    <label for="tole">Tole</label>
                                    <input type="text" class="form-control" name="tole" aria-describedby="help"
                                           placeholder="Enter Tole" value="{{ $data->tole }}"
                                    >
                                    @if ($errors->has('tole'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('tole') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('emergency_contact_one') ? 'has-error' : '' }}">
                                    <label for="name">Emergency Contact One</label>
                                    <input type="text" class="form-control" name="emergency_contact_one"
                                           aria-describedby="help" placeholder="Enter Emergency Contact One"
                                           value="{{ $data->emergency_contact_one }}"
                                    >
                                    @if ($errors->has('emergency_contact_one'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                                    @endif
                                </div>
                                <div class="form-group {{ $errors->has('emergency_contact_two') ? 'has-error' : '' }}">
                                    <label for="name">Emergency Contact Two</label>
                                    <input type="text" class="form-control" name="emergency_contact_two"
                                           aria-describedby="help" placeholder="Enter Emergency Contact Two"
                                           value="{{ $data->emergency_contact_two }}"
                                    >
                                    @if ($errors->has('emergency_contact_two'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('emergency_contact_two') }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label" for="caste">Occupation</label>
                                    <select name="occupation" class="form-control">
                                        <option value="" disabled selected>Select
                                            Occupation
                                        </option>
                                        <option {{ $data->occupation == '1' ? "selected" : "" }} value="1">Front Line
                                            Health Worker
                                        </option>
                                        <option {{ $data->occupation == '2' ? "selected" : "" }} value="2">Doctor
                                        </option>
                                        <option {{ $data->occupation == '3' ? "selected" : "" }} value="3">Nurse
                                        </option>
                                        <option {{ $data->occupation == '4' ? "selected" : "" }} value="4">Police/Army
                                        </option>
                                        <option {{ $data->occupation == '5' ? "selected" : "" }} value="5">
                                            Business/Industry
                                        </option>
                                        <option {{ $data->occupation == '6' ? "selected" : "" }} value="6">
                                            Teacher/Student/Education
                                        </option>
                                        <option {{ $data->occupation == '7' ? "selected" : "" }} value="7">Journalist
                                        </option>
                                        <option {{ $data->occupation == '8' ? "selected" : "" }} value="8">Agriculture
                                        </option>
                                        <option {{ $data->occupation == '9' ? "selected" : "" }} value="9">
                                            Transport/Delivery
                                        </option>
                                        <option {{ $data->occupation == '10' ? "selected" : "" }} value="10">Other
                                        </option>
                                    </select>
                                    @if ($errors->has('occupation'))
                                        <small id="help"
                                               class="form-text text-danger">{{ $errors->first('occupation') }}</small>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label class="control-label">Have you traveled till 15 days ?</label>
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <input type="radio" name="travelled"
                                                   {{ $data->travelled == "0" ? 'checked' : '' }} value="0" checked>No
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="travelled"
                                                   {{ $data->travelled == "1" ? 'checked' : '' }} value="1">Yes
                                        </label>

                                    </div>
                                </div>
                            </div>

                            @if(!$samples->isEmpty())
                                <h4>2. Sample Collection Information </h4>

                                <div>
                                    <table class="table" id="table">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th title="Sample Unique ID">SID</th>
                                            <th title="Sample Type">Sample Type</th>
                                            <th title="Service Type">Service Type</th>
                                            <th title="Sample Collected Date">Date</th>
                                            <th title="Sample Result">Result</th>
                                            <th><i class="fa fa-cogs" aria-hidden="true"></i>
                                            </th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach ($samples as $sample)
                                            <tr>
                                                <td>{{$loop->iteration }}</td>
                                                <td>{{$sample->token}}</td>
                                                <td>
                                                    @if($sample->sample_type = 1)
                                                        Nasopharyngeal
                                                    @elseif($sample->sample_type = 2)
                                                        Oropharyngeal
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($sample->service_type = 1)
                                                        Paid Service
                                                    @elseif($sample->service_type = 2)
                                                        Free of cost service
                                                    @endif
                                                </td>
                                                <td>{{$sample->created_at}}</td>
                                                <td>
                                                    @if($sample->result = 2)
                                                        Pending
                                                    @elseif($sample->result = 3)
                                                        Positive
                                                    @elseif($sample->result = 4)
                                                        Negative
                                                    @elseif($sample->result = 5)
                                                        Don't Know
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(auth()->user()->role == 'main')
{{--                                                        <button title="Edit Sample Detail" {{ url("admin/sample/$sample->token/edit") }}'">--}}
{{--                                                            <i class="fa fa-edit"></i>--}}
{{--                                                        </button>--}}
                                                        <a title="Edit Sample Detail" class="btn btn-primary" href="{{ url('admin/sample/'.$sample->token.'/edit') }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                            {!! rcForm::close('post') !!}

                        </form>
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

                function sampleTypeValue(value) {
                    if (value === 1)
                        return "Nasopharyngeal";
                    else if (value === 2)
                        return "Oropharyngeal";
                }

                function serviceTypeValue(value) {
                    if (value === 1)
                        return "Paid Service";
                    else if (value === 2)
                        return "Free of cost service";
                }

                function resultValue(value) {
                    switch (value) {
                        case 1:
                            return "Registered Only";
                        case 2:
                            return "Pending";
                        case 3:
                            return "Positive";
                        case 4:
                            return "Negative";
                        case 5:
                            return "Dont know";
                    }
                }

                function createdDate(date) {
                    var dateObject = new Date(date);

                    var dateFormat = dateObject.getFullYear() + "/" + (dateObject.getMonth() + 1) + "/" + dateObject.getDate();

                    let dateConverter = DataConverter.ad2bs(dateFormat);

                    return dateConverter.en.day + ' ' + dateConverter.en.strMonth + ', ' + dateConverter.en.year;
                }

                $(function () {
                    $.validator.addMethod("nameCustom", function (value, element) {
                        return this.optional(element) || /^[a-zA-Z\.\'\-]{2,50}(?: [a-zA-Z\.\'\-]{2,50})+$/i.test(value);
                    }, "Email Address is invalid: Please enter a valid email address.");

                    $.validator.addMethod("ageCustom", function (value, element) {
                        return this.optional(element) || /^(12[0-7]|1[01][0-9]|[1-9]?[0-9])$/i.test(value);
                    }, "Age is invalid: Please enter a valid age.");

                    $.validator.addMethod("phoneCustom", function (value, element) {
                        return this.optional(element) || /^((984|985|986|974|975|980|981|982|961|988|972|963)\d{7})|((097|095|081|053|084|083|029|056|096|089|093|010|026|041|068|049|094|064|079|027|046|087|091|076|061|036|025|066|077|099|044|057|023|021|069|055|037|075|024|067|051|086|082|071|033|031|092|047|038|063|035)(4|5|6)\d{5})|(01)(4|5|6)\d{6}$/i.test(value);
                    }, "Contact number is invalid: Please enter a valid phone number.");
                    $("form[name='updateCase']").validate({
                        // Define validation rules
                        rules: {
                            name: {
                                required: true,
                                nameCustom: true
                            },
                            age: {
                                required: true,
                                ageCustom: true,
                            },
                            district_id : {
                                required : true
                            },
                            municipality_id : {
                                required : true
                            },
                            sex: {
                                required: true,
                            },
                            ward: {
                                required: true,
                            },
                            tole: {
                                required: true,
                            },
                            emergency_contact_one: {
                                required: true,
                                phoneCustom: true
                            },
                            occupation: {
                                required: true,
                            }
                        },
                        // Specify validation error messages
                        messages: {
                            name: "Please provide a valid name.",
                            age: "Please provide a valid age.",

                        },
                        submitHandler: function (form) {
                            form.submit();
                        }
                    });
                });
            </script>
@endsection