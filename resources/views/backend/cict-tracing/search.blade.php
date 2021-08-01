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
                    <form method="GET" action="">
                        <div class="row">
                            <div class="col-lg-8 form-group">
                                <p style="color: red">This is in testing phase. Data will be deleted.</p>
                                <div class="case-id-class">
                                    <input type="text" name="case_id" class="form-control" placeholder="Enter Case Id" value="{{ request()->get('case_id') }}" required><br>
                                    <button class="btn btn-info" title="Search CaseID" value="Submit">
                                        <i class="fa fa-search"> Search</i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                @if(isset($patient))
                <div class="panel-body">
                    <div class="col-12 table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Case ID</th>
                                    <th>Name</th>
                                    <th>Action (CICT)</th>
                                </tr>
                            </thead>
                            <tr>
                                <td>{{ $patient->case_id }}</td>
                                <td>{{ $patient->name }}</td>
                                <td>
                                    {!! rcForm::open('POST', route('cict-tracing.store'), ['name' => 'createCase', 'id' => 'createCict']) !!}
                                        <input type="hidden" name="case_id" value="{{ $patient->case_id }}">
                                        <button type="button" id="btnSubmit" class="btn btn-sm btn-success"><i class="fa fa-plus" aria-hidden="true"></i> Create</button>
                                    </form>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                @endif
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

$('#btnSubmit').on('click', function() {
	if (confirm("Are you sure you want to perform Case Investigation and Contact Tracing?")){
        $('form#createCict').submit();
    }
});
</script>
@endsection