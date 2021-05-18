@extends('layouts.backend.app')
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
                    @if (Request::session()->has('error'))
                        <div class="alert alert-block alert-danger">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            {!! Request::session()->get('error') !!}

                        </div>
                    @endif
                    <form action="{{ route('observation-cases.store') }}"  method="POST" id="observation_form">
                        @csrf
                            <div class="h1 panel-heading"><strong>Observation Cases</strong></div>
                                <div class="panel-body">
                                        <div class="col-lg-12 form-group">
                                            <label class="control-label" for="add">New number of Cases in Emergency or Observation</label>
                                            <input type="number" min="0" name="add" placeholder="Enter the new number of cases in Emergency or Observation" class="form-control" id="add" />
                                        </div>
                                        <div class="col-lg-12 form-group">
                                            <label class="control-label" for="add">Number of persons transferred to bed</label>
                                            <input type="number" min="0" name="transfer_to_bed" placeholder="Enter the number of persons transferred to bed" class="form-control" id="transfer_to_bed" />
                                        </div>
                                        <div class="col-lg-12 form-group">
                                            <label class="control-label" for="add">Number of persons returned to home</label>
                                            <input type="number" min="0" name="return_to_home" placeholder="Enter the number of persons returned to home" class="form-control" id="return_to_home" />
                                        </div>
                                    </div>
                                    <input type="hidden" name="hp_code" value="{{ $organization_hp_code }}">
                                        <div class="col-lg-12 form-group">
                                            <button type="button" class="btn btn-primary btn-lg btn-block" id="submit_btn">Save</button>
                                        </div>
                    </form>
            </div>
        </div>
    </div>
@endsection
@section('script')

<script>
    $("#submit_btn").click(function(){
        if (confirm("Click OK to continue?")){
            $('form#observation_form').submit();
        }
    });
</script>
@endsection
