<template>
  <div>
    <div class="form-group" :class="{ 'has-error': $v.data.id.$error }">
      <label class="control-label">Enter Registered ID</label>
      <div class="inputGroupContainer">
        <div>
          <div class="input-group">
            <span class="input-group-addon"><i
                class="fa fa-key"></i></span>
            <input id="token" name="" placeholder="Enter Registered ID"
                   class="form-control" required="true" v-model.trim="data.id"
                   type="text">
          </div>
          <button class="btn btn-primary pull-right" style="margin-top: 2px" @click="checkSID">Check</button>
        </div>
      </div>
      <div class="help-block" v-if="!$v.data.id.required">Field is required.</div>
      <!--      <div class="help-block" v-if="!$v.data.id.maxLength">Field must have valid numbers length.</div>-->
    </div>
    <label style="margin-top:5px;" class="control-label btn-primary" id="message">{{ message }}</label>
    <p style="margin-bottom:-10px; margin-top: -10px" class="panel-body" v-show="isShow">
      Name : {{ name }}<br>
      Age : {{ age }}
    </p>
    <br>
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
    <div class="form-group">
      <label class="control-label">Vaccinated Address</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-hospital-o"></i></span>
          <input class="form-control" placeholder="Enter Vaccinated Address"
                 v-model="data.vaccinated_address"/>
        </div>
      </div>
    </div>
    <button class="btn btn-primary btn-sm btn-block"
            @click.prevent="submitVaccinationData(data)">
      Submit
    </button>
  </div>
</template>

<script type="text/javascript">

import {maxLength, required} from "vuelidate/lib/validators";
import DataConverter from "ad-bs-converter";
import axios from "axios";

export default {
  props: {
    item: Object,
  },
  data() {
    return {
      data: {},
      isShow: false,
      name: '',
      age: '',
      message: '',
    }
  },
  validations: {
    data: {
      id: {
        required
        // maxLength: maxLength(6)
      },
      vaccination_date: {
        required
      },
    }
  },
  methods: {
    submitVaccinationData(data) {
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      const payload = {
        'vaccinated_id': data.id,
        'vaccinated_date_np': this.data.vaccination_date,
        'vaccinated_date_en': this.bs2ad(this.data.vaccination_date),
        'vaccinated_address': this.data.vaccinated_address
      }
      axios.post('/api/v1/vaccination-data', payload)
          .then((response) => {
            if (response.data === 'success') {
              this.$swal({
                position: 'top-end',
                icon: 'success',
                title: 'Your work has been saved',
                showConfirmButton: false,
                timer: 1500
              });
              this.$v.$reset();
              this.data.id = '';
              this.data.vaccinated_address = '';
              // this.data = {};
              // var today = new Date();
              // this.data.vaccination_date = this.ad2bs(today);
              if (this.item) {
                this.$dlg.closeAll(function () {

                })

              }
            } else {
              this.$dlg.toast('Oops. Something went wrong.', {
                messageType: 'error',
                closeTime: 3, // auto close dialog time(second)
                language: 'en',
                position: 'topRight'
              })
            }
          })
    },
    bs2ad: function (date) {
      var dateObject = date.split("-");

      var dateFormat = dateObject[0] + "/" + dateObject[1] + "/" + dateObject[2];

      let dateConverter = DataConverter.bs2ad(dateFormat);

      return dateConverter.year + '-' + dateConverter.month + '-' + dateConverter.day;

    },
    ad2bs: function (date) {
      if (date === undefined) {
        return '';
      }
      var dateObject = new Date(date);

      var dateFormat = dateObject.getFullYear() + "/" + (dateObject.getMonth() + 1) + "/" + dateObject.getDate();

      let dateConverter = DataConverter.ad2bs(dateFormat);

      return dateConverter.en.year + '-' + dateConverter.en.month + '-' + dateConverter.en.day;

    },
    checkSID: function () {
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      axios.get('/api/vaccinated/qr-search?token=' + this.data.id)
          .then((response) => {
            if (response.data.message === 'Data Not Found') {
              this.isShow = false;
              this.message = response.data.message;
            } else {
              this.isShow = true;
              this.message = 'Data Found Successfully';
              this.name = response.data.name;
              this.age = response.data.age;
            }
          });
    },

  },
  created() {
    if (this.item) {
      this.data.id = this.item.id;
    }
    var today = new Date();
    this.data.vaccination_date = this.ad2bs(today);
  }
}
</script>