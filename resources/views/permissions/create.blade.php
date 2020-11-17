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
                        {!! rcForm::open('POST', route('permissions.store')) !!}

                        {!! rcForm::text('Name', 'name') !!}
                        {!! rcForm::close('post') !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection