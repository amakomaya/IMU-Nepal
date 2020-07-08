@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Healthpost : {{$data->name}}
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                    @if(\App\User::checkAuthForViewByWard()===true)
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
                        <form method="post" action="{{route('healthpost.destroy', $data->id)}}" onsubmit="return confirmDelete()">
                            {{csrf_field()}}

                            {{method_field('DELETE')}}

                            <a class="btn btn-primary" href="{{ route('healthpost.edit', $data->id )}}">Edit</a>

                            
                            <button class="btn btn-danger">Delete</button>
                        </form>
                    @endif
                    <table class="table table-striped table-bordered detail-view">
                    <tbody>
                             
                                <tr>
                                    <th width="30%">Name</th>
                                    <td>{{$data->name}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Token</th>
                                    <td>{{$data->token}}</td>
                                </tr>                                     
                                <tr>
                                    <th>province</th>
                                    <td>@if(!empty(\App\Models\Healthpost::find($data->id)->province->province_name)){{\App\Models\Healthpost::find($data->id)->province->province_name}}
                                        @endif
                                    </td>
                                </tr>                                     
                                <tr>
                                    <th>District</th>
                                    <td>@if(!empty(\App\Models\Healthpost::find($data->id)->district->district_name)){{\App\Models\Healthpost::find($data->id)->district->district_name}}
                                        @endif
                                    </td>
                                </tr>                                     
                                <tr>
                                    <th>Municipality</th>
                                    <td>@if(!empty(\App\Models\Healthpost::find($data->id)->municipality->municipality_name)){{\App\Models\Healthpost::find($data->id)->municipality->municipality_name}}
                                        @endif
                                    </td>
                                </tr>                                        
                                <tr>
                                    <th>Ward No</th>
                                    <td>{{$data->ward_no}}</td>
                                </tr>                                         
                                <tr>
                                    <th>Hp Code</th>
                                    <td>{{$data->hp_code}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Phone</th>
                                    <td>{{$data->phone}}</td>
                                </tr>      
                                <tr>
                                    <th>Email</th>
                                    <td>{{$user->email}}</td>
                                </tr>                  
                                <tr>
                                    <th>Address</th>
                                    <td>{{$data->address}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Longitude</th>
                                    <td>{{$data->longitude}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Lattitude</th>
                                    <td>{{$data->lattitude}}</td>
                                </tr>                                     
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        @if($data->status=='0')
                                            Inactive
                                        @else
                                            Active
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


