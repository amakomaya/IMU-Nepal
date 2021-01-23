<template>
  <div style="margin-left: 5px" class="row">
    <div class="col-md-12">
      <GChart
          :settings="{ packages: ['table'] }"
          type="Table"
          :data="chartTableData"
          :options="chartTableOptions"
          ref="gChart"
      />
    </div>
  </div>
</template>

<script>
import { GChart } from "vue-google-charts";
import axios from "axios";

export default {
  components: {
    GChart
  },
  data() {
    return {
      chartTableData: [],
      chartTableOptions: {},
    }
  },
  created() {
    let url = window.location.protocol + '/data/api/antigen';
    axios.get(url)
        .then((response) => {
          this.chartTableData = response.data;
        })
        .catch((error) => {
          console.error(error)
        })
        .finally(() => {
        })
  },
}
</script>

<style scoped>

</style>