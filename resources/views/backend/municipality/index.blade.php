@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
                        <div class="form-group">
                            <a class="btn btn-success" href="{{route('municipality.create') }}">{{trans('index.create')}}</a>
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

                $(document).ready(function() {
                    $('#dataTables-example').DataTable( {
                        "paging":   false,
                        "ordering": false,
                        "info":     false
                    } );
                } );
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
                        {{trans('index.local_level_info')}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>{{trans('index.sn')}}</th>                                      
                                        <th>{{trans('index.province')}}</th>                                     
                                        <th>{{trans('index.district')}}</th>                                    
                                        <th>{{trans('index.local_level')}}</th>
                                        <th>{{trans('index.options')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($municipalityInfos as $municipalityInfo)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $municipalityInfo->province }}
                                        </td>                                     
                                        <td>
                                            {{ $municipalityInfo->district }}
                                        </td>                                           
                                        <td>
                                            {{ $municipalityInfo->municipality }}
                                        </td>  
                                        <td>
                                        <form method="post" action="{{route('municipality.destroy', $municipalityInfo->id)}}" onsubmit="return confirmDelete()">
                                            <div class="icon">
                                                <a  href="{{route('municipality.show', $municipalityInfo->id) }}">
                                                <span class="glyphicon glyphicon-eye-open"></span>                                                </a>

                                                    <a href="{{route('municipality.edit', $municipalityInfo->id) }}">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
                                                    {{csrf_field()}}
                                                    {{method_field('DELETE')}}
                                                    <button name="submit" class="pull-right" title="Delete" style="border: 0; background: transparent;"><i class="fa fa-trash-o"></i></button>
                                                    </span>
                                                @endif
                                            </div>
                                            </form>
                                            @if(\App\User::checkAuthForViewByMain()===true || Auth::user()->role === "center" || Auth::user()->role === "province" || Auth::user()->role === "dho")
                                                <form method="post" action="{{route('user-manager.login-as', \App\User::getUserId($municipalityInfo->token) )}}" >
                                                    {{csrf_field()}}
                                                    <div class="form-group">
                                                        <a class="btn btn-xs btn-primary"  href="{{route('user-manager.change-paswword', \App\User::getUserId($municipalityInfo->token) ) }}">
                                                            Change Password
                                                        </a>
                                                        <button class="btn btn-xs btn-primary" name="submit" >Login As</i></button>
                                                    </div>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <span style="float: right">{{ $municipalityInfos->links() }}</span>
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
