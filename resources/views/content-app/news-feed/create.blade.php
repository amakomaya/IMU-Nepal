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
					{!! rcForm::open('POST', route('news-feed.store')) !!}
					{!! rcForm::text('Author', 'author') !!}
                    {!! rcForm::ckEditor('Title of Post', 'title') !!}
                    {!! rcForm::standalone('Choose Image', 'url_to_image') !!}
                    {!! rcForm::text('External link', 'url') !!}
                    {!! rcForm::status('Status', 'status') !!}
					{!! rcForm::close('post') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection