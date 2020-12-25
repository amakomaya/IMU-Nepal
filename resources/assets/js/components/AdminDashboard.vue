<template>
  <div class="row">
    <div v-if="this.$userRole == 'fchv'">
      <div class="col-lg-12">
        <h3>Last 24 hours update </h3>
      </div>
      <div class="col-lg-4 col-md-6">
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
      <div class="col-lg-4 col-md-6">
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
      <div class="col-lg-4 col-md-6">
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
      <div class="col-lg-12">
        <hr>
        <h3>Total Records </h3>
      </div>
      <div class="col-lg-4 col-md-6">
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
          <a href="/admin/lab-received-patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
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
          <a href="/admin/positive-patients">
            <div class="panel-footer">
              <span class="pull-left">View Details</span>
              <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

              <div class="clearfix"></div>
            </div>
          </a>
        </div>
      </div>
      <div class="col-lg-4 col-md-6">
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
        <a href="/admin/negative-patients">
          <div class="panel-footer">
            <span class="pull-left">View Details</span>
            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>

            <div class="clearfix"></div>
          </div>
        </a>
      </div>
    </div>
    </div>
    <div v-else>
      <div class="col-lg-12">
        <h3>Last 24 hours update | <sub> Registered : {{ report.registered_in_24_hrs }}</sub></h3>
      </div>
      <div class="col-lg-3 col-md-6">
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
                <div>Swab Collection</div>
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
                <div>Lab Received</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-3 col-md-6">
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
                <div>Positive</div>
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
                <div>Negative</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-lg-12">
        <hr>
        <h3>Total Records | <sub> Registered : {{ report.registered }}</sub></h3>
      </div>
      <div class="col-lg-3 col-md-6">
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
      <div class="col-lg-3 col-md-6">
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
      <div class="col-lg-3 col-md-6">
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
      <div class="col-lg-3 col-md-6">
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
      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";

export default {
  data(){
    return {
      report : []
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
  }
}
</script>

<style scoped>


</style>