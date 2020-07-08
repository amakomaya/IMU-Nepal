@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Edit
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
					{!! rcForm::open('POST', route('advertisement.update', $row->token)) !!}
                    {{ method_field('PATCH') }}
                    {!! rcForm::selectOptions('Choose type', 'type', config('advertisement.type'), $row->type) !!}
                    {!! rcForm::standalone('Choose multimedia file', 'url', $row->url) !!}
                    {!! rcForm::selectOptions('Choose Plan', 'plan', config('advertisement.plan'), $row->plan) !!}
                    {!! rcForm::text('External URL ( Optional )', 'external_url', $row->external_url) !!}
                    {!! rcForm::dateTime('Expire Date', 'expire_date', $row->expire_date) !!}
					{!! rcForm::close('PATCH') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection