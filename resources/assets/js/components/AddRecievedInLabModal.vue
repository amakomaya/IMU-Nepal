<template>
  <div>
    <div class="form-group" :class="{ 'has-error': $v.sample_lot_id.$error }">
      <label class="control-label">Enter Received Swab Lot ID ( First ten lot numbers )</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-key"></i></span>
          <input class="form-control" placeholder="#### - ######" id="sample_lot_id" type="text" v-mask="'####-######'"
                 v-model="sample_lot_id">
        </div>
        <div class="help-block" v-if="!$v.sample_lot_id.required">Field is required.</div>
        <div class="help-block" v-if="!$v.sample_lot_id.minLength">Field must have valid numbers length.</div>
      </div>
    </div>
    <div class="form-group" :class="{ 'has-error': $v.data.unique_id.$error }">
      <label class="control-label">Enter Unique Received Swab ID ( Only unique last five digits )</label>
      <div class="inputGroupContainer">
        <div class="input-group pull-left col-md-9"><span class="input-group-addon"><i class="fa fa-key"></i></span>
          <input class="form-control" placeholder="#####" id="unique_id" type="text" v-mask="'#####'"
                 v-model="data.unique_id">
        </div>
        <button class="btn btn-primary pull-right col-md-2" @click="checkSID">Check</button>
      </div>
      <div class="help-block" v-if="!$v.data.unique_id.required">Field is required.</div>
      <div class="help-block" v-if="!$v.data.unique_id.minLength">Field must have valid numbers length.</div>
    </div>
    <label style="margin-top:5px;" class="control-label btn-primary" id="message">{{ message }}</label>
    <p style="margin-bottom:-10px; margin-top: -10px" v-show="isShow" class="panel-body">
        Name : {{ name }}<br>
        Age : {{ age }}, Gender : {{ gender }}
    </p>
    <div class="form-group" :class="{ 'has-error': $v.data.token.$error }">
      <label class="control-label">Enter Registered Lab ID ( Unique )</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-key"></i></span>
          <input id="full_name" name="" placeholder="Enter Registered Lab ID ( Unique )"
                 class="form-control" required="true" v-model="data.token"
                 type="text">
        </div>
      </div>
      <div class="help-block" v-if="!$v.data.token.required">Field is required.</div>
      <br>
      <button class="btn btn-primary btn-sm btn-block"
              @click.prevent="submitLabIdToSampleId()">
        Submit
      </button>

    </div>
  </div>
</template>

<script type="text/javascript">

import axios from "axios";
import {required, minLength} from 'vuelidate/lib/validators'

export default {
  props:{
    item : String
  },
  data() {
    return {
      sample_lot_id: String,
      isShow: false,
      name: '',
      age: '',
      gender: '',
      message: '',
      data: {}
    }
  },
  validations: {
    sample_lot_id: {
      required,
      minLength: minLength(11)
    },
    data: {
      unique_id: {
        required,
        minLength: minLength(3)
      },
      token: {
        required
      }
    }
  },
  methods: {
    submitLabIdToSampleId() {
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      this.data.sample_token = this.sample_lot_id + '-' + this.data.unique_id
      const payload = {
        'sample_token': this.data.sample_token,
        'token': this.data.token
      }
      axios.post('/api/v1/received-in-lab', payload)
          .then((response) => {
            if (response.data === 'success') {
              this.$swal({
                title: 'Record received in lab',
                type: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
              this.$v.$reset()
              this.data = {};
            } else {
              this.$swal({
                title: 'Oops. Something went wrong. \n Already received Swab ID : ' + this.data.sample_token + ' \n\t or Lab Unique ID : ' + this.data.token,
                type: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
            }
          })
    },
    checkSID: function () {
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      this.data.sample_token = this.sample_lot_id + '-' + this.data.unique_id
      axios.get('/api/v1/check-sid?token=' + this.data.sample_token)
          .then((response) => {
            if (response.data.message === 'Data Found Successfully') {
              this.isShow = true;
              this.message = response.data.message;
              this.name = response.data.name;
              this.age = response.data.age;
              this.gender = response.data.gender;
            } else if (response.data.message === 'Data Not Found') {
              this.isShow = false;
              this.message = response.data.message;
            }
          });
    },
  },
  created(){
    if(this.item){
      this.sample_lot_id = this.item;
      this.data.unique_id = this.item.split(/[-]+/).pop();
    }
  }

}
</script>