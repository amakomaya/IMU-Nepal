<template>
	<div>
        <div class="form-group">
            <h3><u><strong>Patient Details :</strong></u></h3>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>Name : </td>
                        <td>{{ data.name }}</td>
                    </tr>
                    <tr>
                        <td>Age : </td>
                        <td>{{ data.age }} / <span v-if="data.age_unit == 1">Months</span><span v-if="data.age_unit == 2">Days</span><span v-else>Years</span></td>
                    </tr>
                    <tr>
                        <td>Gender : </td>
                        <td><span v-if="data.sex == 1">Male</span><span v-if="data.sex == 2">Female</span><span v-else>Other</span></td>
                    </tr>
                    <tr>
                        <td>Emergency Phone : </td>
                        <td>One : {{ data.emergency_contact_one }} <br> Two : {{ data.emergency_contact_two }}</td>
                    </tr>
                    <tr>
                        <td>Created At : </td>
                        <td>{{ data.created_at }}</td>
                    </tr>
                </tbody>
            </table>

        	<h3>Where do you want to transfer this patient, Please search Hospital</h3>

            <v-select label="name"
                v-model="healthpostSelected"
                placeholder="Type to search healthpost informations .."
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
                healthpostSelected : null
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
                    this.$dlg.toast('Please Select Hosptial !', {
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


				// this.$dlg.alert('Are you sure? Do you want to transfer this patient!', {
				//   messageType: 'confirm',
				//   language : 'en',
				//   cancelCallback: function(){
				    
				//   }
				// });


                axios.post('/api/v1/patient-transfer', payload,{headers: {'Accept': 'application/json'}})
                        .then(response => {
                            // JSON responses are automatically parsed.
                            if (response.data.length > 0) {
                            	console.log(response.data);
                                this.$dlg.toast('Successfully transfer patient, please refresh page to see changes !', {
								  messageType: 'success',
								  closeTime: 3, // auto close dialog time(second)
								  position : 'topCenter',
								  language : 'en'
								});                            
                            }
                        })
                        .catch(function (error) {
                            this.$dlg.toast('Something went wrong ? Please try again !', {
								  messageType: 'error',
								  closeTime: 3, // auto close dialog time(second)
								  position : 'topCenter',
								  language : 'en'
							});  
                    });
            },
        }
        
    }
</script>