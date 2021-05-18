@extends('layouts.backend.app')

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}" />
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                        {{trans('index.stock_list')}}
                        </div>
                        <!-- /.panel-heading -->
                        <div class="panel-body">
                            
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                    <tr>
                                        <th>{{trans('index.sn')}}</th>
                                        <th>Hospital Name</th>                                    
                                        @foreach ($availableAssets as $asset)                                 
                                        <th>
                                            {{ $asset }}
                                        </th> 
                                        @endforeach  
                                    </tr>
                                    </thead>
                                    
                                    <tbody>
                                    @foreach ($stockList as $stock)
                                      <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $stock['organization_name'] }}
                                        </td>  
                                        @foreach ($availableAssets as $asset)                                 
                                        <td>
                                            {{ $stock[$asset]??0 }}
                                        </td> 
                                        @endforeach
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
