@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
            <script type="text/javascript">
                function confirmDelete(){
                    if(confirm("Are you sure to delete?")){
                        return true;
                    }

                    else
                    {
                        return false;
                    }
                }
            </script>
                @if (Request::session()->has('message'))
                <div class="alert alert-block alert-success">
                    <button type="button" class="close" data-dismiss="alert">
                        <i class="ace-icon fa fa-times"></i>
                    </button>
                    {!! Request::session()->get('message') !!}
                </div>
                @endif
                
            @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
                <div class="form-group">
                    @if($role=="healthworker")
                        <a class="btn btn-success" href="{{route('health-worker.create') }}">Create</a>
                    @else
                        <a class="btn btn-success" href="{{route('fchv.create') }}">Create</a>
                    @endif
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if($role=="healthworker")
                        Users
                    @else
                        Lab Users
                    @endif
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>S.N</th>                                      
                                <th>Name</th>
                                <th>User ID</th>
                                <th>Username</th>
                                <th>Permissions</th>
                                <th>Post</th>
                                <th>Phone No:</th>  
                                <th>Status</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 0; @endphp
                            @foreach($healthWorkers as $healthWorker)
                            @php $i++ @endphp
                            <tr>
                                <td> {{ $i }}</td>                                          
                                <td> {{ $healthWorker->name }} </td>
                                <td>{{ str_pad($healthWorker->id, 4, '0', STR_PAD_LEFT) }}</td>
                                    <td>{{ $healthWorker->user->username }}</td>
                                    <td>{{ implode(', ', $healthWorker->user->getPermissionNames()->toArray()) }}</td>
                                <td> {{ $healthWorker->post }} </td>
                                <td> {{ $healthWorker->phone }}</td>                                     
                                  
                                <td> 
                                    @if($healthWorker->status=='0')
                                    <span class="label label-danger">Inactive</span>
                                    @else
                                        <span class="label label-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                @if($role=="healthworker")
                                    <form method="post" action="{{route('health-worker.destroy', $healthWorker->id)}}" onsubmit="return confirmDelete()">
                                        <div class="icon">
                                            <a  href="{{route('health-worker.show', $healthWorker->id) }}" title="View">
                                            <span class="glyphicon glyphicon-eye-open"></span>                                            </a>
                                            @if(\App\User::checkAuthForViewByHealthpost()===true )
                                                <a href="{{route('health-worker.edit', $healthWorker->id) }}" title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                <button name="submit" class="pull-right" style="border: 0; background: transparent;" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            @endif
                                        </div>
                                    </form>
                                @else
                                    <form method="post" action="{{route('fchv.destroy', $healthWorker->id)}}" onsubmit="return confirmDelete()">
                                        <div class="icon">
                                            <a  href="{{route('fchv.show', $healthWorker->id) }}" title="View">
                                            <span class="glyphicon glyphicon-eye-open"></span>                                            </a>
                                            @if(\App\User::checkAuthForViewByHealthpost()===true )
                                                <a href="{{route('fchv.edit', $healthWorker->id) }}" title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                <button name="submit" class="pull-right" style="border: 0; background: transparent;" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            @endif
                                            @endif
                                        </div>
                                    </form>
                                @endif
                                @if(\App\User::checkAuthForViewByHealthpost()===true && $healthWorker->status=='1')
                                    <form method="post" action="{{route('user-manager.login-as', \App\User::getUserId($healthWorker->token) )}}" >
                                        {{csrf_field()}}
                                        <div class="form-group">
                                            <a class="btn btn-xs btn-primary"  href="{{route('user-manager.change-paswword', \App\User::getUserId($healthWorker->token) ) }}">
                                                Change Password
                                            </a>
                                            @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main'
                                                || \App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Province'
                                                )
                                                <button class="btn btn-xs btn-primary" name="submit" >Login As</i></button>
                                            @endif
                                        </div>
                                    </form>
                                @endif
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.dataTable_wrapper -->
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