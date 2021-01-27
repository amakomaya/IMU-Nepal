@extends('layouts.backend.app')
@section('style')

@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
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
                    <table class="table table-striped">
                        <tr>
                            <th>Organization</th>
                            <th>Data</th>
                        </tr>
                        @foreach($data['organization_data'] as $organization)
                            @php
                                $org = \App\Models\Organization::where('hp_code', $organization->hp_code)->first();
                            @endphp
                            <tr>
                                <td>{{ $org->name ?? '' }}</td>
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