<template>
    <div>
        <div class="btn btn-primary pull right" v-on:click="excelDownloadConformation()">
            Download Data
                <i class="fa fa-file-excel-o" aria-hidden="true"></i>
                <div v-html="exportHtml" ref="exportDiv"></div>
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
                <th>Current Hospital</th>
                <th>Total Collection</th>
                <th>Latest Lab Result</th>
                <th>Action</th>
            </tr>
            </thead>
            <tr slot-scope="{item}" v-if="latestLabResultNotNegative(item.latest_anc)">
                <td>
                    <input type="checkbox" v-model="womanTokens" @click="select" :value="item.token">                           
                </td>
                <td><div v-if="item.latest_anc.result == '3'">Case ID : {{ item.case_id }}</div>
                    <div v-if="item.parent_case_id !== null">Parent Case ID : {{ item.parent_case_id }}</div>
                </td>
                <td>{{item.name}}</td>
                <td>{{item.age}}</td>
                <td>One : {{item.emergency_contact_one}} <br>
                    Two : {{item.emergency_contact_two}}
                </td>
                <td>{{ checkMunicipality(item.municipality_id) }}</td>
                <td>{{ item.healthpost.name }}</td>
                <td><span class="label label-info"> {{ item.ancs.length }}</span></td>
                <td><div v-html="latestLabResult(item.latest_anc)">
                </div>
                </td>
                <td>
                    <button v-on:click="sendPatientData(item)" title="Send / Transfer Patient to other Hospital">
                        <i class="fa fa-hospital-o"></i>
                    </button>
                    
                    <button v-if="item.latest_anc.result == '3'" v-on:click="viewConfirmReportForm(item)" title="Confirmed Case Record Form">
                        |
                    <i class="fa fa-file"></i>
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
    import SendPatientDataModel from './SendPatientDataModel.vue'
    import viewConfirmReportFormModel from './viewConfirmReportFormModel.vue'

    export default {
        components: {Filterable},
        data() {
            return {
                filterable: {
                    url: '/data/api/women',
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
                    'Complete name': 'name',
                    'City': 'city',
                    'Telephone': 'phone.mobile',
                    'Telephone 2' : {
                        field: 'phone.landline',
                        callback: (value) => {
                            return `Landline Phone - ${value}`;
                        }
                    },
                },
                json_data: [
                    {
                        'name': 'Tony Peña',
                        'city': 'New York',
                        'country': 'United States',
                        'birthdate': '1978-03-15',
                        'phone': {
                            'mobile': '1-541-754-3010',
                            'landline': '(541) 754-3010'
                        }
                    },
                    {
                        'name': 'Thessaloniki',
                        'city': 'Athens',
                        'country': 'Greece',
                        'birthdate': '1987-11-23',
                        'phone': {
                            'mobile': '+1 855 275 5071',
                            'landline': '(2741) 2621-244'
                        }
                    }
                ],
                json_meta: [
                    [
                        {
                            'key': 'charset',
                            'value': 'utf-8'
                        }
                    ]
                ],
                exportHtml : ''
            }
        },
        mounted() {
            this.$refs['exportDiv'].firstChild.addEventListener('click', function(event) {
                  event.preventDefault();
                  console.log('clicked: ', event.target);
            })
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
                    height : 500,
                    width : 600,
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
                            return '<span class=\"label label-warning\"> Recieved</span>'
                        }else{
                            return '<span class=\"label label-default\"> Don\'t Know</span>'
                        }
                    }
                }
            },
            latestLabResultNotNegative : function(value){
                if (value) {
                    if (value == '0' || value == null || value == ''){
                    return true;
                }else{
                    if (value == '0' || value == null || value == ''){
                        return true;
                    }else{
                        if (value.result == '4') {
                            return false;
                        }else{
                            return true;
                        }
                    }
                }
                }
            },
            excelFileName : function(){
                var ext = '.xlxs';
                return new Date()+ext;
            },
            excelDownloadConformation : function(){
                console.log("as0");
                this.exportHtml = `<download-excel
                :data   = "json_data"
                :fields = "json_fields"
                :name    = "excelFileName()"
                >
            </download-excel>`;
            }
        }
    }
</script>