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
                                        <th>Medicine</th>
                                        <th>Current Stock</th>                                    
                                        <th>Add Medicine</th>
                                        <th>Used Medicine</th>
                                    </tr>
                                    </thead>
                                    
                                    <tbody>
                                    @foreach ($stocks as $stock)
                                      <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $stock['name'] }}
                                        </td>                                     
                                        <td>
                                            {{ $stock['current_stock'] }}
                                        </td>                                           
                                        <td>
                                            <input type="number" name="new_stock{{ $loop->iteration }}" value="0" />
                                        </td>  
                                        <td>
                                          <input type="number" name="remove_stock{{ $loop->iteration }}" value="0" />
                                        </td>
                                        <td>
                                           <button class="btn btn-primary" onClick="confirmUpdate({{ $loop->iteration }}, {{$stock['id'] ?? "0"}}, {{$stock['asset_id']}})">Update</button>
                                    
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

@section('script')
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<script type="text/javascript">
      function confirmUpdate(iteration, stockId, assetId){
        if(confirm("Are you sure to update stock?")){
              var data = {
                stock_id: stockId,
                asset_id: assetId,
                org_code: "{{ \App\Models\Organization::where('token', auth()->user()->token)->first()->org_code }}",
                new_stock: $("input[name='new_stock"+iteration+"']").val(),
                remove_stock: $("input[name='remove_stock"+iteration+"']").val(),
                current_stock:  $("input[name='current_stock"+iteration+"']").val(),
              }
              $.post( "/admin/stock-update",data, function( res ) {
                  location.reload();
              });
          }                                        
          else
          {
              return false;
          }
      }
</script>
@endsection