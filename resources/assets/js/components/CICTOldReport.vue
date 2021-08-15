<template>
  <div class="row">
    <div>
      <div class="col-lg-6">
        <h3>CICT Old Data Report</h3>
      </div>
      <div class="col-lg-12" style="margin-bottom: 20px;">
        <div class="col-md-3">
          <div class="panel panel-danger">
            <input type="date" class="form-control" v-model.trim="report.date_from">
          </div>
        </div>
        <div class="col-md-3">
          <div class="panel panel-danger">
            <input type="date" class="form-control" v-model.trim="report.date_to">
          </div>
        </div>
        <div class="col-md-3">
        <button type="submit" @click="submitData(report)" class="btn btn-primary"><i class="fa fa-search" aria-hidden="true"></i> Search</button>
        </div>
      </div>
      <div class="clearfix"></div>
      
      <div class="col-lg-12">
        <div class="col-lg-3 col-md-6">
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
                  <div class="huge">{{ report.positive_count }}</div>
                  <div>Positive</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-info">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-file-text-o fa-3x"></i>
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
                  <div class="huge">{{ report.case_mgmt_count }}</div>
                  <div>A form</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-file-o fa-3x"></i>
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
                  <div class="huge">{{ report.contact_tracing_count }}</div>
                  <div>B1 Form</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-success">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-file-o fa-3x"></i>
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
                  <div class="huge">{{ report.contact_followup_count }}</div>
                  <div>B2 Form</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
      <hr>
      <div class="col-lg-12">
        <h3 class="text-center" style="padding-bottom: 10px;">OVERALL REPORTING</h3>
      </div>
      <div class="col-lg-12">
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-danger">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-plus-square-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">{{ totalReport.positive }}</div>
                  <div>Positive</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-info">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-file-text-o fa-3x"></i>
                </div>
                <div class="col-xs-9 text-right">
                  <div class="huge">{{ totalReport.case_mgmt }}</div>
                  <div>A Form</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-warning">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-file-o fa-3x" aria-hidden="true"></i>
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
                  <div class="huge">{{ totalReport.contact_tracing }}</div>
                  <div>B1 Form</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6">
          <div class="panel panel-success">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-3">
                  <i class="fa fa-file-o fa-3x"></i>
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
                  <div class="huge">{{ totalReport.contact_followup }}</div>
                  <div>B2 Form</div>
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

export default {
  data() {
    return {
      indeterminate: true,
      progress: 100,
      date_from: '',
      date_to: '',
      totalReport: [],
      report: []
    }
  },
  created: function () {
    let url = window.location.protocol + '/api/v1/old-cict-total';
    axios.get(url)
      .then((response) => {
        this.totalReport = response.data;
      })
      .catch((error) => {
        console.error(error)
      });
    url = window.location.protocol + '/api/v1/old-cict-datewise';
    axios.get(url)
      .then((response) => {
        this.report = response.data;
      })
      .catch((error) => {
        console.error(error)
      });
    },
  methods: {
    submitData(data){
      axios.get('/api/v1/old-cict-datewise?date_from=' + data.date_from + '&date_to=' + data.date_to)
        .then((response) => {
          this.report = response.data;
        })
        .catch((error) => {
          console.error(error)
        })
        .finally(() => {
        });
    }
  }
}
</script>

<style scoped>


</style>