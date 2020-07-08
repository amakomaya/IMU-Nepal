<div id="printable">
<div class="main-frame">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style>
  @media print{
  @page {size: landscape; margin:55px 20px 0 20px;}
}
  .tdPaddingRow td{padding:1px 5px;}
  .pagebreak { page-break-before: always; }
  table{
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 3px;
    font-size: 11px;
    text-align: center;

    }
      table th {
        padding:5px;
        text-align: center;
    }
    .tdPadding td{padding:6px;}

    .clearfix{
      clear: both;
    }
  
    .tab2{
      width: 100%;
      
      font-size: 11px;
      border-collapse: collapse;
      }
  
      .tab2,.tab2 tr,.tab2 td,.tab2 th{
      padding:5px;
      }

      
      .circle {
  width: 20px;
  height: 20px;
  border-radius: 50%;
  border: 1px solid #000;
  font-size: 11px;
  color: #000;
  line-height: 21px;
  text-align: center;
  background: #fff
}
</style>

  <div style="float: right; font-size: 9.1px;">HMIS 3.5 Maternal and New Born Health Card</div>
        <table>
          <tr>
            <td width="50%" valign="top" style="padding-right:20px;">
              <table border="1">
                <tr>
                  <th>सुत्केरी सेवा</th>
                </tr>
              </table>

              <table border="1" style="text-align: center;   width: 35%; float: left;">
                <tr>
                  <td colspan="3">आमालाई भिटामिन ए दिएको मिति</td>
                </tr>
                <tr>
                  <td>
                      @php $viataminA = $data->vaccinations()->vitaminA()->active()->first() @endphp
                      {{ isset($viataminA) ? date('d/m/Y' , strtotime($viataminA->vaccinated_date_np)) : '..../..../........' }}
                  </td>
                </tr>
              </table>

              <table border="1" style="text-align: center; width: 30%; float: right;">
                <tr >
                  <td height="20">आमालाई आईरन <br/> चक्कि दिएको संख्या</td>
                  <td width="30%">
                      @if($countIronCapsuleAfterDelivery = $data->countIronCapsuleAfterDelivery($data) > 0)
                          {{ $countIronCapsuleAfterDelivery }}
                      @endif
                  </td>
                </tr>
              </table>

              <table border="1">
                <tr>
                  <th>सुत्केरी जाचँको (PNC) विवरण</th>
                </tr>
              </table>

              <table border="1" style="text-align: center; margin-bottom: 1.5%">
                <tr>
                  <td rowspan="2">जाँच</td>
                  <td colspan="3">मिति</td>
                  <td rowspan="2">आमाको अवस्था</td>
                  <td rowspan="2">बच्चाको अवस्था</td>
                  <td rowspan="2">उपचार/ सल्लाह</td>
                  <td rowspan="2" width="10%">परिवार नियोजन सेवा</td>
                  <td colspan="2">सेवा प्रदायकको</td>
                </tr>
                <tr>
                  <td width="4%">ग</td>
                  <td width="4%">म</td>
                  <td width="6%">सा</td>
                  <td width="20%">नाम र थर</td>
                  <td width="13%">सही</td>
                </tr>
                @php 
                $pncs=$data->modelpnc($data->token); 
                @endphp
                  @if(count($pncs)>0)
                    @foreach($pncs as $pnc)

                        @php 
                        $deliveryDateTime = $data->findDeliveryDateTime($data->token); 
                        $pncDateTime = $pnc->date_of_visit. " ".$pnc->visit_time.":00"; 
                        $deliveryDateTime = StrToTime ( $deliveryDateTime );
                        $pncDateTime = StrToTime ( $pncDateTime );
                        $diff = $pncDateTime - $deliveryDateTime;
                        $hours = $diff / ( 60 * 60 );
                        $pncDateNepali = \App\Helpers\ViewHelper::convertEnglishToNepali($pnc->date_of_visit);
                        list($pncYear,$pncMonth,$pncDay) = explode("-",$pncDateNepali);
                        @endphp
                    @if($hours<=24)
                    <tr>
                        <td>PNC  I ( जन्मेको<br/>२४ घण्टन्भित्र)</td>
                        <td>{{$pncDay}}</td>
                        <td>{{$pncMonth}}</td>
                        <td>{{$pncYear}}</td>
                        <td>{{$pnc->mother_status}}</td>
                        <td>{{$pnc->baby_status}}</td>
                        <td>{{$pnc->advice}}</td>
                        <td>{{$pnc->family_plan}}</td>
                        <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($pnc->checked_by)}}</td>
                        <td>{{\App\Models\HealthWorker::findHealthWorkerSignature($pnc->checked_by)}}</td>
                    </tr>
                    @endif
                    @endforeach
                @else

                <tr>
                    <td>PNC  I ( जन्मेको<br/>२४ घण्टन्भित्र)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif

                @if(count($pncs)>0)
                    @foreach($pncs as $pnc)

                        @php 
                        $deliveryDateTime = $data->findDeliveryDateTime($data->token); 
                        $pncDateTime = $pnc->date_of_visit. " ".$pnc->visit_time.":00"; 
                        $deliveryDateTime = StrToTime ( $deliveryDateTime );
                        $pncDateTime = StrToTime ( $pncDateTime );
                        $diff = $pncDateTime - $deliveryDateTime;
                        $days = $diff / ( 60 * 60 * 24 );
                        $days = number_format((float)$days, 0, '.', '');
                        $pncDateNepali = \App\Helpers\ViewHelper::convertEnglishToNepali($pnc->date_of_visit);
                        list($pncYear,$pncMonth,$pncDay) = explode("-",$pncDateNepali);
                        @endphp
                    @if($days>1 && $days <=3 )
                    <tr>
                        <td>PNC II(जन्मेको<br/>तेश्रो दिन)</td>
                        <td>{{$pncDay}}</td>
                        <td>{{$pncMonth}}</td>
                        <td>{{$pncYear}}</td>
                        <td>{{$pnc->mother_status}}</td>
                        <td>{{$pnc->baby_status}}</td>
                        <td>{{$pnc->advice}}</td>
                        <td>{{$pnc->family_plan}}</td>
                        <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($pnc->checked_by)}}</td>
                        <td>{{\App\Models\HealthWorker::findHealthWorkerSignature($pnc->checked_by)}}</td>
                    </tr>
                    @endif
                    @endforeach
                @else
                
                <tr>
                    <td width="17%">PNC II(जन्मेको<br/>तेश्रो दिन)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif

                @if(count($pncs)>0)
                    @foreach($pncs as $pnc)

                        @php 
                        $deliveryDateTime = $data->findDeliveryDateTime($data->token); 
                        $pncDateTime = $pnc->date_of_visit. " ".$pnc->visit_time.":00"; 
                        $deliveryDateTime = StrToTime ( $deliveryDateTime );
                        $pncDateTime = StrToTime ( $pncDateTime );
                        $diff = $pncDateTime - $deliveryDateTime;
                        $days = $diff / ( 60 * 60 * 24 );
                        $days = number_format((float)$days, 0, '.', '');
                        $pncDateNepali = \App\Helpers\ViewHelper::convertEnglishToNepali($pnc->date_of_visit);
                        list($pncYear,$pncMonth,$pncDay) = explode("-",$pncDateNepali);
                        @endphp
                    @if($days>3 && $days <=7 )
                    <tr>
                        <td>PNC III(जन्मेको<br/>सातौ दिनमा)</td>
                        <td>{{$pncDay}}</td>
                        <td>{{$pncMonth}}</td>
                        <td>{{$pncYear}}</td>
                        <td>{{$pnc->mother_status}}</td>
                        <td>{{$pnc->baby_status}}</td>
                        <td>{{$pnc->advice}}</td>
                        <td>{{$pnc->family_plan}}</td>
                        <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($pnc->checked_by)}}</td>
                        <td>{{\App\Models\HealthWorker::findHealthWorkerSignature($pnc->checked_by)}}</td>
                    </tr>
                    @endif
                    @endforeach
                @else
                
                <tr>
                    <td>PNC III(जन्मेको<br/>सातौ दिनमा)</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                @endif

                @if(count($pncs)>0)
                    @foreach($pncs as $pnc)

                        @php 
                        $deliveryDateTime = $data->findDeliveryDateTime($data->token); 
                        $pncDateTime = $pnc->date_of_visit. " ".$pnc->visit_time.":00"; 
                        $deliveryDateTime = StrToTime ( $deliveryDateTime );
                        $pncDateTime = StrToTime ( $pncDateTime );
                        $diff = $pncDateTime - $deliveryDateTime;
                        $days = $diff / ( 60 * 60 * 24 );
                        $days = number_format((float)$days, 0, '.', '');
                        $pncDateNepali = \App\Helpers\ViewHelper::convertEnglishToNepali($pnc->date_of_visit);
                        list($pncYear,$pncMonth,$pncDay) = explode("-",$pncDateNepali);
                        @endphp
                    @if($days>7)
                    <tr>
                        <td>थप जाँच<br/>( {{ convertToNepali($days) }} औं दिन)</td>
                        <td>{{$pncDay}}</td>
                        <td>{{$pncMonth}}</td>
                        <td>{{$pncYear}}</td>
                        <td>{{$pnc->mother_status}}</td>
                        <td>{{$pnc->baby_status}}</td>
                        <td>{{$pnc->advice}}</td>
                        <td>{{$pnc->family_plan}}</td>
                        <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($pnc->checked_by)}}</td>
                        <td>{{\App\Models\HealthWorker::findHealthWorkerSignature($pnc->checked_by)}}</td>
                    </tr>
                    @endif
                    @endforeach
                @else    
                <tr>
                  <td>थप जाँच<br/>(… … औं दिन)</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>

                <tr>
                  <td>थप जाँच<br/>(… … औं दिन)</td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                </tr>
                @endif
              </table>

              <table border="1">
                <tr>
                  <th>प्रयोगशाला परीक्षण</th>
                </tr>
              </table>

              <table border="1" style="text-align: center; margin-bottom: 1.2%;" class="tdPadding">
                <tr>
                  <td rowspan="2" colspan="3">Date<br/>(DD/MM/YY)</td>
                  <td rowspan="2">Hb</td>
                  <td rowspan="2">Albumin</td>
                  <td colspan="2">Urine</td>
                  <td rowspan="2">Blood Sugar</td>
                  <td rowspan="2">HBsAg</td>
                  <td rowspan="2">VDRL</td>
                  <td rowspan="2">Retro-virus</td>
                  <td rowspan="2">Other</td>
                </tr>

                <tr>
                  <td>Protin</td>
                  <td>Sugar</td>
                </tr>
    @php
    $labTests = $data->modelLabTest($data->token);
    @endphp

    @if(count($labTests)>0)
      @foreach($labTests as $labTest)
      @php
        $labTestDateNepali = \App\Helpers\ViewHelper::convertEnglishToNepali($labTest->test_date);
        list($labTestYear, $labTestMonth, $labTestDay) = explode("-",$labTestDateNepali);
      @endphp
      <tr>
        <td>{{$labTestDay}}</td>
        <td>{{$labTestMonth}}</td>
        <td>{{$labTestDay}}</td>
        <td>{{$labTest->hb}}</td>
        <td>{{$labTest->albumin}}</td>
        <td>{{$labTest->urine_protein}}</td>
        <td>{{$labTest->urine_sugar}}</td>
        <td>{{$labTest->blood_sugar}}</td>
        <td>{{$labTest->hbsag}}</td>
        <td>{{$labTest->vdrl}}</td>
        <td>{{$labTest->retro_virus}}</td>
        <td>{{$labTest->other}}</td>
      </tr>
      @endforeach
    @else
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    @endif
  </table>
  <table border="1" style="text-align: center;">
    <tr>
      <td>&nbsp;</td>
    </tr>
    <tr height="105px">
      <th valign="top">स्वास्थ्य संवन्धी मुख्य मुख्य समस्या छोटकरीमा लेख्नुहोस् ।</th>
    </tr>
  </table>
  </td>
  <!---New Table Begin-->
  <td  width="100%" style="float: right;">
<table border="0">
    <tr>
        <td>
          <img src="{{ asset('images/nepal-logo.jpeg') }}" alt="Nepal Logo" width="75px" >
        </td>
        <td>
          नेपाल सरकार
          <br/> स्वास्थ्य तथा जनसंख्या मन्त्रालय
          <br/> स्वास्थ्य सेवा विभाग
          <br/> स्वास्थ्य व्यवस्थापन सूचना प्रणाली
        </td>
        <td>
          {!! QrCode::size(100)->generate($data->token); !!}
        </td>
    </tr>
  </table>  

  <table border="1">
    <tr>

      <th>मातृ तथा नवाशिशु स्वास्थ्य कार्ड </th>

    </tr>
  </table>  

  <table border="1">
    <tr >
      <td width="40%">स्वास्थ्य संस्थाको नाम </td>
      <td>जिल्ला </td>
      <td>गा.वि.स/न.पा.</td>
      <td colspan="3">वडा नं.</td>  
    </tr>
    <tr >
      <td>{{ $data->getHealthpost($data->hp_code) }}</td>
      <td>{{ $data->getHealthPostInfo($data->hp_code)->district->district_name }}</td>
      <td>{{ $data->getHealthPostInfo($data->hp_code)->municipality->municipality_name }} </td>
      <td colspan="3">{{ $data->getWard($data->hp_code) }}</td>  
    </tr>
    </table>
    <table border="1">
    <tr >
      <td width="20%">मूल दर्ता न </td>
      <td width="20%">सेवा दर्ता नं.</td>
      <td>ORC दर्ता नं.</td>
      <td colspan="3">सेवा दर्ता मिति </td>
    </tr>
    @php 

      $createdAt = explode(" ",$data->created_at);
      $createdAtNep = \App\Helpers\ViewHelper::convertEnglishToNepali($createdAt[0]); 

    @endphp
    <tr>
      <td>{{ $data->mool_darta_no }}</td>
      <td>{{ $data->sewa_darta_no }}</td>
      <td>{{ $data->orc_darta_no }}</td>
      <td>{{ Carbon\Carbon::parse($createdAtNep)->format('d') }}</td>
      <td>{{ Carbon\Carbon::parse($createdAtNep)->format('m') }}</td>
      <td>{{ Carbon\Carbon::parse($createdAtNep)->format('Y') }}</td>
     </tr>
  </table>

  <table border="1">
    <tr>

      <th>गर्भवती  महिलाको व्यक्तिगत विवरण </th> 

    </tr>
  </table>  

  <table border="1" class="anc">
    <tr>
      <td width="10%">नाम,थर</td>
      <td >{{ $data->getWomanName($data->token) }}</td>
      <td  width="15%">उमेर </td>
      <td >{{ $data->age }}</td>
      <td width="12%">उचाई</td>
      <td >{{ $data->height }}</td>
    </tr>
  </table>
  <table border="1">
    <tr>
      <td width="10%">जिल्ला </td>
      <td >
        @if(!empty($data->district->district_name))
        {{ $data->district->district_name }}
        @endif
      </td>
      <td width="15%">गा.वि.स/न.पा.</td>
      <td >{{ $data->municipality->municipality_name }}</td>
      <td width="12%">वडा </td>
      <td >{{ $data->ward ?? '' }}</td>
    </tr>
  </table>
  <table border="1">
    <tr>
      <td width="10%">गाउँ टोल </td>
      <td width="20%">{{ $data->tole }}</td>
      <td width="10%">सम्पर्क नं</td>
      <td width="10%">{{ $data->phone }}</td>
      <td width="12%">रक्त समुह</td>
      <td width="5%">{{ $data->getBloodGroup($data->blood_group) }}</td>
    </tr>
  </table>
  <table border="1">
    <tr>
      <td width="30%">गर्भको पटक (हालको समेत) </td>
      <td width="10%">{{ $data->getDeliveryCount($data->token) }}</td>
      <td width="17%">पतिको नाम,थर</td>
      <td width="50%">{{ $data->husband_name }}</td>
    </tr>
  </table>
  <table border="1">
    <tr>

      <th>अघिल्ला गर्भहरुको बिभारण </th> 

    </tr>
  </table>  

  <table border="1" class="tdPaddingRow">
    <tr >
      <th rowspan="2" width="11%">गर्भहरुको संख्या </th>
      <th colspan="5">गर्भको बिवरण</th>
      <th colspan="2">जिवित बच्चाको</th>
      <th rowspan="2">गर्भको जटिलता </th>
      <th rowspan="2">प्रसुतीको किसिम</th>
    </tr>
    <tr >
      <td>जिवित</td>
      <td>मृत  जन्म</td>
      <td width="10%">अवधि नपुगेको  (३७ हप्ता) </td>
      <td>जुम्ल्याहा </td>
      <td>गर्भपतन</td>
      <td>लिङ्ग</td>
      <td>हालको उमेर</td>
    </tr>
    @php 
    $previouseDeliveries = [];
    $i = 1;
    //print_r($previouseDeliveries);
    @endphp
    @if(count($previouseDeliveries)>0)
        @foreach($previouseDeliveries as $previouseDelivery) 
        @php
            $alive = "";
            $death = "";
            $prematureBirth = "";
            $twins="";
            $age = "";
        @endphp
        @if($previouseDelivery->baby_alive=="Alive")
            @php $alive="&#10004;"; @endphp
        @else
            @php $death = "&#10004;"; @endphp
        @endif
        @if($previouseDelivery->premature_birth=="Yes")
            @php $prematureBirth = "&#10004;"; @endphp
        @endif
        @php
            $babies =  \App\Models\BabyDetail::where('delivery_token', $previouseDelivery->delivery_token)->get();
        @endphp
        @if(count($babies)>1)
            @php $twins = "&#10004;" @endphp
        @endif
        @php
            $delivery = $data->modelDeliveryByDeliveryToken($previouseDelivery->delivery_token);
            $deliveryDate = new DateTime($delivery->delivery_date);
            $now = new DateTime(date('Y-m-d'));
            $age = $deliveryDate->diff($now)->y;
            $complex = explode(',',$delivery->complexity);
            
        @endphp
        <tr>
          <td>{{ $i }}</td>
          <td>{{$alive}}</td>
          <td>{{$death}}</td>
          <td>{{$prematureBirth}}</td>
          <td>{{$twins}}</td>
          <td></td>
          <td>{{$previouseDelivery->gender}}</td>
          <td>{{$age}}</td>
          <td>
            @if($complex[0]=="true")
                अत्यधिक रक्तश्राव,
            @endif
            @if($complex[0]=="true")
                ≥ १२ घण्टा बेथा लागेको,
            @endif
            @if($complex[1]=="true")
                साल नझरेको
            @endif
          </td>
          <td>{{$delivery->delivery_type}}</td>
        </tr>
        @php $i++; @endphp
        @endforeach
    @else
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td ></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td ></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td ></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td ></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    @endif
  </table>
    @php
    $tdRegNo = "";
    $tdFirst = $data->vaccinations()->tdVaccine()->orderBy('vaccinated_date_en')->first();
    $tdSecond = $data->vaccinations()->tdVaccine()->orderBy('vaccinated_date_en')->skip(1)->first();
    $tdThird = $data->vaccinations()->tdVaccine()->orderBy('vaccinated_date_en')->skip(2)->first();
      @endphp
  <table border="1">
    <tr style="text-align: center;">
      <td width="10%">टि.डी खोप दर्ता नं.</td>
      <td width="10%">{{ $data->vaccinations()->tdVaccine()->tdRegNo()->first()->vaccine_reg_no ?? '' }} </td>
      <td width="11%">टि.डी १ <br>(ग/म/सा)</td>
      <td width="11%">{{ isset($tdFirst) ? date('d/m/Y' , strtotime($tdFirst->vaccinated_date_np)) : '' }} </td>
      <td width="11%">टि.डी २ <br>(ग/म/सा)</td>
      <td width="11%">{{ isset($tdSecond) ? date('d/m/Y' , strtotime($tdSecond->vaccinated_date_np)) : '' }}</td>
      <td width="11%">टि.डी २+ <br>(ग/म/सा)</td>
      <td width="11%">{{ isset($tdThird) ? date('d/m/Y' , strtotime($tdThird->vaccinated_date_np)) : '' }}</td>
    </tr>
  </table>
  <table border="1">
    <tr>

      <th>कार्ड तयार गर्ने स्वास्थ्यकर्मीको</th> 

    </tr>
  </table> 
  <table border="1" style="text-align: center;">
    <tr>
      <td>नाम र थर</td>
      <td>पद</td>
      <td>दस्तखत</td>
      <td colspan="3">मिति</td>
    </tr>
    <tr>
  <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($data->created_by)}}</td>
  <td>{{ \App\Models\HealthWorker::findHealthWorkerPostByToken($data->created_by) }}</td>
  <td>{{\App\Models\HealthWorker::findHealthWorkerSignature($data->created_by)}}</td>
  <td>{{ Carbon\Carbon::parse($createdAtNep)->format('d') }}</td>
  <td>{{ Carbon\Carbon::parse($createdAtNep)->format('m') }}</td>
  <td>{{ Carbon\Carbon::parse($createdAtNep)->format('Y') }}</td>
    </tr>
  </table>
  <table style="float: left !important; width: 35%;" border="1" >
    <tr>
      <th>स्वास्थ्य संस्थाको सम्पर्क नं.</th>
    </tr>
    <tr >
      <td>{{ $data->getHealthPostInfo($data->hp_code)->phone ?? "." }}</td>
    </tr>
  </table>

  <table style="float: right !important; width: 35%;" border="1" >
    <tr >
      <th>एम्बुलेन्स सेवा सम्पर्क (फोन नं.)</th>
    </tr>
    <tr >
      <td>&nbsp;</td>
    </tr>
  </table>
</tr>
</table>
</td>
</tr>
</table>

<p style="padding-top:5px;">परिमार्जित : २०७०/७१  <span style="float: right;">Print FY:2072/73</span></p>

</div>

<div class="pagebreak"></div>
  
<!-- Page 2 begins -->

  <div style="float: right; font-size: 9.1px;">HMIS 3.5 Maternal and New Born Health Card</div>
  <div class="clearfix blank"></div>
  <table border="1" style="float: left !important; width: 45% !important;" class="tab2">
    <tr>
      <th >आखिरी रजस्वला भएको मिति (गते महिना साल)(LMP)</th>
      <td>{{ $data->getlmpDateDay($data->token) }}</td>
      <td>{{ $data->getlmpDateMonth($data->token) }}</td>
      <td>{{ $data->getlmpDateYear($data->token) }}</td>
    </tr>
  </table>

  <table border="1" style="float: right !important; width: 45% !important;" class="tab2">
    <tr>
      <th >प्रसवको अनुमानित मिति (गते महिना साल)(EDD)</th>
      <td>{{ $data->geteddDay($data->token) }}</td>
      <td>{{ $data->geteddMonth($data->token) }}</td>
      <td>{{ $data->geteddYear($data->token) }} </td>
    </tr>
  </table>

  <table border="1" class="tab2">
    <tr>
      <th>गर्भवती परिक्षण विवरण</th>
    </tr>
  </table>

 

  <table border="1" class="tab2">
    <tr>
      <td colspan="5">गर्भवती जाँच</td>
      <td rowspan="2">तौल <br/>(कि.ग्रा।)</td>
      <td colspan="2">रक्तअल्पता</td>
      <td colspan="2">सुन्निएको</td>
      <td rowspan="2">रक्तचाप </td>
      <td rowspan="2">गर्भको<br/>अवधि<br/>(हप्तामा)</td>
      <td rowspan="2">पाठेघरको<br/>उचाई <br>(से.मि.)</td>
      <td colspan="2">शिशुको</td>
      <td rowspan="2">अन्य<br/>समस्या</td>
      <td rowspan="2">उपचार/सल्लाह/अर्को<br/> पटक सेवा लिन<br/> आउने मिति</td>
{{--      <td rowspan="2">आईरन<br/> चक्की<br/> संख्या</td>--}}
{{--      <td rowspan="2">जुकाको<br/>औषधि</td>--}}
{{--      <td rowspan="2">टिडी<br/>खोप</td>--}}
      <td rowspan="2">परिक्षण गरनेको<br/>नाम र साहि</td>
      <td rowspan="2">परिक्षण<br/>गरेको<br/>संस्था</td>
    </tr>
    <tr>
      <td>पटक</td>
      <td>महिना </td>
      <td colspan="3" width="7%">मिति</td>
      <td>भएको</td>
      <td>नभएको</td>
      <td>हात</td>
      <td>मुख</td>
      <td>प्रिजेन्टेसन</td>
      <td>हृदय गति</td>
    </tr>
    @php 
    $i = 1; 
    @endphp
    @foreach($data->ancs()->orderBy('visit_date')->get() as $row)
    @php 
    $noAnemialClass = "";
    $yesAnemialClass = "";
    $handSwellingClass = "";
    $mouthSwellingClass = "";
    $ancVisitDate = array();
    $ancVisitDateNp = \App\Helpers\ViewHelper::convertEnglishToNepali($row->visit_date);
    list($ancYear, $ancMonth, $ancDay) = explode("-", $ancVisitDateNp);
    $visit_date = new DateTime($row->visit_date);
    $lmp_date = new DateTime($data->lmp_date_en);
    $differeceBetweenLmpAndAncVisit = $lmp_date->diff($visit_date)->m;
    $pregnanayDays = $lmp_date->diff($visit_date)->days;
    $pregnanayWeeks = $pregnanayDays/7;
    $pregnanayWeeks = number_format((float)$pregnanayWeeks, 0, '.', '');

    @endphp 
      @if($row->anemia=="Yes")
          @php($yesAnemialClass= "circle")
      @elseif($row->anemia=="No")
          @php($noAnemialClass= "circle")
      @endif

       @if($row->swelling=="Hand")
          @php($handSwellingClass= "circle")
      @elseif($row->swelling=="Mouth")
          @php($mouthSwellingClass= "circle")
      @elseif($row->swelling=="Both")
          @php($handSwellingClass= "circle")
          @php($mouthSwellingClass= "circle")
      @endif
    <tr>
      <td>{{ convertToNepali($i) }}</td>
      <td>{{$differeceBetweenLmpAndAncVisit}}</td>
      <td>{{$ancDay}}</td>
      <td>{{$ancMonth}}</td>
      <td>{{$ancYear}}</td>
      <td>{{ $row->weight }}</td>
      <td><div class="{{$yesAnemialClass}}">१</div></td>
      <td><div class="{{$noAnemialClass}}">२</div></td>
      <td><div class="{{$handSwellingClass}}">१</div></td>
      <td><div class="{{$mouthSwellingClass}}">२</div></td>
      <td>{{$row->blood_pressure}}</td>
      <td>{{$pregnanayWeeks}}</td>
      <td>{{$row->uterus_height}}</td>
      <td>{{$row->baby_presentation}}</td>
      <td>{{$row->baby_heart_beat}}</td>
      <td>{{$row->other}}</td>
      <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($row->next_visit_date)}}</td>
{{--      <td>{{$row->iron_pills}}</td>--}}
{{--      <td>--}}
{{--        @if($row->worm_medicine=='1')--}}
{{--          Given--}}
{{--        @else--}}
{{--          Not Given--}}
{{--        @endif--}}
{{--        </td>--}}
{{--      <td>{{\App\Helpers\ViewHelper::convertEnglishToNepali($row->td_vaccine)}}</td>--}}
      <td>{{\App\Models\HealthWorker::findHealthWorkerByToken($row->checked_by)}}</td>
      <td>{{\App\Models\Healthpost::getHealthpost($row->hp_code)}}</td>
    </tr>
    @php($i++) 
    @endforeach
    @for($i=$data->ancs()->count(); $i<7; $i++)
    <tr>
      <td>{{ convertToNepali($i+1) }}</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>१</td>
      <td>२</td>
      <td>१</td>
      <td>२</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
{{--      <td></td>--}}
{{--      <td></td>--}}
{{--      <td></td>--}}
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    @endfor
  </table>

  <table border="1" class="tab2">
    <tr>
      <th>प्रसुति सम्बन्धि बिवरण</th>
    </tr>
  </table>

  <table border="1" class="tab2">
    <tr>
      <td colspan="3">प्रसुति भएको मिति </td>
      <td colspan="2">समय</td>
      <td rowspan="2">प्रसुति भएको स्थान</td>
      <td colspan="3">प्रिजेन्टेसन</td>
      <td colspan="3">प्रसूतिको किसिम</td>
      <td colspan="5">प्रसवको जटिलता</td>
    </tr>
    <tr>
      <td>ग</td>
      <td>म</td>
      <td>सा</td>
      <td>AM</td>
      <td>PM</td>
      <td>Cephalic</td>
      <td>Breech</td>
      <td>Shoulder</td>
      <td>Normal</td>
      <td>Vacuum/Forcep</td>
      <td>C/S</td>
      <td>अत्यधिक रक्तश्राव</td>
      <td>≥ १२ घण्टा बेथा लागेको</td>
      <td>साल नझरेको</td>
      <td>अन्य समस्या भए</td>
      <td>उपचार/ सल्लाह</td>
    </tr>
    @php($modelDelivery = $data->modelDelivery($data->token))
    @php($amDelivery="")
    @php($pmDelivery="")
    @php($cephalic="")
    @php($breech="")
    @php($shoulder="")
    @php($normal="")
    @php($vacuum="")
    @php($cs="")
    @php($ComplexityI="")
    @php($ComplexityII="")
    @php($ComplexityIII="")
    @if(count($modelDelivery)>0)
      @php($deliveryDateNepali = \App\Helpers\ViewHelper::convertEnglishToNepali($modelDelivery->delivery_date))
      @php(list($deliveryYear, $deliveryMonth, $deliveryDay) = explode("-",$deliveryDateNepali))
      @php(list($deliveryHour, $deliveryMinute) = explode(":",$modelDelivery->delivery_time))

      @if($deliveryHour>12)
        @php($deliveryHour = $deliveryHour-12)
        @php($pmDelivery = $deliveryHour.":".$deliveryMinute)
      @else
        @php($amDelivery = $modelDelivery->delivery_time)
      @endif

      @if($modelDelivery->presentation=="Cephalic")
        @php($cephalic= "&#10004;")
      @elseif($modelDelivery->presentation=="Breech")
        @php($breech="&#10004;")
      @elseif($modelDelivery->presentation=="Shoulder")
        @php($shoulder= "&#10004;")
      @endif

      @if($modelDelivery->delivery_type=="Normal")
        @php($normal= "&#10004;")
      @elseif($modelDelivery->delivery_type=="Vacuum/Forcep")
        @php($vacuum="&#10004;")
      @elseif($modelDelivery->delivery_type=="C/S")
        @php($cs= "&#10004;")
      @endif

      @php(list($firstComplexity, $secondComplexity, $thirdComplexity) = explode(",",$modelDelivery->complexity))

      @if($firstComplexity=="true")
        @php($ComplexityI= "&#10004;")
      @endif
      @if($secondComplexity=="true")
        @php($ComplexityII="&#10004;")
      @endif
      @if($thirdComplexity=="true")
        @php($ComplexityIII= "&#10004;")
      @endif

    <tr>
      <td>{!! $deliveryDay !!}</td>
      <td>{!! $deliveryMonth !!}</td>
      <td>{!! $deliveryYear !!}</td>
      <td>{!! $amDelivery !!}</td>
      <td>{!! $pmDelivery !!}</td>
      <td>{!! $modelDelivery->delivery_place !!}</td>
      <td>{!! $cephalic !!}</td>
      <td>{!! $breech !!}</td>
      <td>{!! $shoulder !!}</td>
      <td>{!! $normal !!}</td>
      <td>{!! $vacuum !!}</td>
      <td>{!!$cs!!}</td>
      <td>{!!$ComplexityI!!}</td>
      <td>{!!$ComplexityII!!}</td>
      <td>{!!$ComplexityIII!!}</td>
      <td>{!!$modelDelivery->other_problem!!}</td>
      <td>{!!$modelDelivery->advice!!}</td>

    </tr>
    @else
    <tr>
      <td>&nbsp;</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
    </tr>
    @endif
  </table>

  <table border="1" style="float: left !important;width:39%; margin-right: 1%;" class="tab2">
    <tr>
      <th>प्रसूतिको परिणाम</th>
    </tr>
  </table>

  <table border="1" style="float: left !important;width:39%; margin-right: 1%;" class="tab2">
    <tr>
      <th>नवशिशुको अवस्था</th>
    </tr>
  </table>

  <table border="1" style="float: right !important; width: 20%" class="tab2">
    <tr>
      <th>प्रसूति गराउने स्वास्थ्यकर्मीको</th>
    </tr>
  </table>

  <table border="1" style="float: left !important;width:39%; margin-right: 1%;" class="tab2">
    <tr>
      <td colspan="2">जिवित जन्म संख्या</td>
      <td colspan="2">जन्म तौल (ग्राम)</td>
      <td rowspan="2">अवधि नपुगेको (३७ हप्ता) </td>
      <td colspan="2">मृत जन्म (संख्या)</td>
    </tr>

    <tr>
      <td>छोरी</td>
      <td>छोरा</td>
      <td>छोरी</td>
      <td>छोरा</td>
      <td>Fresh</td>
      <td>Macerated</td>
    </tr>
    @php($modelBaby = $data->modelBaby($data->token))
    @php($maleBabyWeight = "")
    @php($femaleBabyWeight = "")
    @php($prematureBirth = "")
    @php($maleBabyNumber = 0)
    @php($femaleBabyNumber = 0)
    @php($deadMacerated = 0)
    @php($deadFresh = 0)
    @php($statusI = "")
    @php($statusII = "")
    @php($statusIII = "")
    @php($statusIV = "")
    @if(count($modelBaby)>0)


      <!-- start modelBaby-->
      @foreach($modelBaby as $baby)
          @php($maleBabyWeight = "")
          @php($femaleBabyWeight = "")
          <!-- baby number -->
           @if($baby->gender=="Male")
              @php($maleBabyNumber++)
           @else
              @php($femaleBabyNumber++)
           @endif

          <!--baby alive-->
          @if($baby->baby_alive=="Dead-Macerated")
              @php($deadMacerated++)
          @elseif($baby->baby_alive=="Dead-Fresh")
              @php($deadFresh++)
          @endif
          <!--premature birth-->
          @if($baby->premature_birth=="Yes")
            @php($prematureBirth = "&#10004;")
          @endif

          <!--Baby Status-->
          @php(list($firstStatus, $secondStatus, $thirdStatus, $fourthStatus) = explode(",",$baby->baby_status))

          @if($firstStatus=="true")
            @php($statusI= "&#10004;")
          @endif
          @if($secondStatus=="true")
            @php($statusII="&#10004;")
          @endif
          @if($thirdStatus=="true")
            @php($statusIII= "&#10004;")
          @endif
          @if($fourthStatus=="true")
            @php($StatusIV= "&#10004;")
          @endif

      @endforeach
      <!-- end modelBaby-->

      @php($countModelBaby = count($modelBaby))

      @if($countModelBaby>1)
        @php($rowSpan = $countModelBaby)
      @else
        @php($rowSpan = "2")
      @endif

    <tr>
      <td rowspan="{{$rowSpan}}">{{ ($femaleBabyNumber > 0) ? $femaleBabyNumber : '' }}</td>
      <td rowspan="{{$rowSpan}}">{{ ($maleBabyNumber > 0) ? $maleBabyNumber : '' }}</td>
      @php($i=0)
      @foreach($modelBaby as $baby)
      @if($i==0)
       @if($baby->gender=="Male")
        @php($maleBabyWeight = $baby->weight)
       @else
        @php($femaleBabyWeight=$baby->weight)
      @endif
      <td>{{$femaleBabyWeight}}</td>
      <td>{{$maleBabyWeight}}</td>
      @endif
      @php($i++)
      @endforeach
      <td rowspan="{{$rowSpan}}">{!!$prematureBirth!!}</td>
      <td rowspan="{{$rowSpan}}">{!! ($deadMacerated > 0) ? $deadMacerated : ''!!}</td>
      <td rowspan="{{$rowSpan}}">{!! ($deadFresh > 0) ? $deadFresh : ''!!}</td>
    </tr>
    

    @php($i=0)
      @foreach($modelBaby as $baby)
      @php($maleBabyWeight = "")
      @php($femaleBabyWeight = "")
      @if($i!=0)
       @if($baby->gender=="Male")
        @php($maleBabyWeight = $baby->weight)
       @else
        @php($femaleBabyWeight=$baby->weight)
      @endif
      <tr height="33px">
        <td>{{$femaleBabyWeight}}</td>
        <td>{{$maleBabyWeight}}</td>
      </tr>
      @endif
      @php($i++)
      @endforeach

    @else
    <tr>
      <td rowspan="2">&nbsp;</td>
      <td rowspan="2"></td>
      <td>&nbsp;</td>
      <td></td>
      <td rowspan="2"></td>
      <td rowspan="2"></td>
      <td rowspan="2"></td>
    </tr>
    <tr height="33px">
      <td>&nbsp;</td>
      <td></td>
    </tr>
    @endif


  </table>

  <table border="1" style="float: left !important; width:39%; margin-right: 1%;" class="tab2">
    <tr >
      <td>स्वस्थ</td>
      <td>तुरुन्त रोएको</td>
      <td>श्वास फेर्न गाह्रो भएको</td>
      <td>विकलाङ्ग</td>
      <td>अन्य … … ... </td>
      <td>उपचार/सल्लाह</td>
    </tr>
    <tr height="90px">
      <td>{!! $statusI !!}</td>
      <td>{!! $statusII !!}</td>
      <td>{!! $statusIII !!}</td>
      <td>{!! $statusIV !!}</td>
      <td></td>
      <td>
        @if(count($modelBaby)>0)
        {{$baby->advice}}
        @endif
      </td>
    </tr>

  </table>

  <table border="1" style="float: right !important; width: 20%" height="136px" class="tab2">
    <tr>
      <td width="60px">नाम, थर</td>
      <td>
        @if(count($modelDelivery)>0)
          {{\App\Models\HealthWorker::findHealthWorkerByToken($modelDelivery->delivery_by)}}
        @endif
      </td>
    </tr>
    <tr>
      <td width="60px">पद</td>
      <td>@if(isset($modelDelivery))
            {{ \App\Models\HealthWorker::findHealthWorkerPostByToken($modelDelivery->delivery_by) }}
          @endif
      </td>
    </tr>
    <tr>
      <td width="60px">सही</td>
      <td>
        @if(count($modelDelivery)>0)
        {{\App\Models\HealthWorker::findHealthWorkerSignature($modelDelivery->delivery_by)}}
        @endif
      </td>
    </tr>
    <tr>
      <th colspan="2" style="text-align: left;">संस्थाको नाम र छाप:
       @if(count($modelDelivery)>0)
       {{\App\Models\Healthpost::getHealthpost($modelDelivery->hp_code)}}
       @endif
     </th>
    </tr>
  </table>
  <p style="clear:both;padding-top:7px;">परिमार्जित : २०७०/७१ <span style="float: right;">Print FY:2072/73</span></p>
</div><!-- /.Section to  Printable-->

