@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    @if (\Request::session()->has('message'))
                        <div class="alert alert-block alert-success">
                            <button type="button" class="close" data-dismiss="alert">
                                <i class="ace-icon fa fa-times"></i>
                            </button>
                            {!! Request::session()->get('message') !!}
                        </div>
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Record Overview
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>                                     
                                            <th title="Organization Name" width="25%">Name</th>
                                            <th>Username</th>
                                            <th>Province</th>
                                            <th>District</th>
                                            <th>Municipality</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                            @foreach($data as $d)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    @php($id = $d->user->id ?? '')
                                                <form method="post" action="{{url('admin/user-manager/'.$id.'/login-as')}}" >
                                                    {{csrf_field()}}
                                                    <a href="#" onclick="this.parentNode.submit()">{{ $d->name }}</a>
                                                </form>
                                                </td>
                                                <td>{{ $d->user->username ?? '' }}</td>
                                                <td>{{ $d->province->province_name ?? '' }}</td>
                                                <td>{{ $d->district->district_name ?? '' }}</td>
                                                <td>{{ $d->municipality->municipality_name ?? '' }}</td>
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