<template>
    <div>
        <div class="pull-right">
            <button type="submit" class="btn btn-primary btn-lg" @click="print">
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
                <p class="govB">{{ data.healthpost.name }}</p>
                <p class="govA">{{ data.healthpost.address }}, {{ districts.find(x => x.id === data.healthpost.district_id).district_name }}</p>
            </div>

            <div class="titleSide">
                <p>Phone: {{ data.healthpost.phone }}</p>
                <!-- <p>Fax: 4252375</p> -->
                <p> E-mail: {{ data.healthpost.email }}</p>
                <p class="date">Date: {{ ad2bs(data.created_at) }}</p>
            </div>
        </div>
        <p class="titleHead"> <u>Laboratory Sample Collection Form for Suspected COVID-19 Case</u></p>
        <p class="subTitle"> <b>Sample Collection Site</b> </h6>
        <table>
            <tr>
                <td style="width:20%">Province</td>
                <td> {{ checkProvince(data.province_quarantine_id) }}
                </td>
                <td colspan="2">Please tick the appropriate option and specify the name</td>
            </tr>
            <tr>
                <td>District </td>
                <td>
                    {{ checkDistrict(data.district_quarantine_id) }}         
                </td>
                <td v-if="data.quarantine_type==0" >1. Institutional Quarantine[ &#10004; ]</td>
                <td v-else>1. Institutional Quarantine[__]</td>
                <td v-if="data.quarantine_type==0" > {{ data.quarantine_specific }} </td>
                <td v-else></td>

            </tr>

            <tr>
                <td>Municipality </td>
                <td>
                    {{ checkMunicipality(data.municipality_quarantine_id) }}
                </td>
                 <td v-if="data.quarantine_type==1">2. Institutional Isolation[ &#10004; ] </td>
                <td v-else>2. Institutional Isolation [__]</td>
                <td v-if="data.quarantine_type==1" > {{ data.quarantine_specific }} </td>
                <td v-else></td>
            </tr>
            <tr>
                <td> Ward </td>
                <td>{{ data.ward_quarantine }}</td>
                 <td v-if="data.quarantine_type==2" >3.Health Institution[ &#10004; ] </td>
                <td v-else>3. Health Institution [__]</td>
                <td v-if="data.quarantine_type==2" > {{ data.quarantine_specific }} </td>
                <td v-else></td>
            </tr>
            <tr>
                <td> Tole </td>
                <td>{{ data.tole_quarantine }}</td>
                 <td v-if="data.quarantine_type==3" >4. Home Quarantine[ &#10004; ] </td>
                <td v-else>4. Home Quarantine[__]</td>
                <td v-if="data.quarantine_type==0" > {{ data.quarantine_specific }} </td>
                <td v-else></td>
            </tr>
            
            <tr>
                <td rowspan="2"> <b>Contact Person </b> for</br> Reporting: (for all</br> collected sample) </td>
                <td rowspan="2" style="width: 20%;" > Name: {{ data.healthworker.name }}</br>
                    Ph. No. {{ data.healthworker.phone }}</br>
                    Email: {{ data.healthworker.email }}</td>
                 <td v-if="data.quarantine_type==4" >5. Home Isolation [ &#10004; ] </td>
                <td v-else>5. Home Isolation[__]</td>
                <td v-if="data.quarantine_type==4" > {{ data.quarantine_specific }} </td>
                <td v-else></td>
            </tr>
            <tr>
                 <td v-if="data.quarantine_type==5" >6. Other  [ &#10004; ] </td>
                <td v-else>6. Other [__]</td>
                <td v-if="data.quarantine_type==5" > {{ data.quarantine_specific }} </td>
                <td v-else></td>
            </tr>
        </table>
        <p class="subTitle"> <b>Patient's Detail:</b> </h6>
        <table>
            <tr>
                <td colspan="5">
                    Full Name : {{ data.name }} 
                </td>

            </tr>
            <tr>
                <td>Age : {{ data.age }} / {{ age_unit(data.age_unit) }}
                </td>
                <td> Gender: {{ gender(data.sex) }} </td>
                <td colspan="3"> Occupation: {{ data.occupation }}</td>
            </tr>
            <tr>
                <td>Current address </td>
                <td> District: {{ checkDistrict(data.district_id) }}</td>
                <td> Municipality: {{ checkMunicipality(data.municipality_id) }}</td>
                <td>Ward No: {{ data.ward }}</td>
                <td> Tole: {{ data.tole }}</td>
            </tr>
            <tr>
                <td>Contact No.
                </td>
                <td>{{ data.phone }}</td>
                <td colspan="3">Email, if necessary: {{ data.email }}</td>

            </tr>
            <tr>
                <td> If other than Nepal </td>
                <td style="border: 0px !important"> Country: {{ data.country_name }}</td>
                <td style="border: 0px !important">Passport No. {{ data.passport_no }}</td> 
                <td style="border: 0px !important">Email: <span v-if="data.country_name">{{ data.email }}</span></td>
                <td style="border: 0px !important"></td>
            </tr>
        </table>

        <table style="margin-top: 20px; border: 2px solid black;">
            <tr>
                <td> Was RT-PCR tested before?</td>
                <td> 1.Yes <span v-if="data.pcr_test == 1"> &#10004; </span></td>
                <td>2. No <span v-if="data.pcr_test == 0"> &#10004; </span></td>
                <td>If tested, mention date of latest test | {{ data.pcr_test_date }} </td>
            </tr>
            <tr>
                <td>Result of the latest test</td>
                <td>Positive <span v-if="data.pcr_test_result == 1">&#10004;</span></td>
                <td>Negative <span v-if="data.pcr_test_result == 2">&#10004;</span></td>
                <td>Don’t know <span v-if="data.pcr_test_result == 3">&#10004;</span></td>

            </tr>
        </table>
        <br>
        <p style="border: 1px solid #000;">
            <b>Type of sample collected: </b> (Please tick [ ] the type)</br>
            <div style="border: 1px solid #000;" v-if="data.latest_anc !== null">
                Nasopharyngeal [_<span v-if='data.latest_anc.sample_type.includes("1")'> &#10004; </span>_] / Oropharyngeal [_<span v-if='data.latest_anc.sample_type.includes("2")'> &#10004; </span>_]/ BAL [_<span v-if='data.latest_anc.sample_type.includes("3")'> &#10004; </span>_]/ Sputum [_<span v-if='data.latest_anc.sample_type.includes("4")'> &#10004; </span>_]/ Endotracheal Aspirate[_<span v-if='data.latest_anc.sample_type.includes("5")'> &#10004; </span>_] </br>

                        If other, please specify: 
            </div>
            <div v-else style="border: 1px solid #000;">
                Nasopharyngeal [__] / Oropharyngeal [__]/ BAL [__]/ Sputum [__]/ Endotracheal Aspirate[__] </br>
                        If other, please specify: 
            </div>

            
        </p>
        <p class="subTitle"><b>Type of Case</b> (Travel, Contact and Symptoms Details) (Please tick [ ] in the box)</h6>
        <table>
            <tr>
                <td> <b>Symptomatic patient with</b> Pneumonia [_<span v-if='data.symptoms.includes("1")'> &#10004; </span>_]</br>
                    ARDS [_<span v-if='data.symptoms.includes("2")'> &#10004; </span>_] /Influenza-like illness[_<span v-if='data.symptoms.includes("3")'> &#10004; </span>_]</br>
                    If Other, specify: {{ data.symptoms_specific }}</td>
                <td>Most recent travel history in the last 14 days</br>
                    Country: If within Nepal:District: </td>
            </tr>
            <tr>
                <td><b>Symptomatic patient with comorbidity</b></br> Diabetes[_<span v-if='data.symptoms_comorbidity.includes("1")'> &#10004; </span>_],HTN[_<span v-if='data.symptoms_comorbidity.includes("2")'> &#10004; </span>_],
                    Hemodialysis[_<span v-if='data.symptoms_comorbidity.includes("3")'> &#10004; </span>_]</br> immunocompromised[_<span v-if='data.symptoms_comorbidity.includes("4")'> &#10004; </span>_] <br> If other, specify: {{ data.symptoms_comorbidity_specific }}</td>
                <td><b>Screening:</b> Pregnant /in labour:[_<span v-if='data.screening.includes("1")'> &#10004; </span>_], <br> > 65 Year[_<span v-if='data.screening.includes("2")'> &#10004; </span>_]
                    Health care worker [_<span v-if='data.screening.includes("3")'> &#10004; </span>_] <br>If other,specify: {{ data.screening_specific }}</br>
                    <p v-if="data.latest_anc !== null">
                        Clinical Suspicious(not admitted/isolated ) [<span v-if='data.latest_anc.sample_case.includes("4")'> &#10004; </span>]
                    </p>
                    <p v-else>
                        Clinical Suspicious (not admitted/ isolated ) [___]
                    </p>
                    </td>
            </tr>
                <tr v-if="data.latest_anc !== null">
                <td><b>Death case</b> [_<span v-if='data.latest_anc.sample_case.includes("1")'> &#10004; </span>_]</td>
                <td>Contact tracing: [_<span v-if='data.latest_anc.sample_case.includes("5")'> &#10004; </span>_]</td>
            </tr>
            <tr v-else>
                <td><b>Death case</b> [___]</td>
                <td>Contact tracing: [___]</td>
            </tr>
            <tr v-if="data.latest_anc !== null">
                <td><b>Health care worker in contact with positive case</b> [__]</td>
                <td>Repeat swab for positive case: [_<span v-if='data.latest_anc.sample_case.includes("6")'> &#10004; </span>_]</td>
            </tr>
            <tr v-else>
                <td><b>Health care worker in contact with positive case</b> [__]</td>
                <td>Repeat swab for positive case: [__]</td>
            </tr>
            <tr v-if="data.latest_anc !== null">
                <td><b>Emergency surgical intervention</b> [_<span v-if='data.latest_anc.sample_case.includes("2")'> &#10004; </span>_]</br>
                    <b>ICU case</b> [_<span v-if='data.latest_anc.sample_case.includes("3")'> &#10004; </span>_]</td>
                <td>If other, Specify: {{ data.latest_anc.sample_case_specific }} </td>
            </tr>  
            <tr v-else>
                <td><b>Emergency surgical intervention</b> [__]</br>
                    <b>ICU case</b> [__]</td>
                <td>If other, Specify:  </td>
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
                    Signature: <img src=''> <br>
                    Name: {{ data.healthworker.name }} <br>
                    Phone: {{ data.healthworker.phone }} <br>
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
    import DataConverter from 'ad-bs-converter'

export default {
    props: {
            data : Object,
            provinces : Array,
            districts : Array,
            municipalities : Array
        },
  data() {
    return {
        age_units : [ 'Years', 'Months', 'Days' ],
        gender_array : ['Male', 'Female', 'Other']
    }
  },
  methods: {
    print() {
     // Get HTML to print from element
      const prtHtml = document.getElementById('report-printMe').innerHTML;

      // Get all stylesheets HTML
      let stylesHtml = '';
      for (const node of [...document.querySelectorAll('link[rel="stylesheet"], style')]) {
        stylesHtml += node.outerHTML;
      }

      // Open the print window
      const WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');

      WinPrint.document.write(`<!DOCTYPE html>
      <html>
        <head>
          ${stylesHtml}
        </head>
        <body>
          ${prtHtml}
        </body>
      </html>`);

      WinPrint.document.close();
      WinPrint.focus();
      WinPrint.print();
      // WinPrint.close();
      },

      ad2bs: function (date) {
                var dateObject = new Date(date);

                var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

                let dateConverter = DataConverter.ad2bs(dateFormat);

                return dateConverter.en.day + ' ' + dateConverter.en.strMonth + ', ' + dateConverter.en.year;

            },
        age_unit: function(unit){
            return this.age_units[unit - 1] ? this.age_units[unit - 1] : 'Years';
        },

        gender: function(value){
            return this.gender_array[value - 1] ? this.gender_array[value - 1] : '';
        },
        checkProvince : function(value){
        if (value == 0 || value == null || value == ''){
            return ''
        }else{
        return this.provinces.find(x => x.id === value).province_name;
        }
        },
        checkDistrict : function(value){
        if (value == 0 || value == null || value == ''){
            return ''
        }else{
        return this.districts.find(x => x.id == value).district_name;
        }
        },
        checkMunicipality : function(value){
        if (value == 0 || value == null || value == ''){
            return ''
        }else{
        return this.municipalities.find(x => x.id === value).municipality_name.split(" ").slice(0, -1).join(" ");
        }
        }
    } 
  }
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

</style>