<template>
    <div>
        <button class="pull-right" v-on:click="sendMessage(womanTokens)" title="Send Message">
            <i class="fa fa-envelope-o"></i>
        </button>
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
            </tr>
<!--            <span>Selected Ids: {{ item }}</span>-->

        </filterable>
    </div>
</template>

<script type="text/javascript">
    import Filterable from './Filterable.vue'
    import DataConverter from 'ad-bs-converter'
    import axios from 'axios'
    import WomanUsermanagementModelVue from './WomanUsermanagementModel.vue'

    export default {
        components: {Filterable},
        data() {
            return {
                filterable: {
                    url: '/data/api/women',
                    orderables: [
                        {title: 'Name', name: 'name'},
                        {title: 'Age', name: 'age'},
                        {title: 'LMP Date', name: 'lmp_date_en'},
                        {title: 'Created At', name: 'created_at'},
                        {title: 'ANC Visit Status', name: 'anc_with_protocol'},

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
            viewReport: function (id) {
                var url = "/admin/woman/" + id;
                window.open(url, '_blank').focus();
            },
            edit: function (token) {
                var url = '/woman?q=' + token;
                window.open(url, '_blank').focus();
            },
            destroy: function (token, name, id) {
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
                        axios.delete('/woman/' + token)
                            .then(response => {
                                window.location.reload();
                            });
                        this.$swal(
                            'Deleted!',
                            'Woman has been deleted.',
                            'success'
                        )
                    }
                })
            },
            sendMessage: async function (token) {
                if (!token.length) {
                    this.$swal({
                        position: 'top-end',
                        type: 'info',
                        title: 'Please select woman.',
                        showConfirmButton: false,
                        timer: 1200
                    });
                    return false;
                }
                var current_datetime = new Date();
                var formated_date = current_datetime.getFullYear() + "-" + (current_datetime.getMonth() + 1) + "-" + current_datetime.getDate() + " " + current_datetime.getHours() + ":" + current_datetime.getMinutes() + ":" + current_datetime.getSeconds();
                const {value: message} = await this.$swal({
                    input: 'textarea',
                    inputPlaceholder: 'Type your message here...',
                    animation: "slide-from-top",
                    showCancelButton: true,
                    confirmButtonText: "Send",
                });
                if (message) {
                    var payload = {
                        woman_token: token,
                        message: message,
                        notified_by: "Message From web",
                        notified_at: formated_date,
                    };

                    axios.post('/api/v2/send_message', payload)
                        .then(response => {
                            // JSON responses are automatically parsed.
                            if (response.data.success_woman_token.length > 0) {
                                this.$swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Your message has been saved',
                                    showConfirmButton: false,
                                    timer: 1500
                                })
                            }
                        })
                        .catch(function (error) {
                            this.$swal({
                                position: 'top-end',
                                type: 'error',
                                title: 'Network error, Please try again...',
                                showConfirmButton: false,
                                timer: 1500
                            })
                        });
                } else {
                    this.$swal({
                        position: 'top-end',
                        type: 'error',
                        title: 'Please enter your message',
                        showConfirmButton: false,
                        timer: 1500
                    })
                }
            },

            userManagement: function (item){
                var user = item.user;
                if(!user){
                    user = {
                        'id' : '',
                        'token' : item.token,
                        'username' : '',
                        'password' : ''
                    }
                }
                this.$dlg.modal(WomanUsermanagementModelVue, {
                    width: 500,
                    height: 350,
                    title: 'User Management',
                    params: {
                        data : user,
                    }
                });
            },

            ancVisitSchedule: function (id) {
                var url = "/admin/woman/" + id + "/anc-visit-schedule";
                window.open(url, '_blank').focus();
            },
            ad2bs: function (date) {
                var dateObject = new Date(date);

                var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

                let dateConverter = DataConverter.ad2bs(dateFormat);

                return dateConverter.en.day + ' ' + dateConverter.en.strMonth + ', ' + dateConverter.en.year;

            },
            lmp2edd: function (lmpDate) {
                let date = new Date(lmpDate);
                return date.setDate(date.getDate() + 280);
            }
        }
    }
</script>