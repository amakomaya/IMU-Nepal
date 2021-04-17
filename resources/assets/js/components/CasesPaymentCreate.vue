<template>
  <div class="container row">
    <div v-show="false" class="panel panel-default">
      <div class="panel-heading text-center"><strong>Search Lab ID in IMU</strong></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-8">
            <label>Lab Name :</label>
            <v-select label="name"
                      v-model="organizationSelected"
                      placeholder="Type Lab Name to search informations .."
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

          </div>
          <div class="col-lg-4 form-group">
            <label>Lab ID : </label> <input type="text" placeholder="Enter Lab ID here" class="form-control" v-model.trim="lab_id" />
          </div>
        </div>
        <button class="btn btn-info pull-right" v-on:click="searchOrganization(organizationSelected, lab_id)" title="Send Patient">
          <i class="fa fa-search"> Search</i>
        </button>
      </div>
    </div>
    <hr v-show="false" style="height:2px;border-width:0;color:gray;background-color:gray">
    <div class="row">
      <div class="form-group col-lg-6">
        <label for="hospital_register_id">Hospital Reg. ID for Case</label>
        <input type="text" placeholder="Enter Hospital Register ID for Case" class="form-control" v-model.trim="data.hospital_register_id" id="hospital_register_id" />
      </div>


      <div class="form-group col-lg-6">
        <label for="date_of_death">Register Date</label>
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-calendar"></i></span>
          <v-nepalidatepicker id="register_date" classValue="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD"
                              format="YYYY-MM-DD" v-model.trim="data.register_date_np" :yearSelect="false"
                              :monthSelect="false"/>
        </div>
      </div>

    </div>
    <div class="form-group" :class="{ 'has-error': $v.data.name.$error }">
        <label for="name">Name</label>
        <input type="text" placeholder="Enter Full Name" class="form-control" v-model.trim="data.name" id="name" />
      </div>
      <div class="row">
        <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.age.$error }">
          <label for="age">Age</label>
          <input type="text" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" v-model.trim="data.age" id="age"/>
        </div>
        <div class="form-group col-lg-4">
          <label>Gender</label><br>
          <input type="radio" id="male" v-model.trim="data.gender"  value="1">
          <label for="male">Male</label> &nbsp; &nbsp;
          <input type="radio" id="female" v-model.trim="data.gender" value="2">
          <label for="female">Female</label> &nbsp; &nbsp;
          <input type="radio" id="other" v-model.trim="data.gender" value="3">
          <label for="other">Other</label>
        </div>
        <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.phone.$error }">
          <label for="phone">Phone</label>
          <input type="text" class="form-control" v-model.trim="data.phone" id="phone" />
        </div>
      </div>
    <div class="form-group" :class="{ 'has-error': $v.data.address.$error }">
      <label for="name">Address</label>
      <input type="text" placeholder="Enter Full Address ( e.g Lazimpat-2, Kathmandu )" class="form-control" v-model.trim="data.address" id="address" />
    </div>
    <hr>
    <div class="row">
      <div class="form-group col-lg-4">
        <label for="health_condition">Health Condition</label>
        <select class="form-control" v-model.trim="data.health_condition" id="health_condition">
          <option value="0" selected hidden>Please Select Medical Condition</option>
          <option value="1">No Symptoms</option>
          <option value="2">Mild</option>
          <option value="3">Moderate</option>
          <option value="4">Severe - ICU</option>
          <option value="5">Severe - Ventilator</option>
        </select>

      </div>
      <div class="form-group col-lg-4">
        <br>
        <input type="radio" id="self" v-model.trim="data.self_free" value="1">
        <label for="self">Self</label> &nbsp; &nbsp;
        <input type="radio" id="free" v-model.trim="data.self_free" value="2">
        <label for="free">Free</label> &nbsp; &nbsp;
      </div>
    </div>
    <hr>
    <div class="form-group" :class="{ 'has-error': $v.data.guardian_name.$error }">
      <label for="guardian_name">Parent/Guardian Name</label>
      <input type="text" class="form-control" v-model.trim="data.guardian_name" id="guardian_name" />
    </div>
    <div class="row">
      <div class="form-group col-lg-4">
        <label>Treatment Outcome</label><br>
        <input type="radio" id="treatment" v-model.trim="data.is_death" value="">
        <label for="treatment">Under Treatment</label> &nbsp; &nbsp;
        <input type="radio" id="discharge" v-model.trim="data.is_death" value="1">
        <label for="discharge">Discharge</label> &nbsp; &nbsp;
        <input type="radio" id="death" v-model.trim="data.is_death" value="2">
        <label for="death">Death</label> &nbsp; &nbsp;
      </div>
      <div v-show="data.is_death !== ''" class="form-group col-lg-4">
        <label for="date_of_death">Date of Outcome</label>
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-calendar"></i></span>
            <v-nepalidatepicker id="date_of_death" classValue="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD"
                                format="YYYY-MM-DD" v-model.trim="data.date_of_outcome" :yearSelect="false"
                                :monthSelect="false"/>
        </div>
      </div>
    </div>
    <hr style="height:2px;border-width:0;color:gray;background-color:gray">

    <div class="row">
      <div class="form-group col-lg-8">
        <label for="lab_name">Lab Name</label>
        <input type="text" placeholder="Enter Lab Name" class="form-control" v-model.trim="data.lab_name" id="lab_name" />
      </div>

      <div class="form-group col-lg-4">
        <label for="lab_id">Lab Id</label>
        <input type="text" placeholder="Enter Lab Id" class="form-control" v-model.trim="data.lab_id" id="lab_id" />
      </div>
      </div>

    <hr style="height:2px;border-width:0;color:gray;background-color:gray">

    <div class="form-group">
        <label for="remark">Remarks</label>
        <textarea class="form-control" v-model.trim="data.remark" id="remark" rows="5"></textarea>
      </div>
      <button type="submit" @click.prevent="submitData(data)" class="btn btn-primary btn-lg btn-block">Save</button>
  </div>
</template>
<script type="text/javascript">

import axios from "axios";
import {required} from "vuelidate/lib/validators";
import DataConverter from "ad-bs-converter";

export default {
  data() {
    return {
      data : {
        health_condition : 0,
        is_death : ''
      },
      lab_id : '',
      options: [],
      organizationSelected : ''
    }
  },
  validations: {
    data: {
      name: {
        required,
      },
      age : {
        required,
      },
      phone : { required },
      guardian_name : { required },
      address : { required }
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

    searchOrganization(organization, id){
      if (organization === '' || id === ''){
        this.$swal({
          title: 'Please fill both lab name and Id',
          type: 'warning',
          toast: true,
          position: 'top-end',
          showConfirmButton: false,
          timer: 3000
        })
        return false;
      }
      var payload = {
        id: id,
        hp_code: organization.hp_code,
      };
      axios.post('/api/v1/cases-search-by-lab-and-id', payload)
          .then((response) => {
            if (response.data.message === 'success') {
              this.$swal({
                title: 'Record Found',
                type: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
              this.data = {
                name : response.data.data.name,
                age : response.data.data.age,
                gender : response.data.data.sex,
                address : response.data.data.tole + '-' + response.data.data.ward + ',' + response.data.data.municipality_name,
                phone : response.data.data.emergency_contact_one,
                health_condition : 0,
                is_death : ''
              };
              if (this.item){
                this.$dlg.closeAll(function(){
                  // do something after all dialog closed
                })
              }
            } else {
              this.$swal({
                title: 'Oops. No record found.',
                type: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
            }
          })
    },
    submitData(data){
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      data.register_date_en = this.bs2ad(data.register_date_np);
      axios.post('/api/v1/cases-payment', data)
          .then((response) => {
            if (response.data.message === 'success') {
              this.$swal({
                title: 'Successfully add records',
                type: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
              this.$v.$reset();
              var today = new Date();
              this.data = {
                health_condition : 0,
                    is_death : '',
                register_date_np : this.ad2bs(today)
              };
              if (this.item){
                this.$dlg.closeAll(function(){
                  // do something after all dialog closed
                })
              }
            } else {
              this.$swal({
                title: 'Oops. Something went wrong.',
                type: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
            }
          })
    },
    ad2bs: function (date) {
      var dateObject = new Date(date);

      var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return dateConverter.en.year + '-' + dateConverter.en.month + '-' + dateConverter.en.day;

    },
    bs2ad: function (date) {
      var split_date = date.split("-");
      var formated_date = split_date[0]+ "/" + split_date[1]+ "/" + split_date[2];
      let dateConverter;
      dateConverter = DataConverter.bs2ad(formated_date);
      return dateConverter.year + '-' + dateConverter.month + '-' + dateConverter.day;
    }
  },
  created(){
    var today = new Date();
    this.data.register_date_np = this.ad2bs(today);
  }
}
</script>
