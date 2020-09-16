<template>
    <div>
        <div class="pull-right">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-print"></i> Print
            </button>
        </div>ull
        <div class="main">
            <div class="header">
                <div class="img">
                    <img src="/images/report-logo.jpg" width="92" height="92" alt="">
                </div>

                <div class="titleMid">
                    <p class="govF">Government of Nepal</p>
                    <p class="govF">Ministry of Health & Population</p>
                    <p class="govM">Department of Health Service</p>
                    <p class="govB">National Public Health Laboratory</p>
                    <p class="govA">Teku, Kathmandu</p>
                </div>

                <div class="titleSide">
                    <p>Phone: 4252421</p>
                    <p>Fax: 4252375</p>
                    <p> E-mail: nphl@nphl.gov.com.np</p>
                    <p class="date">Date: 2077/ / /</p>
                </div>
            </div>
            <p class="titleHead"> <u>Laboratory Sample Collection Form for Suspected COVID-19 Case</u></p>
            <p class="subTitle"> <b>Sample Collection Site</b> </h6>
            <table>
                <tr>
                    <td style="width:20%">Province</td>
                    <td></td>
                    <td colspan="2">Please tick the appropriate option and specify the name</td>
                </tr>
                <tr>
                    <td>District </td>
                    <td></td>
                    <td>1.Quarantine [___] </td>
                    <td></td>
                </tr>

                <tr>
                    <td>Municipality </td>
                    <td></td>
                    <td>2. Home quarantine [___] </td>
                    <td></td>
                </tr>
                <tr>
                    <td> Ward </td>
                    <td></td>
                    <td>3. Health institution [___] </td>
                    <td></td>
                </tr>
                <tr>
                    <td> Tole </td>
                    <td></td>
                    <td>4. Other institution [___] </td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2"> <b>Contact Person </b> for</br> Reporting: (for all</br> collected sample) </td>
                    <td rowspan="2" style="width: 20%;"> Name:</br>
                        Ph. No.</br>
                        Email:</td>
                    <td> 5.Community surveillance </br> [___]</td>

                    <td style="width: 30%;"></td>
                </tr>
                <tr>
                    <td>6.Others (Specify)</td>
                    <td></td>
                </tr>
            </table>
            <p class="subTitle"> <b>Patient's Detail:</b> </h6>
            <table>
                <tr>
                    <td style="border: 0px !important;" colspan="2"> First Name: </td>

                    <td style="border: 0px !important;" colspan="2"> Mid. Name:</td>

                    <td style="border: 0px !important;" colspan="2"> Last Name: </td>

                </tr>
                <tr>
                    <td>Age : Year/month
                    </td>
                    <td> Sex: Male </td>
                    <td> Female </td>
                    <td>Other</td>
                    <td> Occupation:</td>
                </tr>
                <tr>
                    <td>Current address </td>
                    <td> District: </td>
                    <td> Municipality: </td>
                    <td>Ward No:</td>
                    <td> Tole:</td>
                </tr>
                <tr>
                    <td>Contact No.
                    </td>
                    <td></td>
                    <td colspan="3">Email, if necessary:</td>

                </tr>
                <tr>
                    <td> If other than Nepal
                    </td>
                    <td colspan="4">
                        Country: Passport No. Email:

                    </td>
                </tr>
            </table>

            <table style="margin-top: 20px; border: 2px solid black;">
                <tr>
                    <td> Was RT-PCR tested before?</td>
                    <td> 1.Yes</td>
                    <td>2. No</td>
                    <td>If tested, mention date of latest test </td>
                </tr>
                <tr>
                    <td>Result of the latest test</td>
                    <td>Positive</td>
                    <td>Negative</td>
                    <td>Don’t know</td>

                </tr>
            </table>
            <br>
            <p style="border: 1px solid #000;">
                <b>Type of sample collected: </b> (Please tick [ ] the type)</br>

                Nasopharyngeal [___] / Oropharyngeal [___]/ BAL [___]/ Sputum [___]/ Endotracheal Aspirate[___] </br>

                If other, please specify: …………………………………………..
            </p>
            <p class="subTitle"><b>Type of Case</b> (Travel, Contact and Symptoms Details) (Please tick [ ] in the box)</h6>
            <table>
                <tr>
                    <td> <b>Symptomatic patient with</b> Pneumonia [___]</br>
                        ARDS [___] /Influenza-like illness[___]</br>
                        If Other, specify:</td>
                    <td>Most recent travel history in the last 14 days</br>
                        Country: If within Nepal:District: </td>
                </tr>
                <tr>
                    <td><b>Symptomatic patient with comorbidity</b></br> Diabetes[___],HTN[___],
                        Hemodialysis [___]</br> immunocompromised[___]If other, specify: </td>
                    <td><b>Screening:</b> Pregnant /in labour:[___], > 65 Year[___]</br>
                        Health care worker [___] If other,specify:</br>
                        Clinical Suspicious (not admitted/ isolated ) [___]</td>
                </tr>
                <tr>
                    <td><b>Death case</b> [___]</td>
                    <td>Contact tracing: [___]</td>
                </tr>
                <tr>
                    <td><b>Health care worker in contact with positive case</b> [___]</td>
                    <td>Repeat swab for positive case: [___]</td>
                </tr>
                <tr>
                    <td><b>Emergency surgical intervention</b> [___]</br>
                        <b>ICU case</b> [___]</td>
                    <td>If other, Specify: </td>
                </tr>
            </table>
            <div class="noteStyle">
                <div style="width: 45%;">
                    <p style=" padding: 5px;  margin-right: 5%; border: 1px solid #000; "> <b>Note: Please fill complete
                            information in the form
                            before sample collection. Keep
                            it separate and transport separately from the sample.</b></p>
                </div>
                <div style=" width: 55%;">
                    <p style=" padding: 5px; margin-left: 5%; border: 1px solid #000; ">
                        Attending physician/Health worker <br>
                        Signature: <br>
                        Name: …………………………………………… <br>
                        Phone: …………………………………………… <br>
                    </p>
                </div>
            </div>
            <p class="footerStyle">For further information please visit <a href=" www.nphl.gov.np"> www.nphl.gov.np</a>,
                Contact Person: Mr. Rajesh
                Kumar Gupta,(9851239988)
            </p>
            </div>
    </div>
  
</template>


<script type="text/javascript">
    
</script>

<style scoped>
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

/*  table css */

table {
    width: 100%;
    border-collapse: collapse;
    border: 1px solid black;
}

tr {}

td {
    font-size: 15px;
    border: 1px solid black;
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

@media print
    {
        #printable { display: block; }
    }

    </style>
