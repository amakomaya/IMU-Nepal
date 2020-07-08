@extends('layouts.backend.app')

@section('content')
    <div id="page-wrapper">
        <!-- /.row -->

        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                        <strong> खोप कार्यक्रम </strong>
                    </div>
                    <!-- /.panel-heading -->
                    <div class="panel-body">
                        <!-- <div class="form-group">
                            <a class="btn btn-success pull-right" href="#">
                              Print
                            </a>
                        </div> -->
                        @include('reports.layouts.filter')
                        <div class="clearfix"></div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                                <i class="fa fa-print"></i> Print
                            </button>

                            <button type="button" class="btn btn-info pull-right" data-toggle="modal" data-target="#myModal"> <i class="fa fa-send-o"></i> HMIS ( DHIS2 ) मा डाटा पठाउनुहोस् </button>
                            @include('reports.bootstrap-models.vaccination-program')
                        </div>
                        <!-- <div id="printable1">
                        <h1 class="btn-success">Print me</h1>
                        </div> -->
                        @include('reports.session')
                        <div class="clearfix"></div>

                        <div style="page-break-after: always; margin:0 auto;clear: both;">

                            <div id="printable">
                                <style>
                                    .tableDiv {
                                        width: 100%;
                                        margin: 0 auto;
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
                                <div class="hmis">HMIS 9.3</div>
                                <div class="clearfix"></div>
                                <h3 class="titleHeader">१. खाेप कार्यक्रम</h3>
                                <table class="report" style="width:100%">
                                    <tr>
                                        <th rowspan="2" colspan="2" width="10%">खाेपको प्रकार</th>
                                        <th rowspan="2" width="4%">बि.सी.जी.</th>
                                        <th colspan="3" width="9%">डी.पी.टी-हेप वि. - हिब</th>
                                        <th colspan="3" width="9%">पाेलियो</th>
                                        <th colspan="3" width="9%">पी.सी.भी.</th>
                                        <th colspan="2" width="9%">रोटा</th>
                                        <th colspan="2" width="9%">एफ.आइ.पि.भि.</th>
                                        <th colspan="2" width="9%">दादुरा / रुबेला</th>
                                        <th width="4%" rowspan="2">जे.ई</th>
                                        <th rowspan="2" class="text-center" width="10%">एक बर्ष उमेरपछि डी.पी.टी. हेप
                                            वि.हिब र पोलियोको तेश्रो मात्रा पुरा गरेको
                                        </th>
                                        <th colspan="3" width="9%">टि.डी. (गर्भवती महिला)</th>
                                    </tr>
                                    <tr>
                                        <th>पहिलो</th>
                                        <th>दाेस्राे</th>
                                        <th>तेस्राे</th>
                                        <th>पहिलो</th>
                                        <th>दाेस्राे</th>
                                        <th>तेस्राे</th>
                                        <th>पहिलो</th>
                                        <th>दाेस्राे</th>
                                        <th>तेस्राे</th>
                                        <th>पहिलो</th>
                                        <th>दाेस्राे</th>
                                        <th>पहिलो</th>
                                        <th>दाेस्राे</th>
                                        <th width="60">९-११ म</th>
                                        <th>१२-२३ म</th>
                                        <th>पहिलो</th>
                                        <th>दाेस्राे</th>
                                        <th>दाेस्राे+</th>
                                    </tr>
                                    <tr>
                                        <th colspan="2">1</th>
                                        <th>2</th>
                                        <th>3</th>
                                        <th>4</th>
                                        <th>5</th>
                                        <th>6</th>
                                        <th>7</th>
                                        <th>8</th>
                                        <th>9</th>
                                        <th>10</th>
                                        <th>11</th>
                                        <th>12</th>
                                        <th>13</th>
                                        <th>14</th>
                                        <th>15</th>
                                        <th>16</th>
                                        <th>17</th>
                                        <th>18</th>
                                        <th>19</th>
                                        <th>20</th>
                                        <th>21</th>
                                        <th>22</th>
                                    </tr>

                                    <tr>
                                        <th colspan="2" align="center">खाेप पाएका बच्चाहरुको संख्या</th>
                                        <td>{{ $data['immunizedChild']['bcgFirst'] }}</td>
                                        <td>{{ $data['immunizedChild']['pvFirst'] }}</td>
                                        <td>{{ $data['immunizedChild']['pvSecond'] }}</td>
                                        <td>{{ $data['immunizedChild']['pvThird'] }}</td>
                                        <td>{{ $data['immunizedChild']['opvFirst'] }}</td>
                                        <td>{{ $data['immunizedChild']['opvSecond'] }}</td>
                                        <td>{{ $data['immunizedChild']['opvThird'] }}</td>
                                        <td>{{ $data['immunizedChild']['pcvFirst'] }}</td>
                                        <td>{{ $data['immunizedChild']['pcvSecond'] }}</td>
                                        <td>{{ $data['immunizedChild']['pcvThird'] }}</td>
                                        <td>{{ $data['immunizedChild']['rvFirst'] }}</td>
                                        <td>{{ $data['immunizedChild']['rvSecond'] }}</td>
                                        <td>{{ $data['immunizedChild']['fipvFirst'] }}</td>
                                        <td>{{ $data['immunizedChild']['fipvSecond'] }}</td>
                                        <td>{{ $data['immunizedChild']['mrFirst'] }}</td>
                                        <td>{{ $data['immunizedChild']['mrSecond'] }}</td>
                                        <td>{{ $data['immunizedChild']['jeFirst'] }}</td>
                                        <td></td>
                                        <td>{{ $data['immunizedChild']['tdImmunizedRecordFirst'] }}</td>
                                        <td>{{ $data['immunizedChild']['tdImmunizedRecordSecond'] }}</td>
                                        <td>{{ $data['immunizedChild']['tdImmunizedRecordPlus'] }}</td>
                                    </tr>
                                    <tr>
                                        <th rowspan="2">खाेप (डाेज)</th>
                                        <th>प्राप्त भएको</th>
                                        <td>{{ $data['vailStock']['bcgReceived'] }}</td>
                                        <td colspan="3">{{ $data['vailStock']['pentavalentReceived'] }}</td>
                                        <td colspan="3">{{ $data['vailStock']['opvReceived'] }}</td>
                                        <td colspan="3">{{ $data['vailStock']['pcvReceived'] }}</td>
                                        <td colspan="2">{{ $data['vailStock']['rotaReceived'] }}</td>
                                        <td colspan="2">{{ $data['vailStock']['fipvReceived'] }}</td>
                                        <td colspan="2">{{ $data['vailStock']['mrReceived'] }}</td>
                                        <td>{{ $data['vailStock']['jeReceived'] }}</td>
                                        <td rowspan="3" bgcolor="#888">&nbsp;</td>
                                        <td colspan="3">{{ $data['vailStock']['tdReceived'] }}</td>
                                    </tr>
                                    <tr>
                                        <th>खर्च भएको</th>
                                        <td>{{ $data['vailStock']['bcgExpense'] }}</td>
                                        <td colspan="3">{{ $data['vailStock']['pentavalentExpense'] }}</td>
                                        <td colspan="3">{{ $data['vailStock']['opvExpense'] }}</td>
                                        <td colspan="3">{{ $data['vailStock']['pcvExpense'] }}</td>
                                        <td colspan="2">{{ $data['vailStock']['rotaExpense'] }}</td>
                                        <td colspan="2">{{ $data['vailStock']['fipvExpense'] }}</td>
                                        <td colspan="2">{{ $data['vailStock']['mrExpense'] }}</td>
                                        <td>{{ $data['vailStock']['jeExpense'] }}</td>
                                        <td colspan="3">{{ $data['immunizedChild']['tdImmunizedRecord']->count() }}</td>
                                    </tr>
                                    <tr>
                                        <th colspan="2">AEFI cases</th>
                                        <td>{{ $data['aefiCases']['aefi_bcg'] }}</td>
                                        <td colspan="3">{{ $data['aefiCases']['aefi_pentavalent'] }}</td>
                                        <td colspan="3">{{ $data['aefiCases']['aefi_opv'] }}</td>
                                        <td colspan="3">{{ $data['aefiCases']['aefi_pcv'] }}</td>
                                        <td colspan="2">{{ $data['aefiCases']['aefi_rota'] }}</td>
                                        <td colspan="2">{{ $data['aefiCases']['aefi_fipv'] }}</td>
                                        <td colspan="2">{{ $data['aefiCases']['aefi_mr'] }}</td>
                                        <td>{{ $data['aefiCases']['aefi_je'] }}</td>
                                        <td colspan="3"></td>
                                    </tr>
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