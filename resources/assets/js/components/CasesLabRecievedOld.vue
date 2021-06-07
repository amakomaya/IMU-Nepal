<template>
  <div>
      <div class="col-md-12">
        <button class="btn btn-success" style="float:right"  @click="newLink()">Click for Latest Data</button>
      </div>
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
      <tr slot-scope="{item}">
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
          Place : {{ item.healthpost_name }} <br>
          Type : {{ checkCaseType(item.cases) }} <br>
          Management : {{ checkCaseManagement(item.cases, item.case_where) }}
        </td>
        <td>{{ ad2bs(item.created_at) }}</td>
        <td><span class="label label-info"> {{ item.ancs_count }}</span>
          <div v-if="item.ancs_token" title="Swab ID">SID : <strong>{{ item.ancs_token }}</strong></div>
        </td>
        <td>
          <span class="label label-warning"> Received </span>
          <div>{{ labToken(item.ancs_token) }}</div>
        </td>
        <td>
          <button v-on:click="viewCaseDetails(item.token)" title="Case Details Report">
            <i class="fa fa-file" aria-hidden="true"></i> |
          </button>
          <button v-on:click="sendPatientData(item)" title="Send / Transfer Patient to other Hospital">
            <i class="fa fa-hospital-o"></i>
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
      filterable: {
        url: '/data/api/lab-received-old',
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
              {title: 'Phone Number', name: 'emergency_contact_one', type: 'text'},
              {title: 'Case Created At', name: 'created_at', type: 'datetime'},
            ]
          },
          {
            name: 'Swab Collection',
            filters: [
              {title: 'Swab ID ', name: 'ancs.token', type: 'string'},
              {title: 'Swab Created At', name: 'ancs.created_at', type: 'datetime'}
            ]
          },
          {
            name: 'Lab Result',
            filters: [
              {title: 'Lab Result Created At', name: 'ancs.updated_at', type: 'datetime'}
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
      window.location.href = window.location.protocol + '/admin/lab-received-patients';
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
    labToken(data){
      if (data !== null){
        return data.split('-').splice(1).join('-');
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