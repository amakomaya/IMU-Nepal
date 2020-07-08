@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                    <a class="btn btn-success" href="{{route('appointment.plan.create') }}">Create Plan</a>
                </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Appointment Plans
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
                                            <th>Plan Type</th>    
                                            <th>Place </th>    
                                            <th>Start Date </th>    
                                            <th>End Date </th>    
                                            <th>From</th>                                     
                                            <th>To</th>                                     
                                            <th>Duration (in min)</th>                                     
                                            <th>Total Seat</th>                                     
                                            <th>Booked Seat</th>                                     
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>                
                                            <td>{{ $row->typePlan($row->plan) }}</td>           
                                            <td>{{ $row->place }}</td>           
                                            <td>{{ $row->from }}</td>           
                                            <td>{{ $row->to }}</td>           
                                            <td>{{ $row->start_time }}</td>           
                                            <td>{{ $row->end_time }}</td>           
                                            <td>{{ $row->duration }}</td>           
                                            <td>{{ $row->total_seat }}</td>           
                                            <td>{{ $row->booked_seat }}</td>           
                                            <td>
                                                <a href="{{ route('appointment.plan.edit', $row->id) }}" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    <span><strong>Edit</strong></span>            
                                                </a>  
                                                {{--
                                               <form action="{{ route('news-feed.destroy', $row->id) }}" method="POST">
                                                     @method('DELETE')
                                                     @csrf
                                                     <button onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash" aria-hidden="true"></i>Delete</button>          
                                                </form>    --}}                                    
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