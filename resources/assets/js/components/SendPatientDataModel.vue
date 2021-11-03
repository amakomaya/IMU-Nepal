<template>
	<div>
        <div class="form-group">
            <h4><u><strong>Patient Details :</strong></u></h4>
            <table class="table table-striped">
                <tbody>
                    <tr v-if="checkForPositiveOnly(data.latest_anc)">
                        <td>Case ID : </td>
                        <td>{{ data.case_id }}</td>
                    </tr>
                    <tr>
                        <td>Name / Age : </td>
                        <td>
                            {{ data.name }} / 
                            {{ data.age }} ( <span v-if="data.age_unit == 1">Months</span>
                            <span v-if="data.age_unit == 2">Days</span><span v-if="data.age_unit == 0">Years</span> )
                        </td>
                    </tr>
                    <tr>
                        <td>Gender / Emergency Phone: </td>
                        <td>
                            <span v-if="data.sex == 1">Male</span><span v-if="data.sex == 2">Female</span>
                            <span v-if="data.sex == 3">Other</span> / 
                            {{ data.emergency_contact_one }}
                        </td>
                    </tr>
                    <tr>
                        <td>Case : </td>
                        <td>
                            Place : {{ data.organization ? data.organization.name : '' }} <br>
                            Type : {{ checkCaseType(data.cases) }} <br>
                            Management : {{ checkCaseManagement(data.cases, data.case_where) }}
                        </td>
                    </tr>
                    <tr>
                        <td>Test Type / Result Date : </td>
                        <td>
                            {{ checkTestType(data.latest_anc.service_for) }} / 
                            {{ data.latest_anc.sample_test_date_np }}
                        </td>
                    </tr>
                </tbody>
            </table>

            <h4>Which platform would be used for CICT?</h4>
            <input type="radio" v-model="platform" value="1" id="web">
            <label class="font-weight-normal" for="web">Web</label>&nbsp;
            <input type="radio" v-model="platform" value="2" id="mobile">
            <label class="font-weight-normal" for="mobile">Mobile</label>

        	<h4>Where do you want to transfer this patient, Please search </h4>

            <v-select label="name"
                v-model="healthpostSelected"
                placeholder="Type to search informations .."
                :options="options"
                @search="onSearch"
            >
                <template vslot="no-options">
                    type to search informations ...
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
            <button class="btn btn-primary btn-lg btn-block" v-on:click="sendPatient(healthpostSelected, data.case_id, platform)" title="Send Patient">
            <i class="fa fa-send"> Send Patient for CICT</i>
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

            sendPatient: async function (healthpost, case_id, platform) {
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
                    case_id: case_id,
                    org_code: healthpost.org_code,
                    platform: platform
                };

                this.$swal({
                    title: 'Are you sure?',
                    text: "Do you want to transfer this patient to CICT list!",
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

                    axios.post('/api/v1/cict-transfer', payload,{headers: {'Accept': 'application/json'}})
                        .then(response => {
                            // JSON responses are automatically parsed.
                            if (response.data.length > 0) {
                                this.$swal({
                                    position: 'top-end',
                                    type: 'success',
                                    title: 'Successfully transfer patient to CICT !',
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
            checkTestType : function(type){
                switch(type){
                    case '1':
                        return 'PCR';
                    
                    case '2':
                        return 'Antigen';
                    
                    default:
                        return 'Antigen';
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

            checkForPositiveOnly : function (value){

                if (value !== null) {
                    if (value.result == '3') {
                        return true;
                    }
                }               

            },

            checkCaseManagement : function (type, management){
                if (type == '1') {
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

                if (type == '2') {
                    switch(management){
                        case '0':
                        return 'General Ward';

                        case '0':
                        return 'ICU';

                        case '0':
                        return 'Ventilator';

                        default:
                        return 'N/A';
                    }
                }
                return 'N/A';
            }
        }
        
    }
</script>
<style>

.swal2-container {
  z-index: 10000;
}

</style>