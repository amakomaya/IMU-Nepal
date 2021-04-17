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
                    <input type="date" name="selected_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" /><br>
                    <button type="submit" class="btn btn-success pull-right">Get Report</button>
                </div>
            </form>

        </div>

        <hr style="height:2px;border-width:0;color:gray;background-color:gray">

        <div class="row">
            <div class="col-lg-12">
            <table border="1" cellpadding="10">
        <tr>
            <th class="b-color" width="30%" rowspan="4">1. Total beds allocated for COVID-19</th>

        </tr>
        <tr>
            <td width="17.5%">General (A)</td>
            <td width="17.5%">ICU (B)</td>
            <td width="17.5%">Ventilator (among ICU)</td>
            <td class="b-color" width="17.5%">Total (A+B)</td>
        </tr>
        <tr>
            <td><input type="text" value="{{ $data['total_beds_allocated_general'] }}"></td>
            <td><input type="text" value="{{ $data['total_beds_allocated_icu'] }}"></td>
            <td><input type="text" value="{{ $data['total_beds_allocated_ventilators_among_icu'] }}"></td>
            <td ><input class="b-color" type="text" value="{{ $data['total_beds_allocated_general'] + $data['total_beds_allocated_icu'] }}"></td>
        </tr>

    </table>

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
                        }}"></td>
            <td><input type="text"
                       value="{{
                                    $data['free_patients_without_symptoms'] +
                                    $data['free_patients_with_mild_symptoms'] +
                                    $data['free_patients_with_moderate_symptoms'] +
                                    $data['free_patients_with_severe_symptoms_in_icu'] +
                                    $data['free_patients_with_severe_symptoms_in_ventilator']
                        }}"></td>
        </tr>

        <tr>
            <td class="b-color"><b>a. Patients without symptoms</b></td>
            <td><input type="text" value="{{ $data['total_patients_without_symptoms'] }}"></td>
            <td><input type="text" value="{{ $data['free_patients_without_symptoms'] }}"></td>
        </tr>

        <tr>
            <td class="b-color"><b>b. Patients with mild symptoms</b></td>
            <td><input type="text" value="{{ $data['total_patients_with_mild_symptoms'] }}"></td>
            <td><input type="text" value="{{ $data['free_patients_with_mild_symptoms'] }}"></td>
        </tr>

        <tr>
            <td class="b-color"><b>c. Patients with moderate symptoms</b></td>
            <td><input type="text" value="{{ $data['total_patients_with_moderate_symptoms'] }}"></td>
            <td><input type="text" value="{{ $data['free_patients_with_moderate_symptoms'] }}"></td>
        </tr>

        <tr>
            <td class="b-color"><b>d. Patients with severe symptoms in ICU(other than ventilator)</b></td>
            <td><input type="text" value="{{ $data['total_patients_with_severe_symptoms_in_icu'] }}"></td>
            <td><input type="text" value="{{ $data['free_patients_with_severe_symptoms_in_icu'] }}"></td>
        </tr>

        <tr>
            <td class="b-color"><b>e. Patients with severe symptoms in ventilator</b></td>
            <td><input type="text" value="{{ $data['total_patients_with_severe_symptoms_in_ventilator'] }}"></td>
            <td><input type="text" value="{{ $data['free_patients_with_severe_symptoms_in_ventilator'] }}"></td>
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
            <td><input type="text" value="{{ $data['total_admissions'] }}"></td>
            <td><input type="text" value="{{ $data['free_admissions'] }}"></td>
        </tr>

        <tr>
            <td class="b-color"><b>b. Discharge</b></td>
            <td><input type="text" value="{{ $data['total_discharge'] }}"></td>
            <td><input type="text" value="{{ $data['free_discharge'] }}"></td>
        </tr>

        <tr>
            <td class="b-color"><b>c. Death</b></td>
            <td><input type="text" value="{{ $data['total_deaths'] }}"></td>
            <td><input type="text" value="{{ $data['free_deaths'] }}"></td>
        </tr>

    </table>

            </div>
        </div>
    </div>
@endsection