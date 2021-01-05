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
      <p class="text-center">Fig : Positive Case by Occupation</p>
    </div>
    <div class="col-md-4">
      <GChart
          :settings="{ packages: ['corechart'] }"
          type="PieChart"
          :data="PieNegativeData"
          :options="chartPieNegativeOptions"
          ref="gChart"
      />
      <p class="text-center">Fig : Negative Case by Occupation</p>
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
        chartArea: {width: 500, height: 400}

      },
      chartPieNegativeOptions: {
          is3D: true,
        chartArea: {width: 500, height: 400}
      }
    }
  },
  created() {
    let url = window.location.protocol + '/data/api/occupation';
    axios.get(url)
        .then((response) => {
          this.chartTableData = response.data;
          let PiePositiveData = [['Occupation', 'Total']]
          let PieNegativeData = [['Occupation', 'Total']]
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