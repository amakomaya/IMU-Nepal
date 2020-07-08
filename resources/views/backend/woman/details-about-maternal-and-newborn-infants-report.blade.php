
<div id = "printable">
	<style type="text/css">
		@media print{@page {size: landscape}}
		body{margin:0;padding:0;}
		.titleHeader{text-align: center;}
		.report, .report td, .report th {border: 1px solid black; border-collapse: collapse;}
		th{font-size: 12px; padding:5px;background-color:#dfdfdf; text-align:center;}
		td{font-size: 12px;padding:6px;color:#000; text-align:center;}
		.pagebreak { page-break-before: always; } /* page-break-after works, as well */
	</style>

	@php

$j=0;
$k = 0;
$x = 0;
$mainLoopUpto = count($data)/10;
@endphp

@for ($i=0; $i <=$mainLoopUpto ; $i++)

@php
$m=0;
@endphp
<p style="text-align:right;">HMIS 4.2: FCHV Register <br>2070/71</p>
		<h3 class="titleHeader">मातृ तथा नवजात शिशु सम्बन्धि विवरण</h3>
		<table class="report">
			<tr>
				<th rowspan="2" width="1%">क्र. सं.</th>
				<th colspan="3" align="center">मिति</th>
				<th colspan="2">गर्भवती महिलाको</th>
				<th colspan="3" align="center">अन्तिम रजश्वला मिति <br>(LMP) <br>(ग.म.सा.)</th>
				<th colspan="3" align="center">प्रसुतिको अनुमानित मिति <br>(EDD) <br>(ग.म.सा.)</th>
				<th colspan="2" width="10%">जीवन सुरक्षा परामर्श दिएको*</th>
				<th colspan="6" align="center" width="20%">स्वास्थ्य संस्थामा गर्भ जाँच गरेको पटक (अाैँ महिनामा)* </th>
			</tr>
			
			<tr>
				<th>गते</th>
				<th>महिना</th>
				<th>साल</th>
				<th>नाम, थर</th>
				<th>उमेर</th>
				<th>गते</th>
				<th>महिना</th>
				<th>साल</th>
				<th>गते</th>
				<th>महिना</th>
				<th>साल</th>
				<th>छ</th>
				<th>छैन</th>
				<th>४</th>
				<th>६</th>
				<th>८</th>
				<th>९</th>
				<th>अन्य</th>
			</tr>

			<tr>
				<th>१</th>
				<th>२</th>
				<th>३</th>
				<th>४</th>
				<th>५</th>
				<th>६</th>
				<th>७</th>
				<th>८</th>
				<th>९</th>
				<th>१०</th>
				<th>११</th>
				<th>१२</th>
				<th>१३</th>
				<th>१४</th>
				<th>१५</th>
				<th>१६</th>
				<th>१७</th>
				<th>१८</th>
				<th>१९</th>
			</tr>

@foreach($data as $record)
	@if($m>=$j && $m<$j+10 )
		@php
			$x++;
			$fourthMonthAncClass = '';
			$seventhMonthClass = '';
			$eightMonthAncClass = '';
			$nineMonthAncClass = '';
			$deliveryHome = '';
			$deliveryHp = '';
		@endphp
		@if($record['fourthMonthAnc']=='1')
			@php
				$fourthMonthAncClass = 'circle';
			@endphp
		@endif
		@if($record['seventhMonth']=='1')
			@php
				$seventhMonthClass = 'circle';
			@endphp
		@endif
		@if($record['eightMonthAnc']=='1')
			@php
				$eightMonthAncClass = 'circle';
			@endphp
		@endif
		@if($record['nineMonthAnc']=='1')
			@php
				$nineMonthAncClass = 'circle';
			@endphp
		@endif
			<tr>
				<td>{{$x}}</td>
				<td>{{$record['registered_day']}}</td>
				<td>{{$record['registered_month']}}</td>
				<td>{{$record['registered_year']}}</td>
				<td style="text-align:left">{{ ucwords($record['name']) }}</td>
				<td>{{$record['age']}}</td>
				<td>{{$record['lmp_date_np_day']}}</td>
				<td>{{$record['lmp_date_np_month']}}</td>
				<td>{{$record['lmp_date_np_year']}}</td>
				<td>{{$record['edd_date_np_day']}}</td>
				<td>{{$record['edd_date_np_month']}}</td>
				<td>{{$record['edd_date_np_year']}}</td>
				<td>१</td>
				<td>२</td>
				<td><div class="{{ $fourthMonthAncClass }}">१</div></td>
				<td><div class="{{ $seventhMonthClass }}">१</div></td>
				<td><div class="{{ $eightMonthAncClass }}">१</div></td>
				<td><div class="{{ $nineMonthAncClass }}">१</div></td>
				<td>&nbsp;</td>
			</tr>
	@endif
@php
$m++;
@endphp
@endforeach
@php
$j += 10;
@endphp</table>

		<p>*उपयुक्त महलमा गोलो लगाउनुहोस् । <br>Revised :2070/71</p>

	<div class="pagebreak"> </div>
<style type="text/css">

		/* .tableDiv{clear:both;width:1170px;margin:0 auto;} */
		.titleHeader{text-align: center;}
		 .report2, .report2 td, .report2 th {border: 1px solid black; border-collapse: collapse;}
		.report2 th{font-size: 12px; padding:5px;background-color:#dfdfdf;}
		.report2 td{font-size: 12px;padding:10px;color:#000;} 
	</style>

	<div class="tableDiv">
		<table class="report2">
			<tr>
				<th colspan="4">अाइरन चक्की*</th>
				<th colspan="2" rowspan="2" align="center">सुत्केरी पश्चात भितामिन ए*</th>
				<th colspan="3" rowspan="2">प्रसुती भएकाे स्थान*</th>
				<th colspan="2" rowspan="2" align="center">शिशुको जन्म अवस्था*</th>
				<th colspan="4" align="center">स्वास्थ्यकर्मीले नवजात शिशु सँगै सुत्केरी महिलालाई जाँच गरेको*</th>
				<th colspan="2" rowspan="2">परिवार नियोजन साधन प्रयोग*</th>
				<th rowspan="3" align="center">कैफियत</th>
			</tr>
			
			<tr>
				<th colspan="2">गर्भावस्थामा १८० चक्कि</th>
				<th colspan="2">सुत्केरी पश्चात ४५ चक्कि</th>
				<th rowspan="2" width="5%">२४ घण्टा भित्र</th>
				<th rowspan="2" width="5%">तेस्रो दिन</th>
				<th rowspan="2" width="5%">साताै दिन</th>
				<th rowspan="2" width="5%">अन्य</th>
			</tr>

			<tr>
				<th width="5%">पाएको</th>
				<th width="5%">नपाएको</th>
				<th width="5%">पाएको</th>
				<th width="5%">नपाएको</th>
				<th width="5%">पाएको</th>
				<th width="5%">नपाएको</th>
				<th width="5%">घर</th>
				<th width="5%">संस्था</th>
				<th width="5%">अन्य</th>
				<th width="5%">जीवित</th>
				<th width="5%">मृत</th>
				<th width="5%">गरेको</th>
				<th width="5%">नगरेको</th>
			</tr>

			<tr>
				<th>२०</th>
				<th>२१</th>
				<th>२२</th>
				<th>२३</th>
				<th>२४</th>
				<th>२५</th>
				<th>२६</th>
				<th>२७</th>
				<th>२८</th>
				<th>२९</th>
				<th>३०</th>
				<th>३१</th>
				<th>३२</th>
				<th>३३</th>
				<th>३४</th>
				<th>३५</th>
				<th>३६</th>
				<th>३७</th>
			</tr>

@php
$m = 0;
@endphp

@foreach($data as $record)
	@if($m>=$k && $m<$k+10 )
		@php
			$deliveryHome = '';
			$deliveryHp = '';
			$babyAlive = '';
			$babyDeath = '';
			$pnc1day = '';
			$pnc3day = '';
			$pnc7day = '';
			$pncOther = '';
		@endphp

		@if($record['deliveryPlace']=='Home')
			@php
				$deliveryHome = 'circle';
			@endphp
		@endif
		@if($record['deliveryPlace']=='Health-Organization')
			@php
				$deliveryHp = 'circle';
			@endphp
		@endif
		@if($record['babyAlive']=='1')
			@php
				$babyAlive = 'circle';
			@endphp
		@endif
		@if($record['babyDeath']=='1')
			@php
				$babyDeath = 'circle';
			@endphp
		@endif
		@if($record['babyAlive']=='1')
			@php
				$babyAlive = 'circle';
			@endphp
		@endif
		@if($record['babyDeath']=='1')
			@php
				$babyDeath = 'circle';
			@endphp
		@endif
		@if($record['pnc1day']=='1')
			@php
				$pnc1day = 'circle';
			@endphp
		@endif
		@if($record['pnc3day']=='1')
			@php
				$pnc3day = 'circle';
			@endphp
		@endif
		@if($record['pnc7day']=='1')
			@php
				$pnc7day = 'circle';
			@endphp
		@endif
		@if($record['pncOther']=='1')
			@php
				$pncOther = 'circle';
			@endphp
		@endif
			<tr>
				<td align="center">१</td>
				<td align="center">२</td>
				<td align="center">१</td>
				<td align="center">२</td>
				<td align="center">१</td>
				<td align="center">२</td>
				<td align="center"><div class ="{{$deliveryHome}}">१</div></td>
				<td align="center"><div class ="{{$deliveryHp}}">२</div></td>
				<td align="center">&nbsp;</td>
				<td align="center"><div class ="{{ $deliveryHome}}">१</div></td>
				<td align="center"><div class ="{{ $babyDeath }}">२</div></td>
				<td align="center"><div class ="{{ $pnc1day }}">१</div></td>
				<td align="center"><div class ="{{ $pnc3day }}">२</div></td>
				<td align="center"><div class ="{{ $pnc7day }}">३</div></td>
				<td align="center"><div class ="{{ $pncOther }}">४</div></td>
				<td align="center">१</td>
				<td align="center">२</td>
				<td align="center">&nbsp;</td>
			</tr>
	@endif
@php
$m++;
@endphp
@endforeach
@php
$k +=10;
@endphp

</table>

<p>*उपयुक्त महलमा गोलो लगाउनुहोस् । </p>
 <div class="pagebreak"> </div>
@endfor

</div>

