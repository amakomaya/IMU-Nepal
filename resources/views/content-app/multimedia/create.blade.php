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
					{!! rcForm::open('POST', route('multimedia.store')) !!}
                    {!! rcForm::selectOptions('Choose type', 'type', ['Video', 'Audio', 'Text']) !!}
					{!! rcForm::text('Title in English', 'title_en') !!}
                    {!! rcForm::text('Title in Nepali', 'title_np') !!}
                    {!! rcForm::ckeditor('Description in English', 'description_en') !!}
                    {!! rcForm::ckeditor('Description in Nepali', 'description_np') !!}
                    {!! rcForm::standalone('Choose thumbnail', 'thumbnail') !!}
                    {!! rcForm::standalone('Choose multimedia file', 'path') !!}
                    {!! rcForm::selectNumberRange('Choose Week no for Multimedia', 'week_id', '1', '40') !!}
                    {!! rcForm::status('Status', 'status') !!}
					{!! rcForm::close('post') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection