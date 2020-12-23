@extends('layouts.backend.app')
{{--@section('style')--}}
{{--    <link rel="stylesheet" href="{{ mix('css/app.css') }}">--}}
{{--@endsection--}}
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
                            <p class="govB">{{ $data->healthpost->name }}</p>
                            <p class="govA">{{ $data->healthpost->address }}
                                , {{ $data->healthpost->district_id }}</p>
                        </div>

                        <div class="titleSide">
                            <p>Phone: {{  $data->healthpost->phone }}</p>
                            <p class="date">Date: {{  $data->healthpost->created_at }}</p>
                        </div>
                    </div>

                    <br>
                    {{--                        @if(auth()->user()->role == 'main')--}}
                    {{--                            <div class="pull-right">--}}
                    {{--                                <button type="submit" class="btn btn-primary btn-lg"--}}
                    {{--                                        onclick="window.location='{{ url("/admin/patient/$data->token/edit") }}'">--}}
                    {{--                                    <i class="fa fa-edit"> Edit</i>--}}
                    {{--                                </button>--}}
                    {{--                            </div>--}}
                    {{--                        @endif--}}
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <h4>1. Personal Information </h4>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Case ID :</td>
                            <td>{{ $data->case_id }}</td>
                        </tr>
                        <tr>
                            <td>Name :</td>
                            <td>{{ $data->name }}</td>
                        </tr>
                        <tr>
                            <td>Age :</td>
                            <td>{{ $data->age }} / <span> {{ $data->formated_age_unit }} </span>
                            </td>
                        </tr>
                        <tr>
                            <td>Gender :</td>
                            <td>{{ $data->formated_gender }}
                        </tr>
                        <tr>
                            <td>Emergency Phone :</td>
                            <td>
                                @if(is_null($data->emergency_contact_one))
                                @else
                                    One : {{ $data->emergency_contact_one }} <br>
                                @endif
                                @if(is_null($data->emergency_contact_two))
                                @else
                                    One : {{ $data->emergency_contact_two }} <br>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Occupation :</td>
                            <td>
                                @if($data->occupation ==1)
                                    Front Line
                                @elseif($data->occupation == 2)
                                    Doctor
                                @elseif($data->occupation == 3)
                                    Nurse
                                @elseif($data->occupation == 4)
                                    Police/Army
                                @elseif($data->occupation == 5)
                                    Business/Industry
                                @elseif($data->occupation == 6)
                                    Teacher/Student/Education
                                @elseif($data->occupation == 7)
                                    Journalist
                                @elseif($data->occupation == 8)
                                    Agriculture
                                @elseif($data->occupation == 9)
                                    Transport/Delivery
                                @elseif($data->occupation == 10)
                                    Other
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Address :</td>
                            <td>
                                {{ $data->tole }} - {{ $data->ward }} <br>
                                Municipality : {{ $data->municipality_id }}<br>
                                District : {{ $data->district_id }}<br>
                                Province : @if($data->province_id == 1)
                                    Province 1
                                @elseif($data->province_id == 2)
                                    Province 2
                                @elseif($data->province_id == 3)
                                    Bagmati Pradesh
                                @elseif($data->province_id == 4)
                                    Gandaki Pradesh
                                @elseif($data->province_id == 5)
                                    Lumbini Pradesh
                                @elseif($data->province_id == 6)
                                    Karnali Pradesh
                                @elseif($data->province_id == 7)
                                    Sudurpashchim Pradesh
                                @endif
                                <br>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <br>
                    @if(!$data->ancs->isEmpty())
                        <h4>2. Sample Collection Information </h4>
                    @endif
                    @foreach ($data->ancs as $anc)
                        {{--                            @if(auth()->user()->role == 'main')--}}
                        {{--                            <div class="pull-right">--}}
                        {{--                                <button type="submit" class="btn btn-primary btn-lg"--}}
                        {{--                                onclick="window.location='{{ url("admin/sample/$anc->token/edit") }}'">--}}
                        {{--                                    <i class="fa fa-edit"> Edit</i>--}}
                        {{--                                </button>--}}
                        {{--                            </div>--}}
                        {{--                            @endif--}}
                        <div>
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <h5>Sample Collected Date: {{ $anc->created_at }} </h5>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>SID :</td>
                                    <td>{{ $anc->token }}</td>
                                </tr>
                                <tr>
                                    <td>Sample Type :</td>
                                    <td>
                                        @if(is_null($anc->sample_type))
                                        @else
                                            @if($anc->sample_type = 1)
                                                Nasopharyngeal
                                            @elseif($anc->sample_type = 2)
                                                Oropharyngeal
                                            @endif
                                        @endif
                                        @if(is_null($anc->sample_type_specific))
                                        @else
                                            /{{ $anc->sample_type_specific }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Service Type :</td>
                                    @if($anc->service_type = 1)
                                        <td>Paid</td>
                                    @else
                                        <td>Free of cost service</td>
                                    @endif
                                </tr>
                                <tr>
                                    <td>Result :</td>
                                    <td>{{ $anc->formatted_result }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
    {{--    <div id="page-wrapper">--}}
    {{--        <div class="row">--}}
    {{--            <div class="col-lg-12">--}}
    {{--                <div id="app">--}}
    {{--                    <case-detail></case-detail>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
    <script type="text/javascript">
        function getProvinceName(id) {
            $.get("{{ route("admin.district-value") }}?id=" + id, function (data) {
                console.log(data);
            })
        }
    </script>
@endsection
