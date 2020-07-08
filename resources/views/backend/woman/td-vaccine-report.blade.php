	<div id="printable">
	<style type="text/css">
		@media print{@page {size: landscape}}
		table{
			border-collapse: collapse;
			text-align: center;
			font-size: 10px;
			width: 100%;
		}

		#table1{
			margin-bottom: 40px;
		}

		th{
			text-align : center;
			font-size : 12px;
		}

		tr,td,th{
			height: 27px;
		}

		#numrow{
			height: 10px
		}

		header{
			font-size: 10px;
			text-align: right;
			margin-bottom: 25px
		}

		p{
			font-size: 10px;
			float: right;
		}

	</style>
		<header>HMIS 2.24</header>

		<p>वार्ड नं.  {{ $ward_no or '.......' }}</p>
		
		<h1 style="text-align: center; font-size: 20px;">
			टि.डी.खोप विवरण
		</h1>

		<table border="1">
			<tr>
				<th rowspan="3" width="5%">सेवा<br/>दर्ता नं.</th>
				<th rowspan="3" width="15%">गाऊँ/टोल *</th>
				<th rowspan="3" width="15%">गर्भवति महिलाको नाम थर</th>
				<th rowspan="3" width="3%">जाति<br/>कोड</th>
				<th rowspan="3" width="3%">उमेर</th>
				<th rowspan="3" width="10%">सम्पर्क फोन नं.</th>
				<th rowspan="3" width="4%">गर्भको<br/>पटक</th>
				<th rowspan="3" width="5%">पहिले <br/>लगाएको<br/>टी.डी.खोप<br/>को मात्रा</th>
				<th colspan="9">टी.डी.खोप विवरण</th>
				<th rowspan="3" width="15%">कैफियत</th>
			</tr>
			<tr>
				<th colspan="3">१</th>
				<th colspan="3">२</th>
				<th colspan="3">२་<sup>+</sup></th>
			</tr>
			<tr>
				<th width="35px">ग</th>
				<th width="35px">म</th>
				<th width="35px">सा</th>
				<th width="35px">ग</th>
				<th width="35px">म</th>
				<th width="35px">सा</th>
				<th width="35px">ग</th>
				<th width="35px">म</th>
				<th width="35px">सा</th>
			</tr>
			<tr style="font-size: 10px; height: 7px !important;">
				<th style="height: 7px !important; width: 50px">१</th>
				<th style="height: 7px !important; width: 120px;">२</th>
				<th style="height: 7px !important; width: 120px;">३,४</th>
				<th style="height: 7px !important; width: 35px;">५</th>
				<th style="height: 7px !important; width: 35px;">६</th>
				<th style="height: 7px !important; width: 120px;">७</th>
				<th style="height: 7px !important; width: 50px;">८</th>
				<th style="height: 7px !important; width: 70px;">९</th>
				<th style="height: 7px !important; width: 35px;">१०</th>
				<th style="height: 7px !important; width: 35px;">११</th>
				<th style="height: 7px !important; width: 35px;">१२</th>
				<th style="height: 7px !important; width: 35px;">१३</th>
				<th style="height: 7px !important; width: 35px;">१४</th>
				<th style="height: 7px !important; width: 35px;">१५</th>
				<th style="height: 7px !important; width: 35px;">१६</th>
				<th style="height: 7px !important; width: 35px;">१७</th>
				<th style="height: 7px !important; width: 35px;">१८</th>
				<th style="height: 7px !important; width: 70px;">१९</th>
			@if(count($ancs)>0)
			@foreach($ancs as $anc)
				@php
					$tdInfo = App\Models\Anc::tdInformation($anc->woman_token);
					$tdFirstD = "";
					$tdFirstM = "";
					$tdFirstY = "";
					$tdSecondD = "";
					$tdSecondM = "";
					$tdSecondY = "";
					$tdThirdD = "";
					$tdThirdM = "";
					$tdThirdY = "";
					$tdFirstVaccine = explode("/",$tdInfo['tdFirst']);
					$tdSecondVaccine = explode("/",$tdInfo['tdSecond']);
					$tdThirdVaccine = explode("/",$tdInfo['tdThird']);
					if(count($tdFirstVaccine)>1){
						list($tdFirstD, $tdFirstM, $tdFirstY) = explode("/",$tdInfo['tdFirst']);
					} 
					if(count($tdSecondVaccine)>2){
						list($tdSecondD, $tdSecondM, $tdSecondY) = explode("/",$tdInfo['tdSecond']);
					}
					if(count($tdThirdVaccine)>2){
						list($tdThirdD, $tdThirdM, $tdThirdY) = explode("/",$tdInfo['tdThird']);
					}
				@endphp
				<tr>
					<td width="150px">{{$anc->sewa_darta_no}}</td>
					<td width="120px" align="left" style="padding-left:8px">{{ ucfirst($anc->tole) }}</td>
					<td width="35px" align="left" style="padding-left:8px">{{ ucwords($anc->name) }}</td>
					<td width="35px">{{$anc->caste}}</td>
					<td width="120px">{{$anc->age}}</td>
					<td width="50px">{{$anc->phone}}</td>
					<td width="70px"></td>
					<td>{{-- $tdInfo['tdVaccine'] --}}</td>
					<td>{{$tdFirstD}}</td>
					<td>{{$tdFirstM}}</td>
					<td>{{$tdFirstY}}</td>
					<td>{{$tdSecondD}}</td>
					<td>{{$tdSecondM}}</td>
					<td>{{$tdSecondY}}</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			@endforeach
			@endif
			
		</table>
	</div>