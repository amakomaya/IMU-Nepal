@extends('layouts.backend.app')

@section('content')
<style>
        .main {
    width: 90%;
    margin: 0 auto;
}

.header {
    font-family: sans-serif;
    display: flex;
    flex-direction: row;
    align-items: center;
    justify-content: space-between;
}

.date {
    color: #000;
    margin-top: 10px;
    font-size: 17px;
}

*, p {
    margin: 0;
    padding: 0;
}

.img {
    text-align: right;
}

.titleMid {
    text-align: center;
}

.titleSide {
    color: #E61C23;
    font-size: 15px;
}

.govF {
    color: #E61C23;
    font-size: 14px;
}

.govM {
    color: #E61C23;
    font-size: 17px;
}

.govB {
    color: #E61C23;
    font-weight: bolder;
    font-size: large;
}

.govA {
    color: #E61C23;
    font-size: 14px;
}


.titleHead {
    font-size: 18px;
    padding-top: 20px;
    font-weight: 700;
}

.subTitle {
    padding-top: 20px;
    font-size: 15px;
}

.typeSample {
    padding-top: 20px;
    border: 1px solid #000;
}

.noteStyle {
    display: flex;
    margin-top: 20px;
}

.footerStyle {
    margin-top: 20px;
    font-size: 14px;
}

</style>
    <div id="page-wrapper">
            <div class="row">
			<div>
        <div class="pull-right">
        	<button type="submit" class="btn btn-primary btn-lg">
                <i class="fa fa-print"> Print </i>  
            </button>
        </div>
        <div class="main" id="report-printMe">
        <div class="header">
            <div class="img">
                <img src="/images/report-logo.jpg" width="92" height="92" alt="">
            </div>

            <div class="titleMid">
                <p class="govF">Government of Nepal</p>
                <p class="govF">Ministry of Health & Population</p>
                <p class="govM">Department of Health Service</p>
                <p class="govB">{{ $phpArray["healthpost"]["name"] }}</p>
                <p class="govA">{{ $phpArray["healthpost"]["address"] }}, {{ $phpArray["healthpost"]["district_id"] }}</p>
            </div>

            <div class="titleSide">
            	<p>Phone: {{  $phpArray["healthpost"]["phone"] }}</p>
                <p class="date">Date: {{  $phpArray["healthpost"]["created_at"] }}</p>
            </div>
        </div>

    <br>
        <table class="table table-striped">
          <thead>
            <tr>
              <h4>1. Personal Information </h4>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Case ID : </td>
              <td>{{ $phpArray["case_id"] }}</td>
            </tr>
            <tr>
              <td>Name : </td>
              <td>{{ $phpArray["name"] }}</td>
            </tr>
            <tr>
                <td>Age : </td>
                <td>{{ $phpArray["age"] }} / <span> {{ $phpArray["formated_age_unit"] }} </span>
             </td>
            </tr>
            <tr>
                <td>Gender : </td>
                <td>{{ $phpArray["formated_gender"] }}
            </tr>
            <tr>
                <td>Emergency Phone : </td>
                <td>
                @if(is_null($phpArray["emergency_contact_one"]))
                @else
					One : {{ $phpArray["emergency_contact_one"] }} <br>
                @endif
                 @if(is_null($phpArray["emergency_contact_two"]))
                 @else
					One : {{ $phpArray["emergency_contact_two"] }} <br>
                @endif
            	</td>
            </tr>
            <tr>
                <td>Occupation : </td>
                <td>{{ $phpArray["occupation"] }}</td>
            </tr>
            <tr>
                <td>Address : </td>
                <td>
                	{{ $phpArray["tole"] }} - {{ $phpArray["ward"] }} <br>
                    Municipality : {{ $phpArray["municipality_id"] }}<br>
                    District : {{ $phpArray["district_id"] }}<br>
                    Province : {{ $phpArray["province_id"] }}<br>
                </td>
            </tr>
          </tbody>
          </table>
          <br>
        <h4>2. Sample Collection Information </h4>
        @foreach ($phpArray["ancs"] as $anc)
        <table class="table table-striped">
          <thead>
            <tr>
            	<h5>Sample Collected Date: {{ $anc["created_at"] }} </h5>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>SID : </td>
              <td>{{ $anc["token"] }}</td>
            </tr>
            <tr>
              <td>Sample Type : </td>
              <td>
              	@if(is_null($anc["sample_type"]))
              	@else
              		$anc["sample_type"]
              	@endif
              	@if(is_null($anc["sample_type_specific"]))
              	@else
              		/$anc["sample_type_specific"]
              	@endif
              </td>
            </tr>
            <tr>
              <td>Sample Case : </td>
              <td>
              	@if(is_null($anc["sample_case"]))
              	@else
              		$anc["sample_case"]
              	@endif
              	@if(is_null($anc["sample_case_specific"]))
              	@else
              		/$anc["sample_case_specific"]
              	@endif
              </td>
            </tr>
            <tr>
              <td>Service Type : </td>
              @if($anc["service_type"] = 1)
              <td>Free of cost</td>
              @else
              <td>Paid</td>
              @endif
            </tr>
            <tr>
              <td>Result : </td>
              <td>{{ $anc["formatted_result"] }}</td>
            </tr>
          </tbody>
        </table>
        @endforeach
    </div>    
    </div>

</div>
</div>
@endsection

