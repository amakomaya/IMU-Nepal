@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-2">
                    <div class="panel panel-info">
                      <div class="panel-heading text-center"><strong>Total Registred</strong></div>
                      <div class="panel-body text-center"><h4><strong>{{ $rows->count() }}</strong></h4></div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="form-group">
<!--                     <a class="btn btn-success" href="{{route('advertisement.create') }}">Create</a>
 -->                </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Woman Registred From App
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            @if (Request::session()->has('message'))
                                <div class="alert alert-info">
                                    <button type="button" class="close" data-dismiss="alert">
                                        <i class="icon-remove"></i>
                                    </button>
                                    {!! Request::session()->get('message') !!}
                                    <br>
                                </div>
                                @endif 
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>                                     
                                            <th>Name</th>    
                                            <th>Phone</th>    
                                            <th>LMP Date</th>    
                                            <th>Age</th>
                                            <th>Municipality</th>                                     
                                            <th>Ward No</th>                                     
                                            <th>Created At</th>                                     
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>                
                                            <td>{{ $row->name }}</td>           
                                            <td>{{ $row->phone }}</td>           
                                            <td>EN : {{ $row->lmp_date_en }} <br>
                                                NP : {{ $row->lmp_date_np }}
                                            </td>   
                                            <td>{{ $row->age }}</td>
                                            <td>{{ \App\Models\Municipality::where('id', $row->municipality_id)->first()->municipality_name ?? ''  }}</td>
                                            <td>{{ $row->ward_no }}</td>
                                            <td>{{ $row->created_at }}</td>
                                            <td>
                                                @if($row->status !== 0)
                                                                                               
                                                <a href="{{ route('content-app.reg.woman.show', $row->id) }}" class="btn btn-warning btn-sm">
                                                    <i class="fa fa-send-o" aria-hidden="true"></i>
                                                    <span><strong>Send CARE</strong></span>            
                                                </a>  

                                                @endif

                                                {{-- <a href="{{ route('content-app.reg.woman.edit', $row->id) }}" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    <span><strong>Edit</strong></span>            
                                                </a>  --}}

                                               <form action="{{ route('content-app.reg.woman.delete', $row->id) }}" method="POST">
                                                     @method('DELETE')
                                                     @csrf
                                                     <button onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash" aria-hidden="true"></i>Delete</button>          
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