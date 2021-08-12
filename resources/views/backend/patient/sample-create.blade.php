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
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Create
                    </div>
                    <div class="panel-body">
                        <form action="{{ route('patient.sample.store') }}" method="POST" name="createCase" id="createCase" onsubmit="disableSubmit()">
                            @csrf
                        <div class="panel-body">
                            <div class="form-group">
                                <label class="control-label">Test Type</label>
                                <div class="control-group">
                                    <label class="radio-inline">
                                        <input type="radio" class="service_for" name="service_for" value="1" onclick="toggleLayout(true)"
                                               >PCR Swab Collection
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" class="service_for" name="service_for" value="2" onclick="toggleLayout(false)">Antigen
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

                            <div class="form-group antigen-part">
                                <label class="control-label">Enter Registered Lab Id (Unique): </label>
                                <div class="anti-lab-id" style="display: flex;">
                                    <span style="margin-top: 6px;">{{ Carbon\Carbon::now()->format('ymd') }}-</span>
                                    <input class="form-control" type="text" name="lab_token" id="lab_token"/> 
                                </div>
                            </div>
                            <input type="text" name="token" value="{{$swab_id}}" hidden>
                            <input type="text" name="woman_token" value="{{$token}}" hidden>
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

                pcrOrAntigenCheck();
                $('.service_for').on('change', function() {
                    pcrOrAntigenCheck();
                });
                function pcrOrAntigenCheck(){
                    if($('.service_for:checked').val() == '2'){
                        $('.antigen-part').show();
                    }
                    else {
                        $('.antigen-part').hide();
                    }
                }
        

        $(function () {
            $("form[name='createCase']").validate({
                // Define validation rules
                rules: {
                    service_for: {
                        required: true,
                    },
                    infection_type: {
                        required: true,
                    },
                    service_type: {
                        required: true
                    },
                    lab_token: {
                        required:  function () {
                            return $(".service_for:checked").val() == "2";
                        },
                        maxlength: 8
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
            </script>
@endsection