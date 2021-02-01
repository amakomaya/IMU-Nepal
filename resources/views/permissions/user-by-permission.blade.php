@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="dataTables_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Permission</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($users as $permission)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $permission->username }}</td>
                                <td>{{ ($permission->role == 'healthpost') ? 'Organization' : 'Data Entry' }}</td>
                                <td>{{ implode(', ', $permission->getPermissionNames()->toArray()) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection