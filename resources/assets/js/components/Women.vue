<template>
    <div>
        <filterable v-bind="filterable">
            <thead slot="thead">
            <tr>
                <th width="10px"></th>
                <th>Name</th>
                <th>Age</th>
                <th>Phone</th>
                <th>District</th>
                <th>Muicipality</th>
                <th>Situation</th>
                <th>Total Collection</th>
<!--                 <th>Latest Lab Result</th>
 -->                <th>Action</th>
            </tr>
            </thead>
            <tr slot-scope="{item}">
                <td>
                    <input type="checkbox" v-model="womanTokens" @click="select" :value="item.token">
                </td>
                <td>{{item.name}}</td>
                <td>{{item.age}}</td>
                <td>{{item.phone}}</td>
                <td>{{ checkDistrict(item.district_id) }}</td>
                <td>{{ checkMunicipality(item.municipality_id) }}</td>
                <td>
                    <div class="row">
                        <div v-if="item.ancs.length > 0">
                            <status-indicator status="positive" v-if="item.latest_anc.situation == 1"
                                              title="समान्य"/>
                            <status-indicator status="intermediary" v-if="item.latest_anc.situation == 2"
                                              title="सम्भाब्य जोखिम"/>
                            <status-indicator status="negative" v-if="item.latest_anc.situation == 3"
                                              title="जोखिम"/>
                        </div>
                            <status-indicator status="negative-semi" v-else pulse title="Not Recorded"/>
                    </div>
                </td>
                <td><span class="label label-info"> {{ item.ancs.length }}</span></td>
                <td>
                    <button v-on:click="viewReport(item)" title=" Card Laboratory Sample Collection Form Details">
                        <i class="fa fa-newspaper-o"></i>
                    </button>
                </td>              
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
                districts : []
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
                return this.municipalities.find(x => x.id === value).municipality_name.split(" ").slice(0, -1).join(" ");
                }
            }
            }
    }
</script>