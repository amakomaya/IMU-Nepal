@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                <div class="form-group">
                    <a class="btn btn-success" href="{{route('out-reach-clinic.create') }}">Create</a>
                </div>
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
                            Out Reach Clinics (ORC)
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                <thead>
                <tr>
                    <th>S.N</th>                                     
                    <th>Name</th>                                       
                    <th>Address</th>    
                    <th>Phone</th>    
                    <th>Longitude</th>    
                    <th>Lattitude</th>    
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                @php $i = 0; @endphp
                @foreach($outReachClinics as $outReachClinic)
                @php $i++ @endphp
                <tr>
                    <td>{{ $i }}</td>                                     
                    <td>{{$outReachClinic->name}}</td> 
                    <td>{{$outReachClinic->address}}</td>      
                    <td>{{$outReachClinic->phone}}</td>      
                    <td>{{$outReachClinic->longitude}}</td>      
                    <td>{{$outReachClinic->lattitude}}</td>      
                    <td>{{$outReachClinic->status}}</td>
                    <td>{{$outReachClinic->created_at->diffForHumans()}}</td>
                    <td>{{$outReachClinic->updated_at->diffForHumans()}}</td>
                    <td>
                    <form method="post" action="{{route('out-reach-clinic.destroy', $outReachClinic->id)}}" onsubmit="return confirmDelete()">

                        <a  href="{{route('out-reach-clinic.show', $outReachClinic->id) }}">
                            <i class="fa fa-search-plus"></i>
                        </a>

                        <a href="{{route('out-reach-clinic.edit', $outReachClinic->id) }}">
                            <i class="fa fa-pencil"></i>
                        </a>
                        
                        {{csrf_field()}}
                        {{method_field('DELETE')}}
                        <button name="submit" style="border: 0; background: transparent;"><i class="fa fa-trash-o"></i></button>
                        </span>
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
