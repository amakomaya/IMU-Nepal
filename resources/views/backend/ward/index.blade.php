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
            @if(\App\User::checkAuthForViewByMunicipality()===true && \App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
            <div class="form-group">
                <a class="btn btn-success" href="{{route('ward.create') }}">{{trans('index.create')}}</a>
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
            <div class="panel panel-default">
                <div class="panel-heading">
                {{trans('index.wards')}}
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                            <tr>
                                <th>{{trans('index.sn')}}</th>                                     
                                <th>{{trans('index.ward_no')}}</th> 
                                <th>{{trans('index.phone')}}</th>                                        
                                <th>{{trans('index.district')}}</th>                                     
                                <th>{{trans('index.municipality')}}</th>                                     
                                <th>{{trans('index.status')}}</th> 
                                <th>{{trans('index.options')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i = 0; @endphp
                            @foreach($wards as $ward)
                            @php $i++ @endphp
                            <tr>
                                <td>{{ $i }}</td>                                     
                                <td>{{$ward->ward_no}}</td>   
                                <td>{{$ward->phone}}</td>                     
                                <td>@if(!empty(\App\Models\Ward::find($ward->id)->district->district_name))
                                        {{\App\Models\Ward::find($ward->id)->district->district_name}}
                                    @endif
                                </td>                                     
                                <td>@if(!empty(\App\Models\Ward::find($ward->id)->municipality->municipality_name))
                                        {{\App\Models\Ward::find($ward->id)->municipality->municipality_name}}
                                    @endif
                                </td> 
                                <td>
                                    @if($ward->status=='0')
                                    <span class="label label-danger">Inactive</span>

                                    @else
                                    <span class="label label-success">Active</span>

                                    @endif
                                </td>
                                <td>
                                    <form method="post" action="{{route('ward.destroy', $ward->id)}}" onsubmit="return confirmDelete()">

                                        <div class="icon">
                                            <a  href="{{route('ward.show', $ward->id) }}">
                                            <span class="glyphicon glyphicon-eye-open"></span>                                            </a>
                                            @if(\App\User::checkAuthForViewByMunicipality()===true)
                                                <a href="{{route('ward.edit', $ward->id) }}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                                
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                @if(\App\User::getFirstLoggedInRole(\Request::session()->get('user_token')) == 'Main')
                                                    <button name="submit" class="pull-right" title="Delete" style="border: 0; background: transparent;"><i class="fa fa-trash-o"></i></button>
                                                @endif
                                            @endif
                                        </div>
                                    </form>
                                    @if(\App\User::checkAuthForViewByMunicipality()===true && $ward->status=='1')
                                        <form method="post" action="{{route('user-manager.login-as', \App\User::getUserId($ward->token) )}}" >
                                            {{csrf_field()}}
                                            <div class="form-group">
                                                <a class="btn btn-xs btn-primary"  href="{{route('user-manager.change-paswword', \App\User::getUserId($ward->token) ) }}">
                                                    Change Password
                                                </a>
                                                @if(\App\User::getFirstLoggedInRole(\Request::session()->get('user_token')) == 'Main')<button class="btn btn-xs btn-primary" name="submit" >Login As</i></button>@endif
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