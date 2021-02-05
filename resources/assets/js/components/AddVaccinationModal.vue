<template>
  <div>
    <div class="form-group" :class="{ 'has-error': $v.data.id.$error }">
      <label class="control-label">Enter Registered ID</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-key"></i></span>
          <input id="token" name="" placeholder="Enter Registered ID"
                 class="form-control" required="true" v-model.trim="data.id"
                 type="text">
        </div>
      </div>
      <div class="help-block" v-if="!$v.data.id.required">Field is required.</div>
      <!--      <div class="help-block" v-if="!$v.data.id.minLength">Field must have valid numbers length.</div>-->
    </div>
    <div class="form-group" :class="{ 'has-error': $v.data.vaccination_date.$error }">
      <label class="control-label">Vaccination Date</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-calendar"></i></span>
          <v-nepalidatepicker classValue="form-control" calenderType="Nepali" placeholder="YYYY-MM-DD"
                              format="YYYY-MM-DD" v-model="data.vaccination_date" :yearSelect="false"
                              :monthSelect="false"/>
        </div>
      </div>
      <div class="help-block" v-if="!$v.data.vaccination_date.required">Field is required.</div>
    </div>
    <button class="btn btn-primary btn-sm btn-block"
            @click.prevent="submitVaccinationData(data)">
      Submit
    </button>
  </div>
</template>

<script type="text/javascript">

import {minLength, required} from "vuelidate/lib/validators";
import DataConverter from "ad-bs-converter";
import axios from "axios";

export default {
  props: {
    item: Object,
  },
  data() {
    return {
      data: {},
    }
  },
  validations: {
    data: {
      id: {
        required,
        // maxLength: maxLength(6)
      },
      vaccination_date: {
        required
      },
    }
  },
  methods: {
    submitVaccinationData(data) {
      console.log(data)
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      axios.post('/api/v1/vaccination-data', data)
          .then((response) => {
            if (response.data === 'success') {
              this.$swal({
                title: 'Vaccination Added',
                type: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
              this.$v.$reset();
              this.data = {};
              if (this.item) {
                this.$dlg.closeAll(function () {
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

      var dateFormat = dateObject.getFullYear() + "/" + (dateObject.getMonth() + 1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return dateConverter.en.year + '-' + dateConverter.en.month + '-' + dateConverter.en.day;

    },
  },
  created() {
    // if(this.item){
    //   this.data.token = this.item.latest_anc.labreport.token.split('-').splice(1).join('-');
    // }
    var today = new Date();
    this.data.vaccination_date = this.ad2bs(today);
  }
}
</script>