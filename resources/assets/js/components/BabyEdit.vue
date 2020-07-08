<template>
    <div class="panel panel-default">
        <div class="panel-heading fullname">
            Baby( {{ data.baby_name }} )
        </div>

        <div id="exTab1" class="container">
            <div>
            <ul class="nav nav-pills">
                <li class="active">
                    <a href="#register" data-toggle="tab">Child Register</a>
                </li>
                <li>
                    <a href="#vaccination" data-toggle="tab">Vaccination</a>
                </li>
                <li>
                    <a href="#weight" data-toggle="tab">Weight</a>
                </li>
                <li>
                    <a href="#aefiCase" data-toggle="tab">AEFI Case</a>
                </li>
            </ul>
                <div class="pull-right">
                    <div class="input-group">
                        <v-select label="name"
                            v-model="healthpostSelected"
                            placeholder="Type to search healthpost informations .."
                            style="width:300px" 
                            :options="options"
                            @search="onSearch"
                        >
                            <template vslot="no-options">
                                type to search Healthpost informations ...
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
                        <span class="input-group-btn">
                            <button class="btn btn-primary" v-if="healthpostSelected !== null" @click="healthpostTransfer(healthpostSelected)" type="button"><i class="fa fa-send fa-fw"></i> Send</button>
                        </span>
                    </div>
                </div> 
        </div>
            <div class="tab-content clearfix">
                <div class="tab-pane active" id="register">
                    <div class="panel">
                        <div class="panel-body">
                            <table class="table">
                                <tbody>
                                <tr>
                                    <td class="col-md-6 form-horizontal" colspan="1">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Full Name</label>
                                            <div class="col-md-8">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                        class="fa fa-child fa-lg"></i></span>
                                                    <input id="baby_name" name="fullName" placeholder="Full Name"
                                                           class="form-control" required="true" v-model="data.baby_name" value="" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Date of Birth</label>
                                            <div class="col-md-8 inputGroupContainer">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                        class="glyphicon glyphicon-calendar"></i></span>
                                                    <input id="date_of_birth" name="" placeholder="Date of Birth"
                                                           class="form-control" required="true" v-model="data.dob_np" value="" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Birth Place</label>
                                            <div class="col-md-8">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="max-width: 100%;"><i
                                                            class="fa fa-map-marker fa-lg"></i></span>
                                                    <select class="selectpicker form-control" v-model="data.birth_place">
                                                        <option
                                                                v-for="option in birthPlaceOptions"
                                                                v-bind:value="option"
                                                                :selected="option == data.birth_place"
                                                        >{{ option }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Mother Name</label>
                                            <div class="col-md-8 inputGroupContainer">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                        class="glyphicon glyphicon-user"></i></span>
                                                    <input id="mother_name" name="" placeholder="Mother Name"
                                                           class="form-control" value="" type="text" v-model="data.mother_name">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Ward no.</label>
                                            <div class="col-md-8 inputGroupContainer">
                                                <div class="input-group"><span class="input-group-addon"><img
                                                        src="/images/backend/icons/ward.png" alt="" height="15"
                                                        width="15"></span>
                                                    <input id="ward" name="ward" placeholder="Ward no."
                                                           class="form-control" required="true" value="" v-model="data.ward_no" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Ethnic code</label>
                                            <div class="col-md-8 inputGroupContainer">
                                                <div class="input-group">
                                                    <span class="input-group-addon" style="max-width: 100%;"><i
                                                            class="glyphicon glyphicon-list"></i></span>
                                                    <select class="selectpicker form-control"
                                                            v-model="data.caste">
                                                        <option
                                                                v-for="(option, index) in casteOptions"
                                                                v-bind:value="index+1"
                                                                :selected="index+1 == data.caste"
                                                        >{{ option }}
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="col-md-6 form-horizontal" colspan="1">
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Card No.</label>
                                            <div class="col-md-8 inputGroupContainer">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                        class="fa fa-calculator" aria-hidden="true"></i></span>
                                                    <input id="card_no" name="" placeholder="Card No."
                                                           class="form-control" value="" v-model="data.card_no" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Gender</label>
                                            <div class="col-md-4">
                                                <label class="radio-inline" v-for="gender in ['Male','Female']">
                                                    <input
                                                            type="radio"
                                                            :id="gender"
                                                            :value="gender"
                                                            v-model="data.gender">
                                                    {{ gender }}
                                                </label>
                                            </div>
<!--                                            <label class="radio-inline">-->
<!--                                                <input type="radio" name="x_Gender" v-model="data.gender" value="F" class="required"-->
<!--                                                       title="*">-->
<!--                                                Female-->
<!--                                            </label>-->
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Weight</label>
                                            <div class="col-md-8 inputGroupContainer">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                        class="fa fa-map-marker fa-lg"></i></span>
                                                    <input id="baby_weight" name="" placeholder="Weight"
                                                           class="form-control" v-model="data.weight" value="" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Father Name</label>
                                            <div class="col-md-8 inputGroupContainer">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                        class="glyphicon glyphicon-user"></i></span>
                                                    <input id="father_name" name="" placeholder="Father Name"
                                                           class="form-control" value="" v-model="data.father_name" type="text">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-md-4 control-label">Phone</label>
                                            <div class="col-md-8 inputGroupContainer">
                                                <div class="input-group"><span class="input-group-addon"><i
                                                        class="glyphicon glyphicon-earphone"></i></span>
                                                    <input id="phone" name="Phone" placeholder="Phone"
                                                           class="form-control" v-model="data.contact_no" required="true" value="" type="text">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <div class="pull-right">
                                <button class="btn btn-primary" @click.prevent="updateBabyInformation(data)">Update</button>
                                <button class="btn btn-danger" @click.prevent="deleteBaby(data)">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="vaccination">
                    <div class="panel">
                        <div class="panel-body">
                            <div class="card card-body">
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th class="col-md-0">S.N.</th>
                                        <th class="col-md-1">Name</th>
                                        <th class="col-md-1">Period</th>
                                        <th scope="col-md-2">Date NP</th>
                                        <th class="col-md-2">Image</th>
                                        <th class="col-md-2">Vac. Address</th>
                                        <th scope="col-md-2">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-if="Object.keys(data.vaccinations).length > 0" v-for="(vaccination, key) in data.vaccinations">
                                        <th scope="row">{{ key +1 }}</th>
                                        <td><input name="" v-model="vaccination.vaccine_name" placeholder="Vaccine Name" class="form-control" readonly value="" type="text"></td>
                                        <td><input name="" v-model="vaccination.vaccine_period" placeholder="Vaccine Period" class="form-control" readonly value="" type="text"></td>
                                        <td><input name="" v-model="vaccination.vaccinated_date_np" placeholder="Vaccinated Date NP" class="form-control" value="" type="text"></td>
                                        <td><input name="" v-model="vaccination.vial_image" placeholder="Vial Image" class="form-control" value="" type="text"></td>
                                        <td><input name="" v-model="vaccination.vaccinated_address" placeholder="Vial Image" class="form-control" value="" type="text"></td>
                                        <td>
                                            <button class="btn btn-primary btn-sm" @click.prevent="updateVaccination(vaccination)">Update</button>
                                            <button class="btn btn-danger btn-sm" @click.prevent="deleteVaccination(vaccination)">Delete</button> 
                                        </td>
                                    </tr>
                                    <tr v-else>
                                       || No Record Found ||
                                    </tr>
                                    </tbody>
                                </table>

                                <!-- /.row -->

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    export default {
        data(){
            return {
                data: [],
                birthPlaceOptions : [ 'Home', 'Health-Organization', 'Transit' ],
                casteOptions: [
                    'दलित', 'जनजाति', 'मधेसी', 'मुस्लिम', 'ब्राह्मण / क्षेत्री', 'अन्य'
                ],
                options: [],
                healthpostSelected : null
            }
        },
        created() {
            this.fetch()
        },
        methods : {
            fetch() {
                let url = window.location.protocol + '/data/api/baby/' + window.location.href.split('/')[4];
                axios.get(url)
                    .then((response) => {
                        this.data = response.data.record;
                    })
                    .catch((error) => {
                        console.error(error)
                    })
                    .finally(() => {
                    })
            },
            updateBabyInformation(data){
                axios.put('/data/api/baby/' + data.token, data)
                    .then((response) => {
                        if (response.status === 200) {
                            this.$swal({
                                title: data.baby_name + '\'s record updated successfully',
                                type: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        } else {
                            this.$swal({
                                title: 'Oops. Something went wrong. Please try again later.',
                                type: 'error',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    })
            },
            updateVaccination(data){
                axios.put('/data/api/baby/vaccination/' + data.id, data)
                    .then((response) => {
                        if (response.status === 200) {
                            this.$swal({
                                title: 'Vaccination record updated successfully',
                                type: 'success',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        } else {
                            this.$swal({
                                title: 'Oops. Something went wrong. Please try again later.',
                                type: 'error',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 3000
                            })
                        }
                    })
            },
            deleteBaby(data) {
                this.$swal({
                    title: 'Are you sure?',
                    text: "Delete " + data.baby_name + "'s records. You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((willDelete) => {
                    if (willDelete.value) {
                        axios.delete('/data/api/baby/' + data.id)
                            .then(response => {
                                window.location = '/admin/child';
                            })
                    }else {
                        this.$swal("Error!", "Failed to delete!", "error");
                    }
                }).catch(() => {
                    this.$swal("Error!", "Failed to delete!", "error");
                })
            },
            deleteVaccination(data){
                this.$swal({
                    title: 'Are you sure?',
                    text: "Delete this records. You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((willDelete) => {
                    if (willDelete.value) {
                        axios.delete('/data/api/baby/vaccination/' + data.id)
                            .then(response => {
                                this.data.vaccinations.splice(this.data.vaccinations.indexOf(data), 1);
                            })
                    }
                }).catch(() => {
                    this.$swal("Error!", "Failed to delete!", "error");
                })
            },
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
            healthpostTransfer(data){
                var html = `<h4>Do you want to transfer ` + this.data.baby_name + `'s records. You won't be able to revert this!</h4>
                            <hr>
                            <p><b>Health Post Information</b></p>
                            <p align="left">
                                Name: `+ data.name +` <br>
                                District: `+ data.district.district_name +` <br>
                                Municipality: `+ data.municipality.municipality_name +` <br>
                                Phone No: `+ data.phone +` <br>
                                Address: `+ data.address +`                    
                            </p>
                `;

                this.$swal({
                    title: 'Are you sure?',
                    text: "",
                    type: 'warning',
                    html: html,  
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, Send it!'
                }).then((willDelete) => {
                    if (willDelete.value) {
                        let url = window.location.protocol + '/api/v1/vtc/baby-transfer';
                        axios.post(url, 
                                    {token: this.data.token, from: this.data.hp_code, to : data.hp_code}, 
                                    {headers: {'Accept': 'application/json'}})
                            .then(function (response) {
                                window.close();
                            })
                            .catch(function (error) {
                                console.log(error);
                        });
                    }else {
                        this.$swal("Cancelled !", "You cancel the request", "info");
                    }
                }).catch(() => {
                    this.$swal("Error!", "Failed to transfer!", "error");
                })
            }
        }
    }
</script>

<style scoped>
    .fullname {
        text-transform: capitalize;
    }
</style>