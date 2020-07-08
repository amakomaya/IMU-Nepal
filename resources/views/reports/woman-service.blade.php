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
                                        <th>LMP Date</th>
                                        <th>Phone</th>
                                        <th>Ward No.</th>
                                        <th>Register Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $register_index =1; @endphp
                                    @foreach($woman as $women)
                                        @if(in_array($women->token, $register))
                                            <tr>
                                                <td>{{ $register_index }}</td>
                                                <td><a href="{{ route('woman.show', $women->id) }}">{{ $women->name }}</a></td>
                                                <td>{{ \App\Helpers\ViewHelper::convertEnglishToNepali($women->lmp_date_en) }}</td>
                                                <td>{{ $women->phone }}</td>
                                                <td>{{ $women->ward }}</td>
                                                <td>{{ \App\Helpers\ViewHelper::convertEnglishToNepali($women->created_at->format('Y-m-d')) }}</td>
                                            </tr>
                                            @php $register_index ++; @endphp
                                        @endif
                                    @endforeach
                                    @if($register_index == 1)
                                    <td class="bg-danger border" height="75" colspan="12"><p class="text-center">No data found</p></td>
                                    @endif
                                    </tbody>
                                </table>
                                <h3 class="titleHeader">ANC Service</h3>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Name</th>
                                        <th>Ward No</th>
                                        <th>LMP Date</th>
                                        <th>Visit Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $anc_index =1; @endphp
                                    @foreach($woman as $women)
                                        @if(in_array($women->token, $ancs))
                                            <tr>
                                                <td>{{ $anc_index }}</td>
                                                <td><a href="{{ route('woman.show', $women->id) }}">{{ $women->name }}</a></td>
                                                <td>{{ $women->ward }}</td>
                                                <td>{{ \App\Helpers\ViewHelper::convertEnglishToNepali($women->lmp_date_en) }}</td>
                                                <td>
                                                    @foreach($women->ancs as $anc)
                                                        {{ \App\Helpers\ViewHelper::convertEnglishToNepali($anc->visit_date) }} | {{ $women->checkAncVisit($women->lmp_date_en, $anc) }},
                                                        <br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @php $anc_index ++; @endphp
                                        @endif
                                    @endforeach
                                    @if($anc_index == 1)
                                        <td class="bg-danger border" height="75" colspan="12"><p class="text-center">No data found</p></td>
                                    @endif
                                    </tbody>
                                </table>
                                <h3 class="titleHeader">Delivery Service</h3>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Name</th>
                                        <th>Ward</th>
                                        <th>LMP Date</th>
                                        <th>Delivery Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $delivery_index =1; @endphp
                                    @foreach($woman as $women)
                                        @if(in_array($women->token, $delivery))
                                            <tr>
                                                <td>{{ $delivery_index }}</td>
                                                <td><a href="{{ route('woman.show', $women->id) }}">{{ $women->name }}</a></td>
                                                <td>{{ $women->ward }}</td>
                                                <td>{{ \App\Helpers\ViewHelper::convertEnglishToNepali($women->lmp_date_en) }}</td>
                                                <td>{{ \App\Helpers\ViewHelper::convertEnglishToNepali($women->delivery->delivery_date) }}</td>
                                            </tr>
                                            @php $delivery_index ++; @endphp
                                        @endif
                                    @endforeach
                                    @if($delivery_index == 1)
                                        <td class="bg-danger border" height="75" colspan="12"><p class="text-center">No data found</p></td>
                                    @endif
                                    </tbody>
                                </table>
                                <h3 class="titleHeader">PNC Service</h3>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Name</th>
                                        <th>Ward</th>
                                        <th>Visit Date</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $pncs_index =1; @endphp
                                    @foreach($woman as $women)
                                        @if(in_array($women->token, $pncs))
                                            <tr>
                                                <td>{{ $pncs_index }}</td>
                                                <td><a href="{{ route('woman.show', $women->id) }}">{{ $women->name }}</a></td>
                                                <td>{{ $women->ward }}</td>
                                            <td>
                                                @foreach($women->pncs->pluck('date_of_visit') as $visit_date)
                                                    {{ \App\Helpers\ViewHelper::convertEnglishToNepali($visit_date) }},
                                                    <br>
                                                @endforeach
                                            </td>
                                            </tr>
                                            @php $pncs_index ++; @endphp
                                        @endif
                                    @endforeach
                                    @if($pncs_index == 1)
                                        <td class="bg-danger border" height="75" colspan="12"><p class="text-center">No data found</p></td>
                                    @endif
                                    </tbody>
                                </table>
                                <h3 class="titleHeader">TD / Iron capsule / Vitamin A /  Service</h3>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>S.N</th>
                                        <th>Name</th>
                                        <th>Ward</th>
                                        <th>*</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php $medication_index = 1; @endphp
                                    @foreach($woman as $women)
                                        @if(in_array($women->token, $medication))
                                            <tr>
                                                <td>{{ $medication_index }}</td>
                                                <td><a href="{{ route('woman.show', $women->id) }}">{{ $women->name }}</a></td>
                                                <td>{{ $women->ward }}</td>
                                                <td>
                                                    @foreach($women->vaccinations as $vaccination)
                                                        {{ $vaccination->getVaccineDetailsForService($vaccination) }},
                                                        <br>
                                                    @endforeach
                                                </td>
                                            </tr>
                                            @php $medication_index ++; @endphp
                                        @endif
                                    @endforeach
                                    @if($medication_index == 1)
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