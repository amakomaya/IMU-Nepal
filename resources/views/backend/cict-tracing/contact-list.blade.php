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
                        <strong>Contact List</strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <div class="col-12">
                            Name of Case: <b>{{ $cict_tracing->name }}</b><br>
                            Parent Case ID: <b>{{ $cict_tracing->case_id }}</b>
                        </div>
                        <div class="col-12 table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th title="Name of contacts">Name of contacts</th>
                                        <th title="Age">Age</th>
                                        <th title="Sex">Sex</th>
                                        <th title="B1 Form Actions"><i class="fa fa-cogs" aria-hidden="true"></i> B1 Form</th>
                                        <th title="B2 Form Actions"><i class="fa fa-cogs" aria-hidden="true"></i> B2 Form</th>
{{--                                        <th>Do you want to register patient and collect sample?</th>--}}
                                    </tr>
                                </thead>
                                <tbody class="table-sars-cov-tbody text-center">
                                    @foreach ($contact_list as $key => $item)
                                        <tr>
                                            <?php
                                            if($item->sex == '1'){
                                                $sex = "M";
                                            }elseif($item->sex == '2'){
                                                $sex = "F";
                                            }else{
                                                $sex = "O";
                                            }
                                            ?>
                                            <td>{{ ++$key }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->age }}</td>
                                            <td>{{ $sex }}</td>
                                            <td>
                                                Status: 
                                                @if($item->contact)
                                                @if($item->contact->completion_date)
                                                <span class="text-success">Complete <i class="fa fa-check" aria-hidden="true"></i></span>
                                                @else
                                                <span class="text-danger">Incomplete <i class="fa fa-times" aria-hidden="true"></i></span>
                                                @endif
                                                @else
                                                <span class="text-warning">N/A</span>
                                                @endif
                                                @if(auth()->user()->role == 'healthworker')
                                                <form method="GET" action="{{ route('b-one-form.part-one') }}">
                                                    <input type="hidden" name="case_id" value="{{ $item->case_id }}">
                                                    <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-plus" aria-hidden="true"></i> B1 Form</button>
                                                </form>
                                                @endif
                                            </td>
                                            <td>
                                                Status: 
                                                @if($item->followUp)
                                                @if($item->followUp->completion_date && $item->followUp->date_of_follow_up_3 && 
                                                $item->followUp->date_of_follow_up_7 && $item->followUp->date_of_follow_up_10)
                                                <span class="text-success">Complete <i class="fa fa-check" aria-hidden="true"></i></span>
                                                @else
                                                <span class="text-info">In Progress <i class="fa fa-spinner" aria-hidden="true"></i></span>
                                                @endif
                                                @else
                                                <span class="text-warning">N/A</span>
                                                @endif
                                                @if(auth()->user()->role == 'healthworker')
                                                <form method="GET" action="{{ route('b-two-form.follow-up') }}">
                                                    <input type="hidden" name="case_id" value="{{ $item->case_id }}">
                                                    <button class="btn btn-sm btn-primary" type="submit"><i class="fa fa-plus" aria-hidden="true"></i> B2 Form</button>
                                                </form>
                                                @endif
                                            </td>
{{--                                            <td>--}}
{{--                                                @if($item->contact && $item->contact->completion_date)--}}
{{--                                                <a class="btn btn-sm btn-info" href="{{ route('woman.create', ['case_id' => $item->case_id]) }}" target="_blank"><i class="fa fa-flask" aria-hidden="true"></i> Register and Collect Sample</a>--}}
{{--                                                @else--}}
{{--                                                Please complete B1 form first--}}
{{--                                                @endif--}}
{{--                                            </td>--}}
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