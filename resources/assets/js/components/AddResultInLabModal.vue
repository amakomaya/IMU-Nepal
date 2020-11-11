<template>
  <div>
    <div class="form-group" :class="{ 'has-error': $v.data.token.$error }">
      <label class="control-label">Enter Registered Lab ID ( Unique )</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-key"></i></span>
          <input id="token" name="" placeholder="Enter Registered Lab ID ( Unique )"
                 class="form-control" required="true" v-model.trim="data.token"
                 type="text">
        </div>
      </div>
      <div class="help-block" v-if="!$v.data.token.required">Field is required.</div>
      <div class="help-block" v-if="!$v.data.token.minLength">Field must have valid numbers length.</div>
    </div>
    <div class="form-group" :class="{ 'has-error': $v.data.sample_test_date.$error }">
    <label class="control-label">Sample Test Date</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-calendar"></i></span>
          <v-nepalidatepicker classValue="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD" format="YYYY-MM-DD" v-model="data.sample_test_date" :yearSelect="false" :monthSelect="false" />
        </div>
      </div>
      <div class="help-block" v-if="!$v.data.sample_test_date.required">Field is required.</div>
    </div>
    <div class="form-group" :class="{ 'has-error': $v.data.sample_test_time.$error }">
    <label class="control-label">Sample Test Time</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-times"></i></span>
          <input id="sample_test_time" name="" placeholder="Sample Test Time"
                 class="form-control" required="true" v-model="data.sample_test_time"
                 type="text">
        </div>
      </div>
      <div class="help-block" v-if="!$v.data.sample_test_time.required">Field is required.</div>
    </div>
    <div class="form-group" :class="{ 'has-error': $v.data.sample_test_result.$error }">
        <label>Sample Test Result <span class="text-danger" v-if="!$v.data.sample_test_result.required"> ( * Field is required. )</span></label><br>
        <input v-model="data.sample_test_result" value="4" type="radio">Negative
        <input v-model="data.sample_test_result" value="3" type="radio" >Positive
        <input v-model="data.sample_test_result" value="5" type="radio" checked>Don't Know
      </div>
      <button class="btn btn-primary btn-sm btn-block"
              @click.prevent="submitLabIdToSampleId(data)">
        Submit
      </button>
    </div>
</template>

<script type="text/javascript">

import { minLength, required} from "vuelidate/lib/validators";
import DataConverter from "ad-bs-converter";
import axios from "axios";

export default {
  props: {
    item: Object,
  },
  data() {
    return {
      data : {},
    }
  },
  validations: {
    data: {
      token: {
        required,
        minLength: minLength(1)
      },
      sample_test_date:{
        required
      },
      sample_test_time:{
        required
      },
      sample_test_result:{
        required
      },
    }
  },
  methods: {
    submitLabIdToSampleId(data){
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      axios.post('/api/v1/result-in-lab-from-web', data)
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
    ad2bs: function (date) {
      var dateObject = new Date(date);

      var dateFormat = dateObject.getFullYear()  + "/" + (dateObject.getMonth()+1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return dateConverter.en.year + '-' + dateConverter.en.month + '-' + dateConverter.en.day;

    },
  },
  created(){
    if(this.item){
      this.data.token = this.item.latest_anc.labreport.token.split('-').splice(1).join('-');
    }
    var today = new Date();
    this.data.sample_test_date = this.ad2bs(today);
    this.data.sample_test_time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
  }
}
</script>