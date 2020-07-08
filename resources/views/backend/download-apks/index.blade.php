@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
    <!-- /.row -->
    <div class="row">
        <div class="col-lg-12">
                <script type="text/javascript">
                     function confirmDelete(){
                        if(confirm("Are you sure to delete?")){
                                                return true;
                                            }
                                            
                                            else
                                            {
                                                return false;
                                            }
                    }
                </script>
                @if (Request::session()->has('message'))
                    <div class="alert alert-block alert-success">
                        <button type="button" class="close" data-dismiss="alert">
                            <i class="ace-icon fa fa-times"></i>
                        </button>
                        {!! Request::session()->get('message') !!}

                    </div>
                @endif
                <div class="form-group">
                <a href="#" class="btn btn-success add-modal">Add APK</a>

                    <!-- <a class="btn btn-success" href="{{route('download-dev-apks.create') }}">Create</a> -->
                </div>
            <div class="panel panel-default">
                <div class="panel-heading">
                    APKs Management
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>S.N</th>                                     
                                    <th>App Name</th>                                  
                                    <th>Created at</th>                                     
                                    <th>Status</th>                                     
                                    <th>Download</th>    
                                    <th>Options</th>                                   
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $row)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>                                     
                                    <td>{{ $row->name }}</td>                                      
                                    <td>{{ $row->created_at->diffForHumans() }}</td>                                     
                                    <td>

                                    @switch($row->is_in_google_play)
                                        @case(1)
                                        <i class="fa fa-play fa-lg" aria-hidden="true">  In Play Store</i> 
                                            @break
                                        @case(0)
                                        <i class="fa fa-tumblr fa-lg" aria-hidden="true">  In Testing</i> 
                                            @break
                                    @endswitch
                                    </td>
                                    <td class="text-center"><a href="{{ Storage::url('apks/'.$row->apk_path) }}"><i class="fa fa-download fa-lg" aria-hidden="true"></i> </a></td>    
                                    <td>
                                        <form method="post" action="{{route('download-dev-apks.destroy', $row->id)}}" onsubmit="return confirmDelete()">
                                            <div class="icon">
                                                {{csrf_field()}}
                                                {{method_field('DELETE')}}
                                                <button name="submit" class="pull-right" style="border: 0; background: transparent;" title="Delete"><i class="fa fa-trash-o"></i></button>
                                            </div>
                                        </form>
                                    </td>                                   
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.dataTable_wrapper -->
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

<!-- Modal form to CREATE -->
<div id="addModal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ route('download-dev-apks.store') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                        <div class="form-group">
                            <label class="control-label col-sm-3" for="title">App Name:</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" name="name" autofocus required>
                                <p class="errorTitle text-center alert alert-danger hidden"></p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3" for="apk_path">APKs to add:</label>
                            <div class="col-sm-9">
                                <input id="apk" type="file" name="apk_path" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-3" for="content">Status:</label>
                            <div class="col-sm-9">
                            <select class="form-control" name="status">
                                <option value= "0">For Testing</option>
                                <option value="1">In Play Store</option>
                            </select>                                
                            <p class="errorContent text-center alert alert-danger hidden"></p>
                            </div>
                        </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success add">
                            <span id="" class='glyphicon glyphicon-check'></span> Add
                        </button>
                        <button type="button" class="btn btn-warning" data-dismiss="modal">
                            <span class='glyphicon glyphicon-remove'></span> Close
                        </button>
                    </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
    $(document).on('click', '.add-modal', function() {
            $('.modal-title').text('Add');
            $('#addModal').modal('show');
    });
</script>

@endsection
