@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Create
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
					{!! rcForm::open('POST', route('advertisement.store')) !!}
                    {!! rcForm::selectOptions('Choose type', 'type', config('advertisement.type')) !!}
                    {!! rcForm::standalone('Choose multimedia file', 'url') !!}
                    {!! rcForm::selectOptions('Choose Plan', 'plan', config('advertisement.plan')) !!}
                    {!! rcForm::text('External URL ( Optional )', 'external_url') !!}
                    {!! rcForm::dateTime('Expire Date', 'expire_date') !!}
					{!! rcForm::close('post') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection