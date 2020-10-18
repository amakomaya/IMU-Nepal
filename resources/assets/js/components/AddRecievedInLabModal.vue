<template>
  <div>
      <div class="form-group">
        <label class="control-label">Enter Received Swab</label>
        <div class="inputGroupContainer">
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-key"></i></span>
            <input id="sample_id" name="" placeholder="Enter Received Swab"
                   class="form-control" required="true" v-model="data.sample_token"
                   type="text">
          </div>
        </div>
      </div>
      <div class="form-group">
        <label class="control-label">Enter Registered Lab ID ( Unique )</label>
        <div class="inputGroupContainer">
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-key"></i></span>
            <input id="full_name" name="" placeholder="Enter Registered Lab ID ( Unique )"
                   class="form-control" required="true" v-model="data.token"
                   type="text">
        </div>
      </div>
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

export default {
  data() {
    return {
      data : {}
    }
  },
  methods: {
    submitLabIdToSampleId(data){
      axios.post('/api/v1/received-in-lab', data)
          .then((response) => {
            if (response.status === 200) {
              this.$swal({
                title: 'Record recieved in lab',
                type: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
              })
            } else {
              this.$swal({
                title: 'Oops. Something went wrong. Please try again later.',
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