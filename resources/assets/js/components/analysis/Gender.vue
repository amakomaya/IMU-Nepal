<template>
  <div style="margin-left: 5px" class="row">
    <div class="col-md-4">
      <GChart
          :settings="{ packages: ['table'] }"
          type="Table"
          :data="chartTableData"
          :options="chartTableOptions"
          ref="gChart"
      />
    </div>
    <div class="col-md-4">
      <GChart
          :settings="{ packages: ['corechart'] }"
          type="PieChart"
          :data="PiePositiveData"
          :options="chartPiePositiveOptions"
          ref="gChart"
      />
      <p class="center-block">Fig : Positive Case by Gender</p>
    </div>
    <div class="col-md-4">
      <GChart
          :settings="{ packages: ['corechart'] }"
          type="PieChart"
          :data="PieNegativeData"
          :options="chartPieNegativeOptions"
          ref="gChart"
      />
      <p class="center-block">Fig : Negative Case by Gender</p>
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
      PiePositiveData: [],
      PieNegativeData: [],
      chartTableOptions: {},
      chartPiePositiveOptions: {
        is3D: true,
        chartArea: {width: 600, height: 400}
      },
      chartPieNegativeOptions: {
          is3D: true,
        chartArea: {width: 600, height: 400}

      }
    }
  },
  created() {
    let url = window.location.protocol + '/data/api/gender';
    axios.get(url)
        .then((response) => {
          this.chartTableData = response.data;
          let PiePositiveData = [['Gender', 'Total']]
          let PieNegativeData = [['Gender', 'Total']]
          this.chartTableData.filter((item) => {
            if (item[1] === 'Positive'){
              return PiePositiveData.push([item[0], item[2]])
            }
          })
          this.PiePositiveData = PiePositiveData;
          this.chartTableData.filter((item) => {
            if (item[1] === 'Negative'){
              return PieNegativeData.push([item[0], item[2]])
            }
          })
          this.PieNegativeData = PieNegativeData;

          console.log(this.PieNegativeData)
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