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

    <br>
        <table class="table">
          <thead>
            <tr>
              <h4>1. Personal Information </h4>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Case ID : </td>
              <td>{{ data.case_id }}</td>
            </tr>
            <tr>
              <td>Name : </td>
              <td>{{ data.name }}</td>
            </tr>
            <tr>
                <td>Age : </td>
                <td>{{ data.age }} / <span v-if="data.age_unit == 1">Months</span><span v-if="data.age_unit == 2">Days</span><span v-if="data.age_unit == 0">Years</span></td>
            </tr>
            <tr>
                <td>Gender : </td>
                <td><span v-if="data.sex == 1">Male</span><span v-if="data.sex == 2">Female</span><span v-if="data.sex == 3">Other</span></td>
            </tr>
            <tr>
                <td>Emergency Phone : </td>
                <td>One : {{ data.emergency_contact_one }} <br> Two : {{ data.emergency_contact_two }}</td>
            </tr>
            <tr>
                <td>Address : </td>
                <td>
                    {{ data.tole }} - {{ data.ward }} <br>
                    Municipality : {{ checkMunicipality(data.municipality_id) }}<br>
                    District : {{ checkDistrict(data.district_id) }}
                </td>
            </tr>
          </tbody>
          <br>
          <!-- <thead>
            <tr>
              <h4>2. Clinical Information </h4>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Case ID : </td>
              <td>{{ data.case_id }}</td>
            </tr>
            <tr>
              <td>Name : </td>
              <td>{{ data.name }}</td>
            </tr>
            <tr>
                <td>Age : </td>
                <td>{{ data.age }} / <span v-if="data.age_unit == 1">Months</span><span v-if="data.age_unit == 2">Days</span><span v-if="data.age_unit == 0">Years</span></td>
            </tr>
            <tr>
                <td>Gender : </td>
                <td><span v-if="data.sex == 1">Male</span><span v-if="data.sex == 2">Female</span><span v-if="data.sex == 3">Other</span></td>
            </tr>
            <tr>
                <td>Emergency Phone : </td>
                <td>One : {{ data.emergency_contact_one }} <br> Two : {{ data.emergency_contact_two }}</td>
            </tr>
            <tr>
                <td>Address : </td>
                <td>
                    {{ data.tole }} - {{ data.ward }} <br>
                    Municipality : {{ checkMunicipality(data.municipality_id) }}<br>
                    District : {{ checkDistrict(data.district_id) }}
                </td>
            </tr>
          </tbody> -->
        </table>
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
        return this.districts.find(x => x.id === value).district_name;
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