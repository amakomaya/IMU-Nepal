<template>
	<div>
        <div class="form-group">
            <h4><u><strong>Patient Details :</strong></u></h4>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Name : </td>
                        <td>{{ data.name }}</td>
                    </tr>
                    <tr>
                        <td>Age : </td>
                        <td>{{ data.age }} / <span v-if="data.age_unit == 1">Months</span><span v-if="data.age_unit == 2">Days</span><span v-if="data.age_unit == 0">Years</span></td>
                    </tr>
                    <tr>
                        <td>Gender : </td>
                        <td><span v-if="data.sex == 1">Male</span><span v-if="data.sex == 2">Female</span><span v-if="data.sex == 3">Other</span></td>
                    </tr>
                    <tr>
                        <td>Emergency Phone : </td>
                        <td>One : {{ data.emergency_contact_one }} <br> Two : {{ data.emergency_contact_two }}</td>
                    </tr>
                    <tr>
                        <td>Current Hospital : </td>
                        <td>{{ data.healthpost.name }}</td>
                    </tr>
                </tbody>
            </table>

        	<h4>Where do you want to transfer this patient, Please search Hospital</h4>

            <v-select label="name"
                v-model="healthpostSelected"
                placeholder="Type to search Hospital informations .."
                :options="options"
                @search="onSearch"
            >
                <template vslot="no-options">
                    type to search Hospital informations ...
                </template>
                <template slot="option" slot-scope="option">
                    {{ option.name }} <br>
                    {{ option.province.province_name }}, {{ option.municipality.municipality_name }}, {{ option.district.district_name }}<br>
                    {{ option.address }}
                </template>
                <template slot="selected-option" slot-scope="option">
                    <div class="selected d-center">
                        {{ option.name }}, {{ option.address }}
                    </div>
                </template>
            </v-select> 
			
			<br>
            <button class="btn btn-primary btn-lg btn-block" v-on:click="sendPatient(healthpostSelected, data.token)" title="Send Patient">
            <i class="fa fa-send"> Send Patient</i>
        </button>     
        </div>
	</div>
</template>

<script type="text/javascript">
    import axios from 'axios'

    export default {
    	props: {
            data : Object,
            provinces : Array,
            districts : Array,
            municipalities : Array
        },

        data() {
            return {
                options: [],
                healthpostSelected : null,
            }
        },
        methods: {
            onSearch(search, loading) {
                    loading(true);
                    this.search(loading, search, this);
                },
                search: _.debounce((loading, search, vm) => {
                    let url = window.location.protocol + '/api/v1/healthposts';
                    axios.get(url)
                        .then(response => {
                            vm.options = response.data;
                            loading(false);
                        })
                        .catch((error) => {
                            console.error(error)
                        })
                        .finally(() => {
                        })
                }, 350),

            sendPatient: async function (healthpost, token) {
                if (!healthpost) {
                    this.$dlg.toast('Please Select Hospital !', {
					  messageType: 'warning',
					  closeTime: 3, // auto close dialog time(second)
					  position : 'topCenter',
					  language : 'en'
					});
                    return false;
                }

                var payload = {
                    token: token,
                    hp_code: healthpost.hp_code,
                };

                this.$swal({
                    title: 'Are you sure?',
                    text: "Do you want to transfer this patient!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, send it!',
                    customClass: {
                        container: 'my-swal'
                    }
                }).then((result) => {
                    if (result.value) {
                        this.$swal({
                        title: "Checking...",
                        text: "Please wait",
                        showConfirmButton: false,
                        allowOutsideClick: false
                    });

                    axios.post('/api/v1/patient-transfer', payload,{headers: {'Accept': 'application/json'}})
                        .then(response => {
                            // JSON responses are automatically parsed.
                            if (response.data.length > 0) {
                                this.$swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Successfully transfer patient, please refresh page to see changes !',
                                    showConfirmButton: false,
                                    timer: 1500
                                });                
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
                    }
                }) 
            },
        }
        
    }
</script>
<style>

.swal2-container {
  z-index: 10000;
}

</style>