<div class="form-group">
    <button type="submit" class="btn btn-primary" onclick="printDiv('report-printable')">
        <i class="fa fa-print"></i> Print
    </button>
</div>
<div class="container-rep">
    <div class="main" id="report-printable">
    <style>
        .main2 {
            position: relative;
            width: 50%;
            margin: 0 auto;
        }
        .main1 {
            position: relative;
            width: 50%;
            margin: 0 auto;
        }

        .letterhead {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            background-color: #174894;
        }

        *, p {
            margin: 0;
            padding: 0;
        }

        .govLogo {
            padding-left: 2%;
            text-align: right;
        }

        .mid{
            padding-top: 2em;
            padding-bottom: 2em;
            padding-left: 2%;
            background-color: #f8f49b !important;
        }

        .vaccineLogo{
            padding-right: 2%;
            text-align: left;
        }

        .lheadtitle {
            text-align: center;
        }

        .govF {
            color: #ffffff;
            font-size: 14px;
        }

        .govM {
            color: #ffffff;
            font-size: 17px;
        }

        .govB {
            color: #ffffff;
            font-weight: bolder;
            font-size: large;
        }

        .card-table {
            width:100%;
        }
        .table-card, .card-th, .card-td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        .card-th, .card-td {
            padding: 15px;
            text-align: left;
        }

        .signature{
            margin-top: 2em;
            margin-bottom: 1em;
        }

        .container2{
            width: 100%;
            display: flex;
            margin-left: 5px;
            padding: 5px;
            flex-direction: column;
            border: 1px dashed #000;
        }

        .container1{
            width: 100%;
            margin-right: 5px;
            display: flex;
            padding: 5px;
            flex-direction: column;
            border: 1px dashed #000;
        }

        .mid{
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: space-between;
            background-color: blue;
        }

        .qr{
            position: absolute;
            padding-left: 60%;
            padding-top: 5px;
        }

        .card-main-container{
            display: flex;
            flex-direction: row;
        }

    </style>
<div class="card-main-container">
    @php
        $vaccination = App\Models\VaccinationRecord::where('vaccinated_id', $data->id)->get();

        $first = collect($vaccination)->where('vaccine_period', '1M')->first();
        $second = collect($vaccination)->where('vaccine_period', '3M')->first();

    @endphp
    <div class="main1">
        <div class="letterhead">
            <div class="govLogo">
                <img src="{{ asset('images/v-card/gov_logo.png') }}" width="115" height="92" alt="">
            </div>

            <div class="lheadtitle">
                <p class="govF">नेपाल सरकार</p>
                <p class="govF">स्वास्थ्य सेवा जनसंख्या मन्त्रालय</p>
                <p class="govM">स्वास्थ्य सेवा विभाग</p>
                <p class="govB">परिवार कल्याण महाशाखा </p>
            </div>

            <div class="vaccineLogo">
                <img src="{{ asset('images/v-card/khop_logo.png') }}" width="92" height="92" alt="">
            </div>
        </div>
        <div class="mid">
            <b>कोभिड १९ बिरूद्धको खोप अभियान </b>
        </div>
        <div class="qr">
            {!! QrCode::size(150)->generate(str_pad($data->id, 6, "0", STR_PAD_LEFT)); !!}
        </div>
        <div class="container2">
            <b style="text-align: center;">खोप कार्ड - स्वास्थ्य संस्था प्रति</b>
            <p>दर्ता न.: {{ str_pad($data->id, 6, "0", STR_PAD_LEFT) }}</p>
            <p>खोप लिने व्यक्ति को लक्षित समूह <b>.............................</b></p>
            <p>नाम : <b>{{ str_pad($data->name,  24, "___") }}</b> उमेर : <b>{{ $data->age }}</b><p>
            <p>पालिका : <b>{{ str_pad($data->municipality->municipality_name,  21, "___") ?? '' }}</b> वार्ड न : <b>{{ $data->ward }}</b><p>
            <p>सम्पर्क न  : <b>{{ str_pad($data->phone,  10, "___") }}</b></p>
            <p>ब्यक्ति पहिचानको संकेत...................</p>
            <br>
            <p>नेपाल सरकार कोभिड १९ विरूद्धमा उपलब्ध भएको खोप मैले मेरो राजा खुशी साथ लिएको  छु र पछि कुनै भवीतब्य परी केहि भएमा कोही कसैलाई दोष नदिई म स्वयमं जिम्मेवारी हुनेछु। </p>
            <p class="signature">दस्तखत / ल्याप्चे <b>.............................</b><p>

            <table class="card-table">
                <tr>
                    <td class="card-td">मात्रा</td>
                    <td class="card-td">खोपको नाम र ब्याच नं </td>
                    <td class="card-td">खोप लगाएको मिति</td>
                    <td class="card-td">खोप दिने स्वास्थ्यकर्मीको दस्तखत </td>
                </tr>
                <tr>
                    <td class="card-td">पहिलो</td>
                    <td class="card-td"> {{ ($first) ? 'Covishield' : ''  }} </td>
                    <td class="card-td">{{ $first->vaccinated_date_np ?? '' }}</td>
                    <td class="card-td"></td>
                </tr>
                <tr>
                    <td class="card-td">दोस्रो</td>
                    <td class="card-td">{{ ($second) ? 'Covishield' : ''  }}</td>
                    <td class="card-td">{{ $second->vaccinated_date_np ?? '' }}</td>
                    <td class="card-td"></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="main2">
        <div class="letterhead">
            <div class="govLogo">
                <img src="{{ asset('images/v-card/gov_logo.png') }}" width="115" height="92" alt="">
            </div>

            <div class="lheadtitle">
                <p class="govF">नेपाल सरकार</p>
                <p class="govF">स्वास्थ्य सेवा जनसंख्या मन्त्रालय</p>
                <p class="govM">स्वास्थ्य सेवा विभाग</p>
                <p class="govB">परिवार कल्याण महाशाखा </p>
            </div>

            <div class="vaccineLogo">
                <img src="{{ asset('images/v-card/khop_logo.png') }}" width="92" height="92" alt="">
            </div>
        </div>
        <div class="mid">
            <b>कोभिड १९ बिरूद्धको खोप अभियान </b>
        </div>
        <div class="qr">
            {!! QrCode::size(150)->generate(str_pad($data->id, 6, "0", STR_PAD_LEFT)); !!}
        </div>
        <div class="container1">
            <p>दर्ता न.: {{ str_pad($data->id, 6, "0", STR_PAD_LEFT) }}</p>
            <p>खोप लिने व्यक्ति को लक्षित समूह <b>.............................</b></p>
            <p>नाम : <b>{{ str_pad($data->name,  24, "___") }}</b> उमेर : <b>{{ $data->age }}</b><p>
            <p>पालिका : <b>{{ str_pad($data->municipality->municipality_name,  21, "___") ?? '' }}</b> वार्ड न : <b>{{ $data->ward }}</b><p>
            <p>सम्पर्क न  : <b>{{ str_pad($data->phone,  10, "___") }}</b></p>
            <b style="text-align: center;">खोप कार्ड– सेवाग्राही प्रति</b>
            <p>ब्यक्ति पहिचानको संकेत...................</p>
            <br>
            <table style="margin-top: 20px" class="table-card">
                <tr>
                    <td class="card-td">मात्रा</td>
                    <td class="card-td">खोपको नाम र ब्याच नं </td>
                    <td class="card-td">खोप लगाएको मिति</td>
                    <td class="card-td">खोप दिने स्वास्थ्यकर्मीको दस्तखत </td>
                </tr>
                <tr>
                    <td class="card-td">पहिलो</td>
                    <td class="card-td"> {{ ($first) ? 'Covishield' : ''  }} </td>
                    <td class="card-td">{{ $first->vaccinated_date_np ?? '' }}</td>
                    <td class="card-td"></td>
                </tr>
                <tr>
                    <td class="card-td">दोस्रो</td>
                    <td class="card-td">{{ ($second) ? 'Covishield' : ''  }}</td>
                    <td class="card-td">{{ $second->vaccinated_date_np ?? '' }}</td>
                    <td class="card-td"></td>
                </tr>
            </table>
            <p style="text-align: center; padding-bottom: 3em; padding-top: 1.5em;">दोश्रो पटक खोप लिन आँउदा यो कार्ड अनिवार्य रुपमा लिएर आउनुपर्दछ ।</p>
        </div>
    </div>
</div>
        </div>
    </div>