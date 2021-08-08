<template>
  <div class="row">
    <div>
      <div class="col-lg-6">
        <h3>{{ headingTitle }}'s Update</h3>
      </div>
      <div class="form-group col-sm-6" style="padding-top: 20px">
          
          <i>*Total Screened: No. of total person registered in the POE</i> <br />
          <i>*Registered Only: No. of registered person who are not tested</i><br />
          <i>*Pending/Lab Received: No. of tested person whose results are not entered</i><br />
          <i>*Positivity Rate(in %): PR = Total Positve Case/(Total Positive Case + Total Negative Case) * 100 </i><br />
      </div>
      <div class="col-lg-12" style="margin-bottom: 20px;">
        <div class="col-md-3">
          <div class="panel panel-danger">
            <select name="date_selected" @change="onDateChange($event)" class="form-control" v-model="dayVal">
              <option value="" disabled>Select Day</option>
              <option value="1">{{ getDates().today }}</option>
              <option value="2">{{ getDates().yesterday }}</option>
              <option value="3">{{ getDates().twoDaysAgoFormatted}}</option>
              <option value="4">{{ getDates().threeDaysAgoFormatted}}</option>
              <option value="5">{{ getDates().fourDaysAgoFormatted}}</option>
              <option value="6">{{ getDates().fiveDaysAgoFormatted}}</option>
              <option value="7">{{ getDates().sixDaysAgoFormatted}}</option>
              <option value="8">{{ getDates().sevenDaysAgoFormatted}}</option>
            </select>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      
      <div class="col-lg-6">
        <h3 class="text-center" style="padding-bottom: 10px;">Registrations</h3>
        <div class="col-lg-6 col-md-6">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-plus-square-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div v-if="Object.keys(report).length === 0">
                    <loading-progress
                        :progress="progress"
                        :indeterminate="indeterminate"
                        shape="line"
                        size="30"
                    />
                  </div>
                  <div class="huge">{{ report.total_registered_all }}</div>
                  <div>Total Screened</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="panel panel-success">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-minus-square-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div v-if="Object.keys(report).length === 0">
                    <loading-progress
                        :progress="progress"
                        :indeterminate="indeterminate"
                        shape="line"
                        size="30"
                    />
                  </div>
                  <div class="huge">{{ report.total_registered_only }}</div>
                  <div>Registered Only</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <h3 class="text-center" style="padding-bottom: 10px;">
          Antigen 
          <span style="font-size: 14px;" v-if="Object.keys(report).length !== 0">
            (Positivity Rate: {{report.pr}})
          </span>
        </h3>
        <div class="col-lg-6 col-md-6">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-plus-square-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div v-if="Object.keys(report).length === 0">
                    <loading-progress
                        :progress="progress"
                        :indeterminate="indeterminate"
                        shape="line"
                        size="30"
                    />
                  </div>
                  <div class="huge">{{ report.antigen_positive }}</div>
                  <div>Positive</div>
                </div>
              </div>
            </div>
            <a :href="'/admin/download/positive-list-poe-by-date?result_chosen=3&amp;service_for_chosen=2&amp;date_chosen=' + headingTitle">
              <div class="panel-footer">
                <span class="pull-left"><i class="fa fa-download"></i> Download</span>
                <div class="clearfix"></div>
              </div>
            </a>
          </div>
        </div>
        <div class="col-lg-6 col-md-6">
          <div class="panel panel-success">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-minus-square-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div v-if="Object.keys(report).length === 0">
                    <loading-progress
                        :progress="progress"
                        :indeterminate="indeterminate"
                        shape="line"
                        size="30"
                    />
                  </div>
                  <div class="huge">{{ report.antigen_negative }}</div>
                  <div>Negative</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="clearfix"></div>
      <hr>

      <div class="col-lg-6">
        <h3>Total Records</h3>
      </div>
      
      <div class="clearfix"></div>
      <div class="col-lg-3">
        <h3 class="text-center" style="padding-bottom: 10px;">Registrations</h3>
        <div class="col-lg-12 col-md-12">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-plus-square-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div v-if="Object.keys(report).length === 0">
                    <loading-progress
                        :progress="progress"
                        :indeterminate="indeterminate"
                        shape="line"
                        size="30"
                    />
                  </div>
                  <div class="huge">{{ totalReport.registered }}</div>
                  <div>Total Screened</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="col-lg-9">
        <h3 class="text-center" style="padding-bottom: 10px;">Antigen</h3>
        <div class="col-lg-4 col-md-4">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-plus-square-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div v-if="Object.keys(totalReport).length === 0">
                    <loading-progress
                        :progress="progress"
                        :indeterminate="indeterminate"
                        shape="line"
                        size="30"
                    />
                  </div>
                  <div class="huge">{{ totalReport.lab_result_positive_antigen }}</div>
                  <div>Positive</div>
                </div>
              </div>
            </div>
            <!-- <a href="/admin/download/positive-list-poe">
              <div class="panel-footer">
                <span class="pull-left"><i class="fa fa-download"></i> Download</span>
                <div class="clearfix"></div>
              </div>
            </a> -->
          </div>
        </div>
        <div class="col-lg-4 col-md-4">
          <div class="panel panel-success">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-minus-square-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div v-if="Object.keys(report).length === 0">
                    <loading-progress
                        :progress="progress"
                        :indeterminate="indeterminate"
                        shape="line"
                        size="30"
                    />
                  </div>
                  <div class="huge">{{ totalReport.lab_result_negative_antigen }}</div>
                  <div>Negative</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import DataConverter from 'ad-bs-converter';
import moment from 'moment';

export default {
  data() {
    return {
      indeterminate: true,
      progress: 100,
      report: [],
      totalReport: [],
      headingTitle: 'Today',
      dayVal: 1
    }
  },
  created: function () {
    let url = window.location.protocol + '/data/api/admin/poe-dashboard';
    axios.get(url)
        .then((response) => {
          this.totalReport = response.data;
        })
        .catch((error) => {
          console.error(error)
        });
    url = window.location.protocol + '/data/api/admin/poe-dashboard-by-date';
    axios.get(url)
        .then((response) => {
          this.report = response.data;
        })
        .catch((error) => {
          console.error(error)
        });
  },
  methods: {
    onDateChange: function(event) {
      this.headingTitle = event.target.selectedOptions[0].text;
      let url = window.location.protocol + '/data/api/admin/poe-dashboard-by-date?date_selected=' + event.target.value;
      axios.get(url)
        .then((response) => {
          this.report = response.data;
        })
        .catch((error) => {
          console.error(error)
        })
        .finally(() => {
        });
    },
    recordUpdatedAt: function () {
      var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

      var dateObject = new Date(this.report.cache_created_at);

      var dateFormat = dateObject.getFullYear() + "/" + (dateObject.getMonth() + 1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return days[dateObject.getDay()] + ', ' + dateConverter.en.day + ' ' + dateConverter.en.strMonth + ' ' + dateConverter.en.year + '  ' + dateObject.toLocaleTimeString();
    },
    checkPermission(value) {
      var arr = this.$userPermissions.split(',');
      return arr.includes(value);
    },
    checkDataEntryRole() {
      var arr = ['fchv', 'healthworker'];
      return arr.includes(this.$userRole);
    },
    checkHospitalTypeForSampleFeature() {
      return this.$hospitalType === '1' || this.$hospitalType === '3';
    },
    checkHospitalTypeForLabFeature() {
      return this.$hospitalType === '2' || this.$hospitalType === '3';
    },
    checkHospitalTypeForOrganization() {
      return this.$hospitalType === '4';
    },

    getDates() {
      var date = new Date();
      var todayFormatted = moment(String(date)).format('YYYY-MM-DD');
      var yesterday = new Date(date.getTime() - (1 * 24 * 60 * 60 * 1000));
      var yesterdayFormatted = moment(String(yesterday)).format('YYYY-MM-DD');
      var twoDaysAgo = new Date(date.getTime() - (2 * 24 * 60 * 60 * 1000));
      var twoDaysAgoFormatted = moment(String(twoDaysAgo)).format('YYYY-MM-DD');
      var threeDaysAgo = new Date(date.getTime() - (3 * 24 * 60 * 60 * 1000));
      var threeDaysAgoFormatted = moment(String(threeDaysAgo)).format('YYYY-MM-DD');
      var fourDaysAgo = new Date(date.getTime() - (4 * 24 * 60 * 60 * 1000));
      var fourDaysAgoFormatted = moment(String(fourDaysAgo)).format('YYYY-MM-DD');
      var fiveDaysAgo = new Date(date.getTime() - (5 * 24 * 60 * 60 * 1000));
      var fiveDaysAgoFormatted = moment(String(fiveDaysAgo)).format('YYYY-MM-DD');
      var sixDaysAgo = new Date(date.getTime() - (6 * 24 * 60 * 60 * 1000));
      var sixDaysAgoFormatted = moment(String(sixDaysAgo)).format('YYYY-MM-DD');
      var sevenDaysAgo = new Date(date.getTime() - (7 * 24 * 60 * 60 * 1000));
      var sevenDaysAgoFormatted = moment(String(sevenDaysAgo)).format('YYYY-MM-DD');

      var dates = [];
      dates['today'] = 'Today',
      dates['yesterday'] = 'Yesterday',
      dates['todayFormatted'] = todayFormatted;
      dates['yesterdayFormatted'] = yesterdayFormatted;
      dates['twoDaysAgoFormatted'] = twoDaysAgoFormatted;
      dates['threeDaysAgoFormatted'] = threeDaysAgoFormatted;
      dates['fourDaysAgoFormatted'] = fourDaysAgoFormatted;
      dates['fiveDaysAgoFormatted'] = fiveDaysAgoFormatted;
      dates['sixDaysAgoFormatted'] = sixDaysAgoFormatted;
      dates['sevenDaysAgoFormatted'] = sevenDaysAgoFormatted;

      return dates;
    }
  }
}
</script>

<style scoped>


</style>