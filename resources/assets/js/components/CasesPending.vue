<template>
    <div>
      <div class="col-md-12">
        <h3>Latest Data</h3>
        <button class="btn btn-success" style="float:right"  @click="newLink()">Click for data older than 15 days</button>
      </div>
      <filterable v-bind="filterable">
            <thead slot="thead">
            <tr>
              <th width="6%">ID</th>
              <th width="10%">Name</th>
              <th width="4%">Age</th>
              <th width="5%" title="Gender">G</th>
              <th width="7%" title="Emergency Contact Number">Phone</th>
              <!-- <th>District</th> -->
              <th width="10%" title="Municipality">Municipality</th>
              <th width="4%" title="Ward No">Ward</th>
              <th width="15%">Case</th>
              <th width="8%" title="Case Created Date">Date</th>
              <th width="10%" title="Sample Collection Details">Sample</th>
              <th width="8%" title="Latest Lab Result">Result</th>
              <th width="10%" title="Actions"><i class="fa fa-cogs" aria-hidden="true"></i></th>
            </tr>
            </thead>
            <tr slot-scope="{item, removeItemOnSuccess}">
                <td>
                    <div v-if="item.parent_case_id !== null" title="Parent Case ID">PC ID : {{ item.parent_case_id }}</div>
                </td>
                <td>{{ roleVisibility(item.name)}}</td>
                <td>{{item.age}}</td>
                <td>{{ gender(item.sex)}}</td>
                <td>{{ roleVisibility(item.emergency_contact_one) }} <br>
                    {{ roleVisibility(item.emergency_contact_two) }}
                </td>
                <td>{{ checkMunicipality(item.municipality_id) }}</td>
                <td>{{ item.ward }}</td>
                <td>
                    Place : {{ item.healthpost.name }} <br>
                    Type : {{ checkCaseType(item.cases) }} <br>
                    Management : {{ checkCaseManagement(item.cases, item.case_where) }}
                </td>
              <td>{{ ad2bs(item.created_at) }}</td>
                <td><span class="label label-info"> {{ item.ancs.length }}</span>
                    <div v-if="item.latest_anc" title="Swab ID">
                      SID : <strong>{{ item.latest_anc.token }}</strong> <br>
                      Type : {{ checkSampleType(item.latest_anc.service_for) }}
                    </div>
                </td>
                <td>
                    <div v-if="item.ancs.length > 0"><span class="label label-primary"> Pending </span></div>
                    <div v-else><span class="label label-primary"> Registered </span></div>
                </td>
                <td>
                  <button v-on:click="viewCaseDetails(item.token)" target="_blank" title="Case Details Report">
                    <i class="fa fa-file" aria-hidden="true"></i> |
                  </button>
                    <button v-if="role === 'healthworker' || role === 'healthpost' || role === 'municipality'" v-on:click="editCaseDetails(item.token)" title="Edit Case Detail">
                      <i class="fa fa-edit" aria-hidden="true"></i> |
                    </button>
                  <button v-if="item.ancs.length === 0 && checkPermission('sample-collection')" v-on:click="addSampleCollection(item.token)" title="Add Sample Collection / Swab Collection Report">
                     <i class="fa fa-medkit" aria-hidden="true"></i> |
                  </button>
                  <button v-if="checkPermission('lab-received') && checkAddReceivedView(item.latest_anc) && pcrAllowedOrganizationType.includes(hospitalType)" v-on:click="addReceivedInLab(item.latest_anc.token)" title="Lab Received ( PCR / Antigen )">
                    <i class="fa fa-flask" aria-hidden="true"></i> |
                  </button>
                  <button v-on:click="sendPatientData(item)" title="Send / Transfer Patient to other Hospital">
                        <i class="fa fa-hospital-o"></i> |
                  </button>
                  <button v-on:click="deletePatientData(item, removeItemOnSuccess)" title="Delete Patient Data">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>
                <!-- </div>             -->
            </tr>

        </filterable>

      <div v-if="this.$userRole == 'healthworker'">
        <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

        <fab
            :position="fabOptions.position"
            :bg-color="fabOptions.bgColor"
            :actions="fabActions"
            :start-opened = true
            @addPatient="addPatient"
        ></fab>
      </div>
    </div>
</template>

<script type="text/javascript">
import Filterable from './Filterable.vue'
import DataConverter from 'ad-bs-converter'
import axios from 'axios'
import ViewLabResultReportModel from './ViewLabResultReportModel.vue'
import SendPatientDataModel from './SendPatientDataModel.vue'
import viewConfirmReportFormModel from './viewConfirmReportFormModel.vue'
import fab from 'vue-fab'
import  AddRecievedInLabModal from "./AddRecievedInLabModal";

export default {
  components: {Filterable, fab},
  data() {
    return {
      pcrAllowedOrganizationType: this.$pcrAllowedOrganizationType,
      role: this.$userRole,
      hospitalType: this.$hospitalType,
      filterable: {
        url: '/data/api/active-pending-patient',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Case Created At', name: 'created_at'},
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'phone', type: 'numeric'},
              {title: 'Case Created At', name: 'created_at', type: 'datetime'},
            ]
          },
          {
            name: 'Swab Collection',
            filters: [
              {title: 'Swab Created At', name: 'ancs.created_at', type: 'datetime'}
            ]
          }
        ],
      },
      token: Filterable.data().collection.data,
      provinces: [],
      municipalities: [],
      districts: [],
      exportHtml: '',
      fabOptions: {
        bgColor: '#778899',
        position: 'bottom-right',
      },
      fabActions: [
        {
          name: 'addPatient',
          icon: 'group_add',
          tooltip: "Add Covid 19 Cases"
        }
      ]
    }
  },
  created() {
    this.fetch()
  },
  methods: {
    newLink() {
      window.location.href = window.location.protocol + '/admin/patients-pending-old';
    },
    sendPatientData: function (item) {
      this.$dlg.modal(SendPatientDataModel, {
        title: 'Do you want to send ' + item.name + ' \'s patients data ?',
        height: 600,
        width: 700,
        params: {
          data: item,
          provinces: this.provinces,
          districts: this.districts,
          municipalities: this.municipalities
        },
      })
    },

    viewLabReport: function (item) {
      this.$dlg.modal(ViewLabResultReportModel, {
        height: 700,
        width: 800,
        title: 'Laboratory Result Form for Suspected COVID-19 Case',
        params: {
          item: item,
          provinces: this.provinces,
          districts: this.districts,
          municipalities: this.municipalities
        },
      })
    },

    viewConfirmReportForm: function (item) {
      this.$dlg.modal(viewConfirmReportFormModel, {
        title: 'Confirmed report form of \'s ' + item.name,
        height: 700,
        width: 800,
        params: {
          data: item,
          provinces: this.provinces,
          districts: this.districts,
          municipalities: this.municipalities
        },
      })
    },

    deletePatientData: function (item, removeItemOnSuccess) {
      this.$swal({
        title: "Are you sure?",
        text: "You won\'t able to to retrieve this data.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      }).then((result) => {
        if (result.value) {
          axios.post('/api/v1/suspected-case-delete/' + item.token)
              .then((response) => {
                if (response.data.message === 'success') {
                  removeItemOnSuccess(item);
                  this.$swal({
                    title: 'Record Deleted',
                    type: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                  })
                  this.healthpostSelected = null;
                  
                } else {
                  this.$swal({
                    title: 'Oops. No record found.',
                    type: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  })
                }
              })
        } else {
          this.$swal("Cancelled", "Data not deleted :)", "error");
        }
      })
    },

    fetch() {
      let province_url = window.location.protocol + '/api/province';
      let municipality_url = window.location.protocol + '/api/municipality';
      let district_url = window.location.protocol + '/api/district';
      axios.get(municipality_url)
          .then((response) => {
            this.municipalities = response.data;
          })
          .catch((error) => {
            console.error(error)
          })
          .finally(() => {
          }),
          axios.get(district_url)
              .then((response) => {
                this.districts = response.data;
              })
              .catch((error) => {
                console.error(error)
              })
              .finally(() => {
              }),
          axios.get(province_url)
              .then((response) => {
                this.provinces = response.data;
              })
              .catch((error) => {
                console.error(error)
              })
              .finally(() => {
              })
    },

    ad2bs: function (date) {
      var dateObject = new Date(date);

      var dateFormat = dateObject.getFullYear() + "/" + (dateObject.getMonth() + 1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return dateConverter.en.day + ' ' + dateConverter.en.strMonth + ', ' + dateConverter.en.year;

    },
    checkDistrict: function (value) {
      if (value === 0 || value == null || value === '') {
        return ''
      } else {
        return this.districts.find(x => x.id === value).district_name;
      }
    },
    checkMunicipality: function (value) {
      if (value === 0 || value == null || value === '') {
        return ''
      } else {
        return this.municipalities.find(x => x.id === value).municipality_name;
      }
    },

    checkCaseType: function (type) {
      switch (type) {
        case '0':
          return 'N/A';
        case '1':
          return 'Asymptomatic / Mild Case';
        case '2':
          return 'Moderate / Severe Case';
        default:
          return 'N/A';
      }
    },

    checkSampleType: function (type){
      return (type === '2') ? 'Rapid Antigen Test' : 'SARS-CoV-2 RNA Test';
    },

    checkCaseManagement: function (type, management) {
      if (type === '1') {
        switch (management) {
          case '0':
            return 'Home';

          case '1':
            return 'Hotel';

          case '2':
            return 'Institution';

          default:
            return 'N/A';
        }
      }

      if (type === '2') {
        switch (management) {
          case '0':
            return 'General Ward';

          case '1':
            return 'ICU';

          case '2':
            return 'Ventilator';

          default:
            return 'N/A';
        }
      }
      return 'N/A';
    },

    gender(type) {
      switch (type) {
        case '1':
          return 'M';
        case '2':
          return 'F';
        default:
          return 'O';
      }
    },

    roleVisibility(data) {
      // if (this.role === 'dho' || this.role === 'province' || this.role === 'center') {
      //   return '** ***';
      // }
      return data;
    },
    addSampleCollection(token) {
      window.open(
          '/admin/sample-collection/create/' + token,
          '_blank'
      );
    },
    addPatient() {
      window.open(
          '/admin/patients/create',
          '_blank'
      );
    },
    viewCaseDetails(token) {
      window.open(
          '/admin/patient?token=' + token,
          '_blank'
      );
    },
    editCaseDetails(token) {
      window.open(
          '/admin/patient/' + token + '/edit',
          '_blank'
      );
    },
    checkPermission(value){
      var arr = this.$userPermissions.split(',');
      return arr.includes(value);
    },
    checkAddReceivedView(data){
      return data;
    },
    addReceivedInLab(item){
      this.$dlg.modal(AddRecievedInLabModal, {
        title: 'Received  Cases in Lab',
        width : 700,
        params: {
          item : item,
        },
      })
    },
  }
}

</script>