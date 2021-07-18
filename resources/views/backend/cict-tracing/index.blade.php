@extends('layouts.backend.app')
@section('content')

    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                @if (Request::session()->has('message'))
                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        {!! Request::session()->get('message') !!}
                    </div>
                @endif
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <strong>CICT List</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="col-12 table-responsive">
                            <table class="table table-bordered">
                                <thead style="background: #fff;">
                                    <tr>
                                        <th>SN</th>
                                        <th>Case ID</th>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th title="Gender">G</th>
                                        <th title="Emergency Contact Number">Phone</th>
                                        <th title="Ward No">Ward</th>
                                        <th title="Actions"><i class="fa fa-cogs" aria-hidden="true"></i></th>
                                    </tr>
                                </thead>
                                <tbody class="table-sars-cov-tbody text-center">
                                    @foreach ($cict_tracings as $key => $data)
                                    <?php
                                        if($data->sex == '1'){
                                            $formatted_sex = 'M';
                                        }elseif($data->sex == '2'){
                                            $formatted_sex = 'F';
                                        }else{
                                            $formatted_sex = '0';
                                        }
                                    ?>
                                    <tr>
                                        <td>{{ ++$key }}</td>
                                        <td>{{ $data->case_id }}</td>
                                        <td>{{ $data->name }}</td>
                                        <td>{{ $data->age }}</td>
                                        <td>{{ $formatted_sex }}</td>
                                        <td>{{ $data->emergency_contact_one }}</td>
                                        <td>{{ $data->ward }}</td>
                                        <td>
                                            <a class="btn btn-sm btn-primary" href="{{ route('cict-tracing.section-one', ['case_id' => $data['case_id']]) }}">Edit</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.row -->
        </div>
    </div>
    <!-- /#page-wrapper -->
@endsection