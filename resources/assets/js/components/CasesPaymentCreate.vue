<template>
  <div class="container row">
    <form @submit.prevent>
      <div class="large-12 medium-12 small-12 cell">
        <label>File
          <input type="file" id="file" ref="bulk_file" v-on:change="handleFileUpload()" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"/>
        </label>
          <button class="btn btn-info pull-right" v-on:click="submitFile()">
            Bulk Upload
        </button>
      </div>
    <div v-show="true" class="panel panel-default" :class="{ 'panel-danger': $v.labSelected.$error }">
      <div class="panel-heading text-center"><strong>Search Lab ID in IMU</strong></div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-8 form-group">
            <label>Lab Name * </label>
            <v-select label="name"
                      v-model="labSelected"
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
            <label>Patient ID in Lab * </label> <input type="text" placeholder="Enter Patient ID in Lab" class="form-control" v-model.trim="data.lab_id" />
          </div>
        </div>
        <button class="btn btn-info pull-right" v-on:click="searchOrganization(labSelected, data.lab_id)" title="Varified, Find, Search Patient">
          <i class="fa fa-search"> Search</i>
        </button>
      </div>
    </div>
    <hr v-show="true" style="height:2px;border-width:0;color:gray;background-color:gray">
    <div class="row">
      <div class="form-group col-lg-6"  :class="{ 'has-error': $v.data.hospital_register_id.$error }">
        <label class="control-label" for="hospital_register_id">Patient ID in Hospital *</label>
        <input type="text" placeholder="Enter Patient ID in your Hospital" class="form-control" v-model.trim="data.hospital_register_id" id="hospital_register_id" />
      </div>


      <div class="form-group col-lg-6" :class="{ 'has-error': $v.data.register_date_np.$error }">
        <label class="control-label" for="register_date">Register Date * &nbsp;<span class="label label-info pull-right">{{ data.register_date_np }}</span></label>
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-calendar"></i></span>
          <v-nepalidatepicker id="register_date" classValue="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD"
                              format="YYYY-MM-DD" v-model.trim="data.register_date_np" :yearSelect="false"
                              :monthSelect="false"/>
        </div>
      </div>

    </div>
    <div class="form-group" :class="{ 'has-error': $v.data.name.$error }">
        <label class="control-label" for="name">Name *</label>
        <input type="text" placeholder="Enter Full Name with space between first, middle and last name" class="form-control" v-model.trim="data.name" id="name" />
      </div>
      <div class="row">
        <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.age.$error }">
          <label class="control-label" for="age">Age *</label>
          <input type="radio" id="age_years" v-model.trim="data.age_unit" value="0">
          <label class="control-label" for="age_years">Years</label> &nbsp; &nbsp;
          <input type="radio" id="age_months" v-model.trim="data.age_unit" value="1">
          <label class="control-label" for="age_months">Months</label> &nbsp; &nbsp;
          <input type="radio" id="age_days" v-model.trim="data.age_unit" value="2">
          <label class="control-label" for="age_days">Days</label> &nbsp; &nbsp;

          <input type="text" placeholder="Enter age" class="form-control" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" v-model.trim="data.age" id="age"/>
        </div>
        <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.gender.$error }">
          <label class="control-label">Gender *</label><br>
          <input type="radio" id="male" v-model.trim="data.gender"  value="1">
          <label class="control-label" for="male">Male</label> &nbsp; &nbsp;
          <input type="radio" id="female" v-model.trim="data.gender" value="2">
          <label class="control-label" for="female">Female</label> &nbsp; &nbsp;
          <input type="radio" id="other" v-model.trim="data.gender" value="3">
          <label class="control-label" for="other">Other</label>
        </div>
        <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.phone.$error }">
          <label class="control-label" for="phone">Mobile No *</label>
          <input type="text" placeholder="Enter mobile number" class="form-control" v-model.trim="data.phone" id="phone" />
        </div>
      </div>
    <div class="form-group" :class="{ 'has-error': $v.data.address.$error }">
      <label class="control-label" for="name">Current Address *</label>
      <input type="text" placeholder="Enter Current Address ( e.g Lazimpat-2, Kathmandu )" class="form-control" v-model.trim="data.address" id="address" />
    </div>
    <hr>
      <div class="row">
        <div class="form-group col-lg-12" :class="{ 'has-error': $v.data.method_of_diagnosis.$error }">
          <label class="control-label">Method df Diagnosis</label><br>
          <input type="radio" id="pcr" v-model.trim="data.method_of_diagnosis"  value="1">
          <label class="control-label" for="pcr">PCR</label> &nbsp; &nbsp;
          <input type="radio" id="antigen" v-model.trim="data.method_of_diagnosis" value="2">
          <label class="control-label" for="antigen">Antigen</label> &nbsp; &nbsp;
          <input type="radio" id="clinical" v-model.trim="data.method_of_diagnosis" value="3">
          <label class="control-label" for="clinical">Clinical Diagnosis</label> &nbsp; &nbsp;
          <input type="radio" id="others" v-model.trim="data.method_of_diagnosis" value="10">
          <label class="control-label" for="others">Others</label>
        </div>
      </div>
    <div class="row">
      <div v-if="!is_to_update" class="form-group col-lg-4" :class="{ 'has-error': $v.data.health_condition.$error }">
        <label class="control-label" for="health_condition">Health Condition *</label>
        <select class="form-control" v-model.trim="data.health_condition" id="health_condition">
          <option value="0" selected hidden>Please Select Medical Condition</option>
          <option value="1">No Symptoms</option>
          <option value="2">Mild</option>
          <option value="3">Moderate ( HDU )</option>
          <option value="4">Severe - ICU</option>
          <option value="5">Severe - Ventilator</option>
        </select>
      </div>
      <div v-else class="form-group col-lg-8">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Health Condition</th>
            <th>Start Date</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>{{  getHealthCondition(data.health_condition) }}</td>
            <td>{{ data.register_date_en }}</td>
          </tr>
          <tr v-if="health_condition_update_lists" v-for="item in health_condition_update_lists">
            <td>{{  getHealthCondition(item.id) }}</td>
            <td>{{ item.date }}</td>
          </tr>
          </tbody>

        </table>
        <div v-if="!isHealthConditionAddHidden" class="form-group col-lg-10">

          <div class="form-group col-lg-6">
            <select class="form-control" v-model.trim="health_condition_details_health_condition" id="health_condition_details_health_condition">
              <option value="" selected="selected">Please Select Medical Condition</option>
              <option value="1">No Symptoms</option>
              <option value="2">Mild</option>
              <option value="3">Moderate &  HDU</option>
              <option value="4">Severe - ICU</option>
              <option value="5">Severe - Ventilator</option>
            </select>
          </div>
          <div class="form-group col-lg-4">
            <div class="input-group"><span class="input-group-addon"><i
                class="fa fa-calendar"></i></span>
              <v-nepalidatepicker id="health_condition_details_start_date" classValue="form-control" calenderType="Nepali" placeholder="Start Date"
                                  format="YYYY-MM-DD" v-model.trim="health_condition_details_start_date" :yearSelect="false"
                                  :monthSelect="false"/>
            </div>
          </div>
          <button v-on:click="isHealthConditionAddHidden = true" type="button" class="btn btn-warning btn-sm pull-right"><i class="fa fa-remove" aria-hidden="true"></i>
            Cancel</button>
        </div>
        <button v-on:click="isHealthConditionAddHidden = false" type="button" class="btn btn-primary btn-sm pull-right"><i class="fa fa-plus" aria-hidden="true"></i>
          Add</button>
      </div>
      <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.self_free.$error }">
        <label class="control-label">Payment Provision *</label><br>
        <input type="radio" id="self" v-model.trim="data.self_free" value="1">
        <label class="control-label" for="self">Paid</label> &nbsp; &nbsp;
        <input type="radio" id="free" v-model.trim="data.self_free" value="2">
        <label class="control-label" for="free">Free</label> &nbsp; &nbsp;
      </div>
    </div>
    <p v-if="health_condition_details_validation !== ''" class="text-danger">{{ health_condition_details_validation }}</p>

    <hr>
    <div class="form-group">
      <label for="guardian_name">Parent/Guardian Name</label>
      <input type="text" class="form-control" v-model.trim="data.guardian_name" id="guardian_name" />
    </div>
    <div class="row">
      <div class="form-group col-lg-4">
        <label>Treatment Outcome * </label><br>
        <input type="radio" id="treatment" v-model.trim="data.is_death" value="">
        <label for="treatment">Under Treatment</label> &nbsp; &nbsp;
        <input type="radio" id="discharge" v-model.trim="data.is_death" value="1">
        <label for="discharge">Discharge</label> &nbsp; &nbsp;
        <input type="radio" id="death" v-model.trim="data.is_death" value="2">
        <label for="death">Death</label> &nbsp; &nbsp;
      </div>

      <div v-show="data.is_death !== ''" class="form-group col-lg-4">
        <label for="date_of_outcome">Date of Outcome &nbsp;<span class="label label-info pull-right">{{ data.date_of_outcome }}</span></label>
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-calendar"></i></span>
            <v-nepalidatepicker id="date_of_outcome" classValue="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD"
                                format="YYYY-MM-DD" v-model.trim="data.date_of_outcome" :yearSelect="false"
                                :monthSelect="false"/>
          </div>
      </div>
    </div>
    <div class="form-group">
        <label for="remark">Remarks</label>
        <textarea class="form-control" v-model.trim="data.remark" id="remark" rows="5"></textarea>
      </div>
      <div v-if="$v.invalid" class="alert alert-danger">
        * Please fill the all required fields
      </div>
      <button type="submit" :disabled="isSubmitting" @click="submitData(data)" class="btn btn-primary btn-lg btn-block">Save</button>

    </form>
  </div>
</template>
<script type="text/javascript">

import axios from "axios";
import {required, minValue} from "vuelidate/lib/validators";
import DataConverter from "ad-bs-converter";

export default {
  data() {
    return {
      data : {
        health_condition : 0,
        is_death : '',
        age_unit : 0,
        method_of_diagnosis : 0,
        gender: undefined
      },
      lab_id : '',
      options: [],
      labSelected : '',
      is_to_update : false,
      update_id : '',
      isHealthConditionAddHidden: true,
      health_condition_details_health_condition : '',
      health_condition_details_start_date : '',
      health_condition_details_validation : '',
      health_condition_update_lists : [],
      isSubmitting: false,
      bulk_file: ''
    }
  },
  validations: {
    data: {
      name: { required },
      age : { required },
      phone : { required },
      hospital_register_id : { required },
      register_date_np : { required },
      method_of_diagnosis : { required },
      gender : { required },
      self_free : { required },
      health_condition : { required, minValue : minValue(1) },
      address : { required }
    },
    labSelected : { required }
  },
  methods: {
    handleFileUpload(){
      this.bulk_file = this.$refs.bulk_file.files[0];
    },
    submitFile(){
      let formData = new FormData();
      if(!this.bulk_file){
        alert('Please upload a valid excel file');
        return;
      }
      formData.append('bulk_file', this.bulk_file);
      axios.post( '/api/v1/bulk-case-payment',
        formData,
        {
          headers: {
              'Content-Type': 'multipart/form-data'
          }
        }
      ).then(function(res){
        alert(res.data.message);
      })
      .catch(function(err){
        let errorMsg = 'The Case Payment could not be uploaded due to the following problems: \n';
        err.response.data.message.map((problem, index)=>{
          errorMsg += (index+1)+'. Row: '+problem.row+', Column: '+problem.column+', Error: '+problem.error.join()+'\n';
        })
        alert(errorMsg);
      });
    },
    onSearch(search, loading) {
      loading(true);
      this.search(loading, search, this);
    },
    search: _.debounce((loading, search, vm) => {
      let url = window.location.protocol + '/api/v1/healthposts-for-lab-and-hospital';
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
                is_death : '',
                lab_id : id
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
      this.isSubmitting = true;
      if (this.$v.$invalid) {
        this.isSubmitting = false;
        return false;
      }
      if (this.is_to_update){
        this.data.id = this.update_id;
        if(this.labSelected.name !== undefined){
          data.lab_name = this.labSelected.name;
        }else{
          data.lab_name = this.labSelected;
        }
        this.health_condition_details_validation = ''
        if (!this.isHealthConditionAddHidden){
          if(this.health_condition_details_health_condition !== '' &&
              this.health_condition_details_start_date !== ''
          ){
            if(this.health_condition_update_lists.length === 0){
              data.health_condition_update = JSON.stringify([{
                id : this.health_condition_details_health_condition,
                date : this.bs2ad(this.health_condition_details_start_date)
              }]);
            }else{
              this.health_condition_update_lists.push({
                id : this.health_condition_details_health_condition,
                date : this.bs2ad(this.health_condition_details_start_date)
              });
              data.health_condition_update = JSON.stringify(this.health_condition_update_lists);
            }
            console.log("all Pass");
          }else{
            this.health_condition_details_validation = "Please select both Health Condition and Date"
            this.isSubmitting = false;
            return false;
          }
          
        }

      }else{
        data.lab_name = this.labSelected.name;
      }
      data.register_date_en = this.bs2ad(data.register_date_np);
      if(data.is_death !== ''){
        data.date_of_outcome_en = this.bs2ad(data.date_of_outcome);
      }
      if (data.is_death === ''){
        data.date_of_outcome_en = null;
        data.date_of_outcome = null;
      }
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
              if (this.is_to_update) {
                if (!this.isHealthConditionAddHidden){
                  if(this.health_condition_details_health_condition !== '' &&
                      this.health_condition_details_start_date !== ''
                  ){
                    this.health_condition_details_health_condition = ''
                    this.health_condition_details_start_date = ''
                  }
                }
                this.isSubmitting = false;
                return false;
              }
              this.$v.$reset();
              var today = new Date();
              this.data = {
                health_condition : 0,
                    is_death : '',
                register_date_np : this.ad2bs(today),
                lab_name : this.labSelected.name
              }
              this.isSubmitting = false;

            } else {
              this.$swal({
                title: 'Oops. Something went wrong.',
                type: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
              this.isSubmitting = false;
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
    },
    getHealthCondition(data){
      var items = {
        1: 'No Symptoms',
        2: 'Mild',
        3: 'Moderate ( HDU )',
        4: "Severe - ICU",
        5: 'Severe - Ventilator'
      };
      return items[data];
    }
  },
  created(){
    let url = new URL(window.location.href);
    let id = url.searchParams.get("token");

    if(id){
      // console.log(id);
      axios.get('/api/v1/search-cases-payment-by-id?id='+id)
          .then((response) => {
            if(Object.keys(response.data).length > 0){
              // this.data.lab_name = response.data.lab_name;
              this.data.id  = response.data.id;
              this.data.lab_id = response.data.lab_id;
              this.data.name = response.data.name;
              this.data.age = response.data.age;
              this.data.age_unit = response.data.age_unit;
              this.data.phone = response.data.phone;
              this.data.method_of_diagnosis = response.data.method_of_diagnosis;
              this.data.hospital_register_id = response.data.hospital_register_id;
              this.data.register_date_np = response.data.register_date_np;
              this.data.register_date_np = response.data.register_date_np;
              this.data.register_date_en = (new Date(response.data.register_date_en)).toLocaleString().split(',')[0].split("/").reverse().join("-");
              ;
              this.data.gender = response.data.gender;
              this.data.self_free = response.data.self_free;
              this.data.health_condition = response.data.health_condition;
              if (response.data.is_death == null){
                this.data.is_death = '';
              }else{
                this.data.is_death = response.data.is_death;
                this.data.date_of_outcome = response.data.date_of_outcome;
              }
              this.data.guardian_name = response.data.guardian_name;
              this.data.address = response.data.address;
              this.data.remark = response.data.remark;
              this.labSelected = response.data.lab_name;
              this.is_to_update = true;
              this.update_id = response.data.id;
              if(response.data.health_condition_update !== null){
                   this.health_condition_update_lists =  JSON.parse(response.data.health_condition_update);
              }
            }
          })
          .catch((error) => {
            console.error(error)
          })
          .finally(() => {
          })
    }
    else{
      var today = new Date();
      this.data.register_date_np = this.ad2bs(today);
    }
  }
}
</script>
