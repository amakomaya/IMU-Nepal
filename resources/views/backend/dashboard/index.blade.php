@extends('layouts.backend.app')
@section('content')
    
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            @if (\Request::session()->has('message'))
                <div class="alert alert-block alert-success">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    {!! Request::session()->get('message') !!}
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                   Dashboard
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div>
                        {!! $data ? $data->description : '' !!}
                    </div>

                    @if(auth()->user()->role == 'main' || auth()->user()->role == 'province')

                    @if($data)
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('dashboard.update', $data->id) }}" >
                    {{ method_field('PATCH') }}
                    @else
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('dashboard.store') }}" >
                    @endif
                        {{ csrf_field() }}
                        <hr>
                        <h3 class="text-center">Edit Form</h3>
                        <hr>

                        {{-- <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
                            <label for="title" class="col-md-3 control-label">Title</label>                                  
                            <div class="col-md-7">
                                <input id="title" type="text" class="form-control" title="title" value="" >
                                @if ($errors->has('title'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('title') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div> --}}

                        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                            <label for="description" class="col-md-3 control-label">Description</label>                                  
                            <div class="col-md-7">
                                <textarea name="description" class="form-control" id="description" placeholder="Description">
                                    {{ $data ? $data->description : '' }}
                                </textarea>
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('description') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-9 col-md-offset-3">
                                <button type="submit" class="btn btn-success">
                                    {{ __('create.submit') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    @endif
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

@endsection
@section('script')
<script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>
<script>
    var options = {
		removePlugins : 'image'
 	};
	CKEDITOR.replace( 'description', options);

</script>
@endsection