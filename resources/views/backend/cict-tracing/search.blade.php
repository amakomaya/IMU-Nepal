@extends('layouts.backend.app')
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
                    <strong>CICT Form</strong>
                </div>
                <div class="panel-body">
                    {!! rcForm::open('POST', route('cict-tracing.store'), ['name' => 'createCase', 'id' => 'createCict']) !!}
                        <div class="row">
                            <div class="col-lg-8 form-group">
                                <label>Is the patient already registered in IMU system?</label>
                                <div class="form-group">
                                    <div class="control-group">
                                        <label class="radio-inline">
                                            <h5>Yes</h5>
                                            <input type="radio" name="yes" value="1" class="already_exists" style="top: 7px;">
                                        </label>
                                        <label class="radio-inline" style="padding-right: 60px;">
                                            <h5>No (and continue to form)</h5>
                                            <input type="radio" name="yes" value="2" class="already_exists" style="top: 7px;">
                                        </label>
                                    </div>
                                </div>

                                <div class="case-id-class" style="display: none;">
                                    <input type="text" name="case_id" class="form-control" placeholder="Enter Case Id" value="{{ request()->get('case_id') }}" required><br>
                                    <button class="btn btn-info" title="Search CaseID" value="Submit">
                                        <i class="fa fa-search"> Create and Continue</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
    </div>
</div>
<!-- /#page-wrapper -->
@endsection


@section('script')
<script>

$('.already_exists').on('change', function() {
	case_id_check();
});

function case_id_check() {
	if($('.already_exists:checked').val() == 1){
		$('.case-id-class').show();
	}
	else{
		$('.case-id-class').hide();
        $('#createCict').submit();
		// window.location.href = "{{ route('cict-tracing.section-one') }}"
	}
}
</script>
@endsection