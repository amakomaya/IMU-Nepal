@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    @if(\App\User::getFirstLoggedInRole(Request::session()->get('user_token')) == 'Main')
                        <div class="form-group">
                            <a class="btn btn-success" href="{{route('dho.create') }}">{{trans('index.create')}}</a>
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
                        {{trans('index.dho_info')}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>{{trans('index.sn')}}</th>                                     
                                            <th>{{trans('index.district')}}</th>
                                            <th>{{trans('index.phone')}}</th>                                     
                                            <th>{{trans('index.office_address')}}</th>                                     
{{--                                            <th>{{trans('index.status')}}</th>--}}
{{--                                            <th>{{trans('index.created_at')}}</th>--}}
                                            <th title="Total Vaccination Information Data">Total / Vaccinated </th>
                                            <th>{{trans('index.options')}}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 0; @endphp
                                        @foreach($data as $datum)
                                            @php $i++ @endphp
                                            <tr>
                                                <td>{{ $i }}</td>                                          
                                                <td>@if(!empty(\App\Models\DistrictInfo::find($datum->id)->district->district_name))
                                                        {{\App\Models\DistrictInfo::find($datum->id)->district->district_name}}
                                                    @endif
                                                </td>           
                                                <td>{{$datum->phone}}</td>                                     
                                                <td>{{$datum->office_address}}</td>                                     

                                                <td>{{$datum->total}} / {{ $datum->vaccinated_total }}
                                                    <form method="post" action="{{ route('excel-download.unvaccinated', [$datum->district_id, 'district']) }}" onsubmit="return confirm('Are you sure you want to download all the unvaccinated records?');">
                                                        {{csrf_field()}}
                                                        <button name="submit" class="btn btn-warning btn-xs" title="Download unvaccinated records"><i class="fa fa-download"> Download</i></button>
                                                    </form>
                                                </td>
                                                <td>
                                                    
                                                    <form method="post" action="{{route('dho.destroy', $datum->id)}}" onsubmit="return confirmDelete()"> 
                                                        <div class="icon">
                                                            <a  href="{{route('dho.show', $datum->id) }}">
                                                            <span class="glyphicon glyphicon-eye-open"></span>                                                            </a>
                                                                <a href="{{route('dho.edit', $datum->id) }}">
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
                                                    
                                                    @if(\App\User::checkAuthForViewByMain()===true || Auth::user()->role === "center" || Auth::user()->role === "province" && $datum->status=='1')
                                                    <form method="post" action="{{route('user-manager.login-as', \App\User::getUserId($datum->token) )}}" >
                                                        {{csrf_field()}}
                                                        <div class="form-group">
                                                            <a class="btn btn-xs btn-primary"  href="{{route('user-manager.change-paswword', \App\User::getUserId($datum->token) ) }}">
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
