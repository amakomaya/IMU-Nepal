@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Hospital Record Overview
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>                                     
                                            <th>Hospitals</th>
                                            <th>Username</th>
                                            <th>Municipality</th>
                                            <th>Registers</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                <form method="post" action="{{url('admin/user-manager/'.$d->user->token.'/login-as')}}" >
                                                    {{csrf_field()}}
                                                    <a href="#" onclick="this.parentNode.submit()">{{ $d->name }}</a>
                                                </form>
                                                </td>
                                                <td>{{ $d->user->username ?? '' }}</td>
                                                <td>{{ $d->municipality->municipality_name ?? '' }}</td>
                                                <td></td>
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