<template>
  <div class="row">
    <div v-if="!checkHospitalTypeForOrganization()">
      <div class="col-lg-12"
           v-if="checkHospitalTypeForSampleFeature() || checkPermission('cases-registration') || !checkDataEntryRole()">
        <h3>{{ headingTitle() }} <sub>| Registered : {{ report.registered_in_24_hrs }}</sub>
          <small v-if="!report.cache_created_at" class="pull-right">loading...</small>
          <small v-else class="pull-right">Updated at : {{ recordUpdatedAt() }}</small>
        </h3>
      </div>
      <div class="col-lg-3 col-md-6"
           v-if="checkHospitalTypeForSampleFeature() || checkPermission('sample-collection') || !checkDataEntryRole()">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-flask fa-3x"></i>
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
                <div class="huge">{{ report.sample_collection_in_24_hrs }}</div>
                <div>
                  RAT : {{ report.sample_collection_in_24_hrs_antigen }} <br>
                  PCR : {{ report.sample_collection_in_24_hrs - report.sample_collection_in_24_hrs_antigen }}
                </div>
                <div>Swab Collection</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6"
           v-if="checkHospitalTypeForSampleFeature() || checkPermission('sample-collection') || !checkDataEntryRole()">
        <div class="panel panel-warning">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-check-square-o fa-3x"></i>
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
                <div class="huge">{{ report.sample_received_in_lab_in_24_hrs }}</div>
                <div>
                  RAT : {{ report.sample_received_in_lab_in_24_hrs_antigen }} <br>
                  PCR : {{ report.sample_received_in_lab_in_24_hrs - report.sample_received_in_lab_in_24_hrs_antigen }}
                </div>
                <div>Lab Received</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6"
           v-if="checkHospitalTypeForSampleFeature() || checkPermission('sample-collection') || !checkDataEntryRole()">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-frown-o fa-3x"></i>
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
                <div class="huge">{{ report.lab_result_positive_in_24_hrs }}</div>
                <div>
                  RAT : {{ report.lab_result_positive_in_24_hrs_antigen }} <br>
                  PCR : {{ report.lab_result_positive_in_24_hrs - report.lab_result_positive_in_24_hrs_antigen }}
                </div>
                <div>Positive</div>
              </div>
            </div>
          </div>
          <a href="/admin/download/positive-list">
            <div class="panel-footer">
              <span class="pull-left"><i class="fa fa-download"></i> Download</span>
              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-lg-3 col-md-6"
           v-if="checkHospitalTypeForSampleFeature() || checkPermission('sample-collection') || !checkDataEntryRole()">
        <div class="panel panel-success">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-smile-o fa-3x"></i>
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
                <div class="huge">{{ report.lab_result_negative_in_24_hrs }}</div>
                <div>
                  RAT : {{ report.lab_result_negative_in_24_hrs_antigen }} <br>
                  PCR : {{ report.lab_result_negative_in_24_hrs - report.lab_result_negative_in_24_hrs_antigen }}
                </div>
                <div>Negative</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12"
           v-if="checkHospitalTypeForSampleFeature() || checkPermission('cases-registration') || !checkDataEntryRole()">
        <h3>
          Total Records <sub> | Registered : {{ report.registered }}</sub></h3>
      </div>
      <div v-if="checkHospitalTypeForSampleFeature() || checkPermission('sample-collection') || !checkDataEntryRole()"
           class="col-lg-3 col-md-6">
        <div class="panel panel-info">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-flask fa-3x"></i>
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
                <div class="huge">{{ report.sample_collection }}</div>
                <div>
                  RAT : {{ report.sample_collection_antigen }} <br>
                  PCR : {{ report.sample_collection - report.sample_collection_antigen }}
                </div>
                <div>Swab Collection</div>
              </div>
            </div>
          </div>
          <a href="/admin/patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div v-if="checkHospitalTypeForSampleFeature() || checkPermission('sample-collection') || !checkDataEntryRole()"
           class="col-lg-3 col-md-6">
        <div class="panel panel-warning">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-check-square-o fa-3x"></i>
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
                <div class="huge">{{ report.sample_received_in_lab }}</div>
                <div>
                  RAT : {{ report.sample_received_in_lab_antigen }} <br>
                  PCR : {{ report.sample_received_in_lab - report.sample_received_in_lab_antigen }}
                </div>
                <div>Lab Received</div>
              </div>
            </div>
          </div>
          <a href="/admin/lab-received-patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div v-if="checkHospitalTypeForSampleFeature() || checkPermission('sample-collection') || !checkDataEntryRole()"
           class="col-lg-3 col-md-6">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-frown-o fa-3x"></i>
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
                <div class="huge">{{ report.lab_result_positive }}</div>
                <div>
                  RAT : {{ report.lab_result_positive_antigen }} <br>
                  PCR : {{ report.lab_result_positive - report.lab_result_positive_antigen }}
                </div>
                <div>Positive</div>
              </div>
            </div>
          </div>
          <a href="/admin/positive-patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div v-if="checkHospitalTypeForSampleFeature() || checkPermission('sample-collection') || !checkDataEntryRole()"
           class="col-lg-3 col-md-6">
        <div class="panel panel-success">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-smile-o fa-3x"></i>
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
                <div class="huge">{{ report.lab_result_negative }}</div>
                <div>
                  RAT : {{ report.lab_result_negative_antigen }} <br>
                  PCR : {{ report.lab_result_negative - report.lab_result_negative_antigen }}
                </div>
                <div>Negative</div>
              </div>
            </div>
          </div>
          <a href="/admin/negative-patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
        <div><mark>*RAT = Rapid Antigen Test  and <br> *PCR = SARS-CoV-2 RNA Test</mark></div>
      </div>
      <div class="col-lg-12" v-if="checkHospitalTypeForLabFeature() || checkPermission('lab-received')">
        <h3>Today's update in Lab
          <small v-if="!report.cache_created_at" class="pull-right">loading...</small>
          <small v-else class="pull-right">Updated at : {{ recordUpdatedAt() }}</small>
        </h3>
      </div>
      <div v-if="checkHospitalTypeForLabFeature() || checkPermission('lab-received')" class="col-lg-4 col-md-6">
        <div class="panel panel-warning">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-check-square-o fa-3x"></i>
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
                <div class="huge">{{ report.in_lab_received_in_24_hrs }}</div>
                <div>Lab Received</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="checkHospitalTypeForLabFeature() || checkPermission('lab-received')" class="col-lg-4 col-md-6">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-frown-o fa-3x"></i>
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
                <div class="huge">{{ report.in_lab_received_positive_in_24_hrs }}</div>
                <div>Positive</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="checkHospitalTypeForLabFeature() || checkPermission('lab-received')" class="col-lg-4 col-md-6">
        <div class="panel panel-success">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-smile-o fa-3x"></i>
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
                <div class="huge">{{ report.in_lab_received_negative_in_24_hrs }}</div>
                <div>Negative</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div v-if="checkHospitalTypeForLabFeature() || checkPermission('lab-received')" class="col-lg-12">
        <h3>Total Records in lab</h3>
      </div>
      <div v-if="checkHospitalTypeForLabFeature() || checkPermission('lab-received')" class="col-lg-4 col-md-6">
        <div class="panel panel-warning">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-check-square-o fa-3x"></i>
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
                <div class="huge">{{ report.in_lab_received }}</div>
                <div>Lab Received</div>
              </div>
            </div>
          </div>
          <a href="/admin/lab-patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div v-if="checkHospitalTypeForLabFeature() || checkPermission('lab-received')" class="col-lg-4 col-md-6">
        <div class="panel panel-danger">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-frown-o fa-3x"></i>
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
                <div class="huge">{{ report.in_lab_received_positive }}</div>
                <div>Positive</div>
              </div>
            </div>
          </div>
          <a href="/admin/lab-positive-patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div v-if="checkHospitalTypeForLabFeature() || checkPermission('lab-received')" class="col-lg-4 col-md-6">
        <div class="panel panel-success">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-3">
                <i class="fa fa-smile-o fa-3x"></i>
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
                <div class="huge">{{ report.in_lab_received_negative }}</div>
                <div>Negative</div>
              </div>
            </div>
          </div>
          <a href="/admin/lab-negative-patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-lg-12" v-show="false">
        <h3>5 Days Trend Data</h3>
      </div>
      <div class="col-lg-12" v-show="false">
        <table class="table table-bordered ">
          <thead>
          <tr>
            <th rowspan="3" style="text-align: center; background-color: #dff0d8;">Date</th>
            <th colspan="6" style="text-align: center; background-color: #d9edf7;">Inside Organization</th>
            <th colspan="4" style="text-align: center; background-color: #d9edf7;">Outside Organization</th>
          </tr>
          <tr>
            <th colspan="3" style="text-align: center; background-color: #f2dede;">PCR</th>
            <th colspan="3" style="text-align: center; background-color: #f2dede;">Antigen</th>
            <th colspan="2" style="text-align: center; background-color: #f2dede;">PCR</th>
            <th colspan="2" style="text-align: center; background-color: #f2dede;">Antigen</th>
          </tr>
          <tr>
            <th style="text-align: center; background-color: #dff0d8;">Swab Collection</th>
            <th style="text-align: center; background-color: #dff0d8;">Positive</th>
            <th style="text-align: center; background-color: #dff0d8;">Negative</th>
            <th style="text-align: center; background-color: #dff0d8;">Swab Collection</th>
            <th style="text-align: center; background-color: #dff0d8;">Positive</th>
            <th style="text-align: center; background-color: #dff0d8;">Negative</th>
            <th style="text-align: center; background-color: #dff0d8;">Positive</th>
            <th style="text-align: center; background-color: #dff0d8;">Negative</th>
            <th style="text-align: center; background-color: #dff0d8;">Positive</th>
            <th style="text-align: center; background-color: #dff0d8;">Negative</th>
          </tr>
          </thead>
          <tbody v-if="report.sample_5_trends">
          <tr v-for="(inside, key) in report.sample_5_trends">
            <td style="text-align: center; background-color: #fcf8e3;">{{ key }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.inside_pcr_count ? inside.inside_pcr_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.inside_pcr_postive_cases_count ? inside.inside_pcr_postive_cases_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.inside_pcr_negative_cases_count ? inside.inside_pcr_negative_cases_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.inside_antigen_count ? inside.inside_antigen_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.inside_antigen_postive_cases_count ? inside.inside_antigen_postive_cases_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.inside_antigen_negative_cases_count ? inside.inside_antigen_negative_cases_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.outside_pcr_postive_cases_count ? inside.outside_pcr_postive_cases_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.outside_pcr_negative_cases_count ? inside.outside_pcr_negative_cases_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.outside_antigen_postive_cases_count ? inside.outside_antigen_postive_cases_count : 0 }}</td>
            <td style="text-align: center; background-color: #fcf8e3;">{{ inside.outside_antigen_negative_cases_count ? inside.outside_antigen_negative_cases_count : 0 }}</td>
          </tr>
          </tbody>
          <tbody v-else>
              <tr>
                  <td colspan="11">No data found.</td>
              </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import DataConverter from 'ad-bs-converter'

export default {
  data() {
    return {
      indeterminate: true,
      progress: 100,
      report: []
    }
  },
  created: function () {
    let url = window.location.protocol + '/data/api/admin/dashboard';
    axios.get(url)
        .then((response) => {
          this.report = response.data;
        })
        .catch((error) => {
          console.error(error)
        })
        .finally(() => {
        })
  },
  methods: {
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
    headingTitle(){
      // var today = new Date().toISOString().slice(0, 10);
      // var check_at_1330 = new Date(today + ' 13:30:00');

      // if(check_at_1330 < new Date()){
      //   return 'Today 1:30 pm to Now';
      // }else{
        return 'Today\'s  Update';
      // }
    }
  },
}
</script>

<style scoped>


</style>