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

    </style>
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
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
                                    {{--                            <p class="govB">{{ $data->healthpost->name }}</p>--}}
                                    {{--                            <p class="govA">{{ $data->healthpost->address }}--}}
                                    {{--                                , {{ $data->healthpost->district_id }}</p>--}}
                                </div>

                                <div class="titleSide">
                                    {{--                            <p>Phone: {{  $data->healthpost->phone }}</p>--}}
                                    {{--                            <p class="date">Date: {{  $data->healthpost->created_at }}</p>--}}
                                </div>
                            </div>
                            <br>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <h4>Organization Information </h4>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>Organization Type:</td>
                                    <td>
                                        @if($data->organization_type === '1')
                                            Government
                                        @elseif($data->organization_type === '2')
                                            Non-profit
                                        @elseif($data->organization_type === '3')
                                            Private
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Organization Name :</td>
                                    <td>{{ $data->organization_name }}</td>
                                </tr>
                                <tr>
                                    <td>Organization Phone No. :</td>
                                    <td>{{ $data->organization_phn }}</td>
                                </tr>
                                <tr>
                                    <td>Organization Address :</td>
                                    <td>{{ $data->organization_address }}</td>
                                </tr>
                                <tr>
                                    <td>Designation(Post) :</td>
                                    <td>{{ $data->designation}}</td>
                                </tr>
                                <tr>
                                    <td>Level :</td>
                                    <td>{{ $data->level}}</td>
                                </tr>
                                <tr>
                                    <td>Service Date[Y/MM] :</td>
                                    <td>{{ $data->service_date}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <hr>
                            <h4>Personal Information </h4>
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td>Full Name :</td>
                                    <td>{{ $data->name }}</td>
                                </tr>
                                <tr>
                                    <td>Gender :</td>
                                    <td>
                                        @if($data->gender === '1')
                                            Male
                                        @elseif($data->gender === '2')
                                            Female
                                        @elseif($data->gender === '3')
                                            Other
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Age :</td>
                                    <td>{{ $data->age}}</td>
                                </tr>
                                <tr>
                                    <td>Contact Number :</td>
                                    <td>{{ $data->phone}}</td>
                                </tr>
                                <tr>
                                    <td>Current Address :</td>
                                    <td>{{ $data->province_id}}<br>
                                        {{ $data->district_id}}<br>
                                        {{ $data->municipality_id}}<br>
                                        {{ $data->ward}}<br>
                                        {{ $data->tole}}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Permanent Address :</td>
                                    <td>{{ $data->perm_province_id}}<br>
                                        {{ $data->perm_district_id}}<br>
                                        {{ $data->perm_municipality_id}}<br>
                                        {{ $data->perm_ward}}<br>
                                        {{ $data->perm_tole}}</td>
                                </tr>
                                <tr>
                                    <td>Citizenship/Password No. :</td>
                                    <td>{{ $data->citizenship_no}}</td>
                                </tr>
                                <tr>
                                    <td>Issue authority/District :</td>
                                    <td>{{ $data->issue_district}}</td>
                                </tr>
                                </tbody>
                            </table>
                            <hr>
                            <h4>Health Related Information </h4>
                            <table class="table table-striped">
                                <tbody>
                                <tr>
                                    <td>Allergies :</td>
                                    <td>{{ $data->allergies }}</td>
                                </tr>
                                <tr>
                                    <td>COVID status :</td>
                                    <td>{{ $data->covid_status }}</td>
                                </tr>
                                <tr>
                                    <td>Health/disease :</td>
                                    <td>
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '1'))--}}
                                        {{--                                    DIABETES--}}
                                        {{--                                @endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '2'))--}}
                                        {{--                                    HTN--}}
                                        {{--                                @endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '3))--}}
                                        {{--                                    HERMODIALYSIS@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '4'))--}}
                                        {{--                                    IMMUNOCOMPROMISED@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '5'))--}}
                                        {{--                                    PREGNANCY@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '6'))--}}
                                        {{--                                    MATERNITY@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '7'))--}}
                                        {{--                                    HEART_DISEASE@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '8'))--}}
                                        {{--                                    LIVER_DISEASE@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '9'))--}}
                                        {{--                                    NERVE_DISEASE@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '10'))--}}
                                        {{--                                    KIDNEY_DISEASE@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '11'))--}}
                                        {{--                                    MALNUTRITION@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '12'))--}}
                                        {{--                                    AUTO_IMMUNE_DISEASE@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '13'))--}}
                                        {{--                                    IMMUNODEFICIENCY@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '14'))--}}
                                        {{--                                    MALIGNNACY@endif--}}
                                        {{--                                @if(Illuminate\Support\Str::contains($data->disease, '15'))--}}
                                        {{--                                    CHORNIC_LUNG_ILLNESS--}}
                                        {{--                                @endif--}}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection