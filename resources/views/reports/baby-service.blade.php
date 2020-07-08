@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <strong> Woman Service </strong>
                    </div>
                    <div class="panel-body">
                        @include('reports.layouts.filter')
                        <div class="clearfix"></div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                        <div class="clearfix"></div>
                        <div style="page-break-after: always; margin:0 auto;clear: both;">
                            <div id="printable">
                                <style>

                                    .titleHeader {
                                        text-align: center;
                                        border: 1px solid #000;
                                        padding: 5px;
                                        background-color: #dfdfdf;
                                        font-size: 13px;
                                    }

                                    table.report, .report td, .report th {
                                        border: 1px solid black;
                                        border-collapse: collapse;
                                    }

                                    .report th {
                                        font-size: 12px;
                                        padding: 5px;
                                        background-color: #dfdfdf;
                                        text-align: center;
                                    }

                                    .report td {
                                        font-size: 12px;
                                        padding: 10px;
                                        color: #000;
                                        text-align: center;
                                    }

                                    .hmis {
                                        float: right;
                                        font-size: 10px;
                                    }

                                    .clearfix {
                                        clear: both;
                                    }

                                    @media print {
                                        .tableDiv {
                                            width: 100%;
                                            margin: 0 auto;
                                        }

                                        a[href]:after {
                                            content: none !important;
                                        }

                                        .titleHeader {
                                            text-align: center;
                                            border: 1px solid #000;
                                            padding: 5px;
                                            background-color: #dfdfdf;
                                            font-size: 13px;
                                        }

                                        table.report, .report td, .report th {
                                            border: 1px solid black;
                                            border-collapse: collapse;
                                        }

                                        .report th {
                                            font-size: 12px;
                                            padding: 5px;
                                            background-color: #dfdfdf;
                                        }

                                        .report td {
                                            font-size: 12px;
                                            padding: 10px;
                                            color: #000;
                                        }

                                        .hmis {
                                            float: right;
                                            font-size: 10px;
                                        }

                                        .clearfix {
                                            clear: both;
                                        }
                                    }
                                </style>
                                <div id="print-header">
                                    @include('reports.layouts.header-for-print')
                                </div>
                                <div class="clearfix"></div>
                                <h3 class="titleHeader">New Register</h3>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Name</th>
                                        <th>Date of birth</th>
                                        <th>Contact No</th>
                                        <th>Ward No.</th>
                                        <th>Mother Name</th>
                                        <th>Register At</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $register_index =1; @endphp
                                    @foreach($babies as $baby)
                                        @if(in_array($baby->token, $register))
                                            <tr>
                                                <td>{{ $register_index }}</td>
                                                <td><a href="{{ route('child-report.health-report-card', $baby->id) }}">{{ $baby->baby_name }}</a></td>
                                                <td>{{ $baby->dob_np }}</td>
                                                <td>{{ $baby->contact_no }}</td>
                                                <td>{{ $baby->ward_no }}</td>
                                                <td>{{ $baby->mother_name }}</td>
                                                <td>{{ \App\Helpers\ViewHelper::convertEnglishToNepali($baby->created_at->format('Y-m-d')) }}</td>
                                            </tr>
                                            @php $register_index ++; @endphp
                                        @endif
                                    @endforeach
                                    @if($register_index == 1)
                                        <td class="bg-danger border" height="75" colspan="12"><p class="text-center">No data found</p></td>
                                    @endif
                                    </tbody>
                                </table>

                                <h3 class="titleHeader">Baby Vaccination</h3>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Name</th>
                                        <th>Date of birth</th>
                                        <th>Ward No.</th>
                                        <th>Mother Name</th>
                                        <th>Vaccinated Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $vaccinated_index =1; @endphp
                                    @foreach($babies as $baby)
                                        @if(in_array($baby->token, $vaccination))
                                            <tr>
                                                <td>{{ $vaccinated_index }}</td>
                                                <td><a href="{{ route('child-report.health-report-card', $baby->id) }}">{{ $baby->baby_name }}</a></td>
                                                <td>{{ $baby->dob_np }}</td>
                                                <td>{{ $baby->ward_no }}</td>
                                                <td>{{ $baby->mother_name }}</td>
                                                <td>
                                                    @foreach($baby->vaccinations()->hasVialImage()->get() as $v)
                                                        @if(Carbon\Carbon::createFromFormat('Y-m-d', $v->vaccinated_date_np)->format('Y-m') == $select_year.'-'.substr("0{$select_month}", -2))
                                                        <span class="bg-success">{{ $v->vaccine_name. ', '. $v->vaccine_period. ', '. $v->vaccinated_date_np }},</span>
                                                        @else
                                                            <p>{{ $v->vaccine_name. ', '. $v->vaccine_period. ', '. $v->vaccinated_date_np }},</p>
                                                        @endif
                                                        <br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @php $vaccinated_index ++; @endphp
                                        @endif
                                    @endforeach
                                    @if($vaccinated_index == 1)
                                        <td class="bg-danger border" height="75" colspan="12"><p class="text-center">No data found</p></td>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->

                </div>
                <!-- /.row -->
            </div>
            <!-- /#page-wrapper -->
@endsection