<template>
  <div>
    <div class="panel">
      <div class="panel-heading">
        <div class="panel-title">
          <div class="form-group">
            <h3>Search latest case by SID or LAB ID :</h3>
              <div class="row">
                <div class="col-md-12" style="padding-bottom: 40px;">
                  <button class="btn btn-success" style="float:right"  @click="newLink()">Click for data older than 15 days</button>
                </div>
                <div class="container">
                  <div class="input-group" :class="{ 'has-error': $v.token.$error }">
                    <input v-model="token" type="text" @keyup.enter="searchResult(token)" class="form-control" placeholder="Enter SID or LAB ID"/>
                    <div class="input-group-btn">
                      <button class="btn btn-primary" @click="searchResult(token)">
                        <span class="glyphicon glyphicon-search"></span>
                      </button>
                    </div>
                  </div>
                  <div class="help-block" v-if="!$v.token.required">Field is required.</div>
                  <div class="help-block" v-if="!$v.token.minLength">Field must have at least 2 character.</div>
                  <span v-if="data.length == 0" class="text-danger -align-right">* Only lab user can view report using Lab ID</span>
                  <div v-if="data.length != 0">
                    <br>
                    <table class="table table-striped">
                      <thead>
                      <tr>
                        <th>Name</th>
                        <th>Age</th>
                        <th>Gender</th>
                        <th>Phone No</th>
                      </tr>
                      </thead>
                      <tbody>
                      <tr>
                        <td>{{ data.name }}</td>
                        <td>{{ data.formatted_age }}</td>
                        <td>{{ data.gender }}</td>
                        <td>{{ data.phone_no }}</td>
                      </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            <button class="btn btn-primary btn-block generate-button" v-on:click="generateReport(token)" title="Generate Report">
        <i class="fa fa-file-pdf-o"> Generate Report</i>
      </button>
    </div>
        </div>
      </div>
      <hr>
    <div class="panel-body">
      <div class="pull-right">
        <button type="submit" class="btn btn-primary" @click="print">
          <i class="fa fa-print"> Print </i>
        </button>
      </div>
      <br>
      <div class="main" id="report-printMe">
        <div class="header">
          <div class="img">
            <img src="/images/report-logo.jpg" width="92" height="92" alt="">
          </div>

          <div class="titleMid">
            <div class="govF">Government of Nepal</div>
            <div class="govF">Ministry of Health & Population</div>
            <div class="govM">Department of Health Service</div>
            <div class="govB">{{ reportData.organization_name }}</div>
            <div class="govA">{{ reportData.organization_address_province_district }}</div>
            <div class="govA">{{ reportData.organization_address_municipality_ward }}</div>
            <div class="govA">{{ reportData.organization_address }}</div>
          </div>

          <div class="titleSide">
            <div>Phone: {{ reportData.organization_phone }}</div>
            <div> E-mail: {{ reportData.organization_email }}</div>
            <div class="date">Date: {{ ad2bs(reportData.current_date) }}</div>
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
                Name : {{ reportData.name || '.................' }} <br>
                Age : {{ reportData.formatted_age || '.................' }} <br>
                Gender : {{ reportData.gender || '.................' }} <br>
                Phone No : {{ reportData.phone_no || '.................' }}<br>
                Address : {{ reportData.address || '.................' }}
              </div>
              <div class="col-xs-6 text-right">
                Patient No : {{ reportData.patient_no || '.................' }}<br>
                Sample No : {{ reportData.sample_no || '.................' }}<br>
                Sample Collected Date : {{ ad2bs(reportData.sample_collected_date) || '.................' }}<br>
                Lab No : {{ reportData.lab_no || '.................' }}<br>
                Sample Received Date : {{ reportData.sample_received_date || '.................' }}<br>
                Date & Time of Analysis : {{ reportData.date_and_time_of_analysis || '.................' }}<br>
              </div>
            </div>
          </div>
        </div>
        <br>
        <br>
        <div class="row">
          <div class="col-md-12">
              <h4>IMU Nepal Lab Report</h4>
              <h5 v-if="reportData.sample_tested_by"><strong>Sample Tested By : {{ reportData.sample_tested_by }}</strong></h5>
                <div class="table-responsive">
                  <table class="table table-condensed">
                    <thead>
                    <tr>
                      <td><strong>Test</strong></td>
                      <td><strong>Result</strong></td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                      <td>{{ reportData.test_type || '.................' }}</td>
                      <td>{{ reportData.test_result || '.................' }}</td>
                    </tr>
                    </tbody>
                  </table>
                </div>
          </div>
        </div>
        <div class="row">
          <br><br>
          <div class="col-xs-6">
            <br>
            -------------------------------<br>
            Verified by <br>
            Name : <br>
            Post :
          </div>
        </div>
        <br><br><br><br>
        <div class="text-center">This is an electronically generated copy from https://imucovid19.mohp.gov.np</div>
      </div>
    </div>
    </div>
    </div>
</template>

<script type="text/javascript">
import axios from 'axios'
import {required, minLength} from 'vuelidate/lib/validators'
import DataConverter from "ad-bs-converter";

export default {
  data() {
    return {
      token : '',
      data : [],
      reportData : [],
      serverdate: ''
    }
  },
  validations: {
    token: {
      required,
      minLength: minLength(2)
    }
  },
  methods: {
    newLink() {
      window.location.href = window.location.protocol + '/admin/lab-case-report-old';
    },

    searchResult(token){
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      let url = window.location.protocol + '/api/v1/check-by-sid-or-lab-id?token='+token;
      axios.get(url)
          .then((response) => {
            this.data = response.data;
            if (this.data.length === 0){
              this.$swal({
                position: 'top-end',
                type: 'error',
                title: 'No record found ',
                showConfirmButton: false,
                timer: 1500
              });
            }
          })
          .catch((error) => {
            console.error(error)
          })
          .finally(() => {
          })
    },
    generateReport(token){
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      if(this.data.length === 0){
        let url = window.location.protocol + '/api/v1/check-by-sid-or-lab-id?token='+token;
        axios.get(url)
            .then((response) => {
              this.data = response.data;
              this.reportData = response.data;

              if (response.data.len == 0){
                this.$swal({
                  position: 'top-end',
                  type: 'error',
                  title: 'No record found ',
                  showConfirmButton: false,
                  timer: 1500
                });
              }
            })
            .catch((error) => {
              console.error(error)
            })
            .finally(() => {
            })
      }
      this.reportData = this.data;
    },
    ad2bs: function (date) {
      if(date === undefined){
        return '';
      }

      let url = window.location.protocol + '/api/v1/server-date';
      axios.get(url)
          .then((response) => {
            this.serverdate = response.data.date;
          });

      var dateObject = new Date(this.serverdate);

      var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return dateConverter.en.year + '-' + dateConverter.en.month + '-' + dateConverter.en.day;


    },
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
  }
}
</script>
<style scoped>

.swal2-container {
  z-index: 10000;
}

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

</style>