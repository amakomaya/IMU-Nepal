<template>
  <div class="container row">
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
    <hr>
    <div class="row">
      <div class="form-group col-lg-4">
        <label for="health_condition">Health Condition</label>
        <select class="form-control" v-model.trim="data.health_condition" id="health_condition">
          <option value="0" selected hidden>Please Select Medical Condition</option>
          <option value="1">No Symptoms</option>
          <option value="2">Mild</option>
          <option value="3">Moderate</option>
          <option value="4">Sever</option>
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
        <label>Death Status</label><br>
        <input type="radio" id="yes" v-model.trim="data.is_death" value="">
        <label for="no">No</label> &nbsp; &nbsp;
        <input type="radio" id="no" v-model.trim="data.is_death" value="yes">
        <label for="yes">Yes</label> &nbsp; &nbsp;
      </div>
      <div v-show="data.is_death !== ''" class="form-group col-lg-4">
        <label for="date_of_death">Date of death</label>
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-calendar"></i></span>
            <v-nepalidatepicker id="date_of_death" classValue="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD"
                                format="YYYY-MM-DD" v-model.trim="data.is_death" :yearSelect="false"
                                :monthSelect="false"/>
        </div>
      </div>
    </div>
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

export default {
  data() {
    return {
      data : {
        health_condition : 0,
        is_death : ''
      },
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
      guardian_name : { required }
    }
  },
  methods: {
    submitData(data){
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      axios.post('/api/v1/cases-payment', data)
          .then((response) => {
            console.log(response.data);
            if (response.data === 'success') {
              this.$swal({
                title: 'Record received in lab',
                type: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
              this.$v.$reset();
              this.data = {};
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
  }
}
</script>
