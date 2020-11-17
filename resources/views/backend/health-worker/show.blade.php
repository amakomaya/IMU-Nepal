@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        {{$data->name}}
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                    @if(\App\User::checkAuthForViewByHealthpost()===true)
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
                        @if($role=="healthworker")
                            <form method="post" action="{{route('health-worker.destroy', $data->id)}}" onsubmit="return confirmDelete()">
                                {{csrf_field()}}

                                {{method_field('DELETE')}}

                                <a class="btn btn-primary" href="{{ route('health-worker.edit', $data->id )}}">Edit</a>

                                
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        @else
                            <form method="post" action="{{route('fchv.destroy', $data->id)}}" onsubmit="return confirmDelete()">
                                {{csrf_field()}}

                                {{method_field('DELETE')}}

                                <a class="btn btn-primary" href="{{ route('fchv.edit', $data->id )}}">Edit</a>

                                
                                <button class="btn btn-danger">Delete</button>
                            </form>
                        @endif
                    @endif
                        
                        <table class="table table-striped table-bordered detail-view">
                            <tbody>                                    
                                <tr>
                                    <th>Name</th>
                                    <td>{{$data->name}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Province</th>
                                    <td>@if(!empty(\App\Models\HealthWorker::find($data->id)->province->province_name)){{\App\Models\HealthWorker::find($data->id)->province->province_name}}
                                        @endif
                                    </td>
                                </tr>                                     
                                <tr>
                                    <th>District</th>
                                    <td>@if(!empty(\App\Models\HealthWorker::find($data->id)->district->district_name)){{\App\Models\HealthWorker::find($data->id)->district->district_name}}
                                        @endif
                                    </td>
                                </tr>                                     
                                <tr>
                                    <th>Municipality</th>
                                    <td>@if(!empty(\App\Models\HealthWorker::find($data->id)->municipality->municipality_name)){{\App\Models\HealthWorker::find($data->id)->municipality->municipality_name}}
                                        @endif
                                    </td>
                                </tr>                                     
                                <tr>
                                    <th>Ward</th>
                                    <td>{{$data->ward}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Post</th>
                                    <td>{{ $data->post }}</td>
                                </tr>                                    
                                <tr>
                                    <th>Signature</th>
                                    <td>
                                        @if(!empty($data->image))
                                            <img src="{{ Storage::url('health-worker/'.$data->image) }}" /> <br><br>
                                        @endif
                                    </td>
                                </tr>                                       
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$data->phone}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Tole</th>
                                    <td>{{$data->tole}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Registered Device</th>
                                    <td>{{$data->registered_device}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Role</th>
                                    <td>
                                        @if($data->role == 'fchv')
                                            Lab
                                        @else
                                            Healthworker
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <th>Assigned Permission</th>
                                    <td>{{ implode(',', $data->user->getAllPermissions()->pluck('name')->toArray()) }}</td>
                                </tr>
                                <!-- <tr>
                                    <th>Longitude</th>
                                    <td>{{$data->longitude}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Latitude</th>
                                    <td>{{$data->latitude}}</td>
                                </tr>                                      -->
                                <tr>
                                    <th>IMEI Number</th>
                                    <td>{{ $user->imei }}</td>
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
