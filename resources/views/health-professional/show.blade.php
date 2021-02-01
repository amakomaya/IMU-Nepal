@extends('layouts.backend.app')
@section('content')
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h2>Immunization Card</h2>
                                @include('health-professional.card')
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
                                <td id="diseases">
                                    @if(Illuminate\Support\Str::contains($data->disease, 1))
                                        DIABETES<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 2))
                                        HTN<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 3))
                                        HERMODIALYSIS<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 4))
                                        IMMUNOCOMPROMISED<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 5))
                                        PREGNANCY<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 6))
                                        MATERNITY<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 7))
                                        HEART_DISEASE<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 8))
                                        LIVER_DISEASE<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 9))
                                        NERVE_DISEASE<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 10))
                                        KIDNEY_DISEASE<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 11))
                                        MALNUTRITION<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 12))
                                        AUTO_IMMUNE_DISEASE<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 13))
                                        IMMUNODEFICIENCY<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 14))
                                        MALIGNNACY<br>
                                    @endif
                                    @if(Illuminate\Support\Str::contains($data->disease, 15))
                                        CHORNIC_LUNG_ILLNESS<br>
                                    @endif
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