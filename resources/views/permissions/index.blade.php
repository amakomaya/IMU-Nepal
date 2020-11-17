@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
    <div class="col-md-10 col-md-offset-1">
        <h1>Permissions Management
{{--            <a href="{{ route('users.index') }}" class="btn btn-default pull-right">Users</a>--}}
{{--            <a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Roles</a>--}}
            <a href="{{ URL::to('admin/permissions/create') }}" class="btn btn-success">Add Permission</a>
        </h1>
        <hr>
        @if (Request::session()->has('success'))
            <div class="alert alert-info">
                <button type="button" class="close" data-dismiss="alert">
                    <i class="icon-remove"></i>
                </button>
                {!! Request::session()->get('success') !!}
                <br>
            </div>
        @endif
        <div class="dataTables_wrapper">
            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Permissions</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($permissions as $permission)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $permission->name }}</td>
                        <td>
                            <a href="{{ URL::to('admin/permissions/'.$permission->id.'/edit') }}" class="btn btn-warning btn-xs pull-left"><i class="fa fa-pencil" aria-hidden="true"></i> Edit </a>
{{--                            {!! rcForm::open(['method' => 'DELETE', 'route' => ['permissions.destroy', $permission->id] ]) !!}--}}
{{--                            {!! rcForm::submit('Delete', ['class' => 'btn btn-danger']) !!}--}}
{{--                            {!! rcForm::close() !!}--}}
                            <form action="{{ route('permissions.destroy', $permission->id) }}" method="POST">
                                @method('DELETE')
                                @csrf
                                <button onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-xs pull-right" type="submit"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
        </div>
    </div>
@endsection