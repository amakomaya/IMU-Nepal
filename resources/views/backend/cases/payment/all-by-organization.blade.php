@extends('layouts.backend.app')
@section('style')
    <style>
        .table {
            max-width: 100%;
            max-height: 500px;
            overflow: scroll;
            position: relative;
        }

        table {
            position: relative;
            border-collapse: collapse;
        }

        td,
        th {
            padding: 0.25em;
        }

        thead th {
            position: -webkit-sticky; /* for Safari */
            position: sticky;
            top: 0;
            background: #000;
            color: #FFF;
        }

        thead th:first-child {
            left: 0;
            z-index: 1;
        }

        tbody th {
            position: -webkit-sticky; /* for Safari */
            position: sticky;
            left: 0;
            background: #FFF;
            border-right: 1px solid #CCC;
        }

    </style>
@endsection
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="table">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>S.N.</th>
                            <th width="40%">Organization Name</th>
                            <th>No of Beds</th>
                            <th>No of HDU</th>
                            <th>No of ICU</th>
                            <th>No of ventilators</th>
                            <th>Oxygen Facility</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $organization => $value)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $organization }}</td>
                            <td>Total : {{ $value['total_no_of_beds'] }} <br>
                                Available : {{ $value['total_no_of_beds'] - $value['used_total_no_of_beds'] }}

                            </td>
                            <td>Total : {{ $value['total_no_of_hdu'] }} <br>
                                Available : {{ $value['total_no_of_hdu'] - $value['used_total_no_of_hdu'] }}

                            </td>
                            <td>Total : {{ $value['total_no_of_icu'] }} <br>
                                Available : {{ $value['total_no_of_icu'] - $value['used_total_no_of_icu'] }}

                            </td>
                            <td>Total : {{ $value['total_no_of_ventilators'] }} <br>
                                Available : {{ $value['total_no_of_ventilators'] - $value['used_total_no_of_ventilators'] }}

                            </td>
                            <td>
                                @if($value['is_oxygen_facility'] == 1)
                                    <div class="text-success">Available</div>
                                    {{ $value['daily_consumption_of_oxygen'] }} liter
                                @elseif($value['is_oxygen_facility'] == 2)
                                    <div class="text-info">Partially Available</div>
                                    {{ $value['daily_consumption_of_oxygen'] }} liter
                                @else
                                    <div class="text--secondary">N/A or Not Available</div>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
