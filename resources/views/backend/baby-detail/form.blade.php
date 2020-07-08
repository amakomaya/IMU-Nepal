@extends('layouts.backend.app')

@section('content')
<script type="text/javascript" src="{{ asset('js/nepali.datepicker.v2.2.min.js') }}"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('css/nepali.datepicker.v2.2.min.css') }}" />

<script type="text/javascript">
    $(document).ready(function(){
        $('#date_of_birth_reg').nepaliDatePicker({
            npdMonth: true,
            npdYear: true,
            npdYearCount: 10
        });
    });
</script>
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                             @if (isset($data))
                                Edit Baby Details
                            @else
                                Create Baby Details
                            @endif
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" method="POST" action="@yield('action')" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @yield('methodField')

                                    <div class="form-group{{ $errors->has('baby_name') ? ' has-error' : '' }}">
                                        <label for="baby_name" class="col-md-3 control-label">Baby Name</label>

                                        <div class="col-md-7">
                                            <input id="baby_name" type="text" class="form-control" name="baby_name" value="@yield('baby_name')" >

                                            @if ($errors->has('baby_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('baby_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('date_of_birth_reg') ? ' has-error' : '' }}">
                                        <label for="date_of_birth_reg" class="col-md-3 control-label">Date of Registration</label>

                                        <div class="col-md-7">
                                            <input id="date_of_birth_reg" type="text" class="form-control" name="date_of_birth_reg" value="@yield('date_of_birth_reg')" >

                                            @if ($errors->has('date_of_birth_reg'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('date_of_birth_reg') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('birth_certificate_reg_no') ? ' has-error' : '' }}">
                                        <label for="birth_certificate_reg_no" class="col-md-3 control-label">Birth Certificate Reg No</label>

                                        <div class="col-md-7">
                                            <input id="birth_certificate_reg_no" type="number" class="form-control" name="birth_certificate_reg_no" value="@yield('birth_certificate_reg_no')" >

                                            @if ($errors->has('birth_certificate_reg_no'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('birth_certificate_reg_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('family_record_form_no') ? ' has-error' : '' }}">
                                        <label for="family_record_form_no" class="col-md-3 control-label">Family Record Form No</label>

                                        <div class="col-md-7">
                                            <input id="family_record_form_no" type="number" class="form-control" name="family_record_form_no" value="@yield('family_record_form_no')" >

                                            @if ($errors->has('family_record_form_no'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('family_record_form_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('child_information_by') ? ' has-error' : '' }}">
                                        <label for="child_information_by" class="col-md-3 control-label">Child Information By</label>

                                        <div class="col-md-7">
                                            <input id="child_information_by" type="text" class="form-control" name="child_information_by" value="@yield('child_information_by')" >

                                            @if ($errors->has('child_information_by'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('child_information_by') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('grand_father_name') ? ' has-error' : '' }}">
                                        <label for="grand_father_name" class="col-md-3 control-label">Grand Father Name</label>

                                        <div class="col-md-7">
                                            <input id="grand_father_name" type="text" class="form-control" name="grand_father_name" value="@yield('grand_father_name')" >

                                            @if ($errors->has('grand_father_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('grand_father_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('grand_mother_name') ? ' has-error' : '' }}">
                                        <label for="grand_mother_name" class="col-md-3 control-label">Grand Mother Name</label>

                                        <div class="col-md-7">
                                            <input id="grand_mother_name" type="text" class="form-control" name="grand_mother_name" value="@yield('grand_mother_name')" >

                                            @if ($errors->has('grand_mother_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('grand_mother_name') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('father_citizenship_no') ? ' has-error' : '' }}">
                                        <label for="father_citizenship_no" class="col-md-3 control-label">Father Citizenship No</label>

                                        <div class="col-md-7">
                                            <input id="father_citizenship_no" type="text" class="form-control" name="father_citizenship_no" value="@yield('father_citizenship_no')" >

                                            @if ($errors->has('father_citizenship_no'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('father_citizenship_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('mother_citizenship_no') ? ' has-error' : '' }}">
                                        <label for="mother_citizenship_no" class="col-md-3 control-label">Mother Citizenship No</label>

                                        <div class="col-md-7">
                                            <input id="mother_citizenship_no" type="text" class="form-control" name="mother_citizenship_no" value="@yield('mother_citizenship_no')" >

                                            @if ($errors->has('mother_citizenship_no'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('mother_citizenship_no') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="form-group{{ $errors->has('local_registrar_fullname') ? ' has-error' : '' }}">
                                        <label for="local_registrar_fullname" class="col-md-3 control-label">Local Registrar Fullname</label>

                                        <div class="col-md-7">
                                            <input id="local_registrar_fullname" type="text" class="form-control" name="local_registrar_fullname" value="@yield('local_registrar_fullname')" >

                                            @if ($errors->has('local_registrar_fullname'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('local_registrar_fullname') }}</strong>
                                                </span>
                                            @endif
                                        </div>
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
                    