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
                        {!! rcForm::open('POST', route('permissions.update', $permission->id)) !!}
                        {{ method_field('PATCH') }}
                        {!! rcForm::text('Name', 'name', $permission->name) !!}
                        {!! rcForm::close('PATCH') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection