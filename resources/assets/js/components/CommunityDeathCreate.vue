<template>
  <div class="container row">
    <form @submit.prevent>
    <!--
      <div v-show="is_to_update==false">
      <label for="file" class="btn btn-primary">Bulk Upload
        <i class="fa fa-upload" aria-hidden="true"></i>
      </label>
      <a href="/downloads/excel/cases_payment_import_template.xlsx" onclick="return confirm('Are you sure, do you want to download import format ! ')" title="Do you have template ? if not, please download first and fill data than import.">Do you have template ? </a>
      <input type="file" id="file" ref="bulk_file" v-on:change="handleFileUpload()" accept=".csv, application/vnd.ms-excel, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" style="visibility:hidden;"/>
      </div>
    -->
   
    
    <div class="form-group" :class="{ 'has-error': $v.data.name.$error }">
        <label class="control-label" for="name">Name *</label>
        <input type="text" placeholder="Enter Full Name with space between first, middle and last name" class="form-control" v-model.trim="data.name" id="name"/>
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
        <input type="text" placeholder="Enter mobile number" class="form-control" v-model.trim="data.phone" id="phone"/>
      </div>
    </div>
    <div class="row">
      <div class="col-lg-12">
        <label class="control-label">Current Address *</label>
      </div>
      <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.province_id.$error }">
        <label class="control-label" for="province">Province *</label>
        <select class="form-control show-arrow" v-model.trim="data.province_id" id="province" @change="onProvinceChange(data.province_id)">
          <option value="" selected hidden>Select Province</option>
          <option :value="province.id"  v-for="province in allProvinceList" >
            {{province.province_name}}
          </option>
        
        </select>
      </div>
      <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.district_id.$error }">
        <label class="control-label" for="district">District *</label>
        <select class="form-control show-arrow" v-model.trim="data.district_id" id="district" @change="onDistrictChange(data.district_id)">
          <option value="" selected hidden>Select District</option>
          <option :value="district.id"  v-for="district in filteredDistrictList" >

            {{district.district_name}}

          </option>
        
        </select>
      </div>
      <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.municipality_id.$error }">
        <label class="control-label" for="municipality">Municipality *</label>
        <select class="form-control show-arrow" v-model.trim="data.municipality_id" id="municipality" @change="onMunicipalityChange(data.municipality_id)">>
          <option value="" selected hidden>Select Municipality</option>
          <option :value="municipality.id"  v-for="municipality in filteredMunicipalityList" >
            {{municipality.municipality_name}}
          </option>
        </select>
      </div>
      <div class="form-group col-lg-4 " :class="{ 'has-error': $v.data.ward.$error }">
        <label class="control-label" for="ward">Ward No *</label>
        <input type="text" placeholder="Ward No" class="form-control" v-model.trim="data.ward" id="ward" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"/>
      </div>
      <div class="form-group col-lg-8" :class="{ 'has-error': $v.data.address.$error }">
        <label class="control-label" for="name">Tole *</label>
        <input type="text" placeholder="Enter Tole ( e.g Lazimpat-2, Kathmandu )" class="form-control" v-model.trim="data.address" id="address" />
      </div>
    </div>
    
    
     <div class="row">
        <div v-show="data.gender=='2'" class="form-group col-lg-4" :class="{ 'has-error': $v.data.pregnant_status.$error }">
            <label class="control-label" for="age">Pregnent? *</label><br />
            <input type="radio" id="preg_yes" v-model.trim="data.pregnant_status" value="1">
            <label class="control-label" for="preg_yes">Yes</label> &nbsp; &nbsp;
            <input type="radio" id="preg_no" v-model.trim="data.pregnant_status" value="0">
            <label class="control-label" for="preg_no">No</label> &nbsp; &nbsp;
        </div>
        <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.date_of_positive_np.$error }">
          <label class="control-label" for="date_of_positive_np">Covid 19 Positive Date *</label><br />
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-calendar"></i></span>

              <input readonly id="date_of_positive_np" type="text" placeholder="YYYY-MM-DD" v-model.trim="data.date_of_positive_np" value="" name="date" class="form-control date-picker-date_of_positive_np" />
          </div>
        </div>
      </div>
    <hr>
    <div class="row">
      <div class="form-group col-lg-12" :class="{ 'has-error': $v.data.comorbidity.$error }">
        <label class="control-label">
          Co-morbidity *
        </label><br>
        <label class="control-label">
          <input type="checkbox" @change="toggleHealthy(data)" v-model.trim="data.comorbidity" value="21">
          Normal Health Condition
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="8">
          Blood Pressure (High/ Low)
        </label><br />
        
        <label class="control-label">  
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="1">
          Cancer
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="2">
          Chronic kidney disease
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="3">
          Chronic respiratory diseases / Lung Diseases
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="4">
          Cardio related disease
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="5">
          Diabetes
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="9">
          Neuro related diseases
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="11">
          Tuberculosis (TB)
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="22">
          Mental Disease
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="31">
          HIV / AIDS
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="23">
          Already Covid-19 infected
        </label><br />
        <label class="control-label">
          <input type="checkbox" @change="toggleComorbitidy(data)" v-model.trim="data.comorbidity" value="10">
          Other
        </label><br />
      </div>
    </div>
    <div v-show="data.comorbidity && (data.comorbidity.includes('10') || data.comorbidity.includes(10))" class="form-group col-lg-4" :class="{ 'has-error': $v.data.other_comorbidity.$error }">
        <label for="other_comorbidity">Specify Other Comorbidity</label>
          <div class="input-group">
              <input type="text" class="form-control" v-model.trim="data.other_comorbidity" id="other_comorbidity" />
          </div>
    </div>
    <div class="row">
      <div class="form-group col-lg-12" :class="{ 'has-error': $v.data.method_of_diagnosis.$error }">
        <label class="control-label">Method of Diagnosis *</label><br>
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
      <div class="form-group col-lg-12" :class="{ 'has-error': $v.data.complete_vaccination.$error }">
        <label class="control-label">COVID 19 vaccination *</label><br>
        <input type="radio" id="v_no" v-model.trim="data.complete_vaccination" value="0">
        <label class="control-label" for="v_no">None</label> &nbsp; &nbsp;
        <input type="radio" id="v_first" v-model.trim="data.complete_vaccination"  value="1">
        <label class="control-label" for="v_first">1st Dose</label> &nbsp; &nbsp;
        <input type="radio" id="v_second" v-model.trim="data.complete_vaccination"  value="2">
        <label class="control-label" for="v_second">2nd Dose</label> &nbsp; &nbsp;
      </div>
      <div v-if="data.complete_vaccination=='1' || data.complete_vaccination=='2'" class="form-group col-lg-6" :class="{ 'has-error': $v.data.vaccine_type.$error }">
        <label class="control-label" for="vaccine_type">Vaccine Type *</label>
        <select class="form-control show-arrow" v-model.trim="data.vaccine_type" id="vaccine_type">
          <option value="" selected="selected">Please Select Vaccine</option>
          <option value="1">Verocell (Sinopharm)</option>
          <option value="2">Covishield (The Serum Institute of India)</option>
          <option value="3">Pfizer</option>
          <option value="4">Moderna</option>
          <option value="5">AstraZeneca</option>
          <option value="10">Other</option>
        </select>
      </div>
      <div v-if="data.vaccine_type=='10'" class="form-group col-lg-6" :class="{ 'has-error': $v.data.other_vaccine_type.$error }">
        <label for="other_vaccine_type">Specify other type of vaccine *</label>
          <div class="input-group">
              <input type="text" class="form-control" v-model.trim="data.other_vaccine_type" id="other_vaccine_type" />
          </div>
      </div>
    </div>
    <hr>
    <div class="form-group" :class="{ 'has-error': $v.data.guardian_name.$error }">
      <label for="guardian_name">Parent/Guardian Name *</label>
      <input type="text" placeholder="Parent/Guardian Name" class="form-control" v-model.trim="data.guardian_name" id="guardian_name" />
    </div>
    <div class="row">
      <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.date_of_outcome_np.$error }">
        <label for="date_of_outcome_np">Date of Outcome * &nbsp;<span class="label label-info pull-right">{{ data.date_of_outcome_np }}</span></label>
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-calendar"></i></span>
              <input readonly id="date_of_outcome_np" type="text" placeholder="YYYY-MM-DD" v-model.trim="data.date_of_outcome_np" value="" name="date" class="form-control date-picker-date_of_outcome" />
          </div>
      </div>
      <div class="form-group col-lg-4" :class="{ 'has-error': $v.data.time_of_death.$error }">
        <label for="time_of_death">Time of Death * &nbsp;</label>
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-calendar"></i></span>
              <vue-timepicker id="time_of_death" classValue="form-control" format="hh:mm a" v-model.trim="data.time_of_death"></vue-timepicker>
          </div>
      </div>

      <div class="form-group col-lg-12" :class="{ 'has-error': $v.data.cause_of_death.$error }">
        <label class="control-label">Cause of Death *</label><br>
        <input type="radio" id="d-covid" v-model.trim="data.cause_of_death"  value="1">
        <label class="control-label" for="d-covid">COVID-19</label> &nbsp; &nbsp;
        <input type="radio" id="d-cov-pne" v-model.trim="data.cause_of_death" value="2">
        <label class="control-label" for="d-cov-pne">COVID 19, Pneumonia</label> &nbsp; &nbsp;
        <input type="radio" id="d-cov-pne-cardio" v-model.trim="data.cause_of_death" value="3">
        <label class="control-label" for="d-cov-pne-cardio">COVID 19, Pneumonia with Cardio Respiratory Failure</label> &nbsp; &nbsp;
        <br />
        <input type="radio" id="d-cov-ards" v-model.trim="data.cause_of_death" value="4">
        <label class="control-label" for="d-cov-ards">COVID 19 with ARDS</label>&nbsp; &nbsp;
        <input type="radio" id="d-cov-pne-htn" v-model.trim="data.cause_of_death" value="5">
        <label class="control-label" for="d-cov-pne-htn">Severe COVID 19, Pneumonia with HTN</label>&nbsp; &nbsp;
        <input type="radio" id="d-other" v-model.trim="data.cause_of_death" value="10">
        <label class="control-label" for="d-other">Others</label>
      </div>
      <div v-show="data.cause_of_death=='10'" class="form-group col-lg-4" :class="{ 'has-error': $v.data.other_death_cause.$error }">
        <label for="other_death_cause">Specify Other Cause of Death*</label>
          <div class="input-group">
              <input type="text" placeholder="Specify Other Cause of Death" class="form-control" v-model.trim="data.other_death_cause" id="other_death_cause" />
          </div>
      </div>
    </div>
    <div class="form-group">
        <label for="remarks">Remarks</label>
        <textarea class="form-control" v-model.trim="data.remarks" id="remarks" rows="5"></textarea>
      </div>
      <div v-if="$v.$invalid" class="alert alert-danger">
        * Please fill all the required fields
      </div>
      <button type="submit" :disabled="isSubmitting" @click="submitData(data)" class="btn btn-primary btn-lg btn-block">Save</button>

    </form>
  </div>
</template>

<script type="text/javascript">
import axios from "axios";
import {required, minValue, helpers} from "vuelidate/lib/validators";
import DataConverter from "ad-bs-converter";

export default {
  data() {
    return {
      data : {
        name: null,
        age_unit : 0,
        method_of_diagnosis : undefined,
        gender: undefined,
        complete_vaccination: undefined,
        time_of_death: null,
        comorbidity: [],
        other_comorbidity: null,
        pregnant_status: null,
        date_of_positive_en: '',
        date_of_positive_np: '',
        cause_of_death: null,
        other_death_cause: null,
        province_id: this.$federalInfo.province_id,
        district_id: this.$federalInfo.district_id,
        municipality_id: this.$federalInfo.municipality_id,
        ward: null,
        vaccine_type: null,
        other_vaccine_type: null,
        tole: null,
        guardian_name: null,
        phone: null,
        date_of_outcome_np: this.date_today_np,
        address: ''
      },
      options: [],
      is_to_update : false,
      update_id : '',
      isSubmitting: false,
      bulk_file: '',
      allProvinceList: [],
      allDistrictList: [],
      filteredDistrictList: [],
      allMunicipalityList: [],
      filteredMunicipalityList: [],
    }
  },
  mounted () {
    this.getFederalDropdown();
    this.getTodayDate();
  },
  validations() {
    let causeOfDeathVdn = { required };
    let otherDeathCauseVdn = (this.data.cause_of_death && this.data.cause_of_death.toString() === '10')?{ required }:{};
    let pregnantStatusVdn = this.data.gender && this.data.gender.toString()==='2'?{ required }:{};
    let otherComorbidityVdn = this.data.comorbidity && (this.data.comorbidity.includes('10')||this.data.comorbidity.includes(10))?{ required }:{};
    let dateofOutcomeVdn = { required };
    let timeOfDeathVdn = { required };
    let vacccineTypeVdn = this.data.complete_vaccination && this.data.complete_vaccination.toString()!='0'?{ required }:{};
    let otherVaccineTypeVdn = this.data.vaccine_type && this.data.vaccine_type.toString() === '10'?{ required }:{};
    let validationRules = {
      data: {
        name: { required },
        age : { required },
        phone : { 
          required,
          isPhoneValid(phone) {
            const regex = /(?:\+977[- ])?\d{2}-?\d{7,8}/i;
            return regex.test(phone);
          },
        },
        gender : { required },
        address : { required },
        complete_vaccination : { required },
        comorbidity: {required},
        cause_of_death: causeOfDeathVdn,
        other_death_cause: otherDeathCauseVdn,
        pregnant_status: pregnantStatusVdn,
        date_of_positive_np: { required },
        other_comorbidity: otherComorbidityVdn,
        date_of_outcome_np: dateofOutcomeVdn,
        time_of_death: timeOfDeathVdn,
        province_id: { required },
        district_id: { required } ,
        municipality_id: {required},
        ward: {required},
        vaccine_type: vacccineTypeVdn,
        other_vaccine_type: otherVaccineTypeVdn,
        guardian_name: {required},
        method_of_diagnosis : { required },
      },
    }
    return validationRules;
  },
  methods: {
    deleteObjById(arrObj, searchId) {
      let filterIndex;
       var result = arrObj.filter((obj, index) => {
         if(obj.id===searchId) filterIndex = index;
        });
        arrObj.splice(filterIndex,1);
        return arrObj;
    },
    getFederalDropdown() {
      let self = this;
      axios.get('/api/province')
        .then((response) => {
          self.allProvinceList = response.data;
      });
      axios.get('/api/district')
        .then((response) => {
          self.allDistrictList = response.data;
          self.filteredDistrictList = response.data;
      });
      axios.get('/api/municipality')
        .then((response) => {
          self.allMunicipalityList = response.data;
          self.filteredMunicipalityList = response.data;
      });
    },
    filterMunicipalityByProvince(selectedProvinceId) {
      let filteredMunicipalityList = this.allMunicipalityList.filter(municipality => (municipality.province_id == selectedProvinceId));
      this.filteredMunicipalityList = filteredMunicipalityList;
      this.data.municipality_id = null;
    },
    filterMunicipalityByDistrict(selectedDistrictId) {
      let selectedDistrict = this.allDistrictList.filter(district => (district.id == selectedDistrictId))[0];
      let filteredMunicipalityList = this.allMunicipalityList.filter(municipality => (municipality.district_id == selectedDistrictId));
      this.filteredMunicipalityList = filteredMunicipalityList;
      this.data.province_id = selectedDistrict.province_id;
      this.data.municipality_id = null;
    },
    filterDistrictByProvince(provinceId) {
      this.filterMunicipalityByProvince(provinceId);
      let filteredDistrictList = this.allDistrictList.filter(district=>district.province_id==provinceId);
      this.filteredDistrictList = filteredDistrictList;
    },
    onProvinceChange(provinceId) {
      if(provinceId) {
        this.filterDistrictByProvince(provinceId);
      }
    },
    onDistrictChange(selectedDistrictId) {
      if(selectedDistrictId) {
        this.filterMunicipalityByDistrict(selectedDistrictId);
      }
    },
    onMunicipalityChange(municipalityId) {
      if(municipalityId){
        let selectedMunicipality = this.allMunicipalityList.filter(municipality => (municipality.id == municipalityId))[0];
        this.data.province_id = selectedMunicipality.province_id;
        this.data.district_id = selectedMunicipality.district_id;
      }
    },
    getTodayDate() {
      axios.get('/api/v1/server-date')
        .then((response) => {
          let date = response.data.date;
          this.date_today_en = date;
          this.date_today_np = this.ad2bs(date);
          this.renderDatepickerNp();
      });
    },
  
    handleFileUpload(){
      this.bulk_file = this.$refs.bulk_file.files[0];
      this.submitFile();
    },
    submitFile(){
      let formData = new FormData();
      if(!this.bulk_file){
        alert('Please upload a valid excel file');
        return;
      }
      formData.append('bulk_file', this.bulk_file);
      formData.append('bed_status', JSON.stringify(this.bed_status));
      axios.post( '/api/v1/bulk-case-payment',
        formData,
        {
          headers: {
              'Content-Type': 'multipart/form-data'
          }
        }
      ).then(function(res){
        alert(res.data.message);
        $("#file").val(null);
      })
      .catch(function(err){
        let errorMsg;
        if(err.response.status===500) {
          errorMsg = 'Please use the latest valid template downloaded from the system. If problem persists, please contact support.';
        } else {
          errorMsg = 'The Case Payment could not be uploaded due to the following problems: \n';
        
          err.response.data.message.map((problem, index)=>{
            let mainError = '';
            if(problem.error instanceof Object){
              mainError = Object.values(problem.error).join(',');
            } else if(problem.error instanceof Array) {
              mainError = problem.error.join();
            }
            errorMsg += (index+1)+'. Row: '+problem.row+', Column: '+problem.column+', Error: '+mainError+'\n';
          });
        }
        alert(errorMsg);
        $("#file").val(null);
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
              });
              this.data.name = response.data.data.name;
              this.data.age = response.data.data.age;
              this.data.gender = response.data.data.sex;
              this.data.address =  response.data.data.tole + '-' + response.data.data.ward + ',' + response.data.data.municipality_name;
              this.data.phone = response.data.data.emergency_contact_one;
              this.data.date_of_positive_en = response.data.data.date_of_positive_en;
              this.data.date_of_positive_np = this.ad2bs(response.data.data.date_of_positive_en);
              this.data.method_of_diagnosis = response.data.data.service_for||null;
              this.data.province_id = response.data.data.province_id;
              this.data.district_id = response.data.data.district_id;
              this.data.municipality_id = response.data.data.municipality_id;
              this.data.tole = response.data.data.tole;
              this.data.ward = response.data.data.ward;
              if (this.item){
                this.$dlg.closeAll(function(){
                  // do something after all dialog closed
                })
              }
            } else {
              this.$swal({
                title: 'Oops. No record found. \n Even than you can continue to fill data.',
                type: 'warning',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
            }
          })
    },
    resetForm() {
      let self = this;
      Object.keys(this.data).forEach(function(key,index) {
        if(key==='register_date_np'|| key==='register_date_en') return;
        self.data[key] = '';
      });
    },
    getCurrentTime() {
      var date = new Date();
      var hours = date.getHours();
      var minutes = date.getMinutes();
      var ampm = hours >= 12 ? 'pm' : 'am';
      hours = hours % 12;
      hours = hours ? hours : 12; // the hour '0' should be '12'
      hours = hours < 10 ? '0'+hours : hours;
      minutes = minutes < 10 ? '0'+minutes : minutes;
      var strTime = hours + ':' + minutes + ' '+ ampm;
      return strTime;
    },
    submitData(data){
      this.$v.$touch();
      this.isSubmitting = true;
      if (this.$v.$invalid) {
        this.isSubmitting = false;
        return false;
      }
        console.log('dd');
        data.date_of_outcome_en = this.bs2ad(data.date_of_outcome_np);
      if(data.date_of_positive_np) {  
        data.date_of_positive_en = this.bs2ad(data.date_of_positive_np);
      }
      if(data.time_of_death && typeof data.time_of_death === 'object'){
        data.time_of_death =   data.time_of_death["hh"]+':'+data.time_of_death["mm"]+' '+data.time_of_death["a"];
      }
      axios.post('/api/v1/community-deaths', data)
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
              if (this.is_to_update === false) {
                this.$v.$reset();
                this.resetForm();
                this.data = {};
                this.data = {
                  age_unit : 0,
                  method_of_diagnosis : 0,
                  gender: undefined,
                  complete_vaccination: undefined,
                  time_of_death: null,
                  comorbidity: [],
                  other_comorbidity: null,
                  pregnant_status: null,
                  date_of_positive_en: '',
                  date_of_positive_np: '',
                  cause_of_death: null,
                  other_death_cause: null,
                  province_id: null,
                  district_id: null,
                  municipality_id: null,
                  ward: null,
                  vaccine_type: null,
                  other_vaccine_type: null,
                  guardian_name: null,
                  date_of_outcome_np: ''
                }
              }
              this.isSubmitting = false;
              if (this.is_to_update) {
                if (!this.isHealthConditionAddHidden) {
                  if (this.health_condition_details_health_condition !== '' &&
                      this.health_condition_details_start_date !== ''
                  ) {
                    this.health_condition_details_health_condition = ''
                    this.health_condition_details_start_date = ''
                  }
                }
              }
              this.isSubmitting = false;
              return false;
            }
            else {
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
      try{
        var dateObject = new Date(date);

        var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

        let dateConverter = DataConverter.ad2bs(dateFormat);

        return dateConverter.en.year + '-' + dateConverter.en.month + '-' + dateConverter.en.day;
      }catch (e){
        return date;
      }

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
    },
    toggleHealthy(data){
    if (data.comorbidity.includes('21')) {
          data.comorbidity = []
          data.comorbidity = ['21']
          data.other_comorbidity = null
      }
    },
    toggleComorbitidy(data){
      if (data.comorbidity.includes(21)) {
          (data.comorbidity).splice((data.comorbidity).indexOf(21), 1)
      }
      if (data.comorbidity.includes('21')) {
          (data.comorbidity).splice((data.comorbidity).indexOf("21"), 1)
      }
    },
    renderDatepickerNp(){
      let self = this;
      $('.date-picker-date_of_positive_np').nepaliDatePicker({
        language: 'english',
        disableAfter: this.date_today_np,
        onChange: function() {
            self.data.date_of_positive_np = $('#date_of_positive_np').val()
        }
      });
      $('.date-picker-date_of_outcome').nepaliDatePicker({
        language: 'english',
        disableAfter: this.date_today_np,
        onChange: function() {
            self.data.date_of_outcome_np = $('#date_of_outcome_np').val()
        }
      });
      $('.date-picker-register_date_np').nepaliDatePicker({
        language: 'english',
        onChange: function() {
            self.data.register_date_np = $('#register_date_np').val()
        }
      });
    },
    checkEditPermission(){
      //TODO fix by setting from controller
      return this.$userSessionToken === '5a4425' || this.$permissionId === '1';
      
    }
  },
  created(){
    let url = new URL(window.location.href);
    let id = url.searchParams.get("token");
    if(id){
      axios.get('/api/v1/search-community-deaths-by-id?id='+id)
          .then((response) => {
            if(Object.keys(response.data).length > 0) {
              this.data.id  = response.data.id;
              this.data.name = response.data.name;
              this.data.age = response.data.age;
              this.data.age_unit = response.data.age_unit;
              this.data.phone = response.data.phone;
              this.data.method_of_diagnosis = response.data.method_of_diagnosis;
              this.data.date_of_positive_en = response.data.date_of_positive_en?(new Date(response.data.date_of_positive_en)).toLocaleString().split(',')[0].split("/").reverse().join("-"):null;
              this.data.date_of_positive_np = response.data.date_of_positive_np;
              this.data.cause_of_death = response.data.cause_of_death;
              this.data.other_death_cause = response.data.other_death_cause;
              this.data.comorbidity = response.data.comorbidity?JSON.parse(response.data.comorbidity):[];
              this.data.other_comorbidity = response.data.other_comorbidity;
              this.data.time_of_death = response.data.time_of_death,
              this.data.pregnant_status = response.data.pregnant_status;
              this.data.gender = response.data.gender;
              this.data.date_of_outcome_np = response.data.date_of_outcome_np;
              this.data.guardian_name = response.data.guardian_name;
              this.data.address = response.data.address;
              this.data.remarks = response.data.remarks;
              this.is_to_update = true;
              this.update_id = response.data.id;
              this.data.complete_vaccination = response.data.complete_vaccination;
              var today = new Date();
              this.data.province_id = response.data.province_id || this.$federalInfo.province_id;
              this.data.district_id = response.data.district_id || this.$federalInfo.district_id;
              this.data.municipality_id = response.data.municipality_id || this.$federalInfo.municipality_id;
              this.data.ward = response.data.ward;
              this.data.vaccine_type = response.data.vaccine_type;
              this.data.other_vaccine_type = response.data.other_vaccine_type;
            }
          })
          .catch((error) => {
            console.error(error)
          })
          .finally(() => {
          })
    }
  }
}
</script>
