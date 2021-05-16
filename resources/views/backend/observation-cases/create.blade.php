@extends('layouts.backend.app')
@section('style')
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
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
                <div id="app">
                    {{-- <observation-cases-create></observation-cases-create> --}}
                    <form action="{{ route('observation-cases.store') }}"  method="POST" id="observation_form">
                        @csrf
                        <div class="container row">
                            <div class="panel-heading"><strong>Observation Cases</strong></div>
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label class="control-label" for="add">Add *</label>
                                            <input type="text" name="add" placeholder="Enter Add for Case" class="form-control" id="add" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label class="control-label" for="add">Transfer to bed *</label>
                                            <input type="text" name="transfer_to_bed" placeholder="Enter Transfer to bed for Case" class="form-control" id="transfer_to_bed" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label class="control-label" for="add">Return to home *</label>
                                            <input type="text" name="return_to_home" placeholder="Enter Return to home for Case" class="form-control" id="return_to_home" />
                                        </div>
                                    </div>
                                    <input type="hidden" name="hp_code" value="{{ $organization_hp_code }}">
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <button type="button" class="btn btn-primary btn-lg btn-block" id="submit_btn">Save</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
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
