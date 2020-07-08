<template>
    <div>
        <filterable v-bind="filterable">
            <thead slot="thead">
            <tr>
                <th width="10px"><input type="checkbox" @click="selectAll" v-model="allSelected"></th>
                <th>Name</th>
                <th>Gender</th>
                <th>Date of Birth</th>
                <th>Mother Name</th>
                <th>Ward No</th>
                <th>Action</th>
            </tr>
            </thead>
            <tr slot-scope="{item}">
                <td><input type="checkbox" v-model="babyTokens" @click="select" :value="item.token"></td>
                <td>{{item.baby_name}}</td>
                <td>{{item.gender}}</td>
                <td>{{item.dob_np}}</td>
                <td>{{item.mother_name}}</td>
                <td>
                    <span v-if="item.ward_no >= 1" class="label label-info">{{item.ward_no}}</span>
                    <span v-else class="label label-warning">Other</span>
                </td>
                <td>
                    <button v-on:click="viewCard(item)" title="Amakomaya Card">
                        <i class="fa fa-newspaper-o"></i>
                    </button>
                    |
                    <button v-on:click="viewVaccinationStatusChart(item)" title="Vaccination Status">
                        <i class="fa fa-line-chart"></i>
                    </button>
                    |
                    <button v-on:click="viewBirthRegistrationCertificate(item.id)"
                            title="Birth Registration Certificate">
                        <i class="fa fa-certificate"></i>
                    </button>
                    |
                    <button v-on:click="viewChildHealthCardReport(item.id)" title="View Child Health Card Report">
                        <i class="fa fa-credit-card"></i>
                    </button>

                    <div class="pull-right">
                        <button v-on:click="edit(item.token)" title="Edit">
                            <i class="fa fa-pencil-square-o"></i>
                        </button>|
                        <button v-on:click="destroy(item)" title="Delete">
                            <i class="fa fa-trash-o"></i>
                        </button>
                    </div>
                </td>
            </tr>
        </filterable>
    </div>
</template>

<script type="text/javascript">
    import Filterable from './Filterable.vue'
    import DataConverter from 'ad-bs-converter'
    import { GChart } from 'vue-google-charts'
    import axios from 'axios'
    import Card from './Card.vue'
    import BabyVaccinationModel from './BabyVaccinationModel'

    export default {
        components: {Filterable, GChart},
        data() {
            return {
                filterable: {
                    url: '/data/api/baby',
                    orderables: [
                        {title: 'Created At', name: 'created_at'},
                        {title: 'Name', name: 'baby_name'},
                        {title: 'Date of Birth', name: 'dob_en'},
                        {title: 'Ward', name: 'ward_no'}
                    ],
                    filterGroups: [
                        {
                            name: 'Baby',
                            filters: [
                                {title: 'Name', name: 'baby_name', type: 'string'},
                                {title: 'Date of Birth', name: 'dob_en', type: 'datetime'},
                                {title: 'Ward', name: 'ward_no', type: 'numeric'}
                            ]
                        },
                        {
                            name: 'Vaccination',
                            filters: [
                                {title: 'Vaccinated Date', name: 'vaccinations.vaccinated_date_en', type: 'datetime'},
                            ]
                        }
                    ]
                },
                selected: [],
                allSelected: false,
                babyTokens: [],
                singleBabyData: [],
                vaccinationStatus : false,
                chartData: []
            }
        },
        methods: {
            selectAll: function () {
                this.babyTokens = [];

                if (!this.allSelected) {
                    console.log("adsfa");

                }
            },
            select: function () {
                this.allSelected = false;
            },
            viewBirthRegistrationCertificate: function (id) {
                var url = "/admin/child/" + id;
                window.open(url, '_blank').focus();
            },
            viewChildHealthCardReport: function (id) {
                var url = "/admin/child-health-report-card/" + id;
                window.open(url, '_blank').focus();
            },
            showModal() {
                let element = this.$refs.modal.$el;
                $(element).modal('show')
            },
            edit: function (token) {
                var url = '/baby/' + token + '/edit';
                window.open(url, '_blank').focus();
            },
            destroy: function(item){
                this.$swal({
                    title: 'Are you sure?',
                    text: "Delete " + name + "'s records. You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.value) {
                        axios.delete('/data/api/baby/' + item.id)
                            .then(response => {
                                window.location.reload();
                            });
                        this.$swal(
                            'Deleted!',
                            'Baby has been deleted.',
                            'success'
                        )
                    }
                })
            },
            viewVaccinationStatusChart: function (item) {
                this.singleBabyData = item;
                const data = [];
                data.push([
                    {type: "string", label: "Name", id: "name"},
                    {type: "string", label: "Period", id: "period"},
                    {type: "string", label: "Vaccinated Date(Y-m-d)", id: "date"},
                    {type: "string", label: "Created at", id: "created"},
                    {type: "boolean", label: "Has Images", id: "vial_images"},
                ]);

                if (this.singleBabyData.vaccinations.length > 0) {
                    for (let i = 0; i < this.singleBabyData.vaccinations.length; i++) {
                        const single_data = [];
                        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                        single_data.push(this.singleBabyData.vaccinations[i].vaccine_name);
                        single_data.push(this.singleBabyData.vaccinations[i].vaccine_period);
                        single_data.push(this.singleBabyData.vaccinations[i].vaccinated_date_np);
                        single_data.push(this.ad2bs(this.singleBabyData.vaccinations[i].created_at));
                        if (this.singleBabyData.vaccinations[i].vial_image.length == 0) {
                            single_data.push(false);
                        } else {
                            single_data.push(true);
                        }
                        data.push(single_data);
                    }
                    this.chartData = data;
                    this.vaccinationStatus = true;
                    this.$dlg.modal(BabyVaccinationModel, {
                    width: 500,
                    height: 350,
                    title: item.baby_name + '\'s Data',
                    params: {
                        data : this.chartData,
                        vaccinationStatus : this.vaccinationStatus
                    },
                })
                }else{
                    this.$swal({
                        type: 'info',
                        title: 'No records found !!!',
                        timer: 1500
                    })
                }
            },
            ad2bs: function (date) {
                var dateObject = new Date(date);

                var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

                let dateConverter = DataConverter.ad2bs(dateFormat);

                return dateConverter.en.day + ' ' + dateConverter.en.strMonth + ', ' + dateConverter.en.year + '  '+ dateObject.getHours() +':'+ dateObject.getMinutes();

            },
            viewCard: function(item){
                this.$dlg.modal(Card, {
                    width: 500,
                    height: 350,
                    title: item.baby_name + '\'s Card',
                    params: {
                        data : item
                    },
                })
            }
        }
    }
</script>