<template>
  <div id="lab-test-modal">
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
            <input readonly id="sample_test_np" type="text" placeholder="YYYY-MM-DD" v-model.trim="data.sample_test_date" value="" name="sample_test_np" class="form-control date-picker-sample_test_np" />
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
        <input v-model="data.sample_test_result" value="5" type="radio" >Don't Know
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
    onSuccessCallback: Function
  },
  data() {
    return {
      data : {
        sample_test_result: null,
        sample_test_date: null,
        sample_test_time: null,
        token: null
      },
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
      
      if(this.item) data.sample_token = this.item.latest_anc.token;
      axios.post('/api/v1/result-in-lab-from-web', data)
          .then((response) => {
            if (response.data === 'success') {
              if(this.item) {
                this.onSuccessCallback(this.item);
              }
              this.$swal({
                title: 'Test successfully added in lab',
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
      console.log(dateObject);
      var dateFormat = dateObject.getFullYear()  + "/"  + (dateObject.getMonth()+1) + "/" + dateObject.getDate();
      let dateConverter = DataConverter.ad2bs(dateFormat);
      return dateConverter.en.year+'-'+dateConverter.en.month+'-'+dateConverter.en.day;

    },
    getTodayDate() {
      axios.get('/api/v1/server-date')
        .then((response) => {
          let date = response.data.date;
          this.date_today_en = date;
          this.date_today_np = this.ad2bs(date);
          var today = new Date();
          
          this.data.sample_test_date = this.ad2bs(today);
          this.data.sample_test_time = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
          this.renderDatepickerNp();
      });
    },
    renderDatepickerNp(){
      let self = this;
      let testDateNp = this.data.sample_test_date.split('-');
      if(testDateNp[1] && testDateNp[1].length===1) {
        testDateNp[1] = '0'+testDateNp[1];
      }
      if(testDateNp[2] && testDateNp[2].length===1) {
        testDateNp[2] = '0'+testDateNp[2];
      }
      $('.date-picker-sample_test_np').nepaliDatePicker({
        language: 'english',
        container: ".v-dialog-modal",
        disableAfter: this.date_today_np,
        onChange: function() {
          self.data.sample_test_date = $('#sample_test_np').val()
        }
      });
    },
  },
  created(){
    if(this.item){
      this.data.token = this.item.latest_anc.lab_token.split('-').splice(1).join('-');
    }
  },
  mounted () {
    this.getTodayDate();
  },
}
</script>