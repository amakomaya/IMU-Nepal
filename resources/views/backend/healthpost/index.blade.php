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
                @if (\Request::session()->has('message'))
                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        {!! Request::session()->get('message') !!}
                    </div>
                @endif
                @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
                <div class="form-group">
                    <a class="btn btn-success" href="{{route('healthpost.create') }}">{{trans('index.create')}}</a>
                </div>
                @endif
            <div class="panel panel-default">
                <div class="panel-heading">
                    Hospitals / CICT Teams
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>{{trans('index.sn')}}</th>                                     
                                    <th>{{trans('index.name')}}</th>
                                    <th>{{trans('index.municipality')}}</th>    
                                    <th>{{trans('index.status')}}</th>
                                    <th>Info</th>
                                    <th>{{trans('index.updated_at')}}</th>
                                    <th>{{trans('index.options')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($healthposts as $healthpost)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $healthpost->name }}</td>
                                    <td>
                                        {{ $healthpost->municipality->municipality_name ?? '' }}
                                    </td>
                                    <td>
                                        @if($healthpost->status=='0')
                                        <span class="label label-danger">Inactive</span>

                                        @else
                                        <span class="label label-success">Active</span>

                                        @endif
                                    </td>
                                    <td>
                                        Hospital Type : {{ $healthpost->hospitalType($healthpost->hospital_type) }} <br>
                                        <span><u>Total number of</u></span><br>
                                        Beds : {{ $healthpost->no_of_beds }}<br>
                                        Ventilators : {{ $healthpost->no_of_ventilators }}<br>
                                        ICU : {{ $healthpost->no_of_icu }}
                                    </td>
                                    <td>{{ $healthpost->updated_at->diffForHumans() }}</td>
                                    <td>
                                        <form method="post" action="{{route('healthpost.destroy', $healthpost->id)}}" onsubmit="return confirmDelete()">
                                            <div class="icon">
                                                <a  href="{{route('healthpost.show', $healthpost->id) }}">
                                                <span class="glyphicon glyphicon-eye-open"></span>                                                </a>
                                                @if(\App\User::checkAuthForViewByMunicipality()===true)
                                                    <a href="{{route('healthpost.edit', $healthpost->id) }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                    @if(Request::session()->get('permission_id') == 1)
                                                      {{csrf_field()}}
                                                      {{method_field('DELETE')}}
                                                      <button name="submit" class="pull-right" title="Delete" style="border: 0; background: transparent;"><i class="fa fa-trash-o"></i></button>
                                                    @endif
                                                    </span>
                                                @endif
                                            </div>
                                        </form>
                                        @if(\App\User::checkAuthForViewByMunicipality()===true && $healthpost->status=='1'
                                        )
                                            <form method="post" action="{{route('user-manager.login-as', \App\User::getUserId($healthpost->token) )}}" >
                                                {{csrf_field()}}
                                                <div class="form-group">
                                                    <a class="btn btn-xs btn-primary"  href="{{route('user-manager.change-paswword', \App\User::getUserId($healthpost->token) ) }}">
                                                        Change Password
                                                    </a>
                                                    <button class="btn btn-xs btn-primary" name="submit" >Login As</button>
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
