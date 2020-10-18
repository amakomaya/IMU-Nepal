<template>
  <div>
    <div class="form-group">
        <label class="control-label">Enter Registered Lab ID ( Unique )</label>
        <div class="inputGroupContainer">
          <div class="input-group"><span class="input-group-addon"><i
              class="fa fa-key"></i></span>
            <input id="token" name="" placeholder="Enter Registered Lab ID ( Unique )"
                   class="form-control" required="true" v-model="data.token"
                   type="text">
          </div>
        </div>
      <label class="control-label">Sample Test Date</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-calendar"></i></span>
          <input id="sample_test_date" name="" placeholder="Sample Test Date"
                 class="form-control" required="true" v-model="data.sample_test_date"
                 type="text">
        </div>
      </div>
      <label class="control-label">Sample Test Time</label>
      <div class="inputGroupContainer">
        <div class="input-group"><span class="input-group-addon"><i
            class="fa fa-times"></i></span>
          <input id="sample_test_time" name="" placeholder="Sample Test Time"
                 class="form-control" required="true" v-model="data.sample_test_time"
                 type="text">
        </div>
      </div>

      <div class="form-group">
        <label>Sample Test Result</label><br>
        <input v-model="data.sample_test_result" value="1" type="radio">Negative
        <input v-model="data.sample_test_result" value="2" type="radio" >Positive
        <input v-model="data.sample_test_result" value="3" type="radio" checked>Don't Know
        <br>

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

export default {
  data() {
    return {
      data : [
      ],
    }
  },
  methods: {
    submitLabIdToSampleId(data){
      console.log(data);
    }
  }

}
</script>