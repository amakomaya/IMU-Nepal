@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Overview of Data
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>                                     
                                            <th>Health Post Name</th>    
                                            <th>HP Code</th>                                     
                                            <th>District</th>                                     
                                            <th>AMC Records</th>
                                            <th>VTC Records</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $i = 0; @endphp
                                            @foreach($data as $d)
                                            @php $i++ @endphp
                                            <tr>
                                                <td>{{ $i }}</td>                                          
                                                <td>
                                                <form method="post" action="{{url('admin/user-manager/'.\App\User::getUserId($d->token).'/login-as')}}" >
                                                    {{csrf_field()}}
                                                    <a href="#" onclick="this.parentNode.submit()">{{ $d->name }}</a>
                                                </form>
                                                </td>
                                                <td>{{ $d->hp_code }}</td>                                     
                                                <td>{{ $d->getDistrictName($d->district_id) }}</td>                                     
                                                <td>{{ $d->getAmcRecords($d->hp_code) }}</td>
                                                <td>{{ $d->getVtcRecords($d->hp_code) }}</td>
                                                <td>
                                                    <form method="get" action="{{route('vaccine-vial-management', $d->hp_code)}}" >
                                                        {{csrf_field()}}
                                                        <a class="btn btn-xs btn-primary" onclick="this.parentNode.submit()" href="#" title="Vial Stock, Expense, Used, details">
                                                            Vial Management
                                                        </a>
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
