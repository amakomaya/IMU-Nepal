<template>
  <div>
      <div class="form-group" :class="{ 'has-error': $v.data.sample_token.$error }">
        <label class="control-label">Enter Received Swab</label>
        <div class="inputGroupContainer">
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-key"></i></span>
<!--            <input id="sample_id" name="" placeholder="Enter Received Swab"-->
<!--                   class="form-control" required="true" v-model="data.sample_token"-->
<!--                   type="text">-->
            <input class="form-control" placeholder="#### - ###### - #####" id="sample_id" type="text" v-mask="'####-######-#####'" v-model="data.sample_token">
          </div>
          <div class="help-block" v-if="!$v.data.sample_token.required">Field is required.</div>
          <div class="help-block" v-if="!$v.data.sample_token.minLength">Field must have valid numbers length.</div>
        </div>
      </div>
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
                @click.prevent="submitLabIdToSampleId(data)">
          Submit
        </button>

    </div>
  </div>
</template>

<script type="text/javascript">

import axios from "axios";
import { required, minLength } from 'vuelidate/lib/validators'

export default {
  data() {
    return {
      data : {}
    }
  },
  validations: {
    data: {
      sample_token: {
        required,
        minLength: minLength(13)
      },
      token:{
        required
      }
    }
  },
  methods: {
    submitLabIdToSampleId(data){
      this.$v.$touch()
      if (this.$v.$invalid) {
        return false;
      }
      axios.post('/api/v1/received-in-lab', data)
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
              this.data = {};
            } else {
              this.$swal({
                title: 'Oops. Something went wrong. \n Already received Swab ID : ' + data.sample_token +' \n\t or Lab Unique ID : '+ data.token,
                type: 'error',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
            }
          })
    }
  }

}
</script>