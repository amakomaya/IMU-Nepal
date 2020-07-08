@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
            @if($centers->isEmpty())
                <div class="form-group">
                    <a class="btn btn-success" href="{{route('center.create') }}">Create</a>
                </div>
            @endif
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
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Center Info
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>                                     
                                            <th>Center</th>    
                                            <th>Phone</th>                                     
                                            <th>Office Address</th>                                     
                                            <th>Status</th>
                                            <th>Created At</th>
                                            <th>Updated At</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 0; @endphp
                                        @foreach($centers as $center)
                                            @php $i++ @endphp
                                            <tr>
                                                <td>{{ $i }}</td>                                          
                                                <td>{{ $center->name }}</td>           
                                                <td>{{$center->phone}}</td>                                     
                                                <td>{{$center->office_address}}</td>                                     
                                                <td>
                                                    @if($center->status=='0')
                                                    <span class="label label-danger">Inactive</span>
                                                    @else
                                                    <span class="label label-success">Active</span>
                                                    @endif 
                                                </td>
                                                <td>{{$center->created_at->diffForHumans()}}</td>
                                                <td>{{$center->updated_at->diffForHumans()}}</td>
                                                <td>
                                                    <form method="post" action="{{route('center.destroy', $center->id)}}" onsubmit="return confirmDelete()">
                                                        <div class="icon">

                                                            <a  href="{{route('center.show', $center->id) }}">
                                                                <span class="glyphicon glyphicon-eye-open"></span>                                                           </a>

                                                            <a href="{{route('center.edit', $center->id) }}">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                            
                                                            {{csrf_field()}}
                                                            {{method_field('DELETE')}}
                                                            <button name="submit" class="pull-right" title="Delete" style="border: 0; background: transparent;"><i class="fa fa-trash-o"></i></button>
                                                            </span>
                                                        </div>
                                                    </form>
                                                   <form method="post" action="{{route('user-manager.login-as', \App\User::getUserId($center->token) )}}" >
                                                        {{csrf_field()}}
                                                        <div class="form-group">
                                                            <a class="btn btn-xs btn-primary"  href="{{route('user-manager.change-paswword', \App\User::getUserId($center->token) ) }}">
                                                                Change Password
                                                            </a>
                                                            <button class="btn btn-xs btn-primary" name="submit" >Login As</i></button>
                                                        </div>
                                                    </form>
                                                    </td>
                                                </tr>
                                            @endforeach                            
                                        </tbody>
                                </table>
                            </div>
                            <!-- /.table-responsive -->
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
