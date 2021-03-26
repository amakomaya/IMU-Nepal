<template>
  <div class="row">
    <div class="col-md-12 text-center">
      <h1>Type to search Organization informations</h1>
      <br>
      <div class="input-group" style="width: 50%;margin: 0 auto;">
        <v-select label="name"
                  v-model="healthpostSelected"
                  placeholder="Type to search organization informations .."
                  style="width:800px"
                  :options="options"
                  @search="onSearch"
        >
          <template vslot="no-options">
            type to search Healthpost informations ...
          </template>
          <template slot="option" slot-scope="option">
            {{ option.name }} <br>
            {{ option.province.province_name }}, {{ option.district.district_name }}, {{ option.municipality.municipality_name }}<br>
            {{ option.address }}
          </template>
          <template slot="selected-option" slot-scope="option">
            <div class="selected d-center">
              {{ option.name }}, {{ option.address }}
            </div>
          </template>
        </v-select>
      </div>
      <hr>
      <div v-if="healthpostSelected !== null" style="margin-left: 5px;">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>Organization Name</th>
            <th>Province</th>
            <th>District</th>
            <th>Municipality</th>
            <th>Options</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>{{ healthpostSelected.name }}</td>
            <td>{{ healthpostSelected.province.province_name }}</td>
            <td>{{ healthpostSelected.district.district_name }}</td>
            <td>{{ healthpostSelected.municipality.municipality_name }}</td>
            <td>
              <a v-on:click="organizationEdit">
                <i class="fa fa-edit fa-2x" style="color:green"></i>
              </a>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script type="text/javascript">
import axios from 'axios'

export default {
  data() {
    return {
      selected: [],
      allSelected: false,
      womanTokens: [],
      options: [],
      healthpostSelected : null,
    }
  },
  methods: {
    organizationEdit: function () {
      var url = "/admin/organization/" + this.healthpostSelected.id + "/edit-record";
      window.open(url, '_blank').focus();
    },
    onSearch(search, loading) {
      loading(true);
      this.search(loading, search, this);
    },
    search: _.debounce((loading, search, vm) => {
      let url = window.location.protocol + '/api/v1/healthposts';
      axios.get(url)
          .then(response => {
            vm.options = response.data;
            loading(false);
          })
          .catch((error) => {
            console.error(error)
          })
          .finally(() => {
          })
    }, 350),
  }
}
</script>
