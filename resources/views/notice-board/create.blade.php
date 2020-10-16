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
					{!! rcForm::open('POST', route('notice-board.store')) !!}
                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                        <label for="type">Choose type </label>
                        <select name="type" class="form-control">
                            <option value="Information" {{ old('type') == 'Information' ? 'selected' : '' }}>Information</option>
                            <option value="Warning" {{ old('type') == 'Warning' ? 'selected' : '' }}>Warning</option>
                            <option value="Danger" {{ old('type') == 'Danger' ? 'selected' : '' }}>Danger</option>
                        </select>
                        @if ($errors->has('type'))
                            <small id="help" class="form-text text-danger">{{ $errors->first('type') }}</small>
                        @endif
                    </div>
                    {!! rcForm::text('Title', 'title') !!}
                    {!! rcForm::ckeditor('Description', 'description') !!}
					{!! rcForm::close('post') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection