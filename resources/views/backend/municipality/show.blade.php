@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Municipality : 
                            @if(!empty(\App\Models\MunicipalityInfo::find($data->id)->municipality->municipality_name))
                            {{\App\Models\MunicipalityInfo::find($data->id)->municipality->municipality_name}}
                            @endif
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
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
                                
                            
                            @if(\App\User::checkAuthForViewByMain()===true)
                                <form method="post" action="{{route('municipality.destroy', $data->id)}}" onsubmit="return confirmDelete()">
                                    <p>
                                        {{csrf_field()}}

                                        {{method_field('DELETE')}}

                                        <a class="btn btn-primary" href="{{ route('municipality.edit', $data->id )}}">Edit</a>

                                        
                                        <button class="btn btn-danger">Delete</button>
                                    </p>
                                </form>
                            @endif
                            <table class="table table-striped table-bordered detail-view">
                                    <tbody>
                                         
                                            <tr>
                                                <th width="30%">Token</th>
                                                <td>{{$data->token}}</td>
                                            </tr>                                     
                                            <tr>
                                                <th>Phone</th>
                                                <td>{{$data->phone}}</td>
                                            </tr>                                     
                                            <tr>
                                                <th>province</th>
                                                <td>@if(!empty(\App\Models\MunicipalityInfo::find($data->id)->province->province_name)){{\App\Models\MunicipalityInfo::find($data->id)->province->province_name}}
                                                    @endif
                                                </td>
                                            </tr>                                     
                                            <tr>
                                                <th>District</th>
                                                <td>@if(!empty(\App\Models\MunicipalityInfo::find($data->id)->district->district_name)){{\App\Models\MunicipalityInfo::find($data->id)->district->district_name}}
                                                    @endif
                                                </td>
                                            </tr>                                       
                                            <tr>
                                                <th>Municipality</th>
                                                <td>@if(!empty(\App\Models\MunicipalityInfo::find($data->id)->municipality->municipality_name)){{\App\Models\MunicipalityInfo::find($data->id)->municipality->municipality_name}}
                                                    @endif
                                                </td>
                                            </tr>                                    
                                            <tr>
                                                <th>Office Address</th>
                                                <td>{{$data->office_address}}</td>
                                            </tr>                                     
                                            <tr>
                                                <th>Office Longitude</th>
                                                <td>{{$data->office_longitude}}</td>
                                            </tr>                                     
                                            <tr>
                                                <th>Office Lattitude</th>
                                                <td>{{$data->office_lattitude}}</td>
                                            </tr>                                     
                                            <tr>
                                                <th>Status</th>
                                                <td>
                                                    @if($data->status=='0')
                                                    <span class="label label-danger">Inactive</span>

                                                    @else
                                                    <span class="label label-success">Active</span>

                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Created At</th>
                                                <td>{{$data->created_at->diffForHumans()}}</td>
                                            </tr>
                                            <tr>
                                                <th>Updated At</th>
                                                <td>{{$data->updated_at->diffForHumans()}}</td>
                                            </tr>                             
                                    </tbody>
                            </table>
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



