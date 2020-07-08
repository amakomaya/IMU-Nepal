<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog" style="width:1250px;">
        <!-- Modal content-->
        <form method="post" action="{{ url('api/hmis/vaccination-program') }}">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title text-center">खाेप कार्यक्रम</h4>
                <p>
                    डाटाहरुलाई सच्चाई HMIS ( DHIS2 ) Server मा पठाउनुहोस्
                </p>
                <div class="form-group">
                    <label for="period" class="col-md-2">Select Period:</label>
                    <div class="from-group  row-sm-3" id="select-year-month">
                        <select class="form-control" style="margin-top: 20px; width: 30%" name="period">
                            @if(!isset($select_year))
                                @php
                                    $now = \Carbon\Carbon::now();
                                    $now_in_nepali = \Yagiten\Nepalicalendar\Calendar::eng_to_nep($now->year, $now->month, $now->day)->getYearMonthDay();
                                @endphp
                                <option value="{{ explode('-' , $now_in_nepali)[0].explode('-' ,$now_in_nepali)[1] }}"
                                        selected> {{ explode('-' ,$now_in_nepali)[0].'/'.explode('-' ,$now_in_nepali)[1] }} </option>
                            @else
                                <option value="{{ $select_year.sprintf("%02d", $select_month) }}"
                                        selected> {{ $select_year.'/'.sprintf("%02d", $select_month) }} </option>
                            @endif
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-body">
                    <style>
                        .tableDiv {
                            width: 100%;
                            margin: 0 auto;
                        }

                        input{
                            text-align: center !important;
                        }

                        .titleHeader {
                            text-align: center;
                            color:#006100;
                            background-color: rgb(198, 239, 206) !important;
                            border: 1px solid #000;
                            padding: 5px;
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

                        @media  print {
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
                    <div class="hmis">HMIS 9.3</div>
                    <div class="clearfix"></div>
                    <h3 class="titleHeader">१. खाेप कार्यक्रम</h3>
                    <table class="report" style="width:100%">
                        <tbody><tr>
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
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="bcgFirst" value="{{ $data['immunizedChild']['bcgFirst'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pvFirst" value="{{ $data['immunizedChild']['pvFirst'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pvSecond" value="{{ $data['immunizedChild']['pvSecond'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pvThird" value="{{ $data['immunizedChild']['pvThird'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="opvFirst" value="{{ $data['immunizedChild']['opvFirst'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="opvSecond" value="{{ $data['immunizedChild']['opvSecond'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="opvThird" value="{{ $data['immunizedChild']['opvThird'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pcvFirst" value="{{ $data['immunizedChild']['pcvFirst'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pcvSecond" value="{{ $data['immunizedChild']['pcvSecond'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pcvThird" value="{{ $data['immunizedChild']['pcvThird'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="fipvFirst" value="{{ $data['immunizedChild']['fipvFirst'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="fipvSecond" value="{{ $data['immunizedChild']['fipvSecond'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="mrFirst" value="{{ $data['immunizedChild']['mrFirst'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="mrSecond" value="{{ $data['immunizedChild']['mrSecond'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="jeFirst" value="{{ $data['immunizedChild']['jeFirst'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="rvFirst" value="{{ $data['immunizedChild']['rvFirst'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="rvSecond" value="{{ $data['immunizedChild']['rvSecond'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pvThirdAndOpvThridAfterOneYear" value="{{ $data['immunizedChild']['pvThirdAndOpvThridAfterOneYear'] ?? 0 }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="tdFirst" value="{{ $data['immunizedChild']['tdFirst'] ?? 0 }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="tdSecond" value="{{ $data['immunizedChild']['tdSecond'] ?? 0 }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="tdThrid" value="{{ $data['immunizedChild']['tdThrid'] ?? 0 }}"></td>
                        </tr>
                        <tr>
                            <th rowspan="2">खाेप (डाेज)</th>
                            <th>प्राप्त भएको</th>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="bcgReceived" value="{{ $data['vailStock']['bcgReceived'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pentavalentReceived" value="{{ $data['vailStock']['pentavalentReceived'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="opvReceived" value="{{ $data['vailStock']['opvReceived'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pcvReceived" value="{{ $data['vailStock']['pcvReceived'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="mrReceived" value="{{ $data['vailStock']['mrReceived'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="jeReceived" value="{{ $data['vailStock']['jeReceived'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="fipvReceived" value="{{ $data['vailStock']['fipvReceived'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="rotaReceived" value="{{ $data['vailStock']['rotaReceived'] }}"></td>
                            <td rowspan="3" bgcolor="#888">&nbsp;</td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="tdReceived" value="{{ $data['vailStock']['tdReceived'] ?? 0 }}"></td>
                        </tr>
                        <tr>
                            <th>खर्च भएको</th>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="bcgExpense" value="{{ $data['vailStock']['bcgExpense'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pentavalentExpense" value="{{ $data['vailStock']['pentavalentExpense'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="opvExpense" value="{{ $data['vailStock']['opvExpense'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="pcvExpense" value="{{ $data['vailStock']['pcvExpense'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="mrExpense" value="{{ $data['vailStock']['mrExpense'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="jeExpense" value="{{ $data['vailStock']['jeExpense'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="fipvExpense" value="{{ $data['vailStock']['fipvExpense'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="rotaExpense" value="{{ $data['vailStock']['rotaExpense'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="tdExpense" value="{{ $data['immunizedChild']['tdExpense'] ?? 0 }}"></td>
                        </tr>
                        <tr>
                            <th colspan="2">AEFI cases</th>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_bcg" value="{{ $data['aefiCases']['aefi_bcg'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_pentavalent" value="{{ $data['aefiCases']['aefi_pentavalent'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_opv" value="{{ $data['aefiCases']['aefi_opv'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_pcv" value="{{ $data['aefiCases']['aefi_pcv'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_mr" value="{{ $data['aefiCases']['aefi_mr'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_je" value="{{ $data['aefiCases']['aefi_je'] }}"></td>
                            <td colspan="2" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_fipv" value="{{ $data['aefiCases']['aefi_fipv'] }}"></td>
                            <td style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_rota" value="{{ $data['aefiCases']['aefi_rota'] }}"></td>
                            <td colspan="3" style="padding: 4px"><input style="padding: 0px 5px; font-size: 12px" class="form-control" type="tel" name="aefi_td" value="{{ $data['aefiCases']['aefi_td'] ?? 0 }}"></td>
                        </tr>
                        </tbody></table>
                </div>
            <div class="modal-footer">
                <label for="" class="pull-left">HMIS ( DHIS2 ) को Username र Password राख्नुहोस</label><br>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="usernamehmis">Username</label>
                        <input type="text" name="hmisUsername" placeholder="Username">
                    </div>
                    <div class="form-group col-md-4">
                        <label for="password">Password</label>
                        <input type="password" name="hmisPassword" placeholder="Password">
                    </div>
                </div>
                <br>
                <input type="text" name="hp_code" value="{{ $hp_code }}" hidden>
                <button type="submit" class="btn btn-success" >Confirm & Send</button>
            </div>
        </div>
        </form>
    </div>
</div>
@section('script')
    <script type="text/javascript" src="{{ mix('js/app.js') }}"></script>
@endsection