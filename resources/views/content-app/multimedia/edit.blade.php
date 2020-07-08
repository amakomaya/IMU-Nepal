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
					{!! rcForm::open('POST', route('multimedia.update', $row->id)) !!}
                    {{ method_field('PATCH') }}
                    {!! rcForm::selectOptions('Choose type', 'type', ['Video', 'Audio', 'Text'], $row->type) !!}
					{!! rcForm::text('Title in English', 'title_en', $row->title_en) !!}
                    {!! rcForm::text('Title in Nepali', 'title_np', $row->title_np) !!}
                    {!! rcForm::ckeditor('Description in English', 'description_en', $row->description_en) !!}
                    {!! rcForm::ckeditor('Description in Nepali', 'description_np', $row->description_np) !!}
                    {!! rcForm::standalone('Choose thumbnail', 'thumbnail', $row->thumbnail) !!}
                    {!! rcForm::standalone('Choose multimedia file', 'path', $row->path) !!}
                    {!! rcForm::selectNumberRange('Choose Week no for contain', 'week_id', '1', '40', $row->week_id) !!}
                    {!! rcForm::status('Status', 'status', $row->status) !!}
					{!! rcForm::close('PATCH') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection