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
</script>
<div id="page-wrapper">
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading text-center">
                       <strong>Immunized Child</strong>
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
                                        @php 
                                            $selectedProvince = "selected"
                                        @endphp
                                    @else
                                        @php
                                            $selectedProvince = ""
                                        @endphp
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
                                        @php
                                            $selectedDistrict = "selected"
                                        @endphp
                                    @else
                                        @php 
                                        $selectedDistrict = ""
                                        @endphp                                        
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
                                        @php
                                            $selectedMunicipality = "selected"
                                        @endphp
                                    @else
                                        @php
                                            $selectedMunicipality = ""
                                        @endphp
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
                                        @php
                                            $selectedWard = "selected"
                                        @endphp
                                    @else
                                        @php 
                                            $selectedWard = ""
                                        @endphp
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
                                        @php
                                            $selectedHealthpost = "selected"
                                        @endphp
                                    @else
                                        @php
                                            $selectedHealthpost = ""
                                        @endphp
                                    @endif
                                    <option value="{{$healthpost->hp_code}}" {{$selectedHealthpost}}>{{$healthpost->name}}</option>
                                @endforeach
                            </select>
                        </div>           

                        <div class="form-group  col-sm-3" id="healthpost">
                            <select name="fiscal_year" class="form-control"  >
                                @foreach($ficalYearList as $year => $fiscal)
                                    @if($year == $fiscal_year){
                                        @php
                                            $selected = 'selected'
                                        @endphp
                                    @else
                                        @php
                                            $selected = ''
                                        @endphp
                                    @endif
                                    <option value="{{$year}}" {{$selected}}>{{$fiscal}}</option>
                                @endforeach
                            </select>
                        </div>
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
<div id="printable">
<style type="text/css">
    @media  print{@page  {size: landscape}}
</style>
    <?php 
        $bcgFirst = 0;
        $pvFirst = 0;
        $pvSecond = 0;
        $pvThird = 0;
        $opvFirst = 0;
        $opvSecond = 0;
        $opvThird = 0;
        $pcvFirst = 0;
        $pcvSecond = 0;
        $pcvThird = 0;
        $fipvFirst = 0;
        $fipvSecond = 0;
        $mrFirst = 0;
        $mrSecond = 0;
        $jeFirst = 0;
        $rvFirst = 0;
        $rvSecond = 0;

        $bcgFirstTotal = 0;
        $pvFirstTotal = 0;
        $pvSecondTotal = 0;
        $pvThirdTotal = 0;
        $opvFirstTotal = 0;
        $opvSecondTotal = 0;
        $opvThirdTotal = 0;
        $pcvFirstTotal = 0;
        $pcvSecondTotal = 0;
        $pcvThirdTotal = 0;
        $fipvFirstTotal = 0;
        $fipvSecondTotal = 0;
        $mrFirstTotal = 0;
        $mrSecondTotal = 0;
        $jeFirstTotal = 0;
        $rvFirstTotal = 0;
        $rvSecondTotal = 0;


    ?>

    <?php
        // from date        
        $fromNepali = $fiscal_year."-".'04'."-".'01';
        $from_date = \App\Helpers\ViewHelper::convertNepaliToEnglish($fromNepali); 
    ?>
    
    <table class="table table-bordered">
        <tr>
            <th rowspan="2" style="vertical-align:middle;" class="text-center">Months\Type of Vaccine</th>
            <th rowspan="2" style="vertical-align:middle;" class="text-center">BCG</th>
            <th colspan="3" class="text-center">Pentavalent</th>
            <th colspan="3" class="text-center">OPV</th>
            <th colspan="3" class="text-center">PCV</th>
            <th colspan="2" class="text-center">*FIPV</th>
            <th colspan="2" class="text-center">MR</th>
            <th rowspan="2" style="vertical-align:middle;" class="text-center">JE</th>
            <th colspan="2" class="text-center">*RV</th>        
        </tr>

        <tr class="text-center">
            <td>1st</td>
            <td>2nd</td>
            <td>3rd</td>
            
            <td>1st</td>
            <td>2nd</td>
            <td>3rd</td>

            <td>1st</td>
            <td>2nd</td>
            <td>3rd</td>

            <td>1st</td>
            <td>2nd</td>
            
            <td>1st</td>
            <td>2nd</td>

            <td>1st</td>
            <td>2nd</td>

        </tr>
        <?php
        $months = ['Shrawn','Bhadra','Aswin','Kartik','Mansir','Poush','Magh', 'Falgun', 'Chaitra', 'Baishakh', 'Jestha', 'Asad'];
        foreach ($monthlyreport as $key => $achievementRecord) {

            if($key!=0){
                    $from_date = $to_date;
            }
            $to_date = date('Y-m-d', strtotime("+1 month", strtotime($from_date)));
                

            if($achievementRecord['bcgFirst']){
                $bcgFirst++;
                $bcgFirstTotal++;
            }

            if($achievementRecord['pvFirst']){
                $pvFirst++;
                $pvFirstTotal++;
            }

            if($achievementRecord['pvSecond']){
                $pvSecond++;
                $pvSecondTotal++;
            }

            if($achievementRecord['pvThird']){
                $pvThird++;
                $pvThirdTotal++;
            }

            if($achievementRecord['opvFirst']){
                $opvFirst++;
                $opvFirstTotal++;
            }

            if($achievementRecord['opvSecond']){
                $opvSecond++;
                $opvSecondTotal++;
            }

             if($achievementRecord['opvThird']){
                $opvThird++;
                $opvThirdTotal++;
            }

             if($achievementRecord['pcvFirst']){
                $pcvFirst++;
                $pcvFirstTotal++;
            }

             if($achievementRecord['pcvSecond']){
                $pcvSecond++;
                $pcvSecondTotal++;
            }

             if($achievementRecord['pcvThird']){
                $pcvThird++;
                $pcvThirdTotal++;
            }

             if($achievementRecord['fipvFirst']){
                $fipvFirst++;
                $fipvFirstTotal++;
            }

             if($achievementRecord['fipvSecond']){
                $fipvSecond++;
                $fipvSecondTotal++;
            }

             if($achievementRecord['mrFirst']){
                $mrFirst++;
                $mrFirstTotal++;
            }

             if($achievementRecord['mrSecond']){
                $mrSecond++;
                $mrSecondTotal++;
            }

             if($achievementRecord['jeFirst']){
                $jeFirst++;
                $jeFirstTotal++;
            }

             if($achievementRecord['rvFirst']){
                $rvFirst++;
                $rvFirstTotal++;
            }

             if($achievementRecord['rvSecond']){
                $rvSecond++;
                $rvSecondTotal++;
            }

        ?>
        <tr class="text-center">
            <td><?=$months[$key]?></td>
            <td>
                    <?=$bcgFirst?>
            </td>
            <td>
                    <?=$pvFirst?>
            </td>
            <td>
                    <?=$pvSecond?>
            </td>
            <td>
                    <?=$pvThird?>
            </td>

            <td>
                    <?=$opvFirst?>
            </td>
            <td>
                    <?=$opvSecond?>
            </td>
            <td>
                    <?=$opvThird?>
            </td>

            <td>
                    <?=$pcvFirst?>
            </td>
            <td>
                    <?=$pcvSecond?>
            </td>
            <td>
                    <?=$pcvThird?>
            </td>
            <td>
                    <?=$fipvFirst?>
            </td>
            <td>
                    <?=$fipvSecond?>
            </td>

            <td>
                    <?=$mrFirst?>
            </td>
            <td>
                    <?=$mrSecond?>
            </td>

            <td>
                    <?=$jeFirst?>
            </td>

            <td>
                    <?=$rvFirst?>
            </td>
            <td>
                    <?=$rvSecond?>
            </td>
        </tr>
        <?php
        $bcgFirst = 0;
        $pvFirst = 0;
        $pvSecond = 0;
        $pvThird = 0;
        $opvFirst = 0;
        $opvSecond = 0;
        $opvThird = 0;
        $pcvFirst = 0;
        $pcvSecond = 0;
        $pcvThird = 0;
        $fipvFirst = 0;
        $fipvSecond = 0;
        $mrFirst = 0;
        $mrSecond = 0;
        $jeFirst = 0;
        $rvFirst = 0;
        $rvSecond = 0;
        }
        ?>
        <tr class="text-center">
            <th class="text-center">Total</th>
            <td><?=$bcgFirstTotal?></td>
            <td><?=$pvFirstTotal?></td>
            <td><?=$pvSecondTotal?></td>
            <td><?=$pvThirdTotal?></td>
            
            <td><?=$opvFirstTotal?></td>
            <td><?=$opvSecondTotal?></td>
            <td><?=$opvThirdTotal?></td>

            <td><?=$pcvFirstTotal?></td>
            <td><?=$pcvSecondTotal?></td>
            <td><?=$pcvThirdTotal?></td>

            <td><?=$fipvFirstTotal?></td>
            <td><?=$fipvSecondTotal?></td>
            
            <td><?=$mrFirstTotal?></td>
            <td><?=$mrSecondTotal?></td>
            <td><?=$jeFirstTotal?></td>
            <td><?=$rvFirstTotal?></td>
            <td><?=$rvSecondTotal?></td>

        </tr>
        
    </table>
    <div class="form-group">
        Note* : FIPV and RV are new Vaccine
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