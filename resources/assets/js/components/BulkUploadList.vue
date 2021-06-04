<template>
  <div>
    <h2>Bulk Upload</h2>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Name</th>
          <th>Description</th>
          <th>Template</th>
          <th>Bulk Upload</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Lab Received(PCR/Antigen)</td>
          <td>Create Lab Received(PCR/Antigen) by entering SID (15 digit IMU genereted code) & Patient Lab ID(Unique ID for your patient for external/own reference.) </td>
          <td><a href="/downloads/excel/lab_received_template.xlsx" onclick="return confirm('Are you sure, do you want to download import format ! ')" title="Do you have template ? If not, please download first and fill data than import.">Download Latest Template</a></td>
          <td>
            <div v-if="checkPermission('lab-received')">
            <label for="bulk_file_lab_received" class="btn btn-primary">Bulk Upload
              <i class="fa fa-upload" aria-hidden="true"></i>
            </label>
            <input type="file" id="bulk_file_lab_received" ref="bulk_file_lab_received" v-on:change="handleFileUpload('bulk_file_lab_received')" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="visibility:hidden;"/>
            </div>
            <div v-else><span class="label label-primary"> Permission not granted. </span></div>
          </td>
        </tr>
        <tr>
          <td>Lab Results (PCR/Antigen)</td>
          <td>Update Lab Results of existing "Lab Received" by entering Patient Lab ID (Unique ID for your patient for external/own reference) & Result (Positive/Negative)</td>
          <td><a href="/downloads/excel/lab_result_template.xlsx" onclick="return confirm('Are you sure, do you want to download import format ! ')" title="Do you have template ? If not, please download first and fill data than import.">Download Latest Template</a></td>
          <td>
            <div v-if="checkPermission('lab-result')||checkPermission('antigen-result')">
              <label for="bulk_file_lab_result" class="btn btn-primary">Bulk Upload
                <i class="fa fa-upload" aria-hidden="true"></i>
              </label>
              <input type="file" id="bulk_file_lab_result" ref="bulk_file_lab_result" v-on:change="handleFileUpload('bulk_file_lab_result')" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="visibility:hidden;"/>
            </div>
            <div v-else><span class="label label-primary"> Permission not granted. </span></div>
          </td>
        </tr>
        <tr>
          <td>Lab Received & Results</td>
          <td>Create new "Lab Received"(PCR/Antigen) & update "Lab Results"(PCR/Antigen).</td>
          <td><a href="/downloads/excel/lab_received_result_template.xlsx" onclick="return confirm('Are you sure, do you want to download import format ! ')" title="Do you have template ? If not, please download first and fill data than import.">Download Latest Template</a></td>
          <td>
            <div v-if="(checkPermission('antigen-result') || checkPermission('lab-result')) && checkPermission('lab-received')">
            <label for="bulk_file_lab_received_result" class="btn btn-primary">Bulk Upload
              <i class="fa fa-upload" aria-hidden="true"></i>
            </label>
            <input type="file" id="bulk_file_lab_received_result" ref="bulk_file_lab_received_result" v-on:change="handleFileUpload('bulk_file_lab_received_result')" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="visibility:hidden;"/>
            </div>
            <div v-else><span class="label label-primary"> Permission not granted. </span></div>
          </td>
        </tr>
        <tr>
          <td>Registration & Sample Collection</td>
          <td>Register new "Registered/Pending case" & create "sample collection" (you'll get an imu generated 15 digit code).</td>
          <td><a href="/downloads/excel/registration_sample_collection_template.xlsx" onclick="return confirm('Are you sure, do you want to download import format ! ')" title="Do you have template ? If not, please download first and fill data than import.">Download Latest Template</a></td>
          <td>
            <div v-if="checkPermission('sample-collection') && checkPermission('cases-registration')">
              <label for="bulk_file_registration_sample_collection" class="btn btn-primary">Bulk Upload
                <i class="fa fa-upload" aria-hidden="true"></i>
              </label>
              <input type="file" id="bulk_file_registration_sample_collection" ref="bulk_file_registration_sample_collection" v-on:change="handleFileUpload('bulk_file_registration_sample_collection')" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="visibility:hidden;"/>
            </div>
            <div v-else><span class="label label-primary"> Permission not granted. </span></div>
          </td>
        </tr>
        <tr>
          <td>Registration, Sample Collection & Lab Received/Results(PCR/Antigen)</td>
          <td>Register new "New Case"(Case Type will vary according to the Lab Received), create "Sample Collection" ,create "Lab Received"(PCR/Antigen) & update "Lab Results"(PCR/Antigen).</td>
          <td><a href="/downloads/excel/registration_sample_collection_lab_tests_template.xlsx" onclick="return confirm('Are you sure, do you want to download import format ! ')" title="Do you have template ? If not, please download first and fill data than import.">Download Latest Template</a></td>
          <td>
            <div v-if="(checkPermission('antigen-result') || checkPermission('lab-result')) && checkPermission('lab-received') && checkPermission('sample-collection') && checkPermission('cases-registration')">
              <label for="bulk_file_registration_sample_collection_lab_test" class="btn btn-primary">Bulk Upload
                <i class="fa fa-upload" aria-hidden="true"></i>
              </label>
              <input type="file" id="bulk_file_registration_sample_collection_lab_test" ref="bulk_file_registration_sample_collection_lab_test" v-on:change="handleFileUpload('bulk_file_registration_sample_collection_lab_test')" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="visibility:hidden;"/>
            </div>
            <div v-else><span class="label label-primary"> Permission not granted. </span></div>
          </td>
        </tr>
      </tbody>
    </table>
    <div id="uploading-modal" class="modal" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Uploading</h5>
          </div>
          <div class="modal-body">
            <p>The bulk file is being uploaded...</p>
          </div>
          <div class="modal-footer">
           
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script type="text/javascript">
import axios from "axios";

export default {
  data() {
    return {
      bulk_file_lab_received: "",
      bulk_file_lab_result: "",
      bulk_file_lab_received_result: "",
      bulk_file_registration_sample_collection: "",
      bulk_file_registration_sample_collection_lab_test: "",
      isUploading: false,
    };
  },
  action: {
    isUploading(showModal) {
      let modalAction = showModal?"show":"hide";
      $('#uploading-modal').modal(modalAction);
    }
  },
  methods: {
    checkPermission(value) {
      var arr = this.$userPermissions.split(",");
      return arr.includes(value);
    },
    handleFileUpload(type) {
      this.isUploading = true;
      switch (type) {
        case "bulk_file_lab_received":
          this.bulk_file_lab_received = this.$refs.bulk_file_lab_received.files[0];
          this.submitBulkLabReceivedFile();
          return;
        case "bulk_file_lab_result":
          this.bulk_file_lab_result = this.$refs.bulk_file_lab_result.files[0];
          this.submitBulkLabResultFile();
          return;
        case "bulk_file_lab_received_result":
          this.bulk_file_lab_received_result = this.$refs.bulk_file_lab_received_result.files[0];
          this.submitBulkLabReceivedResultFile();
          return;
        case "bulk_file_registration_sample_collection":
          this.bulk_file_registration_sample_collection = this.$refs.bulk_file_registration_sample_collection.files[0];
          this.submitBulkRegistrationSampleCollectionFile();
          return;
        case "bulk_file_registration_sample_collection_lab_test":
          this.bulk_file_registration_sample_collection_lab_test = this.$refs.bulk_file_registration_sample_collection_lab_test.files[0];
          this.submitBulkRegistrationSampleCollectionLabTestFile();
          return;
        default:
          return;
      }
    },
    submitBulkLabResultFile() {
      let self = this;
      let formData = new FormData();
      if (!this.bulk_file_lab_result) {
        alert("Please upload a valid excel file");
        return;
      }
      formData.append("bulk_file_lab_result", this.bulk_file_lab_result);
      axios
        .post("/api/v1/bulk-upload/lab-result", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function (res) {
          self.isUploading = false;
          alert(res.data.message);
          $("#bulk_file_lab_result").val(null);
        })
        .catch(function (err) {
          let errorMsg;
          self.isUploading = false;
          if (err.response.status === 500) {
            errorMsg =
              "Please use the latest valid template downloaded from the system. If problem persists, please contact support.";
          } else {
            errorMsg =
              "The Lab Results could not be uploaded due to the following problems: \n";

            err.response.data.message.map((problem, index) => {
              let mainError = "";
              if (problem.error instanceof Object) {
                mainError = Object.values(problem.error).join(",");
              } else if (problem.error instanceof Array) {
                mainError = problem.error.join();
              }
              errorMsg +=
                index +
                1 +
                ". Row: " +
                problem.row +
                ", Column: " +
                problem.column +
                ", Error: " +
                mainError +
                "\n";
            });
          }
          alert(errorMsg);
          $("#bulk_file_lab_result").val(null);
        });
    },
    submitBulkLabReceivedFile(type) {
      let self = this;
      let formData = new FormData();
      if (!this.bulk_file_lab_received) {
        alert("Please upload a valid excel file");
        return;
      }
      formData.append("bulk_file_lab_received", this.bulk_file_lab_received);
      axios
        .post("/api/v1/bulk-upload/lab-received", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function (res) {
          self.isUploading = false;
          alert(res.data.message);
          $("#bulk_file_lab_received").val(null);
        })
        .catch(function (err) {
          self.isUploading = false;
          let errorMsg;
          if (err.response.status === 500) {
            errorMsg =
              "Please use the latest valid template downloaded from the system. If problem persists, please contact support.";
          } else {
            errorMsg =
              "The Lab Received could not be uploaded due to the following problems: \n";

            err.response.data.message.map((problem, index) => {
              let mainError = "";
              if (problem.error instanceof Object) {
                mainError = Object.values(problem.error).join(",");
              } else if (problem.error instanceof Array) {
                mainError = problem.error.join();
              }
              errorMsg +=
                index +
                1 +
                ". Row: " +
                problem.row +
                ", Column: " +
                problem.column +
                ", Error: " +
                mainError +
                "\n";
            });
          }
          alert(errorMsg);
          $("#bulk_file_lab_received").val(null);
        });
    },
    submitBulkLabReceivedResultFile(type) {
      let self = this;
      let formData = new FormData();
      if (!this.bulk_file_lab_received_result) {
        alert("Please upload a valid excel file");
        return;
      }
      formData.append(
        "bulk_file_lab_received_result",
        this.bulk_file_lab_received_result
      );
      axios
        .post("/api/v1/bulk-upload/lab-received-result", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function (res) {
          self.isUploading = false;
          alert(res.data.message);
          $("#bulk_file_lab_received_result").val(null);
        })
        .catch(function (err) {
          self.isUploading = false;
          let errorMsg;
          if (err.response.status === 500) {
            errorMsg =
              "Please use the latest valid template downloaded from the system. If problem persists, please contact support.";
          } else {
            errorMsg =
              "The Lab Received & Lab Result could not be uploaded due to the following problems: \n";

            err.response.data.message.map((problem, index) => {
              let mainError = "";
              if (problem.error instanceof Object) {
                mainError = Object.values(problem.error).join(",");
              } else if (problem.error instanceof Array) {
                mainError = problem.error.join();
              }
              errorMsg +=
                index +
                1 +
                ". Row: " +
                problem.row +
                ", Column: " +
                problem.column +
                ", Error: " +
                mainError +
                "\n";
            });
          }
          alert(errorMsg);
          $("#bulk_file_lab_received_result").val(null);
        });
    },
    submitBulkRegistrationSampleCollectionFile(type) {
      let self = this;
      let formData = new FormData();
      if (!this.bulk_file_registration_sample_collection) {
        alert("Please upload a valid excel file");
        return;
      }
      formData.append(
        "bulk_file_registration_sample_collection",
        this.bulk_file_registration_sample_collection
      );
      axios
        .post("/api/v1/bulk-upload/registration-sample-collection", formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function (res) {
          self.isUploading = false;
          alert(res.data.message);
          $("#bulk_file_registration_sample_collection").val(null);
        })
        .catch(function (err) {
          self.isUploading = false;
          let errorMsg;
          if (err.response.status === 500) {
            errorMsg =
              "Please use the latest valid template downloaded from the system. If problem persists, please contact support.";
          } else {
            errorMsg =
              "The Sample Collection could not be uploaded due to the following problems: \n";

            err.response.data.message.map((problem, index) => {
              let mainError = "";
              if (problem.error instanceof Object) {
                mainError = Object.values(problem.error).join(",");
              } else if (problem.error instanceof Array) {
                mainError = problem.error.join();
              }
              errorMsg +=
                index +
                1 +
                ". Row: " +
                problem.row +
                ", Column: " +
                problem.column +
                ", Error: " +
                mainError +
                "\n";
            });
          }
          alert(errorMsg);
          $("#bulk_file_registration_sample_collection").val(null);
        });
    },
    submitBulkRegistrationSampleCollectionLabTestFile(type) {
      let self = this;
      let formData = new FormData();
      if (!this.bulk_file_registration_sample_collection_lab_test) {
        alert("Please upload a valid excel file");
        return;
      }
      formData.append(
        "bulk_file_registration_sample_collection_lab_test",
        this.bulk_file_registration_sample_collection_lab_test
      );
      axios
        .post(
          "/api/v1/bulk-upload/registration-sample-collection-lab-test",
          formData,
          {
            headers: {
              "Content-Type": "multipart/form-data",
            },
          }
        )
        .then(function (res) {
          self.isUploading = false;
          alert(res.data.message);
          $("#bulk_file_registration_sample_collection_lab_test").val(null);
        })
        .catch(function (err) {
          self.isUploading = false;
          let errorMsg;
          if (err.response.status === 500) {
            errorMsg =
              "Please use the latest valid template downloaded from the system. If problem persists, please contact support.";
          } else {
            errorMsg =
              "The Sample Collection could not be uploaded due to the following problems: \n";

            err.response.data.message.map((problem, index) => {
              let mainError = "";
              if (problem.error instanceof Object) {
                mainError = Object.values(problem.error).join(",");
              } else if (problem.error instanceof Array) {
                mainError = problem.error.join();
              }
              errorMsg +=
                index +
                1 +
                ". Row: " +
                problem.row +
                ", Column: " +
                problem.column +
                ", Error: " +
                mainError +
                "\n";
            });
          }
          alert(errorMsg);
          $("#bulk_file_registration_sample_collection_lab_test").val(null);
        });
    },
  },
};
</script>