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
    <div class="col-md-12">
      <GChart
          :settings="{ packages: ['corechart'] }"
          type="LineChart"
          :data="lineChartData"
          :options="lineChartOptions"
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
      // Array will be automatically processed with visualization.arrayToDataTable function
      chartTableData: [],
      chartTableOptions: {},
      lineChartData: [],
      lineChartOptions: {},
    }
  },
  created() {
    let url = window.location.protocol + '/data/api/time-series';
    axios.get(url)
        .then((response) => {
          this.chartTableData = response.data.data;
          this.lineChartData = response.data.forecast.registered
          console.log(this.lineChartData)
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