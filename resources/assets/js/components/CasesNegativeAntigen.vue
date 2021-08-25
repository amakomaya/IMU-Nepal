<template>
    <div>
      <!-- <div class="col-md-12">
        <h3>Latest Data</h3>
        <button class="btn btn-success" style="float:right"  @click="newLink()">Click for data older than 15 days</button>
      </div> -->
        <filterable v-bind="filterable">
            <thead slot="thead">
            <tr>
              <th width="7%">ID</th>
              <th width="15%">Name</th>
              <th width="6%">Age</th>
              <th width="7%" title="Gender">G</th>
              <th width="10%" title="Emergency Contact Number">Phone</th>
              <th>District</th>
              <th width="8%" title="Municipality">Municipality</th>
              <th width="4%" title="Ward No">Ward</th>

<!--              <th width="15%">Case</th>-->
              <th width="8%" title="Test Date">Test Date</th>
              <th width="8%" title="Sample Collection Details">Sample</th>
              <th width="8%" title="Latest Lab Result">Result</th>
              <th width="4%" title="Tested By">Tested By</th>
              <th width="4%" title="Infection Type">Type</th>
              <th width="10%" title="Actions"><i class="fa fa-cogs" aria-hidden="true"></i></th>
            </tr>
            </thead>
            <tr slot-scope="{item, removeItemOnSuccess}">
                <td></td>
                <td>{{item.name}}</td>
                <td>{{item.age}}</td>
                <td>{{ gender(item.sex)}}</td>
                <td>{{item.emergency_contact_one}} <br>
                    {{item.emergency_contact_two}}
                </td>
                <td>{{ checkDistrict(item.district_id) }}</td>
                <td>{{ checkMunicipality(item.municipality_id) }}</td>
                <td>{{ item.ward }}</td>
                <td>{{ formattedDate(item.latest_anc.sample_test_date_np) }}</td>

                <td><span class="label label-info"> {{ item.ancs.length }}</span>
                  <div title="Swab ID">SID : <strong>{{ item.latest_anc.token }}</strong></div>
                </td>
                <td><span class="label label-success"> Negative</span>
                  <div>{{ labToken(item.latest_anc.lab_token) }}</div>
                </td>
                <td>
                  <div v-if="item.ancs.length > 0">
                    {{ item.latest_anc.get_organization.name }}
                  </div>
                </td>
                <td>
                  {{ checkInfectionType(item.symptoms_recent) }}
                </td>
                <td>
                  <button v-on:click="viewCaseDetails(item.token)" title="Case Details Report">
                    <i class="fa fa-file" aria-hidden="true"></i> |
                  </button>
                  <button v-if="checkPermission('sample-collection')" v-on:click="addSampleCollection(item.token)" title="Add Sample Collection / Swab Collection Report">
                    <i class="fa fa-medkit" aria-hidden="true"></i> |
                  </button>
                  <button v-if="permission == 1" v-on:click="deletePatientData(item, removeItemOnSuccess)" title="Move Patient Data">
                    <i class="fa fa-trash"></i>
                  </button>
                </td>  
                <!-- </div>             -->
            </tr>
<!--            <span>Selected Ids: {{ item }}</span>-->

        </filterable>
    </div>
</template>

<script type="text/javascript">
    import Filterable from './Filterable.vue'
    import DataConverter from 'ad-bs-converter'
    import axios from 'axios'
    import ViewLabReportModel from './ViewLabReportModel.vue'
    import ViewLabResultReportModel from './ViewLabResultReportModel.vue'

    export default {
        components: {Filterable},
        data() {
            return {
                permission: this.$permissionId,
                filterable: {
                    url: '/data/api/passive-patient-antigen',
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
                  ],
                },
                token : Filterable.data().collection.data,
                selected: [],
                allSelected: false,
                womanTokens: [],
                provinces : [],
                municipalities : [],
                districts : []
            }
        },
        created() {
            this.fetch()
        },
        methods: {
            newLink() {
              window.location.href = window.location.protocol + '/admin/negative-patients-antigen-old';
            },
            selectAll: function (item) {
                this.womanTokens = [];

                if (this.allSelected) {
                    console.log(item);

                }
            },
            select: function () {
                this.allSelected = false;
            },
            viewReport: function (item) {
                this.$dlg.modal(ViewLabReportModel, {
                    height : 700,
                    width : 800,
                    title: 'Laboratory Sample Collection Form for Suspected COVID-19 Case',
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

            deletePatientData: function (item, removeItemOnSuccess) {
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
              if(data == null) {
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
                return data.split('-').splice(1).join('-');
              }
            },
            checkDistrict : function(value){
                if (value == 0 || value == null || value == ''){
                    return ''
                }else{
                return this.districts.find(x => x.id == value).district_name;
                }
            },
            checkMunicipality : function(value){
                if (value == 0 || value == null || value == ''){
                    return ''
                }else{
                return this.municipalities.find(x => x.id == value).municipality_name;
                }
            },

            latestLabResultNegative : function(value){
                if (value) {
                    if (value == '0' || value == null || value == ''){
                    return false;
                }else{
                    if (value == '0' || value == null || value == ''){
                        return false;
                    }else{
                        if (value.result == '4') {
                            return true;
                        }else{
                            return false;
                        }
                    }
                }
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
          checkInfectionType(value) {
            switch (value) {
              case '0':
                return 'Asymptomatic';
              case '1':
                return 'Symptomatic';
              default:
                return "N/A";
            }
          },
          addSampleCollection(token) {
            window.open(
                '/admin/sample-collection/create/' + token,
                '_blank'
            );
          },
          checkPermission(value){
            var arr = this.$userPermissions.split(',');
            return arr.includes(value);
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