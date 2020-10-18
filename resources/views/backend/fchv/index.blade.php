@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
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
                        <a class="btn btn-success" href="{{route('fchv.create') }}">Create</a>
                </div>
                @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                   Lab Users
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
                                <th>Post</th>                                     
                                <th>Phone No:</th>  
                                <th>Lab Info</th>
                                <th>Sample Received</th>
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
                                <td> {{ str_pad($healthWorker->id, 4, '0', STR_PAD_LEFT)  }}</td>
                                <td> {{ $healthWorker->post }} </td>                                     
                                <td> {{ $healthWorker->phone }}</td>   
                                <td> 
                                    Name : {{ $healthWorker->hp_code }} <br>
                                    Address : {{ $healthWorker->municipality->municipality_name ?? '' }}
                                </td>
                                <td>{{ \App\Models\LabTest::where('checked_by', $healthWorker->token)->get()->count()}}</td>
                                <td> 
                                    @if($healthWorker->status=='0')
                                    <span class="label label-danger">Inactive</span>
                                    @else
                                        <span class="label label-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <form method="post" action="{{route('fchv.destroy', $healthWorker->id)}}" onsubmit="return confirm('Are you sure?')">
                                        <div class="icon">
                                            <a  href="{{route('fchv.show', $healthWorker->id) }}" title="View">
                                                <span class="glyphicon glyphicon-eye-open"></span>                                            
                                            </a>
                                                <a href="{{route('fchv.edit', $healthWorker->id) }}" title="Edit">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                <button name="submit" class="pull-right" style="border: 0; background: transparent;" title="Delete"><i class="fa fa-trash-o"></i></button>
                                                @endif
                                        </div>
                                    </form>
                                    <a class="btn btn-xs btn-primary"  href="{{route('user-manager.change-paswword', \App\User::getUserId($healthWorker->token) ) }}">
                                        Change Password
                                    </a>
                                    <form method="post" action="{{url('admin/user-manager/'.\App\User::getUserId($healthWorker->token).'/login-as')}}" >
                                        {{csrf_field()}}
                                        <a href="#" class="btn btn-xs btn-primary" onclick="this.parentNode.submit()">Login As</a>
                                    </form>
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