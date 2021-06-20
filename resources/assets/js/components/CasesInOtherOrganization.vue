<template>
  <div>
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
        <th width="10%" title="Case Register Date">Date</th>
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
        <td>{{ roleVisibility(item.emergency_contact_one) }} <br>
          {{ roleVisibility(item.emergency_contact_two) }}
        </td>
        <td>{{ checkMunicipality(item.municipality_id) }}</td>
        <td>{{ item.ward }}</td>
        <td>
          Place : {{ item.healthpost ? item.healthpost.name : '' }} <br>
          Type : {{ checkCaseType(item.cases) }} <br>
          Management : {{ checkCaseManagement(item.cases, item.case_where) }}
        </td>
        <td>{{ ad2bs(item.register_date_en) }}</td>
        <td><span class="label label-info"> {{ item.ancs.length }}</span>
          <div v-if="item.latest_anc" title="Swab ID">
            Type : {{ checkSampleType(item.latest_anc.service_for) }}
          </div>
        </td>
        <td>
          <div v-if="item.ancs.length > 0" v-html="latestLabResult(item.latest_anc)"> </div>
          <div v-else><span class="label label-primary"> Registered </span></div>
        </td>
        <td>
          <button v-on:click="viewCaseDetails(item.token)" target="_blank" title="Case Details Report">
            <i class="fa fa-file" aria-hidden="true"></i> |
          </button>
          <span v-if="item.latest_anc">
            <button v-on:click="receivePatientData(item)" title="Receive Patient from other Hospital">
              <i class="fa fa-hospital-o"></i>
            </button>
          </span>
        </td>
        <!-- </div>             -->
      </tr>

    </filterable>
  </div>
</template>

<script type="text/javascript">
import Filterable from './Filterable.vue'
import DataConverter from 'ad-bs-converter'
import axios from 'axios'
import ViewLabResultReportModel from './ViewLabResultReportModel.vue'
import SendPatientDataModel from './SendPatientDataModel.vue'
import ReceivePatientDataModel from './ReceivePatientDataModel.vue'
import viewConfirmReportFormModel from './viewConfirmReportFormModel.vue'
import fab from 'vue-fab'

export default {
  components: {Filterable, fab},
  data() {
    return {
      role: this.$userRole,
      filterable: {
        url: '/data/api/cases-in-other-organization',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Case Register At', name:  'register_date_en'},
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'phone', type: 'numeric'},
              {title: 'Register At', name: 'register_date_en', type: 'datetime'},
            ]
          },
          {
            name: 'Swab Collection',
            filters: [
              {title: 'Collection Date', name: 'ancs.collection_date_en', type: 'datetime'}
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


    receivePatientData: function (item) {
      this.$dlg.modal(ReceivePatientDataModel, {
        title: 'Do you want to receive '+item.name+' \'s patients data ?',
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
      window.location.href = '/admin/sample-collection/create/' + token;
    },
    addPatient() {
      window.location.href = '/admin/patients/create'
    },
    viewCaseDetails(token) {
      window.open(
          '/admin/patient?token=' + token,
          '_blank'
      );
    },
    latestLabResult :function(value){
      if (value == '0' || value == null || value == ''){
        return '<span class=\"label label-default\"> Don\'t Know </span>';
      }else{
        if (value == '0' || value == null || value == ''){
          return '<span class=\"label label-default\"> Don\'t Know </span>';
        }else{
          if (value.result == '4') {
            return '<span class=\"label label-success\"> Negative</span>'
          }
          if (value.result == '2') {
            return '<span class=\"label label-info\"> Pending</span>'
          }
          if (value.result == '3') {
            return '<span class=\"label label-danger\"> Positive</span>'
          }
          if (value.result == '9') {
            return '<span class=\"label label-warning\"> Received</span>'
          }else{
            return '<span class=\"label label-default\"> Don\'t Know</span>'
          }
        }
      }
    },
    editCaseDetails(token) {
      window.open(
          '/admin/patient/' + token + '/edit',
          '_blank'
      );
    }
  }
}

</script>