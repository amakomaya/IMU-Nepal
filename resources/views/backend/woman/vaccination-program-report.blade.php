@extends('layouts.backend.app')

@section('content')

<script type="text/javascript">

        function provinceOnchange(id){
            $("#district").text("Loading...").fadeIn("slow");
            $.get( "{{route("admin.district-select-province")}}?id="+id,function(data){
                $("#district").html(data);
            });
        }

        function districtOnchange(id){
            $("#municipality").text("Loading...").fadeIn("slow");
            $.get( "{{route("admin.municipality-select-district")}}?id="+id,function(data){
                $("#municipality").html(data);
            });
        }

        function municipalityOnchange(id){
            $("#ward").text("Loading...").fadeIn("slow");
            $.get( "{{route("admin.ward-select-municipality")}}?id="+id,function(data){
                $("#ward").html(data);
            });
        }

        function wardOnchange(id){
            $("#healthpost").text("Loading...").fadeIn("slow");
            municipality_id = $("#municipality_id").val();
            $.get( "{{route("admin.healthpost-select-ward")}}?id="+id+"&municipality_id="+municipality_id,function(data){
                $("#healthpost").html(data);
            });
        }



        $(document).ready(function(){
        $.get( "{{route("admin.select-from-to")}}?from_date={{$from_date}}&to_date={{$to_date}}",function(data){
                $("#from_to").html(data);
            });
        });

        function validateform(){
            var from_date = document.forms["info"]["from_date"].value;
            var to_date = document.forms["info"]["to_date"].value;
            
            if(from_date=="" && to_date!=""){
                alert('Both From Date and To Date is rerquired');
                return false;
            }

            if(to_date=="" && from_date!=""){
                alert('Both From Date and To Date is rerquired');
                return false;
            }

            if(from_date>to_date){
                alert("From Date must be smaller than To Date");
                return false;
            }
            return true;
        }

</script>


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

                  <form method="get" name="info">
                        <div class="form-group col-sm-3" id="province">
                            <select name="province_id" class="form-control" onchange="provinceOnchange($(this).val())">
                                @if(Auth::user()->role!="province" && Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Provinces</option>
                                @endif
                                @foreach($provinces as $province)
                                    @if($province_id==$province->id)
                                        @php($selectedProvince = "selected")
                                    @else
                                        @php($selectedProvince = "")
                                    @endif
                                    <option value="{{$province->id}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-sm-3" id = "district">
                            <select name="district_id" class="form-control" onchange="districtOnchange($(this).val())">
                                @if(Auth::user()->role!="dho" && Auth::user()->role!="municipality" &&Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Districts</option>
                                @endif
                                @foreach($districts as $district)
                                    @if($district_id==$district->id)
                                        @php($selectedDistrict = "selected")
                                    @else
                                        @php($selectedDistrict = "")
                                    @endif
                                    <option value="{{$district->id}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-sm-3" id="municipality">
                            <select name="municipality_id" class="form-control" onchange="municipalityOnchange($(this).val())" id="municipality_id">
                                @if(Auth::user()->role!="municipality" && Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Municipalities</option>
                                @endif
                                @foreach($municipalities as $municipality)
                                    @if($municipality_id==$municipality->id)
                                        @php($selectedMunicipality = "selected")
                                    @else
                                        @php($selectedMunicipality = "")
                                    @endif
                                    <option value="{{$municipality->id}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group  col-sm-3" id="ward">
                            <select name="ward_id" class="form-control"  onchange="wardOnchange($(this).val())">
                                @if(Auth::user()->role!="ward" && Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Wards</option>
                                @endif
                                @foreach($wards as $ward)
                                    @if($ward_id==$ward->id)
                                        @php($selectedWard = "selected")
                                    @else
                                        @php($selectedWard = "")
                                    @endif
                                    <option value="{{$ward->id}}" {{$selectedWard}}>{{$ward->ward_no}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="clearfix"></div>
                        <div class="form-group  col-sm-3" id="healthpost">
                            <select name="hp_code" class="form-control"  >
                                @if(Auth::user()->role!="healthpost" && Auth::user()->role!="healthworker")
                                    <option value="">Select All Healthposts</option>
                                @endif
                                @foreach($healthposts as $healthpost)
                                    @if($hp_code==$healthpost->hp_code)
                                        @php($selectedHealthpost = "selected")
                                    @else
                                        @php($selectedHealthpost = "")
                                    @endif
                                    <option value="{{$healthpost->hp_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>
                                @endforeach
                            </select>
                        </div>                                
                        <div id ="from_to"></div>
                        <div class="form-group col-sm-3">
                            <button type="submit" class="btn btn-success">Submit</button>
                        </div>
                    </form>
                    <div class="clearfix"></div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" onclick="printDiv('printable')">
                            <i class="fa fa-print"></i> Print
                        </button>
                    </div>
                    <!-- <div id="printable1">
                    <h1 class="btn-success">Print me</h1>
                    </div> -->
                    <div class="clearfix"></div>

                  <div style="page-break-after: always; margin:0 auto;clear: both;">

                    <div id="printable">
                        <style>
                        .tableDiv{width:100%;margin:0 auto;}
                        .titleHeader{text-align: center; border: 1px solid #000; padding: 5px; background-color: #dfdfdf; font-size: 13px;}
                        table.report, .report td, .report th {border: 1px solid black; border-collapse: collapse;}
                        .report th{font-size: 12px; padding:5px;background-color:#dfdfdf; text-align:center;}
                        .report td{font-size: 12px;padding:10px;color:#000; text-align:center;}
                        .hmis{float: right; font-size: 10px;}
                        .clearfix{clear: both;}

                        @media print { 
                        .tableDiv{width:100%;margin:0 auto;}
                        .titleHeader{text-align: center; border: 1px solid #000; padding: 5px; background-color: #dfdfdf; font-size: 13px;}
                        table.report, .report td, .report th {border: 1px solid black; border-collapse: collapse;}
                        .report th{font-size: 12px; padding:5px;background-color:#dfdfdf;}
                        .report td{font-size: 12px;padding:10px;color:#000;}
                        .hmis{float: right; font-size: 10px;}
                        .clearfix{clear: both;}
                        }
                </style>
                    <div class="hmis">HMIS 9.3 </div>
                    <div class="clearfix"></div>
                    <h3 class="titleHeader">१. खाेप कार्यक्रम</h3>
                    <table class="report" style="width:100%">
                        <tr>
                            <th rowspan="2" colspan="2" width="10%">खाेपको प्रकार</th>
                            <th rowspan="2" width="4%">बि.सी.जी.</th>
                            <th colspan="3" width="9%">डी.पी.टी-हेप वि. - हिब</th>
                            <th colspan="3" width="9%">पाेलियो</th>
                            <th colspan="3" width="9%">पी.सी.भी.</th>
                            <th colspan="2" width="9%">दादुरा / रुबेला</th>
                            <th rowspan="2" class="text-center" width="10%">एक बर्ष उमेरपछि डी.पी.टी. हेप वि.हिब र पोलियोको तेश्रो मात्रा पुरा गरेको</th>
                            <th width="4%">जे.ई</th>
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
                            <th width="60">९-११ म</th>
                            <th width="70">१२-२३ म</th>
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
                        </tr>
                        @foreach($vaccinationRecored as $key => $data)
                            @if($key == "immunizedChild")  
                        <tr>
                            <th colspan="2" align="center">खाेप पाएका बच्चाहरुको संख्या</th>
                            <td>{{$data['bcgFirst']}}</td>
                            <td>{{$data['pvFirst']}}</td>
                            <td>{{$data['pvSecond']}}</td>
                            <td>{{$data['pvThird']}}</td>
                            <td>{{$data['opvFirst']}}</td>
                            <td>{{$data['opvSecond']}}</td>
                            <td>{{$data['opvThird']}}</td>
                            <td>{{$data['pcvFirst']}}</td>
                            <td>{{$data['pcvSecond']}}</td>
                            <td>{{$data['pcvThird']}}</td>
                            <td>{{$data['mrFirst']}}</td>
                            <td>{{$data['mrSecond']}}</td>
                            <td>{{$data['pvThirdAndOpvThirdAfeterOneYear']}}</td>
                            <td>{{$data['jeFirst']}}</td>
                            <td>{{$data['tdFirst']}}</td>
                            <td>{{$data['tdSecond']}}</td>
                            <td>{{-- {{$data['tdThird']}} --}}</td>
                        </tr>
                            @endif
                            @if($key == "vailStock")
                        <tr>
                            <th rowspan="2">खाेप (डाेज)</th>
                            <th>प्राप्त भएको</th>
                            <td>{{$data['bcgReceived']}}</td>
                            <td colspan="3">{{$data['pentavalentReceived']}}</td>
                            <td colspan="3">{{$data['opvReceived']}}</td>
                            <td colspan="3">{{$data['pcvReceived']}}</td>
                            <td colspan="2">{{$data['mrReceived']}}</td>
                            <td rowspan="3" bgcolor="#888">&nbsp;</td>
                            <td>{{$data['jeReceived']}}</td>
                            <td colspan="3">{{$data['tdReceived']}}</td>
                        </tr>

                        <tr>
                            <th>खर्च भएको</th>
                            <td>{{$data['bcgExpense']}}</td>
                            <td colspan="3">{{$data['pentavalentExpense']}}</td>
                            <td colspan="3">{{$data['opvExpense']}}</td>
                            <td colspan="3">{{$data['pcvExpense']}}</td>
                            <td colspan="2">{{$data['mrExpnse']}}</td>
                            <td>{{$data['jeExpense']}}</td>
                            <td colspan="3">{{$data['tdExpense']}}</td>
                        </tr>
                        @endif
                        @if($key == 'aefiCases')
                        <tr>
                            <th colspan="2">AEFI cases</th>
                            <td>{{$data['aefi_bcg']}}</td>
                            <td colspan="3">{{$data['aefi_pentavalent']}}</td>
                            <td colspan="3">{{$data['aefi_opv']}}</td>
                            <td colspan="3">{{$data['aefi_pcv']}}</td>
                            <td colspan="2">{{$data['aefi_mr']}}</td>
                            <td>{{$data['aefi_je']}}</td>
                            <td colspan="3">{{$data['aefi_td']}}</td>
                        </tr>			
                        @endif
                        @endforeach		
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