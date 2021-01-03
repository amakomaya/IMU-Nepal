<template>
  <div v-if="data.length > 0" style="height: 80vh; margin-left: 5px">
    <LMap  style="height: 100%; overflow: auto;" :zoom="zoom" :center="center">
      <LTileLayer :url="url"></LTileLayer>
      <Vue2LeafletHeatmap :lat-lng="data" :radius="20" :min-opacity=".75" :max-zoom="5" :blur="30" :gradient="1"></Vue2LeafletHeatmap>
    </LMap>

    <h3>Logics: </h3>
    <ol>
      <li>Get All the Geo Location data of positive cases</li>
      <li>Intensity = positive cases / Maximum positive cases</li>
    </ol>
    <strong>Note : Higher Intensity, Higher Positive cases</strong>
  </div>
</template>

<script>
import { LMap, LTileLayer, LMarker } from "vue2-leaflet";
import Vue2LeafletHeatmap from "./Vue2LeafletHeatmap";

import axios from "axios";
export default {
  name: "Map",
  components: {
    Vue2LeafletHeatmap,
    LMap,
    LTileLayer,
    LMarker
  },
  data() {
    return {
      url: "https://{s}.tile.osm.org/{z}/{x}/{y}.png",
      zoom: 7,
      center: [28.2096,83.9856],
      bounds: null,
      data : []
    };
  },
  created() {
    let url = window.location.protocol + '/admin/maps/data';
    axios.get(url)
        .then((response) => {
          this.data = response.data;
        })
        .catch((error) => {
          console.error(error)
        })
        .finally(() => {
        })
  },
};
</script>