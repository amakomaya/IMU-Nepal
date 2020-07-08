@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
            <!-- /.row -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                    <a class="btn btn-success" href="{{route('news-feed.create') }}">Create</a>
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
                                            <th>Author</th>    
                                            <th>Title </th>    
                                            <th>Image</th>                                     
                                            <th>Published At</th>                                     
                                            <th>Status</th>                                     
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rows as $row)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>                
                                            <td>{{ $row->author }}</td>           
                                            <td>{!! Illuminate\Support\Str::limit($row->title, 250, $end='...') !!}</td>           
                                            <td align="center">
                                                @if($row->url_to_image !== null)
                                                <img src="{{ asset($row->url_to_image) }}" style="display:inline-block; width:100px; height:50px; overflow:hidden;">
                                                @else
                                                N/A
                                                @endif
                                            </td>   
                                            @if(!empty($row->published_at))
                                                <td>{{ $row->published_at }} <br> {{ $row->published_at->diffForHumans() }}</td>
                                            @else
                                                <td>Not yet published</td>
                                            @endif                                    
                                            @if($row->status == 1)
                                                <td class="text-success"><span class="label label-success">Active</span></td>
                                            @else
                                                <td class="text-warning"><span class="label label-warning">Inactive</span></td>
                                            @endif
                                            <td>
                                                <a href="{{ route('news-feed.show', $row->id) }}" class="btn btn-primary btn-xs">
                                                    <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                                    <span><strong>Edit</strong></span>            
                                                </a>  
                                               <form action="{{ route('news-feed.destroy', $row->id) }}" method="POST">
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
