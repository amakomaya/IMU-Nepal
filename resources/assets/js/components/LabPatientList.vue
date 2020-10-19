<template>
  <div>
    <div class="btn btn-primary pull right">

      <download-excel
          :fetch   = "fetchData"
          :fields = "json_fields"
          :name    = "excelFileName()"
      >
        Download Data
        <i class="fa fa-file-excel-o" aria-hidden="true"></i>
      </download-excel>
    </div>

    <filterable v-bind="filterable">
      <thead slot="thead">
      <tr>
        <th width="10px"></th>
        <th>ID</th>
        <th>Name</th>
        <th>Age</th>
        <th>Emergency Contact</th>
        <!-- <th>District</th> -->
        <th>Muicipality</th>
        <th>Case</th>
        <th>Created At</th>
        <th>Total Collection</th>
        <th>Lab Result</th>
        <th>Action</th>
      </tr>
      </thead>
      <tr slot-scope="{item}">
        <td>
          <input type="checkbox" v-model="womanTokens" @click="select" :value="item.token">
        </td>
        <td><div v-if="checkForPositiveOnly(item.latest_anc)">Case ID : {{ item.case_id }}</div>
          <div v-if="item.parent_case_id !== null">Parent Case ID : {{ item.parent_case_id }}</div>
        </td>
        <td>{{item.name}}</td>
        <td>{{item.age}}</td>
        <td>One : {{item.emergency_contact_one}} <br>
          Two : {{item.emergency_contact_two}}
        </td>
        <td>{{ checkMunicipality(item.municipality_id) }}</td>
        <td>
          Place : {{ item.healthpost.name }} <br>
          Type : {{ checkCaseType(item.cases) }} <br>
          Management : {{ checkCaseManagement(item.cases, item.case_where) }}
        </td>
        <td>{{ ad2bs(item.created_at) }}</td>
        <td><span class="label label-info"> {{ item.ancs.length }}</span>
          <div v-if="item.latest_anc" title="Swab ID">SID : <strong>{{ item.latest_anc.token }}</strong></div>
        </td>
        <td>
          <div v-if="item.ancs.length > 0" v-html="latestLabResult(item.latest_anc)"></div>
          <div v-else><span class="label label-primary"> Registered </span></div>
          <div v-if="item.ancs.length > 0 && item.latest_anc.result == 9">{{ item.latest_anc.labreport.token.split('-').splice(1).join('-') }}</div>
        </td>
        <td>
           <button v-if="item.latest_anc.result == 9" v-on:click="addResultInLab(item)" title="Add Result">
            <i class="fa fa-medkit"></i>
          </button>
        </td>
        <!-- </div>             -->
      </tr>
      <!--            <span>Selected Ids: {{ item }}</span>-->

    </filterable>
<!--    <div v-if="this.$userRole == 'healthworker'">-->
<!--      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">-->
<!--      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">-->
<!--      <fab-->
<!--          :position="fabOptions.position"-->
<!--          :bg-color="fabOptions.bgColor"-->
<!--          :actions="fabActions"-->
<!--          :start-opened = true-->
<!--          @addRecievedInLab="addRecievedInLab"-->
<!--          @addResultInLab="addResultInLab"-->
<!--      ></fab>-->
<!--    </div>-->

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
      filterable: {
        url: '/api/v1/recieved-in-lab',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Created At', name: 'created_at'}
        ],
        filterGroups: [
          {
            name: 'Patient',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'phone', type: 'numeric'},
            ]
          },
          {
            name: 'Tests',
            filters: [
              {title: 'Created At', name: 'ancs.visit_date', type: 'datetime'}
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
        'Patient Name': 'name',
        'Age': 'age',
        'Age Unit' : {
          field: 'age_unit',
          callback: (value) => {
            switch(value){
              case '1':
                return "Months";

              case '2':
                return "Days";

              default:
                return "Years";
            }
          }
        },
        'Municipality' : 'municipality.municipality_name',
        'District' : 'district.district_name',
        'Emergency Contact One' : 'emergency_contact_one',
        'Emergency Contact Two' : 'emergency_contact_two',
        'Current Hospital' : 'healthpost.name',
        'Sample Token' : 'latest_anc.token',
        'Created AT' : 'created_at',
        'Latest Lab Result' : {
          field: 'latest_anc.result',
          callback: (value) => {
            switch(value){
              case '3':
                return "Positive";

              case '9':
                return "Recieved";

              case '':
                return "Pending";

              default:
                return "Do not know";
            }
          },
          'Lab ID' : 'latest_anc.labreport.formated_token'
        },
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
      if (value == 0 || value == null || value == ''){
        return ''
      }else{
        return this.districts.find(x => x.id === value).district_name;
      }
    },
    checkMunicipality : function(value){
      if (value == 0 || value == null || value == ''){
        return ''
      }else{
        return this.municipalities.find(x => x.id === value).municipality_name;
      }
    },
    latestLabResult :function(value){
      switch(value.result){
        case '4':
          return '<span class=\"label label-success\"> Negative</span>';

        case '2':
          return '<span class=\"label label-info\"> Pending</span>';

        case '3':
          return '<span class=\"label label-danger\"> Positive</span>';

        case '9':
          return '<span class=\"label label-warning\"> Recieved</span>';

        default:
          return '<span class=\"label label-default\"> Don\'t Know</span>';
      }
    },
    checkForPositiveOnly : function (value){
      if (value !== null) {
        if (value.result == '3') {
          return true;
        }
      }
    },
    latestLabResultNotNegative : function(value){

      if (value == '0' || value == null || value == ''){
        return true;
      }

      if (value.result == '4') {
        return false;
      }else{
        return true;
      }


    },
    excelFileName : function(){
      var ext = '.xls';
      return 'Patient Details '+ new Date()+ext;
    },
    async fetchData(){

      if(confirm("Do you want to Download all records in excel ! ")){

        const response = await axios.get('/data/api/patient/export');
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
      if (type == '1') {
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

      if (type == '2') {
        switch(management){
          case '0':
            return 'General Ward';

          case '0':
            return 'ICU';

          case '0':
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
    addResultInLab(){
      this.$dlg.modal(AddResultInLabModal, {
        title: 'Lab Result',
        width : 700
      })
    },

  }
}

</script>