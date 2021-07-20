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
                        <div class="col-12 table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Age</th>
                                        <th title="Gender">Sex</th>
                                        {{-- <th title="Relationship">Relationship</th> --}}
                                        <th title="Actions"><i class="fa fa-cogs" aria-hidden="true"></i></th>
                                    </tr>
                                </thead>
                                <tbody class="table-sars-cov-tbody text-center">
                                    @if($contact_list->household_details != null && $contact_list->household_details != '[]')
                                    @php $household_details = json_decode($contact_list->household_details) @endphp
                                    @foreach ($household_details as $key => $item)
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
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->age }}</td>
                                            <td>{{ $sex }}</td>
                                            {{-- <td>{{ $item->sex }}</td> --}}
                                            <td>
                                                <form method="POST" action="{{ route('b-one-form.part-one', $contact_list->token) }}">
                                                    @csrf
                                                    <input type="hidden" name="contact_values" value="{{ serialize($household_details[$key]) }}">
                                                    <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    @if($contact_list->travel_vehicle_details != null && $contact_list->travel_vehicle_details != '[]')
                                    @php $travel_vehicle_details = json_decode($contact_list->travel_vehicle_details) @endphp
                                    @foreach ($travel_vehicle_details as $key => $item)
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
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->age }}</td>
                                            <td>{{ $sex }}</td>
                                            {{-- <td>{{ $item->sex }}</td> --}}
                                            <td>
                                                <form method="POST" action="{{ route('b-one-form.part-one', $contact_list->token) }}">
                                                    @csrf
                                                    <input type="hidden" name="contact_values" value="{{ serialize($travel_vehicle_details[$key]) }}">
                                                    <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                    @if($contact_list->other_direct_care_details != null && $contact_list->other_direct_care_details != '[]')
                                    @php $other_direct_care_details = json_decode($contact_list->other_direct_care_details) @endphp
                                    @foreach ($other_direct_care_details as $key => $item)
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
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->age }}</td>
                                            <td>{{ $sex }}</td>
                                            {{-- <td>{{ $item->sex }}</td> --}}
                                            <td>
                                                <form method="POST" action="{{ route('b-one-form.part-one', $contact_list->token) }}">
                                                    @csrf
                                                    <input type="hidden" name="contact_values" value="{{ serialize($other_direct_care_details[$key]) }}">
                                                    <button class="btn btn-sm btn-success" type="submit"><i class="fa fa-plus" aria-hidden="true"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
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