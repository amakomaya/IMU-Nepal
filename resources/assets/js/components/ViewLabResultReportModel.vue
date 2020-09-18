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
                <p class="govB">{{ item.healthpost.name }}</p>
                <p class="govA">{{ item.healthpost.address }}, {{ districts.find(x => x.id === item.healthpost.district_id).district_name }}</p>
            </div>

            <div class="titleSide">
                <p>Phone: {{ item.healthpost.phone }}</p>
                <!-- <p>Fax: 4252375</p> -->
                <p> E-mail: {{ item.healthpost.email }}</p>
                <p class="date">Date: {{ ad2bs(item.created_at) }}</p>
            </div>
        </div>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            </div>
            <hr>
            <br>
            <div class="row">
                <div class="col-xs-6">
                    Name : {{ item.name }} <br>
                    Age : {{ item.age }} / {{ age_unit(item.age_unit) }} <br>
                    Gender : {{ gender(item.sex) }}
                </div>
                <div class="col-xs-6 text-right">
                    Patient No : <br>
                    Sample No : <br>
                    Sample Recieved Date : <br>
                    Date & Time of Analysis : <br>
                </div>
            </div> 
        </div>
    </div>
    <br>
    <br>    
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-condensed">
                            <thead>
                                <tr>
                                    <td><strong>Test</strong></td>
                                    <td class="text-right"><strong>Result</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>BS-200</td>
                                    <td class="text-right">$10.99</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <br>
            -------------------------------<br>
            Verified by
            Name : 
            HP : name
            Post : 
        </div>
    </div>
</div>
    </div>
    </div>
  
</template>


<script type="text/javascript">
    import DataConverter from 'ad-bs-converter'
    import axios from 'axios'

export default {
    props: {
            item : Object,
            provinces : Array,
            districts : Array,
            municipalities : Array,
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