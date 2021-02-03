@extends('layouts.backend.app')
@section('style')

@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <form class="form-horizontal" name="excelExport" role="form" method="GET"
                      action="{{ route('vaccination.report') }}" enctype="multipart/form-data">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="created_at">Select Date:</label>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" id="from" title="Enter from date"
                                   name="from" min="2021-01-27" max="<?php echo date('Y-m-d'); ?>"
                                   value="{{ request()->from ?? '2021-01-27' }}" required>

                        </div>
                        <div class="col-sm-3">
                            <input type="date" class="form-control" id="to" title="Enter to date" name="to"
                                   min="2021-01-27" max="<?php echo date('Y-m-d'); ?>"
                                   value="{{ request()->to ?? date('Y-m-d') }}" required>
                        </div>
                        <div class="col-sm-2 col-sm-9">
                            <button type="submit" class="btn btn-primary pull-right">Submit Request</button>
                        </div>
                    </div>
                </form>
                <hr>

                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa- fa-3x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $data['vaccination_count'] }}</div>
                                <div>Total Immunization</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if(auth()->user()->role == 'main' || auth()->user()->role == 'center')
            <div class="col-lg-12">
                <div class="panel panel-info">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa- fa-3x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge">{{ $data['klb_valley_data'] }}</div>
                                <div>Total Kathmandu/Lalitpur/Bhaktapur </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <div class="col-lg-12">
                    <table class="table table-striped">
                        <tr>
                            <th>Province</th>
                            <th>Data</th>
                        </tr>
                        @foreach($data['provincial_data'] as $key => $province)
                        <tr>
                            <td>{{ \App\Models\province::where('id', $key)->first()->province_name }}</td>
                            <td>{{ $province }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
                <hr>
                <div class="col-lg-12">
                    <table class="table table-striped">
                        <tr>
                            <th>District</th>
                            <th>Data</th>
                        </tr>
                        @foreach($data['district_data'] as $key => $province)
                        <tr>
                            <td>{{ \App\Models\District::where('id', $key)->first()->district_name ?? '' }}</td>
                            <td>{{ $province }}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                <hr>
                <br>
                <div class="col-lg-12">
                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                        <tr>
                            <th>Organization</th>
                            <th>District</th>
                            <th>Municipality</th>
                            <th>Data</th>
                        </tr>
                        @foreach($data['organization_data'] as $organization)
                            @php
                                $org = \App\Models\Organization::where('hp_code', $organization->hp_code)->with('municipality', 'district')->first();
                            @endphp
                            <tr>
                                <td>{{ $org->name ?? '' }}</td>
                                <td>{{ $org->district->district_name ?? '' }}</td>
                                <td>{{ $org->municipality->municipality_name ?? '' }}</td>
                                <td>{{ $organization->total }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            @endif
        </div>
@endsection
@section('script')

@endsection