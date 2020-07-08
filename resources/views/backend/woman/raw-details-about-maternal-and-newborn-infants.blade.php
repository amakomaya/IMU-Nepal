@extends('layouts.backend.app')

@section('content')
<div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                    मातृ तथा नवजात शिशु सम्बन्धि विवरण
                    </div>
                    <!-- /.panel-heading -->
                <div class="panel-body">
                    @include('reports.layouts.filter')
                    <div class="clearfix"></div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>

                    <div class="clearfix"></div>

                <!-- /.report-->
                <div class="tableDiv print" id="printable">
                    <style>
                        @media print {
                            @page {size: landscape; margin:55px 20px 0 20px;}
                            body { -webkit-print-color-adjust: exact;}
                        }

                        body{margin:0;padding:0;}
                        /* .tableDiv{clear:both;width:1170px;margin:0 auto;} */
                        .titleHeader{text-align: center;}
                        table, td, th {/*width:1170px;*/border: 1px solid black; border-collapse: collapse;}
                        th{font-size:12px;text-align:center;background-color:#dfdfdf;padding:5px;}
                        td{font-size: 12px;color:#000; padding:3px;text-align:center;}

                    </style>
                    <div id="print-header">
                        @include('reports.layouts.header-for-print')
                    </div>
                    <p style="text-align:right;">HMIS 4.2: FCHV Register
                    <h3 class="titleHeader">मातृ तथा नवजात शिशु सम्बन्धि विवरण</h3>
                    <table width="100%">
                        <tr>
                            <th rowspan="2" width="3">क्र. सं.</th>
                            <th colspan="2">गर्भवती महिलाको</th>
                            <th align="center" rowspan="2" width="80">अन्तिम रजश्वला मिति <br>(LMP) <br>(ग.म.सा.)</th>
                            <th align="center" rowspan="2" width="80">प्रसुतिको अनुमानित मिति <br>(EDD) <br>(ग.म.सा.)</th>
                            <th rowspan="2" width="5">जीवन सुरक्षा परामर्श दिएको*</th>
                            <th colspan="5" align="center">स्वास्थ्य संस्थामा गर्भ जाँच गरेको पटक (अाैँ महिनामा)* </th>
                            <th colspan="2">अाइरन चक्की*</th>
                            <th align="center" rowspan="2" width="3">सुत्केरी पश्चात भितामिन ए*</th>
                            <th rowspan="2" width="4">प्रसुती भएकाे स्थान*</th>
                            <th rowspan="2" align="center" width="3">शिशुको जन्म अवस्था*</th>
                            <th align="center" width="10">स्वास्थ्यकर्मीले नवजात शिशु सँगै सुत्केरी महिलालाई जाँच गरेको*</th>

                            <th rowspan="2" width="5">परिवार नियोजन साधन प्रयोग*</th>
                            <th rowspan="2" align="center">कैफियत</th>
                        </tr>

                        <tr>
                            <th>नाम, थर</th>
                            <th width="5%">उमेर</th>
                            <th width="3">४</th>
                            <th width="3">६</th>
                            <th width="3">८</th>
                            <th width="3">९</th>
                            <th width="3">अन्य</th>
                            <th width="3">गर्भावस्थामा १८० चक्कि</th>
                            <th width="3">सुत्केरी पश्चात ४५ चक्कि</th>
                            <th></th>
                        </tr>
                        <tr>
                            @for ($i = 1; $i <= 19; $i++)
                                <th>{{ convertToNepali($i) }}</th>
                            @endfor
                        </tr>

                        @foreach($data as $record)
                            <tr>
                                <td>{{ convertToNepali($loop->iteration) }}</td>
                                <td style="text-align:left; padding-left:8px">{{ $record->name }}</td>
                                <td>{{ $record->age }}</td>
                                <td>{{ $record->getNepaliDate(\Carbon\Carbon::parse($record->lmp_date_en)) }}</td>
                                <td>{{ $record->getNepaliDate(\Carbon\Carbon::parse($record->lmp_date_en)->addDays(280)) }}</td>
                                <td>&nbsp;</td>
                                <td>@if($record->isFirstAnc($record) )
                                    <span class="glyphicon glyphicon-ok">&nbsp;</span>
                                    @endif
                                </td>
                                <td>@if($record->isSecondAnc($record))
                                    <span class="glyphicon glyphicon-ok">&nbsp;</span>
                                    @endif
                                </td>
                                <td>@if($record->isThirdAnc($record))
                                    <span class="glyphicon glyphicon-ok">&nbsp;</span>
                                    @endif
                                </td>
                                <td>@if($record->isForthAnc($record))
                                    <span class="glyphicon glyphicon-ok">&nbsp;</span>
                                    @endif
                                </td>
                                <td>&nbsp;</td>
                                <td>
                                    @if($record->countIronCapsuleBeforeDelivery($record) !== 0)
                                        {{ $record->countIronCapsuleBeforeDelivery($record) }}
                                    @endif
                                </td>
                                <td>@if($record->countIronCapsuleAfterDelivery($record) !== 0)
                                        {{ $record->countIronCapsuleAfterDelivery($record) }}
                                    @endif
                                </td>
                                <td>@if($record->countVitaminAfterDelivery($record) !== 0)
                                        {{ $record->countVitaminAfterDelivery($record) }}
                                    @endif
                                </td>
                                <td>@if($record->deliveries()->active()->latest()->first())
                                        {{ $record->deliveries()->active()->latest()->first()->delivery_place }}
                                    @endif
                                </td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td></td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <!-- /.report-->
            <!-- /.panel -->
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
</div>
<!-- /#page-wrapper -->

@endsection