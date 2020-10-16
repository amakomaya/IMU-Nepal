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
					{!! rcForm::open('POST', route('notice-board.update', $row->id)) !!}
                    {{ method_field('PATCH') }}
                    {!! rcForm::text('Title', 'title', $row->title) !!}
                    <div class="form-group {{ $errors->has('type') ? 'has-error' : '' }}">
                        <label for="type">Choose type </label>
                        <select name="type" class="form-control">
                            <option value="Information" {{ $row->type == 'Information' ? 'selected' : '' }} {{ old('type') == 'Information' ? 'selected' : '' }}>Information</option>
                            <option value="Warning" {{ $row->type == 'Warning' ? 'selected' : '' }} {{ old('type') == 'Warning' ? 'selected' : '' }}>Warning</option>
                            <option value="Danger" {{ $row->type == 'Danger' ? 'selected' : '' }} {{ old('type') == 'Danger' ? 'selected' : '' }}>Danger</option>
                        </select>
                        @if ($errors->has('type'))
                            <small id="help" class="form-text text-danger">{{ $errors->first('type') }}</small>
                        @endif
                    </div>
                    {!! rcForm::ckeditor('Description', 'description', $row->description) !!}
					{!! rcForm::close('PATCH') !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection