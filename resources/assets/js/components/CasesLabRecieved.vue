<template>
  <div>
    <!-- <div class="col-md-12">
      <h3>Latest Data</h3>
      <button class="btn btn-success" style="float:right"  @click="newLink()">Click for data older than 15 days</button>
    </div> -->
    <filterable v-bind="filterable">
      <thead slot="thead">
      <tr>
        <th width="6%">ID</th>
        <th width="10%">Name</th>
        <th width="4%">Age</th>
        <th width="6%" title="Gender">G</th>
        <th width="7%" title="Emergency Contact Number">Phone</th>
        <!-- <th>District</th> -->
        <th width="10%" title="Municipality">Municipality</th>
        <th width="4%" title="Ward No">Ward</th>
        <th width="15%">Case</th>
        <th width="10%" title="Received Date">Date</th>
        <th width="10%" title="Sample Collection Details">Sample</th>
        <th width="8%" title="Latest Lab Result">Result</th>
        <th width="8%" title="Actions"><i class="fa fa-cogs" aria-hidden="true"></i></th>
      </tr>
      </thead>
      <tr slot-scope="{item, removeItemOnSuccess}">
        <td>
          <div v-if="item.parent_case_id !== null" title="Parent Case ID">PC ID : {{ item.parent_case_id }}</div>
        </td>
        <td>{{ roleVisibility(item.name)}}</td>
        <td>{{item.age}}</td>
        <td>{{ gender(item.sex)}}</td>
        <td>One : {{ roleVisibility(item.emergency_contact_one) }} <br>
          Two : {{ roleVisibility(item.emergency_contact_two) }}
        </td>
        <td>{{ checkMunicipality(item.municipality_id) }}</td>
        <td>{{ item.ward }}</td>
        <td>
          Place : {{ item.healthpost ? item.healthpost.name : '' }} <br>
          Type : {{ checkCaseType(item.cases) }} <br>
          Management : {{ checkCaseManagement(item.cases, item.case_where) }}
        </td>
        <td>
          <div v-if="item.latest_anc">
            {{ formattedDate(item.latest_anc.received_date_np) }}
          </div>
        </td>
        <td><span class="label label-info"> {{ item.ancs.length }}</span>
          <div v-if="item.latest_anc" title="Swab ID">SID : <strong>{{ item.latest_anc.token }}</strong></div>
        </td>
        <td>
          <span class="label label-warning"> Received </span>
          <div>{{ labToken(item.latest_anc.labreport) }}</div>
        </td>
        <td>
          <button v-on:click="viewCaseDetails(item.token)" title="Case Details Report">
            <i class="fa fa-file" aria-hidden="true"></i> |
          </button>
          <button v-on:click="sendPatientData(item)" title="Send / Transfer Patient to other Hospital">
            <i class="fa fa-hospital-o"></i> |
          </button>
          <button v-if="permission == 1" v-on:click="deleteResultData(item, removeItemOnSuccess)" title="Move Patient Data">
            <i class="fa fa-trash"></i>
          </button>
        </td>
        <!-- </div>             -->
      </tr>
    </filterable>

    <div v-if="this.$userRole === 'healthworker'">
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

export default {
  components: {Filterable, fab},
  data() {
    return {
      role : this.$userRole,
      permission: this.$permissionId,
      filterable: {
        url: '/data/api/lab-received',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Case Register At', name: 'register_date_en'},
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'emergency_contact_one', type: 'text'},
              {title: 'Case Register At', name: 'register_date_en', type: 'datetime'},
            ]
          },
          {
            name: 'Swab Collection',
            filters: [
              {title: 'Swab ID ', name: 'ancs.token', type: 'string'},
              {title: 'Collection Date', name: 'ancs.collection_date_en', type: 'datetime'}
            ]
          },
          {
            name: 'Lab Result',
            filters: [
              {title: 'Lab Result Created At', name: 'ancs.sample_test_date_en', type: 'datetime'}
            ]
          }
        ],
      },
      token : Filterable.data().collection.data,
      provinces : [],
      municipalities : [],
      districts : [],
      exportHtml : '',
      fabOptions : {
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
      window.location.href = window.location.protocol + '/admin/lab-received-patients-old';
    },
    sendPatientData: function (item) {
      this.$dlg.modal(SendPatientDataModel, {
        title: 'Do you want to send '+item.name+' \'s patients data ?',
        height : 600,
        width : 700,
        params: {
          data : item,
          provinces : this.provinces,
          districts : this.districts,
          municipalities : this.municipalities
        },
      })
    },

    viewLabReport: function (item) {
      this.$dlg.modal(ViewLabResultReportModel, {
        height : 700,
        width : 800,
        title: 'Laboratory Result Form for Suspected COVID-19 Case',
        params: {
          item : item,
          provinces : this.provinces,
          districts : this.districts,
          municipalities : this.municipalities
        },
      })
    },

    viewConfirmReportForm : function(item){
      this.$dlg.modal(viewConfirmReportFormModel, {
        title: 'Confirmed report form of \'s ' + item.name,
        height : 700,
        width : 800,
        params: {
          data : item,
          provinces : this.provinces,
          districts : this.districts,
          municipalities : this.municipalities
        },
      })
    },

    deleteResultData: function (item, removeItemOnSuccess) {
      this.$swal({
        title: "Are you sure?",
        text: "Your data will be moved to Pending List.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, move it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      }).then((result) => {
        if (result.value) {
          axios.post('/api/v1/lab-sample-delete/' + item.latest_anc.token)
              .then((response) => {
                if (response.data.message === 'success') {
                  removeItemOnSuccess(item);
                  this.$swal({
                    title: 'Record Moved',
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
          this.$swal("Cancelled", "Data not moved :)", "error");
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

      var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return dateConverter.en.day + ' ' + dateConverter.en.strMonth + ', ' + dateConverter.en.year;

    },

    formattedDate(data) {
      if(data === null) {
        return '';
      }else {
        var date_array = data.split('-');
        switch (date_array[1]) {
          case '1':
          case '01':
            return date_array[2] + " Baishakh, " + date_array[0];

          case '2':
          case '02':
            return  date_array[2] + " Jestha, " + date_array[0];

          case '3':
          case '03':
            return  date_array[2] + " Ashad, " + date_array[0];

          case '4':
          case '04':
            return  date_array[2] + " Shrawan, " + date_array[0];

          case '5':
          case '05':
            return  date_array[2] + " Bhadra, " + date_array[0];

          case '6':
          case '06':
            return  date_array[2] + " Ashwin, " + date_array[0];

          case '7':
          case '07':
            return  date_array[2] + " Karthik, " + date_array[0];

          case '8':
          case '08':
            return  date_array[2] + " Mangsir, " + date_array[0];

          case '9':
          case '09':
            return  date_array[2] + " Poush, " + date_array[0];

          case '10':
            return  date_array[2] + " Magh, " + date_array[0];

          case '11':
            return  date_array[2] + " Falgun, " + date_array[0];

          case '12':
            return  date_array[2] + " Chaitra, " + date_array[0];

          default:
            return '';
        }
      }
    },
    labToken(data){
      if (data !== null){
        return data.token.split('-').splice(1).join('-');
      }
    },
    checkDistrict : function(value){
      if (value === 0 || value == null || value === ''){
        return ''
      }else{
        return this.districts.find(x => x.id === value).district_name;
      }
    },
    checkMunicipality : function(value){
      if (value === 0 || value == null || value === ''){
        return ''
      }else{
        return this.municipalities.find(x => x.id === value).municipality_name;
      }
    },

    checkCaseType : function(type){
      switch(type){
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

    checkCaseManagement : function (type, management){
      if (type === '1') {
        switch(management){
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
        switch(management){
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

    gender(type){
      switch (type){
        case '1':
          return 'M';
        case '2':
          return  'F';
        default:
          return 'O';
      }
    },

    roleVisibility(data){
      // if(this.role === 'dho' || this.role === 'province' || this.role === 'center'){
      //   return '** ***';
      // }
      return data;
    },

    aadSampleCollection(token){
      window.location.href = '/admin/sample-collection/create/'+token;
    },
    addPatient(){
      window.location.href = '/admin/patients/create'
    },
    viewCaseDetails(token){
      window.open(
          '/admin/patient?token=' + token,
          '_blank'
      );
    }
  }
}

</script>