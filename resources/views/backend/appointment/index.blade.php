@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
<!--                     <a class="btn btn-success" href="{{route('news-feed.create') }}">Create</a>
 -->                </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Appointments
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
                                 @foreach($rows as $key => $row)
            <br>
            <strong>
              <h4>{{ $key }}
                @if($loop->first)
                @endif
              </h4>
            </strong>
          <div class="dataTable_wrapper">
          <table class="table table-striped table-bordered table-hover" id="dataTables-example.{{ $key }}">
              <thead>
                  <tr>
                      <th>S.N</th>                                     
                      <th>Name</th>    
                      <th>Phone</th>                                     
                      <th>Type / Purpose</th>
                      <th>Place</th>
                      <th>Date</th>
                      <th>Time</th>
                      <th>Problems</th>
                      <th>Options</th>
                  </tr>
              </thead>
              <tbody>
                  @php $i = 0; @endphp
                                                                                                  
                      @foreach($row as $r)
                      @php $i++ ; @endphp
                      <tr>
                            <td>{{ $i }}</td>                                          
                            <td>{{ $r['data']['name'] }}</td>           
                            <td>{{ $r['data']['phone'] }}</td>           
                            <td>{{ $r->typePurpose($r['data']['appointment_purpose']) }}</td>                                       
                            <td>{{ $r['data']['appointment_place'] }}</td>           
                            <td>{{ $r['data']['date'] }}</td>           
                            <td>{{ $r['data']['time'] }}</td>           
                            <td>{{ $r['data']['problems'] }}</td>           
                          <td>
                            
                          </td>                    
                      </tr>  
                      @endforeach
                  </tbody>
          </table>
          <hr style="border-top : 1px dashed">
          @endforeach      
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