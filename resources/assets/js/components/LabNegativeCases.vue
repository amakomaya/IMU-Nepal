<template>
  <div>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
        <th width="10%" title="Case Created Date">Date</th>
        <th width="10%" title="Sample Collection Details">Sample</th>
        <th width="8%" title="Latest Lab Result">Result</th>
        <th width="8%" title="Actions"><i class="fa fa-cogs" aria-hidden="true"></i></th>
      </tr>
      </thead>
      <tr slot-scope="{item, removeItemOnSuccess}">
        <td>
          <div v-if="item.parent_case_id !== null">Parent Case ID : {{ item.parent_case_id }}</div>
        </td>
        <td>{{item.name}}</td>
        <td>{{item.age}}</td>
        <td>{{ gender(item.sex)}}</td>
        <td>One : {{item.emergency_contact_one}} <br>
          Two : {{item.emergency_contact_two}}
        </td>
        <td>{{ checkMunicipality(item.municipality_id) }}</td>
        <td>{{ item.ward }}</td>
        <td>
          Place : {{ item.healthpost.name }} <br>
          Type : {{ checkCaseType(item.cases) }} <br>
          Management : {{ checkCaseManagement(item.cases, item.case_where) }}
        </td>
        <td>{{ ad2bs(item.latest_anc.sample_test_date_en) }}</td>
        <td><span class="label label-info"> {{ item.ancs.length }}</span>
          <div v-if="item.latest_anc" title="Swab ID">SID : <strong>{{ item.latest_anc.token }}</strong></div>
        </td>
        <td>
          <div><span class="label label-success"> Negative </span></div>
          <div>{{ labToken(item.latest_anc.lab_token) }}</div>
        </td>
        <td>
          <button v-if="item.latest_anc.result === '9'" v-on:click="addResultInLab(item)" title="Add Result">
            <i class = "material-icons">biotech</i> | 
          </button>
          <button v-if="permission == 1" v-on:click="deletePatientData(item, removeItemOnSuccess)" title="Move Patient Data">
            <i class="fa fa-trash"></i>
          </button>
        </td>
        <!-- </div>             -->
      </tr>
      <!--            <span>Selected Ids: {{ item }}</span>-->

    </filterable>
    <div v-if="this.$userRole === 'fchv'">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
      <fab
          :position="fabOptions.position"
          :bg-color="fabOptions.bgColor"
          :actions="fabActions"
          :start-opened = true
          @addRecievedInLab="addRecievedInLab"
          @addResultInLab="addResultInLab"
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
import AddRecievedInLabModal from "./AddRecievedInLabModal";
import AddResultInLabModal from "./AddResultInLabModal";

export default {
  components: {Filterable, fab},
  data() {
    return {
      permission: this.$permissionId,
      filterable: {
        url: '/data/api/lab/add-result-negative',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Case Created At', name: 'register_date_en'}
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'emergency_contact_one', type: 'text'},
              {title: 'Case Created At', name: 'register_date_en', type: 'datetime'},
            ]
          },
          {
            name: 'Swab Collection',
            filters: [
              {title: 'Swab ID ', name: 'ancs.token', type: 'string'},
              {title: 'Swab Created At', name: 'ancs.collection_date_en', type: 'datetime'}
            ]
          },
          {
            name: 'Lab Result',
            filters: [
              {title: 'Lab Result Created At', name: 'ancs.sample_test_date_en', type: 'datetime'}
            ]
          }
        ]
      },
      token : Filterable.data().collection.data,
      selected: [],
      allSelected: false,
      womanTokens: [],
      provinces : [],
      municipalities : [],
      districts : [],
      json_fields: {
        'S.N' : 'serial_number',
        'Case Name': 'name',
        'Age': 'age',
        'Age Unit' : 'age_unit',
        'District' : 'district',
        'Municipality' : 'municipality',
        'Ward' : 'ward',
        'Emergency Contact One' : 'emergency_contact_one',
        'Emergency Contact Two' : 'emergency_contact_two',
        'Current Hospital' : 'current_hospital',
        'Swab ID' : 'swab_id',
        'Lab ID' : 'lab_id',
        'Result' : 'result',
        'Created At' : 'created_at'
      },
      json_meta: [
        [
          {
            'key': 'charset',
            'value': 'utf-8'
          }
        ]
      ],
      exportHtml : '',
      fabOptions : {
        bgColor: '#778899',
        position: 'bottom-right',
      },
      fabActions: [
        {
          name: 'addRecievedInLab',
          icon: 'group_add',
          tooltip: "Add Recieved in Lab"
        },
        {
          name: 'addResultInLab',
          icon: 'biotech',
          tooltip: "Add Result in Lab"
        }

      ]
    }
  },
  created() {
    this.fetch()
  },
  methods: {
    selectAll: function (item) {
      this.womanTokens = [];

      if (this.allSelected) {
        console.log(item);

      }
    },
    select: function () {
      this.allSelected = false;
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

    deletePatientData: function (item, removeItemOnSuccess) {
      this.$swal({
        title: "Are you sure?",
        text: "Your data will be moved to Lab Received List.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, move it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      }).then((result) => {
        if (result.value) {
          axios.post('/api/v1/lab-suspected-case-delete/' + item.token)
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

    checkForPositiveOnly : function (value){
      if (value !== null) {
        if (value.result === '3') {
          return true;
        }
      }
    },
    latestLabResultNotNegative : function(value){
      if (value === '0' || value == null || value === ''){
        return true;
      }
      return value.result !== '4';
    },
    excelFileName : function(){
      var ext = '.xls';
      return 'Patient Details '+ new Date()+ext;
    },
    async fetchData(){
      if(confirm("Do you want to Download all records in excel ! ")){
        const response = await axios.get('/data/api/lab-patient/export');
        return response.data;
        //     }
        // })
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

    addRecievedInLab(){
      this.$dlg.modal(AddRecievedInLabModal, {
        title: 'Received  Cases in Lab',
        width : 700
      })
    },
    addResultInLab(item){
      this.$dlg.modal(AddResultInLabModal, {
        title: 'Lab Result',
        width : 700,
        params: {
          item : item,
        },
      })
    },
    labToken(data){
      if (data !== null){
        return data.split('-').splice(1).join('-');
      }
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
  }
}

</script>