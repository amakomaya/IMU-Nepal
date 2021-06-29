<template>
  <div>
    <div v-show="permissionId==1" class="checkbox-slider">
      <input type="checkbox" id="checkbox" @click="switchValue($event)">
      <label for="checkbox" class="slider"></label>
      <label for="checkbox">{{ bulkMode }}</label>
    </div>
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
        <tr v-for="sTable in tableData">
           <td>{{sTable.name}}</td>
           <td>{{sTable.description}}</td>
           <td><a :href="sTable.templateLocation" onclick="return confirm('Are you sure, do you want to download import format ! ')" title="Do you have template ? If not, please download first and fill data than import.">Download Latest Template</a></td>
           <td>
              <div v-if="sTable.hasPermission">
                <label :for="sTable.slug" class="btn btn-primary">Bulk Upload
                  <i class="fa fa-upload" aria-hidden="true"></i>
                </label>
                <input type="file" :id="sTable.slug" :ref="sTable.slug" v-on:change="handleFileUpload(sTable.slug)" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="visibility:hidden;"/>
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
      bulkMode: 'new',
      tableData: this.getTableData('new'),
      permissionId: this.$permissionId
    };
  },
  watch: {
    isUploading(showModal) {
      let modalAction = showModal?"show":"hide";
      $('#uploading-modal').modal(modalAction);
    },
    bulkMode(mode) {
      this.tableData = this.getTableData(mode);
    }
  },
  methods: {
    getTableData(mode) {
      if(mode=='new') {
        if(this.checkPermission('poe-registration')){
          return ([
            {
                name: 'Asymptomatic POE Registration',
                templateLocation: '/downloads/excel/Asymptomatic_PoE_Bulk_Upload.xlsx',
                hasPermission: this.checkPermission('poe-registration'),
                slug: 'bulk_file_asymptomtic_poe',
                description: 'Register Asymptomatic POE Registration.',
              },
              {
                name: 'Symptomatic POE Registration',
                templateLocation: '/downloads/excel/Symptomatic_PoE_Bulk_Upload.xlsx',
                hasPermission: this.checkPermission('poe-registration'),
                slug: 'bulk_file_symptomtic_poe',
                description: 'Register Symptomatic POE Registration.',
              }
          ]);
        }
        return ([
              {
                name: 'Lab Received(PCR/Antigen)',
                templateLocation: '/downloads/excel/lab_received_template.xlsx',
                hasPermission: this.checkPermission('lab-received'),
                slug: 'bulk_file_lab_received',
                description: 'Create Lab Received(PCR/Antigen) by entering SID (15 digit IMU genereted code) & Patient Lab ID(Unique ID for your patient for external/own reference.)',

              },
              {
                name: 'Lab Results (PCR/Antigen)',
                templateLocation: '/downloads/excel/lab_result_template.xlsx',
                hasPermission: this.checkPermission('lab-result')||this.checkPermission('antigen-result'),
                slug: 'bulk_file_lab_result',
                description: 'Update Lab Results of existing "Lab Received" by entering Patient Lab ID (Unique ID for your patient for external/own reference) & Result (Positive/Negative)',
              },
              {
                name: 'Lab Received & Results',
                templateLocation: '/downloads/excel/lab_received_result_template.xlsx',
                hasPermission: (this.checkPermission('antigen-result') || this.checkPermission('lab-result')) && this.checkPermission('lab-received'),
                slug: 'bulk_file_lab_received_result',
                description: 'Create new "Lab Received"(PCR/Antigen) & update "Lab Results"(PCR/Antigen).',
              },
              {
                name: 'Registration & Sample Collection',
                templateLocation: '/downloads/excel/registration_sample_collection_template.xlsx',
                hasPermission: this.checkPermission('sample-collection') && this.checkPermission('cases-registration'),
                slug: 'bulk_file_registration_sample_collection',
                description: 'Register new "Pending case" & with "sample collection" (you will get an imu generated 15 digit code).',
              },
              {
                name: 'Registration, Sample Collection & Lab Received/Results(PCR/Antigen)',
                templateLocation: '/downloads/excel/registration_sample_collection_lab_tests_template.xlsx',
                hasPermission: (this.checkPermission('antigen-result') || this.checkPermission('lab-result')) && this.checkPermission('lab-received') && this.checkPermission('sample-collection') && this.checkPermission('cases-registration'),
                slug: 'bulk_file_registration_sample_collection_lab_test',
                description: 'Register new "New Case"(Case Type will vary according to the Lab Received), create "Sample Collection" ,create "Lab Received"(PCR/Antigen) & update "Lab Results"(PCR/Antigen).',
              }
          ]
        );
      } else {
         return ([
              {
                name: 'Backdate Cases Payment',
                templateLocation: '/downloads/excel/backdate/cases_payment_import_template.xlsx',
                hasPermission: this.checkPermission('cases-payment') && this.checkBackDatePermission(),
                slug: 'bulk_file_case_payment_bd',
                description: 'Create Cases Payment',
              },
              {
                name: 'Backdate Lab Received(PCR/Antigen)',
                templateLocation: '/downloads/excel/backdate/lab_received_template_backdate.xlsx',
                hasPermission: this.checkPermission('lab-received') && this.checkBackDatePermission(),
                slug: 'bulk_file_lab_received_bd',
                description: 'Create Lab Received(PCR/Antigen) by entering SID (15 digit IMU genereted code) & Patient Lab ID(Unique ID for your patient for external/own reference.)',

              },
              {
                name: 'Backdate Lab Results (PCR/Antigen)',
                templateLocation: '/downloads/excel/backdate/lab_result_template_backdate.xlsx',
                hasPermission: (this.checkPermission('lab-result')||this.checkPermission('antigen-result')) && this.checkBackDatePermission(),
                slug: 'bulk_file_lab_result_bd',
                description: 'Update Lab Results of existing "Lab Received" by entering Patient Lab ID (Unique ID for your patient for external/own reference) & Result (Positive/Negative)',
              },
              {
                name: 'Backdate Lab Received & Results',
                templateLocation: '/downloads/excel/backdate/lab_received_result_template_backdate.xlsx',
                hasPermission: ((this.checkPermission('antigen-result') || this.checkPermission('lab-result')) && this.checkPermission('lab-received')) && this.checkBackDatePermission(),
                slug: 'bulk_file_lab_received_result_bd',
                description: 'Create new "Lab Received"(PCR/Antigen) & update "Lab Results"(PCR/Antigen).',
              },
              {
                name: 'Backdate Registration & Sample Collection',
                templateLocation: '/downloads/excel/backdate/registration_sample_collection_template_backdate.xlsx',
                hasPermission: (this.checkPermission('sample-collection') && this.checkPermission('cases-registration')) && this.checkBackDatePermission(),
                slug: 'bulk_file_registration_sample_collection_bd',
                description: 'Register new "Pending case" & with "sample collection" (you will get an imu generated 15 digit code).',
              },
              {
                name: 'Backdate Registration, Sample Collection & Lab Received/Results(PCR/Antigen)',
                templateLocation: '/downloads/excel/backdate/registration_sample_collection_lab_tests_template_backdate.xlsx',
                hasPermission: ((this.checkPermission('antigen-result') || this.checkPermission('lab-result')) && this.checkPermission('lab-received') && this.checkPermission('sample-collection') && this.checkPermission('cases-registration')) && this.checkBackDatePermission(),
                slug: 'bulk_file_registration_sample_collection_lab_test_bd',
                description: 'Register new "New Case"(Case Type will vary according to the Lab Received), create "Sample Collection" ,create "Lab Received"(PCR/Antigen) & update "Lab Results"(PCR/Antigen).',
              }
          ]
        );
      }
    },
    checkBackDatePermission() {
      let permissionId = this.$permissionId;
      if (permissionId == 1) return true;
      return false;
    },
    checkPermission(value) {
      var arr = this.$userPermissions.split(",");
      return arr.includes(value);
    },
    switchValue(event){
      if(event.target.checked === true) {
        this.bulkMode = 'old';
      } else {
        this.bulkMode = 'new';
      }
    },
    handleFileUpload(slug) {
      this.isUploading = true;
      this[slug] = this.$refs[slug][0].files[0];
      this.submitBulkFile(slug);
      return;
    },
    submitBulkFile(slug){
      let self = this;
      let formData = new FormData();
      if (!this[slug]) {
        alert("Please upload a valid excel file");
        return;
      }
      formData.append('slug', slug);
      formData.append(slug, this[slug]);
      axios
        .post('/api/v1/bulk-upload/submit', formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(function (res) {
          self.isUploading = false;
          alert(res.data.message);
          $("#"+slug).val(null);
        })
        .catch(function (err) {
          let errorMsg;
          self.isUploading = false;
          if (err.response.status === 500) {
            errorMsg =
              "Please use the latest valid template downloaded from the system & try again. If problem persists, please contact support.";
          } else {
            errorMsg =
              "The Bulk File could not be uploaded due to the following problems: \n";

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
          $("#"+slug).val(null);
        });
    },
  },
};
</script>