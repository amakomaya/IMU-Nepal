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
                <th>अवस्था</th>
                <th>Total Tests</th>
                <th>Action</th>
            </tr>
            </thead>
            <tr slot-scope="{item}">
                <td>
                    <input type="checkbox" v-model="womanTokens" @click="select" :value="item.token">
                </td>
                <td>{{item.name}}</td>
                <td>{{item.age}}</td>
                <td>{{item.phone}}</td>
                <td>{{item.district.district_name}}</td>
                <td>{{item.municipality.municipality_name}}</td>
                <td>
                    <div class="row">
                        <div v-if="item.anc_visits">
                            <status-indicator status="positive" v-if="item.anc_visits.situation == 1"
                                              title="समान्य"/>
                            <status-indicator status="intermediary" v-if="item.anc_visits.situation == 2"
                                              title="सम्भाब्य जोखिम"/>
                            <status-indicator status="negative" v-if="item.anc_visits.situation == 3"
                                              title="जोखिम"/>
                        </div>
                            <status-indicator status="negative-semi" v-else pulse title="Not Recorded"/>
                    </div>
                </td>
                <td><span class="label label-info"> {{ item.ancs.length }}</span></td>
                <td>
                    <button v-on:click="viewReport(item)" title="Amakomaya Card">
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
                            name: 'Clients',
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
                womanTokens: []
            }
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
                    title: item.name,
                    params: {
                        data : item
                    },
                })
            },          
            ad2bs: function (date) {
                var dateObject = new Date(date);

                var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

                let dateConverter = DataConverter.ad2bs(dateFormat);

                return dateConverter.en.day + ' ' + dateConverter.en.strMonth + ', ' + dateConverter.en.year;

            },

        }
    }
</script>