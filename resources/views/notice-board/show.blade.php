@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <a class="btn btn-success" href="{{route('notice-board.index') }}">View All</a>
                </div>
                @if($row->type == 'Warning')
                <div class="panel panel-warning">
                    @elseif($row->type == 'Danger')
                        <div class="panel panel-danger">
                    @else
                                <div class="panel panel-info">
                    @endif
                    <div class="panel-heading">
                        {{ $row->title }}
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <table class="table table-striped table-bordered detail-view">
                            <tbody>
                            <tr>
                                <th>Title</th>
                                <td>{{$row->title}}</td>
                            </tr>
                            <tr>
                                <th>Type</th>
                                <td>{{$row->type}}</td>
                            </tr>
                            <tr>
                                <th>Description</th>
                                <td>{!! $row->description !!}</td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td>{{$row->created_at->diffForHumans()}}</td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td>{{$row->updated_at->diffForHumans()}}</td>
                            </tr>
                            </tbody>
                        </table>
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

