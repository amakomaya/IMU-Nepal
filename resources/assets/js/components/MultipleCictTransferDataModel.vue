<template>
	<div>
        <div class="form-group">
            <h4><strong>Patients Selected: {{checkedCount.length}}</strong></h4>
            <br>

            <h4>Which platform would be used for CICT?</h4>
            <input type="radio" v-model="platform" value="1" id="web">
            <label class="font-weight-normal" for="web">Web</label>&nbsp;
            <input type="radio" v-model="platform" value="2" id="mobile">
            <label class="font-weight-normal" for="mobile">Mobile</label>
			
        	<h4>Where do you want to transfer these patient, Please search </h4>

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
            <button v-if="checkedCount.length > 0" class="btn btn-primary btn-lg btn-block" v-on:click="transferMultiCict(healthpostSelected, checkedCount, platform)" title="Receive Patient">
                <i class="fa fa-send"> Transfer Patient for CICT</i>
            </button>     
        </div>
	</div>
</template>

<script type="text/javascript">
    import axios from 'axios'

    export default {
    	props: ['checkedCount', 'provinces', 'districts', 'municipalities'],

        data() {
            return {
                options: [],
                healthpostSelected : null,
                platform : '1',
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

            transferMultiCict: async function (healthpost, checkedCount, platform) {
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
                    checkedCount: checkedCount,
                    org_code: healthpost.org_code,
                    platform: platform
                };

                this.$swal({
                    title: 'Are you sure?',
                    text: "Do you want to transfer these patient to CICT List!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, transfer it!',
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

                    axios.post('/api/v1/cict-transfer-multiple', payload,{headers: {'Accept': 'application/json'}})
                        .then(response => {
                            // JSON responses are automatically parsed.
                            if (response.data.length > 0) {
                                if(response.data == '1') {
                                    this.$swal({
                                        position: 'top-end',
                                        type: 'error',
                                        title: 'Data already registered in CICT !',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });
                                }else if(response.data == '0'){
                                    this.$swal({
                                        position: 'top-end',
                                        type: 'error',
                                        title: 'Data Not Found !',
                                        showConfirmButton: false,
                                        timer: 1500
                                    }); 
                                }else {
                                    this.$swal({
                                        position: 'top-end',
                                        type: 'success',
                                        title: 'Successfully added patient to CICT list, please check CICT list to see changes !',
                                        showConfirmButton: false,
                                        timer: 1500
                                    });                
                                }
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
.font-weight-normal {
    font-weight: normal;
}

</style>