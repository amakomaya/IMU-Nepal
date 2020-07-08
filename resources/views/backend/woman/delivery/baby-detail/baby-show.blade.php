@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        Baby of {{$womanName}}
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
           
                            
                            <table class="table table-striped table-bordered detail-view">
                            <tbody>
                                     
                                       <tr>
                                            <th>Gender</th>
                                            <td>
                                                @if($data->gender=='0')
                                                    No
                                                @else
                                                    Yes
                                                @endif
                                            </td>
                                        </tr>                                     
                                        <tr>
                                            <th>Weight</th>
                                            <td>{{$data->weight}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Premature Birth</th>
                                            <td>{{$data->premature_birth}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Baby Alive</th>
                                            <td>{{$data->baby_alive}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Baby Status</th>
                                            <td>{{$data->baby_status}}</td>
                                        </tr>                                     
                                        <tr>
                                            <th>Advice</th>
                                            <td>{{$data->advice}}</td>
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


