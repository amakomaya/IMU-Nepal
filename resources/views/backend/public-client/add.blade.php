<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css">
    <!-- jQuery -->
    <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('js/pace.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">
    <style>
        .error {
            color: red;
            font-weight: 400;
            display: block;
            padding: 6px 0;
            font-size: 14px;
        }

        .form-control.error {
            border-color: red;
            padding: .375rem .75rem;
        }
    </style>
</head>
<body>
<div class="container">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3>Personal Detail for Covi Shield Vaccination</h3>
                    </div>
                    <!-- /.panel-heading -->
                    {!! rcForm::open('POST', route('public-client.store'),['name' => 'create']) !!}
                    <div class="panel-body">
                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{ session()->get('message') }}
                            </div>
                        @endif
                        <form name="create">
                            <div class="form-group"> <!-- Full Name -->
                                <label for="full_name" class="control-label">Full Name</label>
                                <input type="text" class="form-control" id="full_name" name="full_name"
                                       placeholder="Full Name">
                            </div>

                            <div class="form-group">
                                <label for="caste" class="control-label">Caste</label>
                                <select class="form-control" id="caste" name="caste">
                                    <option {{ old('caste') == '6' ? "selected" : "" }} value="6">Don't Know</option>
                                    <option {{ old('caste') == '0' ? "selected" : "" }} value="0">Dalit</option>
                                    <option {{ old('caste') == '1' ? "selected" : "" }} value="1">Janajati</option>
                                    <option {{ old('caste') == '2' ? "selected" : "" }} value="2">Madheshi</option>
                                    <option {{ old('caste') == '3' ? "selected" : "" }} value="3">Muslim</option>
                                    <option {{ old('caste') == '4' ? "selected" : "" }} value="4">Brahmin/Chhetrai
                                    </option>
                                    <option {{ old('caste') == '5' ? "selected" : "" }} value="5">Other</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="gender" class="control-label">Gender</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Select your gender</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="date_of_birth" class="control-label">Date Of Birth</label>
                                <input type="date" class="form-control" id="date_of_birth" placeholder="Enter from date"
                                       name="date_of_birth" value="<?php echo date('Y-m-d'); ?>"
                                       max="<?php echo date('Y-m-d'); ?>"
                                       onchange="calculateAge()" required>
                            </div>

                            <div class="form-group">
                                <label for="age" class="control-label">Age</label>
                                <input type="text" class="form-control" id="age" name="age"
                                       placeholder="Age">
                            </div>

                            <div class="form-group">
                                <label for="phone" class="control-label">Mobile No.</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                       placeholder="Mobile No.">
                            </div>

                            <div class="form-group">
                                <label for="nationality" class="control-label">Nationality</label>
                                <select class="form-control" id="nationality" name="nationality">
                                    <option value="Afganistan">Afghanistan</option>
                                    <option value="Albania">Albania</option>
                                    <option value="Algeria">Algeria</option>
                                    <option value="American Samoa">American Samoa</option>
                                    <option value="Andorra">Andorra</option>
                                    <option value="Angola">Angola</option>
                                    <option value="Anguilla">Anguilla</option>
                                    <option value="Antigua & Barbuda">Antigua & Barbuda</option>
                                    <option value="Argentina">Argentina</option>
                                    <option value="Armenia">Armenia</option>
                                    <option value="Aruba">Aruba</option>
                                    <option value="Australia">Australia</option>
                                    <option value="Austria">Austria</option>
                                    <option value="Azerbaijan">Azerbaijan</option>
                                    <option value="Bahamas">Bahamas</option>
                                    <option value="Bahrain">Bahrain</option>
                                    <option value="Bangladesh">Bangladesh</option>
                                    <option value="Barbados">Barbados</option>
                                    <option value="Belarus">Belarus</option>
                                    <option value="Belgium">Belgium</option>
                                    <option value="Belize">Belize</option>
                                    <option value="Benin">Benin</option>
                                    <option value="Bermuda">Bermuda</option>
                                    <option value="Bhutan">Bhutan</option>
                                    <option value="Bolivia">Bolivia</option>
                                    <option value="Bonaire">Bonaire</option>
                                    <option value="Bosnia & Herzegovina">Bosnia & Herzegovina</option>
                                    <option value="Botswana">Botswana</option>
                                    <option value="Brazil">Brazil</option>
                                    <option value="British Indian Ocean Ter">British Indian Ocean Ter</option>
                                    <option value="Brunei">Brunei</option>
                                    <option value="Bulgaria">Bulgaria</option>
                                    <option value="Burkina Faso">Burkina Faso</option>
                                    <option value="Burundi">Burundi</option>
                                    <option value="Cambodia">Cambodia</option>
                                    <option value="Cameroon">Cameroon</option>
                                    <option value="Canada">Canada</option>
                                    <option value="Canary Islands">Canary Islands</option>
                                    <option value="Cape Verde">Cape Verde</option>
                                    <option value="Cayman Islands">Cayman Islands</option>
                                    <option value="Central African Republic">Central African Republic</option>
                                    <option value="Chad">Chad</option>
                                    <option value="Channel Islands">Channel Islands</option>
                                    <option value="Chile">Chile</option>
                                    <option value="China">China</option>
                                    <option value="Christmas Island">Christmas Island</option>
                                    <option value="Cocos Island">Cocos Island</option>
                                    <option value="Colombia">Colombia</option>
                                    <option value="Comoros">Comoros</option>
                                    <option value="Congo">Congo</option>
                                    <option value="Cook Islands">Cook Islands</option>
                                    <option value="Costa Rica">Costa Rica</option>
                                    <option value="Cote DIvoire">Cote DIvoire</option>
                                    <option value="Croatia">Croatia</option>
                                    <option value="Cuba">Cuba</option>
                                    <option value="Curaco">Curacao</option>
                                    <option value="Cyprus">Cyprus</option>
                                    <option value="Czech Republic">Czech Republic</option>
                                    <option value="Denmark">Denmark</option>
                                    <option value="Djibouti">Djibouti</option>
                                    <option value="Dominica">Dominica</option>
                                    <option value="Dominican Republic">Dominican Republic</option>
                                    <option value="East Timor">East Timor</option>
                                    <option value="Ecuador">Ecuador</option>
                                    <option value="Egypt">Egypt</option>
                                    <option value="El Salvador">El Salvador</option>
                                    <option value="Equatorial Guinea">Equatorial Guinea</option>
                                    <option value="Eritrea">Eritrea</option>
                                    <option value="Estonia">Estonia</option>
                                    <option value="Ethiopia">Ethiopia</option>
                                    <option value="Falkland Islands">Falkland Islands</option>
                                    <option value="Faroe Islands">Faroe Islands</option>
                                    <option value="Fiji">Fiji</option>
                                    <option value="Finland">Finland</option>
                                    <option value="France">France</option>
                                    <option value="French Guiana">French Guiana</option>
                                    <option value="French Polynesia">French Polynesia</option>
                                    <option value="French Southern Ter">French Southern Ter</option>
                                    <option value="Gabon">Gabon</option>
                                    <option value="Gambia">Gambia</option>
                                    <option value="Georgia">Georgia</option>
                                    <option value="Germany">Germany</option>
                                    <option value="Ghana">Ghana</option>
                                    <option value="Gibraltar">Gibraltar</option>
                                    <option value="Great Britain">Great Britain</option>
                                    <option value="Greece">Greece</option>
                                    <option value="Greenland">Greenland</option>
                                    <option value="Grenada">Grenada</option>
                                    <option value="Guadeloupe">Guadeloupe</option>
                                    <option value="Guam">Guam</option>
                                    <option value="Guatemala">Guatemala</option>
                                    <option value="Guinea">Guinea</option>
                                    <option value="Guyana">Guyana</option>
                                    <option value="Haiti">Haiti</option>
                                    <option value="Hawaii">Hawaii</option>
                                    <option value="Honduras">Honduras</option>
                                    <option value="Hong Kong">Hong Kong</option>
                                    <option value="Hungary">Hungary</option>
                                    <option value="Iceland">Iceland</option>
                                    <option value="Indonesia">Indonesia</option>
                                    <option value="India">India</option>
                                    <option value="Iran">Iran</option>
                                    <option value="Iraq">Iraq</option>
                                    <option value="Ireland">Ireland</option>
                                    <option value="Isle of Man">Isle of Man</option>
                                    <option value="Israel">Israel</option>
                                    <option value="Italy">Italy</option>
                                    <option value="Jamaica">Jamaica</option>
                                    <option value="Japan">Japan</option>
                                    <option value="Jordan">Jordan</option>
                                    <option value="Kazakhstan">Kazakhstan</option>
                                    <option value="Kenya">Kenya</option>
                                    <option value="Kiribati">Kiribati</option>
                                    <option value="Korea North">Korea North</option>
                                    <option value="Korea Sout">Korea South</option>
                                    <option value="Kuwait">Kuwait</option>
                                    <option value="Kyrgyzstan">Kyrgyzstan</option>
                                    <option value="Laos">Laos</option>
                                    <option value="Latvia">Latvia</option>
                                    <option value="Lebanon">Lebanon</option>
                                    <option value="Lesotho">Lesotho</option>
                                    <option value="Liberia">Liberia</option>
                                    <option value="Libya">Libya</option>
                                    <option value="Liechtenstein">Liechtenstein</option>
                                    <option value="Lithuania">Lithuania</option>
                                    <option value="Luxembourg">Luxembourg</option>
                                    <option value="Macau">Macau</option>
                                    <option value="Macedonia">Macedonia</option>
                                    <option value="Madagascar">Madagascar</option>
                                    <option value="Malaysia">Malaysia</option>
                                    <option value="Malawi">Malawi</option>
                                    <option value="Maldives">Maldives</option>
                                    <option value="Mali">Mali</option>
                                    <option value="Malta">Malta</option>
                                    <option value="Marshall Islands">Marshall Islands</option>
                                    <option value="Martinique">Martinique</option>
                                    <option value="Mauritania">Mauritania</option>
                                    <option value="Mauritius">Mauritius</option>
                                    <option value="Mayotte">Mayotte</option>
                                    <option value="Mexico">Mexico</option>
                                    <option value="Midway Islands">Midway Islands</option>
                                    <option value="Moldova">Moldova</option>
                                    <option value="Monaco">Monaco</option>
                                    <option value="Mongolia">Mongolia</option>
                                    <option value="Montserrat">Montserrat</option>
                                    <option value="Morocco">Morocco</option>
                                    <option value="Mozambique">Mozambique</option>
                                    <option value="Myanmar">Myanmar</option>
                                    <option value="Nambia">Nambia</option>
                                    <option value="Nauru">Nauru</option>
                                    <option value="Nepal">Nepal</option>
                                    <option value="Netherland Antilles">Netherland Antilles</option>
                                    <option value="Netherlands">Netherlands (Holland, Europe)</option>
                                    <option value="Nevis">Nevis</option>
                                    <option value="New Caledonia">New Caledonia</option>
                                    <option value="New Zealand">New Zealand</option>
                                    <option value="Nicaragua">Nicaragua</option>
                                    <option value="Niger">Niger</option>
                                    <option value="Nigeria">Nigeria</option>
                                    <option value="Niue">Niue</option>
                                    <option value="Norfolk Island">Norfolk Island</option>
                                    <option value="Norway">Norway</option>
                                    <option value="Oman">Oman</option>
                                    <option value="Pakistan">Pakistan</option>
                                    <option value="Palau Island">Palau Island</option>
                                    <option value="Palestine">Palestine</option>
                                    <option value="Panama">Panama</option>
                                    <option value="Papua New Guinea">Papua New Guinea</option>
                                    <option value="Paraguay">Paraguay</option>
                                    <option value="Peru">Peru</option>
                                    <option value="Phillipines">Philippines</option>
                                    <option value="Pitcairn Island">Pitcairn Island</option>
                                    <option value="Poland">Poland</option>
                                    <option value="Portugal">Portugal</option>
                                    <option value="Puerto Rico">Puerto Rico</option>
                                    <option value="Qatar">Qatar</option>
                                    <option value="Republic of Montenegro">Republic of Montenegro</option>
                                    <option value="Republic of Serbia">Republic of Serbia</option>
                                    <option value="Reunion">Reunion</option>
                                    <option value="Romania">Romania</option>
                                    <option value="Russia">Russia</option>
                                    <option value="Rwanda">Rwanda</option>
                                    <option value="St Barthelemy">St Barthelemy</option>
                                    <option value="St Eustatius">St Eustatius</option>
                                    <option value="St Helena">St Helena</option>
                                    <option value="St Kitts-Nevis">St Kitts-Nevis</option>
                                    <option value="St Lucia">St Lucia</option>
                                    <option value="St Maarten">St Maarten</option>
                                    <option value="St Pierre & Miquelon">St Pierre & Miquelon</option>
                                    <option value="St Vincent & Grenadines">St Vincent & Grenadines</option>
                                    <option value="Saipan">Saipan</option>
                                    <option value="Samoa">Samoa</option>
                                    <option value="Samoa American">Samoa American</option>
                                    <option value="San Marino">San Marino</option>
                                    <option value="Sao Tome & Principe">Sao Tome & Principe</option>
                                    <option value="Saudi Arabia">Saudi Arabia</option>
                                    <option value="Senegal">Senegal</option>
                                    <option value="Seychelles">Seychelles</option>
                                    <option value="Sierra Leone">Sierra Leone</option>
                                    <option value="Singapore">Singapore</option>
                                    <option value="Slovakia">Slovakia</option>
                                    <option value="Slovenia">Slovenia</option>
                                    <option value="Solomon Islands">Solomon Islands</option>
                                    <option value="Somalia">Somalia</option>
                                    <option value="South Africa">South Africa</option>
                                    <option value="Spain">Spain</option>
                                    <option value="Sri Lanka">Sri Lanka</option>
                                    <option value="Sudan">Sudan</option>
                                    <option value="Suriname">Suriname</option>
                                    <option value="Swaziland">Swaziland</option>
                                    <option value="Sweden">Sweden</option>
                                    <option value="Switzerland">Switzerland</option>
                                    <option value="Syria">Syria</option>
                                    <option value="Tahiti">Tahiti</option>
                                    <option value="Taiwan">Taiwan</option>
                                    <option value="Tajikistan">Tajikistan</option>
                                    <option value="Tanzania">Tanzania</option>
                                    <option value="Thailand">Thailand</option>
                                    <option value="Togo">Togo</option>
                                    <option value="Tokelau">Tokelau</option>
                                    <option value="Tonga">Tonga</option>
                                    <option value="Trinidad & Tobago">Trinidad & Tobago</option>
                                    <option value="Tunisia">Tunisia</option>
                                    <option value="Turkey">Turkey</option>
                                    <option value="Turkmenistan">Turkmenistan</option>
                                    <option value="Turks & Caicos Is">Turks & Caicos Is</option>
                                    <option value="Tuvalu">Tuvalu</option>
                                    <option value="Uganda">Uganda</option>
                                    <option value="United Kingdom">United Kingdom</option>
                                    <option value="Ukraine">Ukraine</option>
                                    <option value="United Arab Erimates">United Arab Emirates</option>
                                    <option value="United States of America">United States of America</option>
                                    <option value="Uraguay">Uruguay</option>
                                    <option value="Uzbekistan">Uzbekistan</option>
                                    <option value="Vanuatu">Vanuatu</option>
                                    <option value="Vatican City State">Vatican City State</option>
                                    <option value="Venezuela">Venezuela</option>
                                    <option value="Vietnam">Vietnam</option>
                                    <option value="Virgin Islands (Brit)">Virgin Islands (Brit)</option>
                                    <option value="Virgin Islands (USA)">Virgin Islands (USA)</option>
                                    <option value="Wake Island">Wake Island</option>
                                    <option value="Wallis & Futana Is">Wallis & Futana Is</option>
                                    <option value="Yemen">Yemen</option>
                                    <option value="Zaire">Zaire</option>
                                    <option value="Zambia">Zambia</option>
                                    <option value="Zimbabwe">Zimbabwe</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="identity_no" class="control-label">Citizenship No./Driving License
                                    No./Election
                                    card No.</label>
                                <input type="text" class="form-control" id="identity_no" name="identity_no"
                                       placeholder="Citizenship No./Driving License No./Election card No.">
                            </div>

                            <div class="form-group">
                                <label for="occupation" class="control-label">Occupation</label>
                                <select class="form-control" name="occupation" id="occupation">
                                    <option value="">Select your occupation</option>
                                    <option value="1">Student</option>
                                    <option value="2">Health Professional</option>
                                    <option value="3">Teacher/Professor</option>
                                    <option value="4">Daily wages - Job</option>
                                    <option value="5">Security person (Private)</option>
                                    <option value="6">Police</option>
                                    <option value="7">Army</option>
                                    <option value="8">Transport sector</option>
                                    <option value="9">Journalist</option>
                                    <option value="10">Tourism industry</option>
                                    <option value="11">Unemployed</option>
                                    <option value="12">Social Service</option>
                                    <option value="13">Social Service</option>
                                    <option value="14">Government employ</option>
                                    <option value="15">NGO/INGO employ</option>
                                    <option value="16">Bank/finance</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="control-label" for="company">Address</label>
                                <div class="row">
                                    <div class="form-group col-sm-4" id="province">
                                        <select name="province_id" class="form-control"
                                                onchange="provinceOnchange($(this).val())">
                                            @foreach(App\Models\province::all() as $province)
                                                @if($province_id==$province->id)
                                                    @php($selectedProvince = "selected")
                                                @else
                                                    @php($selectedProvince = "")
                                                @endif
                                                <option value="{{$province->id}}" {{$selectedProvince}}>{{$province->province_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group  col-sm-4" id="district">
                                        <select name="district_id" class="form-control"
                                                onchange="districtOnchange($(this).val())">
                                            @foreach($districts as $district)
                                                @if($district_id==$district->id)
                                                    @php($selectedDistrict = "selected")
                                                @else
                                                    @php($selectedDistrict = "")
                                                @endif
                                                <option value="{{$district->id}}" {{$selectedDistrict}}>{{$district->district_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group  col-sm-4" id="municipality">
                                        <select name="municipality_id" class="form-control">
                                            @foreach($municipalities as $municipality)
                                                @if($municipality_id==$municipality->id)
                                                    @php($selectedMunicipality = "selected")
                                                @else
                                                    @php($selectedMunicipality = "")
                                                @endif
                                                <option value="{{$municipality->id}}" {{$selectedMunicipality}}>{{$municipality->municipality_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="ward">Ward No</label>
                                <input type="text" class="form-control" value="{{ old('ward') }}" name="ward"
                                       aria-describedby="help" placeholder="Enter Ward No"
                                >
                            </div>

                            <div class="form-group">
                                <label for="tole">Tole</label>
                                <input type="text" class="form-control" value="{{ old('tole') }}" name="tole"
                                       aria-describedby="help" placeholder="Enter Tole">
                            </div>

                            <div class="form-group">
                                <label for="email_address" class="control-label">Email</label>
                                <input type="email" class="form-control" id="email_address" name="email_address"
                                       placeholder="Email">
                            </div>

                            <div class="form-group">
                                <label for="first_vaccinated_date" class="control-label">Date of first
                                    vaccinated</label>
                                <input type="date" class="form-control" id="first_vaccinated_date"
                                       placeholder="Enter first vaccinated date"
                                       name="first_vaccinated_date">
                            </div>

                            <div class="form-group">
                                <label for="second_vaccinated_date" class="control-label">Date of second
                                    vaccinated</label>
                                <input type="date" class="form-control" id="second_vaccinated_date"
                                       placeholder="Enter second vaccinated date"
                                       name="second_vaccinated_date">
                            </div>

                            <div class="form-group">
                                <button id="submit" type="submit" class="btn btn-success" >Submit</button>
                            </div>
                        </form>
                        {{--                        {!! rcForm::close('post') !!}--}}

                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="dateConfirmModel" tabindex="-1" role="dialog"
             aria-labelledby="dateConfirmModel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel">Excel Download request form</h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" name="excelExport" role="form" method="GET"
                              action="{{ route('public-client.store') }}" enctype="multipart/form-data">
                            <label>Do you want to save the information ?</label>
                            <hr>
                            <div class="form-group">
                                <div class="col-sm-offset-3 col-sm-9">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary pull-right">Submit Request</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="modal-footer">
                        Note : * Required Fields
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.2/dist/jquery.validate.min.js"></script>
<script>
    function provinceOnchange(id) {
        $("#district").text("Loading...").fadeIn("slow");
        $.get("{{route("district-select-province")}}?id=" + id, function (data) {
            $("#district").html(data);
        });
    }

    function districtOnchange(id) {
        $("#municipality").text("Loading...").fadeIn("slow");
        $.get("{{route("municipality-select-district")}}?id=" + id, function (data) {
            $("#municipality").html(data);
        });
    }

    function calculateAge() {
        var dateOfBirth = document.getElementById("date_of_birth").value;

        var dob = new Date(dateOfBirth.substring(6, 10),
            dateOfBirth.substring(0, 2) - 1,
            dateOfBirth.substring(3, 5));
        var today = new Date();
        var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
        $('#age').html(age);
    }

    $(function () {
        $.validator.addMethod("emailCustom", function (value, element) {
            return this.optional(element) || /^[a-zA-Z\.\'\-]{2,50}(?: [a-zA-Z\.\'\-]{2,50})+$/i.test(value);
        }, "Email Address is invalid: Please enter a valid email address.");

        $.validator.addMethod("phoneCustom", function (value, element) {
            return this.optional(element) || /^((984|985|986|974|975|980|981|982|961|988|972|963)\d{7})|((097|095|081|053|084|083|029|056|096|089|093|010|026|041|068|049|094|064|079|027|046|087|091|076|061|036|025|066|077|099|044|057|023|021|069|055|037|075|024|067|051|086|082|071|033|031|092|047|038|063|035)(4|5|6)\d{5})|(01)(4|5|6)\d{6}$/i.test(value);
        }, "Contact number is invalid: Please enter a valid phone number.");

        $("form[name='create']").validate({
            // Define validation rules
            rules: {
                full_name: {
                    required: true,
                },
                caste: {
                    required: true,
                },
                gender: {
                    required: true,
                },
                age: {
                    required: true,
                    // ageCustom: true,
                    min: 15,
                    max: 125
                },
                date_of_birth: {
                    required: true,
                },
                phone: {
                    required: true
                },
                nationality: {
                    required: true
                },
                identity_no: {
                    required: true,
                },
                occupation: {
                    required: true,
                },
                province_id: {
                    required: true,
                },
                district_id: {
                    required: true,
                },
                municipality_id: {
                    required: true,
                },
                ward: {
                    required: true,
                }
            },
            // Specify validation error messages
            messages: {
                name: "Please provide a valid name.",
                age: "Please provide a valid age.",

            },
            submitHandler: function (form) {
                form.submit();
                // $('#dateConfirmModel').modal('toggle');
            }
        });
    });
</script>
</body>
</html>