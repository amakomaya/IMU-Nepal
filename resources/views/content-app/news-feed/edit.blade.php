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
					{!! rcForm::open('POST', route('news-feed.update', $row->id)) !!}
                    {{ method_field('PATCH') }}
                    {!! rcForm::text('Author', 'author', $row->author) !!}
                    {!! rcForm::ckEditor('Title of Post', 'title', $row->title) !!}
                    {!! rcForm::standalone('Choose Image ( Optional )', 'url_to_image', $row->url_to_image) !!}
                    {!! rcForm::text('External link ( Optional )', 'url', $row->url) !!}
                    {!! rcForm::text('Published At', 'published_at', $row->published_at) !!}
                    {!! rcForm::status('Status', 'status', $row->status) !!}
					{!! rcForm::close('PATCH') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection