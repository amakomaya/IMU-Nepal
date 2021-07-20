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
                        <strong>Contact Interview Form</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        {!! rcForm::open('POST', route('b-two-form.update', ['_case_id' => $cict_contact->case_id]), ['name' => 'createCase']) !!}
                        <div class="panel-body">
                            <label class="control-label"><h4>Case information</h4></label>
                            
                            <div class="form-group">
                                <label for="name">Name of the Case: {{ $cict_tracing ? $cict_tracing->name : '' }}</label><br>
                                <label for="name">EPI ID: {{ $cict_tracing ? $cict_tracing->case_id : '' }}</label><br>
                            </div>

                            <hr>
                            
                            <label class="control-label"><h4>Contact information</h4></label>
                            <div class="form-group">
                                <label for="name">Name: {{ $cict_contact ? $cict_contact->name : '' }}</label><br>
                                <label for="name">EPI ID: {{ $cict_contact ? $cict_contact->case_id : '' }}</label><br>
                            </div>

                            <div class="form-group sars_cov2_vaccinated_yes_class">
                                <div class="col-12 table-responsive">
                                    <table class="table table-bordered">
                                        <thead style="background: #fff;">
                                            <tr>
                                                <th>Days since last contact with the case</th>
                                                <th>Days to follow up</th>
                                                <th>Date of follow up</th>
                                                <th>Symptoms</th>
                                                <th>Other symptoms: specify</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-sars-cov-tbody text-center">
                                            <tr class="table-sars-cov-tr">
                                                {{-- <td>
                                                    <input type="text" class="form-control" name="days_last_contact" value="{{ isset($data) ? $data->days_last_contact : '' }}" aria-describedby="help" placeholder="Enter Name">
                                                </td> --}}
                                                <td>
                                                    <input type="text" class="form-control" name="days_to_follow_up" value="{{ isset($data) ? $data->days_to_follow_up : '' }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="days_of_follow_up" id="days_of_follow_up" value="{{ isset($data) ? $data->days_of_follow_up : '' }}">
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="days_of_follow_up" id="days_of_follow_up" value="{{ isset($data) ? $data->days_of_follow_up : '' }}">
                                                </td>
                                                <td>
                                                    <input type="checkbox" value="1" {{ isset($data) && $data->symptoms == "1" ? 'selected' : "" }}>No symptoms<br>
                                                    <input type="checkbox" value="2" {{ isset($data) && $data->symptoms == "2" ? 'selected' : "" }}>Fever ≥38 °C<br>
                                                    <input type="checkbox" value="3" {{ isset($data) && $data->symptoms == "3" ? 'selected' : "" }}>Runny Nose<br>
                                                    <input type="checkbox" value="4" {{ isset($data) && $data->symptoms == "4" ? 'selected' : "" }}>Cough<br>
                                                    <input type="checkbox" value="5" {{ isset($data) && $data->symptoms == "5" ? 'selected' : "" }}>Sore Throat<br>
                                                    <input type="checkbox" value="5" {{ isset($data) && $data->symptoms == "5" ? 'selected' : "" }}>Shortness of Breath<br>
                                                    <input type="checkbox" value="0" {{ isset($data) && $data->symptoms == "0" ? 'selected' : "" }}>Others<br>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control" name="symptoms_other" id="symptoms_other" value="{{ isset($data) ? $data->symptoms_other : '' }}">
                                                </td>
                                            </tr>
                                    </table>
                                </div>
                            </div>

                            <label class="control-label"><h4>Final Contact Classification at final follow-up</h4></label>
                            <div class="form-group">
                                <select name="high_exposure" class="form-control high_exposure">
                                    <option value=""  {{ isset($data) && $data->high_exposure == "" ? 'selected' : "" }}>Select Final Contact Classification at final follow-up</option>
                                    <option value="1" {{ isset($data) && $data->high_exposure == "1" ? 'selected' : "" }}>Never ill/not a case</option>
                                    <option value="2" {{ isset($data) && $data->high_exposure == "2" ? 'selected' : "" }}>Confirmed Secondary Case</option>
                                    <option value="3" {{ isset($data) && $data->high_exposure == "3" ? 'selected' : "" }}>Lost to follow-up</option>
                                    <option value="4" {{ isset($data) && $data->high_exposure == "4" ? 'selected' : "" }}>Suspected Case</option>
                                    <option value="5" {{ isset($data) && $data->high_exposure == "5" ? 'selected' : "" }}>Probable Case</option>
                                </select>
                            </div>

                            <input type="hidden" name="cict_token" value={{ isset($data) ? $data->cict_token : '' }}>
                            <input type="hidden" name="parent_case_id" value={{ isset($data) ? $data->parent_case_id : '' }}>

                            <button type="submit" class="btn btn-primary btn-sm btn-block ">SAVE</button>
                            
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
            }else{
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
                        digits: true
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