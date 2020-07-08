@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                    <a class="btn btn-success" href="{{route('multimedia.create') }}">Create</a>
                </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            Multimedia
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
                                <div class="dataTable_wrapper">
                                <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                    <thead>
                                        <tr>
                                            <th>S.N</th>                                     
                                            <th>Type</th>    
                                            <th>Thumbnail</th>    
                                            <th>Title EN</th>    
                                            <th>Title NP</th>                                     
                                            <th>Week No.</th>                                     
                                            <th>Status</th>                                     
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>                
                                            <td>{{ $row->type }}</td>           
                                            <td align="center">
                                                @if($row->thumbnail !== null)
                                                <img src="{{ asset($row->thumbnail) }}" style="display:inline-block; width:100px; height:50px; overflow:hidden;">
                                                @else
                                                N/A
                                                @endif
                                            </td>           
                                            <td>{{ $row->title_en }}</td>           
                                            <td>{{ $row->title_np }}</td>                                     
                                            <td>{{ $row->week_number }}</td>
                                            @if($row->status == 1)
                                                <td class="text-success"><span class="label label-success">Active</span></td>
                                            @else
                                                <td class="text-warning"><span class="label label-warning">Inactive</span></td>
                                            @endif
                                            <td>
                                                <a href="{{ route('multimedia.show', $row->id) }}" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    <span><strong>Edit</strong></span>            
                                                </a>  

                                               <form action="{{ route('multimedia.destroy', $row->id) }}" method="POST">
                                                     @method('DELETE')
                                                     @csrf
                                                     <button onclick="return confirm('Are you sure you want to delete this item?');" class="btn btn-danger btn-xs" type="submit"><i class="fa fa-trash" aria-hidden="true"></i>Delete</button>          
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
