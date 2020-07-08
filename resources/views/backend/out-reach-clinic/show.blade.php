@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">

                    <div class="panel panel-default">
                        <div class="panel-heading">Out Reach Clinics : {{$data->name}}</h1>
            <p>
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
            
            
            <form method="post" action="{{route('out-reach-clinic.destroy', $data->id)}}" onsubmit="return confirmDelete()">
                {{csrf_field()}}

                {{method_field('DELETE')}}

                <a class="btn btn-primary" href="{{ route('out-reach-clinic.edit', $data->id )}}">Edit</a>

                
                <button class="btn btn-danger">Delete</button>
                </form>
            </p>
            <table class="table table-striped table-bordered detail-view">
                            <tbody>
                                     
                                        <tr>
                                            <th width="30%">Name</th>
                                            <td>{{$data->name}}</td>
                                        </tr>                                     
                                                                            
                                        <tr>
                                            <th>Address</th>
                                            <td>{{$data->address}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Phone</th>
                                            <td>{{$data->phone}}</td>
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
                                            <td>{{$data->status}}</td>
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



