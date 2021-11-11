<template>
  <div class="row">
      <div class="col-lg-12">
        <h3>{{ headingTitle }}'s Update &nbsp; &nbsp;
        </h3>
      </div>
        <div class="col-md-4">
          <div class="panel panel-danger form-group">
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
              <option value="0">Total</option>
            </select>
          </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="col-lg-3 col-md-3">
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
                        <div class="huge">{{ report.a_form_completed }}</div>
                        <div>Completed A Form</div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
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
                            <div class="huge">{{ report.a_form_incomplete }}</div>
                            <div>Incomplete A Form</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
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
                            <div class="huge">{{ report.b1_form_completed }}</div>
                            <div>Completed B1 Form</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
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
                            <div class="huge">{{ report.b1_form_incomplete }}</div>
                            <div>Incomplete B1 Form</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="clearfix"></div>
        <hr>
        <div class="col-lg-12">
            <h3 class="text-center" style="padding-bottom: 10px;">TOTAL REPORTING</h3>
        </div>
        <div class="col-lg-3 col-md-3">
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
                        <div class="huge">{{ totalReport.a_form_completed }}</div>
                        <div>Completed A Form</div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-minus-square-o fa-3x"></i>
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
                            <div class="huge">{{ totalReport.a_form_incomplete }}</div>
                            <div>Incomplete A Form</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
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
                            <div class="huge">{{ totalReport.b1_form_completed }}</div>
                            <div>Completed B1 Form</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-minus-square-o fa-3x"></i>
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
                            <div class="huge">{{ totalReport.b1_form_incomplete }}</div>
                            <div>Incomplete B1 Form</div>
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
      totalReport: [],
      report: [],
      headingTitle: 'Today',
      dayVal: 1
    }
  },
  created: function () {
    let url = window.location.protocol + '/data/api/cict-municipality-data-total';
    axios.get(url)
    .then((response) => {
    this.totalReport = response.data;
    })
    .catch((error) => {
    console.error(error)
    });

    url = window.location.protocol + '/data/api/cict-municipality-data';
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
  methods: {
    onDateChange: function(event) {
      this.headingTitle = event.target.selectedOptions[0].text;
      var federalInfo = JSON.stringify(this.$federalInfo);
      this.report = []
      let url = window.location.protocol + '/data/api/cict-municipality-data?date_selected=' + event.target.value;
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
      dates['today'] = 'Today'
      dates['yesterday'] = 'Yesterday'
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