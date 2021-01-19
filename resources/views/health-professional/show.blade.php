@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h2>Immunization Card</h2>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary" onclick="printDiv('report-printable')">
                                <i class="fa fa-print"></i> Print
                            </button>
                        </div>
                        <div class="container-rep">
                            <div class="main" id="report-printable">
                                <style>
                                    /* Register Number text */
                                    .reg-number-org-rep {
                                        position: absolute;
                                        top: 200px;
                                        left: 96px;
                                    }

                                    /* Name text */
                                    .name-org-rep {
                                        position: absolute;
                                        top: 247px;
                                        left: 96px;
                                    }

                                    /* Name text */
                                    .municipality-org-rep {
                                        position: absolute;
                                        top: 270px;
                                        left: 96px;
                                    }

                                    /* Age text */
                                    .age-org-rep {
                                        position: absolute;
                                        top: 245px;
                                        left: 365px;
                                    }

                                    /* Phone text */
                                    .phone-org-rep {
                                        position: absolute;
                                        top: 300px;
                                        left: 96px;
                                    }

                                    /* Ward text */
                                    .ward-org-rep {
                                        position: absolute;
                                        top: 270px;
                                        left: 365px;
                                    }

                                    /* Personal */
                                    /* Register Number text */
                                    .reg-number-per-rep {
                                        position: absolute;
                                        top: 200px;
                                        left: 530px;
                                    }

                                    /* Name text */
                                    .name-per-rep {
                                        position: absolute;
                                        top: 247px;
                                        left: 530px;
                                    }

                                    /* Age text */
                                    .age-per-rep {
                                        position: absolute;
                                        top: 247px;
                                        left: 790px;
                                    }

                                    /* Phone text */
                                    .phone-per-rep {
                                        position: absolute;
                                        top: 300px;
                                        left: 530px;
                                    }

                                    /* Ward text */
                                    .ward-per-rep {
                                        position: absolute;
                                        top: 274px;
                                        left: 790px;
                                    }

                                    /* Ward text */
                                    .municipality-per-rep {
                                        position: absolute;
                                        top: 274px;
                                        left: 530px;
                                    }

                                    .container-rep {
                                        position: relative;
                                    }

                                </style>

                                <img src="{{ asset('images/immunization-card.jpg') }}" alt="Immunization Card" class="img-thumbnail">
                                <div class="reg-number-org-rep">{{ str_pad($data->id, 6, "0", STR_PAD_LEFT) }}</div>
                                <div class="name-org-rep">{{ $data->name }}</div>
                                <div class="municipality-org-rep">{{ $data->municipality->municipality_name ?? '' }}</div>
                                <div class="age-org-rep">{{ $data->age }}</div>
                                <div class="ward-org-rep">{{ $data->ward }}</div>
                                <div class="phone-org-rep">{{ $data->phone }}</div>

                                <div class="reg-number-per-rep">{{ str_pad($data->id, 6, "0", STR_PAD_LEFT) }}</div>
                                <div class="name-per-rep">{{ $data->name }}</div>
                                <div class="municipality-per-rep">{{ $data->municipality->municipality_name ?? '' }}</div>
                                <div class="age-per-rep">{{ $data->age }}</div>
                                <div class="ward-per-rep">{{ $data->ward }}</div>
                                <div class="phone-per-rep">{{ $data->phone }}</div>
                            </div>
                        </div>
                        <hr>
                        <h2>Details:</h2>
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
                                <td>Serial Number / Unique ID / Register Number</td>
                                <td>{{ str_pad($data->id, 6, "0", STR_PAD_LEFT) }}</td>
                            </tr>
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
                                <td>
                                    {{ $data->province->province_name ?? ''}}<br>
                                    {{ $data->district->district_name ?? ''}}<br>
                                    {{ $data->municipality->municipality_name ?? ''}}<br>
                                    {{ $data->ward}}<br>
                                    {{ $data->tole}}
                                </td>
                            </tr>
                            <tr>
                                <td>Permanent Address :</td>
                                <td>
                                    {{ $data->province->province_name ?? ''}}<br>
                                    {{ $data->district->district_name ?? ''}}<br>
                                    {{ $data->municipality->municipality_name ?? ''}}<br>
                                    {{ $data->perm_ward}}<br>
                                    {{ $data->perm_tole}}</td>
                            </tr>
                            <tr>
                                <td>Citizenship/Passport No/Organization No/License No :</td>
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
@endsection