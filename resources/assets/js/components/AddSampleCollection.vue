<template>
  <div class="row">
    <div class="col-md-12 text-center">
      <h1>Type to search Case information</h1>
      <p>( Search by Case Name, Phone number ,Previous Swab Id )</p>
      <input type="radio" v-model="search_by" value="name"> Case Name &nbsp; &nbsp;
      <input type="radio" v-model="search_by" value="phone"> Phone &nbsp; &nbsp;
      <input type="radio" v-model="search_by" value="sid"> Swab ID
      <br>
      <div class="input-group" style="width: 50%;margin: 0 auto;">
        <v-select label="name"
                  v-model="CaseSelected"
                  placeholder="Type to search informations .."
                  style="width:800px"
                  :options="options"
                  @search="onSearch"
        >
          <template vslot="no-options">
            type to search informations ...
          </template>
          <template slot="option" slot-scope="option">
            Name : {{ option.name }} <br>
            Age : {{ option.age }}, {{ option.formated_age_unit }} / {{ option.formated_gender }}<br>
            Phone : {{ option.emergency_contact_one }}, {{ option.emergency_contact_two }} <br>
            Address : {{ option.tole }} {{ option.ward }}, {{ option.district.district_name }}, {{ option.municipality.municipality_name }}
            <hr style="margin: 0;padding: 0">
          </template>
          <template slot="selected-option" slot-scope="option">
            <div class="selected d-center">
              {{ option.name }}, {{ option.age }}, {{ option.formated_age_unit }} / {{ option.formated_gender }}
            </div>
          </template>
        </v-select>
      </div>
      <hr>
      <div v-if="CaseSelected !== null" style="margin-left: 5px;">
        <table class="table table-bordered">
          <thead>
          <tr>
            <th>Name</th>
            <th>Age / Gender</th>
            <th>Phone</th>
            <th>District</th>
            <th>Municipality</th>
            <th>Previous Result</th>
            <th>Options</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>{{ CaseSelected.name }}</td>
            <td>{{ CaseSelected.age }}, {{ CaseSelected.formated_age_unit }} / {{ CaseSelected.formated_gender }}</td>
            <td>{{ CaseSelected.emergency_contact_one }} <br>
                {{ CaseSelected.emergency_contact_two }}
            </td>
            <td>{{ CaseSelected.district.district_name }}</td>
            <td>{{ CaseSelected.municipality.municipality_name }}</td>
            <td>
                <li v-for="item in CaseSelected.sample_collection">{{ item.token }} : {{ item.formatted_result }}</li>
            <td>
            <button v-on:click="addSampleCollection(CaseSelected.token)" title="Add Sample Collection / Swab Collection Report">
              <i class="fa fa-medkit" aria-hidden="true"></i>
            </button>
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
      CaseSelected : null,
      search_by : 'name'
    }
  },
  methods: {
    addSampleCollection(token) {
      window.open(
          '/admin/sample-collection/create/' + token,
          '_blank'
      );
    },
    onSearch(search, loading) {
      if(search.length >= 3){
        loading(true);
        this.search(loading, this.search_by , search, this);
      }
    },
    search: _.debounce((loading, search_by, search, vm) => {
      let url = window.location.origin + `/api/v1/suspected-cases?` + search_by + `=${escape(search)}`;
       vm.options = [];
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
