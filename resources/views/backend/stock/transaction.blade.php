@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        {{trans('index.stock_transaction')}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>{{trans('index.sn')}}</th>                                      
                                        <th>Medicine</th>
                                        <th>New Medicine</th>                                    
                                        <th>Used Medicine</th>
                                        <th>Remaining Stock</th>
                                        <th>Updated At</th>
                                    </tr>
                                    </thead>
                                    
                                    <tbody>
                                    @foreach ($stock_transactions as $stock_transaction)
                                      <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $stock_transaction['name'] }}
                                        </td>                                     
                                        <td>
                                            {{ $stock_transaction['new_stock'] }}
                                        </td>
                                        <td>
                                            {{ $stock_transaction['used_stock'] }}
                                        </td> 
                                        <td>
                                            {{ $stock_transaction['current_stock'] }}
                                        </td>
                                        <td>
                                            {{ $stock_transaction['updated_at'] }}
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