@extends('layouts.backend.app')

@section('content')
    <style type="text/css">
        .report {
            border-collapse: collapse;
            text-align: center;
            font-size: 11px;
            width: 100%;
        }

        .th-head {
            text-align: center;
            background-color: #dfdfdf;
        }

        td {
            padding: 10px 0;
        }

        th {
            padding: 5px 0;
        }

        @media print {
            @page {
                size: landscape;
            }
        }
    </style>
    <div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        खोप
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
                        <div class="tableDivsd">

                            <div id="printable">
                                <div id="print-header">
                                    @include('reports.layouts.header-for-print')
                                </div>
                                <div class="panel-heading">
                                    <h3 class="text-center">खोप विवरण</h3>
                                </div>
                                <div class="pull-right">जम्मा : {{ convertToNepali($data->count()) }}</div>
                                <table border="1" id="table1" class="report">
                                    <tbody>
                                    <tr>
                                        <th class="th-head" style="text-align:center;" rowspan="3" width="2%">सेवा दर्ता
                                            नं.
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="3" width="7%">बच्चाको
                                            नाम,थर
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="3" width="2%">जाति कोड
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="2" colspan="2"
                                            width="2%">लिङ्ग
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="3" width="7%">आमा/वुवाको
                                            नाम,थर
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="3" width="5%">वडा नम्बर
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="3" width="4%">सम्पर्क
                                            फोन नं.
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="2" colspan="3"
                                            width="5%">जन्म मिति
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="2" colspan="3"
                                            width="5%">वि.सि.जी. (BCG)
                                        </th>
                                        <th class="th-head" style="text-align:center;" colspan="9" width="12%">
                                            डि.पि.टी./हेप वि/हिब
                                        </th>
                                        <th class="th-head" style="text-align:center;" colspan="9" width="12%">पोलियो
                                            (OPV)
                                        </th>
                                        <th class="th-head" style="text-align:center;" colspan="9" width="12%">पि.सि.भि
                                            (PCV)
                                        </th>
                                        <th class="th-head" style="text-align:center;" colspan="4" width="12%">एफ.आइ.पि.भि. (FIPV)
                                        </th>
                                        <th class="th-head" style="text-align:center;" colspan="4" width="12%">रोटा (RV)

                                        </th>

                                        <th class="th-head" style="text-align:center;" rowspan="2" width="5%">
                                            दादुरा/रुबेला (९ देखि ११ महिना)
                                        </th>
                                        <!-- <th class="th-head" style = "text-align:center;" rowspan="2" width="5%">पूर्ण खोप लगाएको		</th> -->
                                        <th class="th-head" style="text-align:center;" rowspan="2" width="5%">
                                            दादुरा/रुबेला (१२ देखि २३ महिना)
                                        </th>
                                        <th class="th-head" style="text-align:center;" rowspan="2" width="5%">जे.ई. (१२
                                            देखि २३ महिना)
                                        </th>
                                        <!-- <th class="th-head" style = "text-align:center;" rowspan="2" width="5%">१२ महिना पछि डि.पि.टि/हेप वि/हिब, पोलियो ३ मात्र पुरा गरेको </th> -->
                                    </tr>

                                    <tr>
                                        <th class="th-head" style="text-align:center;" colspan="3">१</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">२</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">३</th>

                                        <th class="th-head" style="text-align:center;" colspan="3">१</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">२</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">३</th>

                                        <th class="th-head" style="text-align:center;" colspan="3">१</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">२</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">३</th>

                                        <th class="th-head" style="text-align:center;" colspan="2">१</th>
                                        <th class="th-head" style="text-align:center;" colspan="2">२</th>

                                        <th class="th-head" style="text-align:center;" colspan="2">१</th>
                                        <th class="th-head" style="text-align:center;" colspan="2">२</th>

                                    </tr>

                                    <tr>
                                        <th class="th-head" style="text-align:center;" colspan="2">म / पु</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>

                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>

                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>

                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">ग / म / सा</th>

                                        <th class="th-head" style="text-align:center;" colspan="2">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="2">ग / म / सा</th>

                                        <th class="th-head" style="text-align:center;" colspan="2">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;" colspan="2">ग / म / सा</th>

                                        <th class="th-head" style="text-align:center;">ग / म / सा</th>
                                        <!-- <th>ग / म / सा</th> -->
                                        <th class="th-head" style="text-align:center;">ग / म / सा</th>
                                        <th class="th-head" style="text-align:center;">ग / म / सा</th>
                                        <!-- <th>ग / म / सा</th> -->
                                    </tr>

                                    <tr>
                                        <th class="th-head" style="text-align:center;">१</th>
                                        <th class="th-head" style="text-align:center;">२,३</th>
                                        <th class="th-head" style="text-align:center;">४</th>
                                        <th class="th-head" style="text-align:center;" colspan="2">५, ६</th>
                                        <th class="th-head" style="text-align:center;">७</th>
                                        <th class="th-head" style="text-align:center;">८</th>
                                        <th class="th-head" style="text-align:center;">९</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">१०,११,१२</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">१३,१४,१५</th>

                                        <th class="th-head" style="text-align:center;" colspan="3">१६,१७,१८</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">१९,२०,२१</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">२२,२३,२४</th>

                                        <th class="th-head" style="text-align:center;" colspan="3">२५,२६,२७</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">२८,२९,३०</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">३१,३२,३३</th>

                                        <th class="th-head" style="text-align:center;" colspan="3">३४,४५,३६</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">३७,३८,३९</th>
                                        <th class="th-head" style="text-align:center;" colspan="3">४०,४१,४२</th>

                                        <th class="th-head" style="text-align:center;" colspan="2">४३,४४,४५</th>
                                        <th class="th-head" style="text-align:center;" colspan="2">४६,४७,४८</th>

                                        <th class="th-head" style="text-align:center;" colspan="2">४९,५०,५१</th>
                                        <th class="th-head" style="text-align:center;" colspan="2">५२,५३,५४</th>

                                        <th class="th-head" style="text-align:center;">५५,५६,५७</th>
                                        <th class="th-head" style="text-align:center;">५८,५९,६०</th>
                                        <th class="th-head" style="text-align:center;">६१,६२,६३</th>
                                        <!-- <th>५२,५३,५४</th> -->
                                        <!-- <th>५५,५६,५७</th> -->
                                    </tr>
                                    @php
                                        function convertDateFormat($date){
                                            $str_arr = preg_split("/\-/", $date);  
                                            return $str_arr[2].'/'.$str_arr[1]."/\r\n". $str_arr[0];
                                        }   
                                    @endphp
                                    @foreach($data as $datum)
                                        <tr>
                                            <td></td>
                                            <td style="padding-left:5px;"
                                                align="left">{{ ucwords($datum->baby_name) }}</td>
                                            <td style="text-align:center;">{{ $datum->caste }}</td>
                                            <td style="padding-left:5px;" align="left"
                                                colspan="2">{{ $datum->gender }}</td>
                                            <td style="padding-left:5px;" align="left">{{ $datum->mother_name }}
                                                /{{ $datum->father_name }}</td>
                                            <td style="padding-left:5px;" align="left">{{ $datum->ward_no }}</td>
                                            <td>{{ $datum->contact_no }}</td>
                                            <td colspan="3">{{ convertDateFormat($datum->dob_np) }}</td>

                                            <td colspan="3">
                                                @if($bcgFirst = $datum->vaccinations()->where('vaccine_name','BCG')->first())
                                                    {{ convertDateFormat($bcgFirst->vaccinated_date_np) }}                                                
                                                @endif
                                            </td>

                                            <td colspan="3">
                                                @if($pvFirst = $datum->vaccinations()->where([['vaccine_name','Pentavalent'],['vaccine_period','6W']])->first())
                                                    {{ convertDateFormat($pvFirst->vaccinated_date_np) }}                                                
                                                @endif
                                            </td>
                                            <td colspan="3">
                                                @if($pvSecond = $datum->vaccinations()->where([['vaccine_name','Pentavalent'],['vaccine_period','10W']])->first())
                                                    {{ convertDateFormat($pvSecond->vaccinated_date_np) }}                                                
                                                @endif
                                            </td>
                                            <td colspan="3">
                                                @if($pvThird = $datum->vaccinations()->where([['vaccine_name','Pentavalent'],['vaccine_period','14W']])->first())
                                                    {{ convertDateFormat($pvThird->vaccinated_date_np) }}                                                
                                                @endif
                                            </td>

                                            <td colspan="3">
                                                @if($opvFirst = $datum->vaccinations()->where([['vaccine_name','OPV'],['vaccine_period','6W']])->first())
                                                    {{ convertDateFormat($opvFirst->vaccinated_date_np) }}                                                
                                                @endif
                                            </td>
                                            <td colspan="3">
                                                @if($opvSecond = $datum->vaccinations()->where([['vaccine_name','OPV'],['vaccine_period','10W']])->first())
                                                    {{ convertDateFormat($opvSecond->vaccinated_date_np) }}                                                
                                                @endif
                                            </td>
                                            <td colspan="3">
                                                @if($opvThird = $datum->vaccinations()->where([['vaccine_name','OPV'],['vaccine_period','14W']])->first())
                                                    {{ convertDateFormat($opvThird->vaccinated_date_np) }}                                                
                                                @endif
                                            </td>

                                            <td colspan="3">
                                                @if($pcvFirst = $datum->vaccinations()->where([['vaccine_name','PCV'],['vaccine_period','6W']])->first())
                                                    {{ convertDateFormat($pcvFirst->vaccinated_date_np) }}                                                
                                                @endif
                                            </td>
                                            <td colspan="3">
                                                @if($pcvSecond = $datum->vaccinations()->where([['vaccine_name','PCV'],['vaccine_period','10W']])->first())
                                                    {{ convertDateFormat($pcvSecond->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>
                                            <td colspan="3">
                                                @if($pcvThird = $datum->vaccinations()->where([['vaccine_name','PCV'],['vaccine_period','9M']])->first())
                                                    {{ convertDateFormat($pcvThird->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>

                                            <td colspan="2">
                                                @if($fipvFirst = $datum->vaccinations()->where([['vaccine_name','FIPV'],['vaccine_period','6W']])->first())
                                                    {{ convertDateFormat($fipvFirst->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>
                                            <td colspan="2">
                                                @if($fipvSecond = $datum->vaccinations()->where([['vaccine_name','FIPV'],['vaccine_period','14W']])->first())
                                                {{ convertDateFormat($fipvSecond->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>

                                            <td colspan="2">
                                                @if($rvFirst = $datum->vaccinations()->where([['vaccine_name','RV'],['vaccine_period','6W']])->first())
                                                {{ convertDateFormat($rvFirst->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>
                                            <td colspan="2">
                                                @if($rvSecond = $datum->vaccinations()->where([['vaccine_name','RV'],['vaccine_period','14W']])->first())
                                                {{ convertDateFormat($rvSecond->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>

                                            <td>
                                                @if($mrFirst = $datum->vaccinations()->where([['vaccine_name','MR'],['vaccine_period','9M']])->first())
                                                {{ convertDateFormat($mrFirst->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>

                                            <td>
                                                @if($mrSecond = $datum->vaccinations()->where([['vaccine_name','MR'],['vaccine_period','15M']])->first())
                                                    {{ convertDateFormat($mrSecond->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>

                                            <td>
                                                @if($jeFirst = $datum->vaccinations()->where([['vaccine_name','JE'],['vaccine_period','12M']])->first())
                                                    {{ convertDateFormat($jeFirst->vaccinated_date_np) }}                                               
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                        <!-- /.report-->


                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-lg-12 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /#page-wrapper -->

@endsection