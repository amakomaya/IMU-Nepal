@extends('layouts.backend.app')

@section('content')
    <style>
        table{
            width: 100%;
        }
        th, td{
            padding: 1em;
        }

        .patients-today{
            margin-top: 1em;
        }

        .b-color{
            background-color: #dfdfdf;
        }
    </style>
    <div id="page-wrapper">
        <div class="row">
            <form method="get" action="{{ route('cases.payment.report') }}">
                <div class="form-group col-lg-6">
                    <label for="hospital_register_id">Period</label>
                    <input type="date" name="selected_date" class="form-control" value="{{ app('request')->input('selected_date') ?? date('Y-m-d') }}" min="2021-05-01"  max="{{ date('Y-m-d') }}"/><br>
                    <button type="submit" class="btn btn-success pull-right">Get Report</button>
                </div>
            </form>
        </div>
        @if (Session::get('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif
        @if (Session::get('success'))
            <div class="alert alert-success">
                {{ Session::get('success') }}
                <br>
                कृपया डाटा हेर्न HMIS ( DHIS 2 ) मा जानुहोस
            </div>
        @endif
        <hr style="height:2px;border-width:0;color:gray;background-color:gray">
        <form class="form-inline" method="post" action="{{ route('cases.payment.report-send') }}" onsubmit="syncCasePayment()">
            @csrf
            <div>
                <input type="text" name="period" value="{{ $period }}" hidden>
                @foreach($data as $key => $value)
                    <input type="text" name="{{$key}}" value="{{ $value }}" hidden>
                @endforeach
            </div>
        <div class="row">
            <div class="col-lg-12">
            <table border="1" cellpadding="10">
        <tr>
            <th class="b-color text-center" width="25%" colspan="5">1. Total beds allocated for COVID-19</th>

        </tr>
        <tr>
            <td width="15%">General (A)</td>
            <td width="15%">HDU-Moderate (B)</td>
            <td width="15%">ICU (C)</td>
            <td width="15%">Ventilator (among ICU)</td>
            <td class="b-color" width="15%">Total (A+B+C)</td>
        </tr>
        <tr>
            <td><input type="number" id="no_of_beds" name="no_of_beds" value="{{ $data['total_beds_allocated_general'] }}"></td>
            <td><input type="number" id="no_of_hdu" name="no_of_hdu" value="{{ $data['total_beds_allocated_hdu'] }}"></td>
            <td><input type="number" id="no_of_icu" name="no_of_icu" value="{{ $data['total_beds_allocated_icu'] }}"></td>
            <td><input type="number" name="no_of_ventilators" value="{{ $data['total_beds_allocated_ventilators_among_icu'] }}"></td>
{{--            <td ><input class="b-color" type="text" value="{{ $data['total_beds_allocated_general'] + $data['total_beds_allocated_icu'] }}" readonly></td>--}}
            <td ><input class="b-color" id="total_a_sum_b" type="text" value="" readonly></td>
        </tr>

    </table>
                <table border="1" cellpadding="10">
                    <tr>
                        <th class="b-color" width="30%" rowspan="4">Oxygen Facility</th>
                    </tr>
                    <tr>
                        @if(auth()->user()->role === 'healthpost' || auth()->user()->role === 'healthworker')
                        <td width="17.5%">Available <br>
                            <input type="radio" id="is_oxygen_facility_available" name="is_oxygen_facility" value="1" @if ($data['is_oxygen_facility'] == 1) checked @endif>
                        </td>
                        <td width="17.5%">Partially Available <br>
                            <input type="radio" id="is_oxygen_facility_partially_available" name="is_oxygen_facility" value="2" @if ($data['is_oxygen_facility'] == 2) checked @endif>
                        </td>
                        <td width="17.5%">Not Available <br>
                            <input type="radio" id="is_oxygen_facility_not_available" name="is_oxygen_facility" value="3" @if ($data['is_oxygen_facility'] == 3) checked @endif>
                        </td>
                            <td id="is_oxygen_facility_td" class="b-color" width="17.5%">Daily Consumption of Oxygen ( in liter )<br>
                                <input type="number" name="daily_consumption_of_oxygen" value="{{ $data['total_daily_consumption_of_oxygen'] }}">
                            </td>
                        @else
                            <td class="b-color" width="17.5%">Daily Consumption of Oxygen ( Cylinder in liter )<br>
                                <input type="number" value="{{ $data['total_daily_consumption_of_oxygen'] }}">
                            </td>
                        @endif
                    </tr>
                </table>
                <div>
{{--        <a href="{{ url('/admin/profile') }}">Update Total beds allocated for COVID-19</a>--}}
    </div>
    <table class="patients-today" border="1" cellpadding="10">
        <tr>
            <th class="b-color" width="70%"></th>
            <th class="b-color" width="15%">Total</th>
            <th class="b-color" width="15%">Free Treatment</th>
        </tr>

        <tr>
            <td class="b-color"><b>2. Total number of COVID-19 patients today</b></td>
            <td><input type="text"
                       value="{{
                                    $data['total_patients_without_symptoms'] +
                                    $data['total_patients_with_mild_symptoms'] +
                                    $data['total_patients_with_moderate_symptoms'] +
                                    $data['total_patients_with_severe_symptoms_in_icu'] +
                                    $data['total_patients_with_severe_symptoms_in_ventilator']
                        }}" readonly></td>
            <td><input type="text"
                       value="{{
                                    $data['free_patients_without_symptoms'] +
                                    $data['free_patients_with_mild_symptoms'] +
                                    $data['free_patients_with_moderate_symptoms'] +
                                    $data['free_patients_with_severe_symptoms_in_icu'] +
                                    $data['free_patients_with_severe_symptoms_in_ventilator']
                        }}" readonly></td>
        </tr>

        <tr>
            <td class="b-color"><b>a. Patients without symptoms</b></td>
            <td><input type="text" value="{{ $data['total_patients_without_symptoms'] }}" readonly></td>
            <td><input type="text" value="{{ $data['free_patients_without_symptoms'] }}" readonly></td>
        </tr>

        <tr>
            <td class="b-color"><b>b. Patients with mild symptoms</b></td>
            <td><input type="text" value="{{ $data['total_patients_with_mild_symptoms'] }}" readonly></td>
            <td><input type="text" value="{{ $data['free_patients_with_mild_symptoms'] }}" readonly></td>
        </tr>

        <tr>
            <td class="b-color"><b>c. Patients with moderate symptoms</b></td>
            <td><input type="text" value="{{ $data['total_patients_with_moderate_symptoms'] }}" readonly></td>
            <td><input type="text" value="{{ $data['free_patients_with_moderate_symptoms'] }}" readonly></td>
        </tr>

        <tr>
            <td class="b-color"><b>d. Patients with severe symptoms in ICU(other than ventilator)</b></td>
            <td><input type="text" value="{{ $data['total_patients_with_severe_symptoms_in_icu'] }}" readonly></td>
            <td><input type="text" value="{{ $data['free_patients_with_severe_symptoms_in_icu'] }}" readonly></td>
        </tr>

        <tr>
            <td class="b-color"><b>e. Patients with severe symptoms in ventilator</b></td>
            <td><input type="text" value="{{ $data['total_patients_with_severe_symptoms_in_ventilator'] }}" readonly></td>
            <td><input type="text" value="{{ $data['free_patients_with_severe_symptoms_in_ventilator'] }}" readonly></td>
        </tr>

    </table>

    <table class="patients-today" border="1" cellpadding="10">
        <tr>
            <th class="b-color" width="70%">3. Number of patients registered today</th>
            <th width="15%">Total</th>
            <th width="15%">Free Treatment</th>
        </tr>

        <tr>
            <td class="b-color"><b>a. Admission</b></td>
            <td><input type="text" value="{{ $data['total_admissions'] }}" readonly></td>
            <td><input type="text" value="{{ $data['free_admissions'] }}" readonly></td>
        </tr>

        <tr>
            <td class="b-color"><b>b. Discharge</b></td>
            <td><input type="text" value="{{ $data['total_discharge'] }}" readonly></td>
            <td><input type="text" value="{{ $data['free_discharge'] }}" readonly></td>
        </tr>
        <tr>
            <td class="b-color"><b>c. Death</b></td>
            <td><input type="text" value="{{ $data['total_deaths'] }}" readonly></td>
            <td><input type="text" value="{{ $data['free_deaths'] }}" readonly></td>
        </tr>
    </table>
            </div>
        </div>
        <br>
            @if(auth()->user()->role === 'healthpost' || auth()->user()->role === 'healthworker')

            <div class="panel panel-primary">
{{--            <div class="panel-heading text-center">* HMIS ( DHIS2 )  मा डाटा पठाउन, Confirm & Send बटनमा थिच्नुहोस्।</div>--}}
            <div class="panel-body">

                    <div class="form-row">
                        <div class="col-md-6 pull-right text-center">
                            <button type="submit" id="syncDhis2" class="btn btn-info btn-block" onclick="if (!confirm('Are you sure, Do you want to send data to HMIS ( DHIS2 ) ?')) { return false }"><i class="fa fa-paper-plane" aria-hidden="true"></i>
                                Confirm & Send</button>
                            <div class="text-info">* HMIS ( DHIS2 )  मा डाटा पठाउन, Confirm & Send बटनमा थिच्नुहोस्।</div>
                        </div>
                    </div>
            </div>
        </div>
            @endif
        </form>

    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function(){
            var no_of_beds=$("#no_of_beds");
            var no_of_icu=$("#no_of_icu");
            var no_of_hdu=$("#no_of_hdu");
            // qty.keyup(function(){
            //     var total=isNaN(parseInt(no_of_icu.val() + no_of_beds.val())) ? 0 :(no_of_icu.val() +    no_of_beds.val())
                $("#total_a_sum_b").val(parseInt(no_of_icu.val()) + parseInt(no_of_beds.val()) + parseInt(no_of_hdu.val()));
            // });
            $("input").on("change", function() {
                $("#total_a_sum_b").val(parseInt(no_of_icu.val()) + parseInt(no_of_beds.val()) + parseInt(no_of_hdu.val()));
            })

            var is_oxygen_facility = $("input[name=is_oxygen_facility]").val();

            if (is_oxygen_facility == "3" || is_oxygen_facility == ""){
                $("#is_oxygen_facility_td").hide();
            }

            $("input[name$='is_oxygen_facility']").click(function() {
                var is_oxygen_facility = $(this).val();
                if (is_oxygen_facility == "3") {
                    $("#is_oxygen_facility_td").hide();
                }else{
                    $("#is_oxygen_facility_td").show();

                }
            });
        });

        function syncCasePayment() {
          $("#syncDhis2").prop('disabled', true);
          $("#syncDhis2").html("Sending to HMIS (DHIS2)...");
          return false;
        }
    </script>
@endsection