@extends('layouts.backend.app')
@section('style')
    <style>
        .earning {
            display: none;
        }

        form {
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
                    <div class="panel-heading text-center">
                        <strong>Case Investigation (A Form) (1 of 3)</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('cict-tracing.section-one.update', $data->case_id), ['name' => 'createCase']) !!}
                        {{ method_field('PUT') }}
                        <div class="panel-body">
                            <div class="form-group">
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <h4>Confirmed case</h4>
                                        <input type="radio" name="case_what" value="1" class="case_what" style="top: 7px;" {{ $data && $data->case_what == "1" ? 'checked' : '' }} checked>
                                    </label>
                                    <label class="radio-inline" style="padding-right: 60px;">
                                        <h4>Probable case</h4>
                                        <input type="radio" name="case_what" value="2" class="case_what" style="top: 7px;" {{ $data && $data->case_what == "2" ? 'checked' : '' }}>
                                    </label>
                                </div>
                                @if ($errors->has('case_what'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('case_what') }}</small>
                                @endif
                            </div>

                            @if(isset($data) && isset($data->suspectedCase))
                                @if(isset($data->suspectedCase->latestAnc))
                                    <input type="hidden" id="sample_test_date_np" value="{{ $data->suspectedCase->latestAnc->sample_test_date_np }}">
                                @endif
                            @endif

                            <div class="form-group {{ $errors->has('case_received_date') ? 'has-error' : '' }}">
                                <label for="case_received_date">Date of case received by health authority</label>
                                <input type="text" id="case_received_date" class="form-control" value="{{ $data ? $data->case_received_date : '' }}" name="case_received_date"
                                       aria-describedby="help" placeholder="Enter Date of case received by health authority">
                                @if ($errors->has('case_received_date'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('cict_initiated_date') ? 'has-error' : '' }}">
                                <label for="cict_initiated_date">Date of CICT Initiated</label>
                                <input type="text" id="cict_initiated_date" class="form-control" value="{{ $data ? $data->cict_initiated_date : '' }}" name="cict_initiated_date"
                                       aria-describedby="help" placeholder="Enter Date of CICT Initiated">
                                @if ($errors->has('cict_initiated_date'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('cict_initiated_date') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" class="form-control" value="{{ $data ? $data->name : '' }}" name="name"
                                       aria-describedby="help" placeholder="Enter Full Name">
                                @if ($errors->has('name'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('name') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('age') ? 'has-error' : '' }}">
                                <label for="age">Age</label>
                                <input type="text" id="age" value="{{ $data ? $data->age : '' }}" class="form-control col-xs-9" name="age" placeholder="Enter Age" ><br>
                                <input type="radio" name="age_unit"
                                       {{ $data && $data->age_unit == "0" ? 'checked' : '' }} value="0" data-rel="earning"> Years
                                <input type="radio" name="age_unit"
                                       {{ $data && $data->age_unit == "1" ? 'checked' : '' }} value="1" data-rel="earning"> Months
                                <input type="radio" name="age_unit"
                                       {{ $data && $data->age_unit == "2" ? 'checked' : '' }} value="2" data-rel="earning"> Days
                                <br>
                                @if ($errors->has('age'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('age') }}</small>
                                @endif
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="company">Gender</label>
                                <select name="sex" class="form-control">
                                    <option {{ $data && $data->sex == '' ? "selected" : "" }} value="">Select Gender</option>
                                    <option {{ $data && $data->sex == '1' ? "selected" : "" }} value="1">Male</option>
                                    <option {{ $data && $data->sex == '2' ? "selected" : "" }} value="2">Female</option>
                                    <option {{ $data && $data->sex == '3' ? "selected" : "" }}  value="3">Other</option>
                                </select>
                                @if ($errors->has('sex'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('sex') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('emergency_contact_one') ? 'has-error' : '' }}">
                                <label for="name">Mobile Number</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->emergency_contact_one : '' }}"
                                       name="emergency_contact_one" aria-describedby="help"
                                       placeholder="Enter Mobile Number"
                                >
                                @if ($errors->has('emergency_contact_one'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('emergency_contact_one') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('emergency_contact_two') ? 'has-error' : '' }}">
                                <label for="name">Other Contact Number</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->emergency_contact_two : '' }}"
                                       name="emergency_contact_two" aria-describedby="help"
                                       placeholder="Enter Other Contact Number">
                                @if ($errors->has('emergency_contact_two'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('emergency_contact_two') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('nationality') ? 'has-error' : '' }}">
                                <label for="name">Nationality</label>
                                <select name="nationality" class="form-control nationality" id="nationality">
                                    <option {{ $data && $data->nationality == '167' ? "selected" : "" }} value="167">Nepal</option>
                                    <option {{ $data && $data->nationality == '104' ? "selected" : "" }} value="104">India</option>
                                    <option {{ $data && $data->nationality == '47' ? "selected" : "" }} value="47">China</option>
                                    <option {{ $data && $data->nationality == '300' ? "selected" : "" }} value="300">Other</option>
                                </select>
                                @if ($errors->has('nationality'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('nationality') }}</small>
                                @endif
                            </div>

                            <div class="form-group nationality_other_class {{ $errors->has('nationality_other') ? 'has-error' : '' }}">
                                <label for="name">Please specify other nationality</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->nationality_other : '' }}"
                                       name="nationality_other" aria-describedby="help"
                                       placeholder="Enter Other Natonality">
                                @if ($errors->has('nationality_other'))
                                    <small id="help"
                                           class="form-text text-danger">{{ $errors->first('nationality_other') }}</small>
                                @endif
                            </div>
                            
                            <div class="form-group">
                                <label class="control-label" for="company">Current Address</label>
                                <div class="row">
                                    <div class="form-group col-sm-4" id="province">
                                        <?php $province_id = $data ? $data->province_id : '' ?>
                                        <select name="province_id" class="form-control"
                                                onchange="provinceOnchange($(this).val())">
                                                <option value="">-- Select Province --</option>
                                            @foreach(\Illuminate\Support\Facades\Cache::remember('province-list', 48*60*60, function () {
                                              return \App\Models\Province::select(['id', 'province_name'])->get();
                                            }) as $province)
                                                @if($province_id == $province->id)
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

                                    <div class="form-group  col-sm-4" id="district">
                                        <?php $district_id = $data ? $data->district_id : '' ?>
                                        <select name="district_id" class="form-control"
                                                onchange="districtOnchange($(this).val())">
                                                <option value="">-- Select District --</option>
                                            @foreach(App\Models\District::where('province_id', $province_id)->get() as $district)
                                                @if($district_id == $district->id)
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
                                        <?php $municipality_id = $data ? $data->municipality_id : '' ?>
                                        <select name="municipality_id" class="form-control"
                                                onchange="municipalityOnchange($(this).val())"
                                                id="municipality_id">
                                                <option value="">-- Select Municipality --</option>
                                            @foreach(\App\Models\Municipality::where('district_id', $district_id)->get() as $municipality)
                                                @if($municipality_id == $municipality->id)
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
                            </div>

                            <div class="form-group {{ $errors->has('ward') ? 'has-error' : '' }}">
                                <label for="ward">Ward No</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->ward : '' }}" name="ward" aria-describedby="help" placeholder="Enter Ward No" >
                                @if ($errors->has('ward'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('ward') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('tole') ? 'has-error' : '' }}">
                                <label for="tole">Tole</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->tole : '' }}" name="tole" aria-describedby="help" placeholder="Enter Tole" >
                                @if ($errors->has('tole'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('tole') }}</small>
                                @endif
                            </div>

                            <hr>
                            <h4>Informant Details</h4>
                            <p>(If information is given by other than case)</p>

                            <div class="form-group {{ $errors->has('informant_name') ? 'has-error' : '' }}">
                                <label for="informant_name">Name of the Informant</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->informant_name : '' }}" name="informant_name" aria-describedby="help" placeholder="Enter Name of the Informant" >
                                @if ($errors->has('informant_name'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('informant_name') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('informant_relation') ? 'has-error' : '' }}">
                                <label for="informant_relation">Relationship with Informant</label>
                                <select name="informant_relation" class="form-control informant_relation">
                                    <option {{ $data && $data->informant_relation == '' ? "selected" : "" }} value="">-- Select Relationship --</option>
                                    <option {{ $data && $data->informant_relation == '1' ? "selected" : "" }} value="1">Family</option>
                                    <option {{ $data && $data->informant_relation == '2' ? "selected" : "" }} value="2">Friends</option>
                                    <option {{ $data && $data->informant_relation == '3' ? "selected" : "" }} value="3">Neighbour</option>
                                    <option {{ $data && $data->informant_relation == '4' ? "selected" : "" }} value="4">Relatives</option>
                                    <option {{ $data && $data->informant_relation == '5' ? "selected" : "" }} value="5">Co-Worker</option>
                                    <option {{ $data && $data->informant_relation == '0' ? "selected" : "" }} value="0">Other</option>
                                </select>
                                @if ($errors->has('informant_relation'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('informant_relation') }}</small>
                                @endif
                            </div>

                            <div class="form-group informant_relation_other_class {{ $errors->has('informant_relation_other') ? 'has-error' : '' }}">
                                <label for="informant_relation_other">Please specify other relationship</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->informant_relation_other : '' }}" name="informant_relation_other" aria-describedby="help" placeholder="Enter other relationship" >
                                @if ($errors->has('informant_relation_other'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('informant_relation_other') }}</small>
                                @endif
                            </div>

                            <div class="form-group {{ $errors->has('informant_phone') ? 'has-error' : '' }}">
                                <label for="informant_phone">Informant Contact No.</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->informant_phone : '' }}" name="informant_phone" aria-describedby="help" placeholder="Enter Informant Contact No." >
                                @if ($errors->has('informant_phone'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('informant_phone') }}</small>
                                @endif
                            </div>

                            <hr>

                            <div class="form-group {{ $errors->has('case_managed_at') ? 'has-error' : '' }}">
                                <label for="case_managed_at">Case Managed At</label>
                                <select name="case_managed_at" class="form-control case_managed_at">
                                    <option {{ $data && $data->case_managed_at == '' ? "selected" : "" }} value="">-- Select Option --</option>
                                    <option {{ $data && $data->case_managed_at == '1' ? "selected" : "" }} value="1">Home Isolation</option>
                                    <option {{ $data && $data->case_managed_at == '2' ? "selected" : "" }} value="2">Hotel Isolation</option>
                                    <option {{ $data && $data->case_managed_at == '3' ? "selected" : "" }} value="3">Institutional Isolation</option>
                                    <option {{ $data && $data->case_managed_at == '4' ? "selected" : "" }} value="4">Hospital</option>
                                    <option {{ $data && $data->case_managed_at == '0' ? "selected" : "" }} value="0">Other</option>
                                </select>
                                @if ($errors->has('case_managed_at'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('case_managed_at') }}</small>
                                @endif
                            </div>
                            
                            <div class="case_managed_at_hospital_class">
                                <div class="form-group">
                                    <label for="case_managed_at_hospital">Case Managed At Hospital</label>
                                    <select name="case_managed_at_hospital" class="form-control" id="case_managed_at_hospital">
                                        <option value="" {{ $data && $data->case_managed_at_hospital == '' ? "selected" : "" }}>-- Select Option --</option>
                                        <option {{ $data && $data->case_managed_at_hospital == '1' ? "selected" : "" }} value="1">In Ward</option>
                                        <option {{ $data && $data->case_managed_at_hospital == '2' ? "selected" : "" }} value="2">In ICU</option>
                                        <option {{ $data && $data->case_managed_at_hospital == '3' ? "selected" : "" }} value="3">On Ventilator</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="case_managed_at_other">Date of Admission</label>
                                    <input type="text" class="form-control" value="{{ $data ? $data->case_managed_at_hospital_date : '' }}" name="case_managed_at_hospital_date" aria-describedby="help" placeholder="Enter Date of Admission" id="case_managed_at_hospital_date">
                                    @if ($errors->has('case_managed_at_hospital_date'))
                                        <small id="help" class="form-text text-danger">{{ $errors->first('case_managed_at_hospital_date') }}</small>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group case_managed_at_other_class {{ $errors->has('case_managed_at_other') ? 'has-error' : '' }}">
                                <label for="case_managed_at_other">Please specify other case managed at</label>
                                <input type="text" class="form-control" value="{{ $data ? $data->case_managed_at_other : '' }}" name="case_managed_at_other" aria-describedby="help" placeholder="Enter other case managed at" id="case_managed_at_other">
                                @if ($errors->has('case_managed_at_other'))
                                    <small id="help" class="form-text text-danger">{{ $errors->first('case_managed_at_other') }}</small>
                                @endif
                            </div>

                            <button type="submit" class="btn btn-primary btn-sm btn-block ">SAVE AND CONTINUE</button>
                            
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
    
    <script>
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
        $('#case_received_date').nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });
        $('#cict_initiated_date').nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });
        $('#case_managed_at_hospital_date').nepaliDatePicker({
            language: 'english',
            disableAfter: currentDate
        });

        if($('#cict_initiated_date').val() == ''){
            $('#cict_initiated_date').val(currentDate);
        }

        var sample_test_date_np = $('#sample_test_date_np').val();
        if($('#case_received_date').val() == ''){
            $('#case_received_date').val(sample_test_date_np);
        }

        otherRelationship();
        $('.informant_relation').on('change', function() {
            otherRelationship();
        });
        function otherRelationship(){
            if($('.informant_relation').val() == '0'){
                $('.informant_relation_other_class').show();
            }
            else {
                $('.informant_relation_other_class').hide();
            }
        }

        otherCaseManagedAt();
        $('.case_managed_at').on('change', function() {
            otherCaseManagedAt();
        });
        function otherCaseManagedAt(){
            if($('.case_managed_at').val() == '0'){
                $('.case_managed_at_other_class').show();
                $('.case_managed_at_hospital_class').hide();
                $('#case_managed_at_hospital').val("");
                $('#case_managed_at_hospital_date').val("");
            }else if($('.case_managed_at').val() == '4'){
                $('.case_managed_at_hospital_class').show();
                $('.case_managed_at_other_class').hide();
                $('#case_managed_at_other').val("");
            }else{
                $('.case_managed_at_hospital_class').hide();
                $('.case_managed_at_other_class').hide();
            }
        }

        

        otherNationality();
        $('.nationality').on('change', function() {
            otherNationality();
        });
        function otherNationality(){
            if($('.nationality').val() == '300'){
                $('.nationality_other_class').show();
            }else{
                $('.nationality_other_class').hide();
            }
        }

        $(function () {
            $.validator.addMethod("nameCustom", function (value, element) {
                return this.optional(element) || /^[a-zA-Z\.\'\-]{2,50}(?: [a-zA-Z\.\'\-]{2,50})+$/i.test(value);
            }, "Name is invalid: Please enter a valid name.");

            $.validator.addMethod("ageCustom", function (value, element) {
                return this.optional(element) || /^(12[0-7]|1[01][0-9]|[1-9]?[0-9])$/i.test(value);
            }, "Age is invalid: Please enter a valid age.");

            $.validator.addMethod("phoneCustom", function (value, element) {
                return this.optional(element) || /^((984|985|986|974|975|980|981|982|961|988|972|963)\d{7})|((097|095|081|053|084|083|029|056|096|089|093|010|026|041|068|049|094|064|079|027|046|087|091|076|061|036|025|066|077|099|044|057|023|021|069|055|037|075|024|067|051|086|082|071|033|031|092|047|038|063|035)(4|5|6)\d{5})|(01)(4|5|6)\d{6}$/i.test(value);
            }, "Contact number is invalid: Please enter a valid phone number.");
            $("form[name='createCase']").validate({
                // Define validation rules
                rules: {
                    case_what: {
                        required: true
                    },
                    name: {
                        required: true,
                        nameCustom: true
                    },
                    age: {
                        required: true,
                        ageCustom: true,
                    },
                    age_unit: {
                        required: true
                    },
                    sex: {
                        required: true,
                    },
                    emergency_contact_one: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 10,
                        phoneCustom: true
                    },
                    emergency_contact_two: {
                        digits: true,
                        minlength: 7,
                        maxlength: 10,
                    },
                    province_id: {
                        required: true
                    },
                    district_id: {
                        required: true
                    },
                    municipality_id: {
                        required: true
                    },
                    ward: {
                        required: true,
                        digits: true,
                        range: [1, 35]
                    },
                    tole: {
                        required: true,
                        maxlength: 25,
                    },
                    informant_name: {
                        nameCustom: true
                    },
                    informant_phone: {
                        digits: true,
                        minlength: 7,
                        maxlength: 10,
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