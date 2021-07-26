<template>
  <div>
    <filterable v-bind="filterable">
      <thead slot="thead">
      <tr>
        <!--        <th width="6%">ID</th>-->
        <th width="10%" title="Register No">Register No</th>
        <th width="10%">Name</th>
        <th width="7%">Age</th>
        <th width="5%" title="Gender">G</th>
        <th width="8%" title="Contact Number">Phone</th>
        <th width="15%" title="Municipality">Municipality</th>
        <th width="8%">Ward</th>
        <th width="10%" title="Post">Post</th>
        <th width="10%" title="ID Number">ID Number</th>
        <th width="10%" title="Actions"><i class="fa fa-cogs" aria-hidden="true"></i></th>
      </tr>
      </thead>
      <tr slot-scope="{item}">
        <!--        <td>{{ item }}</td>-->
        <td>{{ item.id }}</td>
        <td>{{ item.name }}</td>
        <td>{{ item.age }}</td>
        <td>{{ gender(item.gender) }}</td>
        <td>{{ item.phone }}</td>
        <td>{{ checkMunicipality(item.municipality_id) }}</td>
        <td>{{ item.ward }}</td>
        <td>{{ item.designation }}</td>
        <td>
          {{ item.citizenship_no }}<br>
          /{{ item.issue_district }}
        </td>
        <td>
          <button class="btn btn-primary" v-on:click="addVaccination(item)" title="Add Vaccination">
            <i class="fa fa-plus-square-o" aria-hidden="true"></i>
          </button>
        </td>
      </tr>
    </filterable>

    <div>
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">

      <fab
          :position="fabOptions.position"
          :bg-color="fabOptions.bgColor"
          :actions="fabActions"
          :start-opened=true
          @addVaccination="addVaccination"
      ></fab>
    </div>
  </div>
</template>

<script type="text/javascript">
import Filterable from './Filterable.vue'
import axios from 'axios'
import fab from 'vue-fab'
import AddVaccinationModal from "./AddVaccinationModal";

export default {
  components: {Filterable, fab},
  data() {
    return {
      role: this.$userRole,
      filterable: {
        url: '/api/v1/covid-vaccination-list',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Register No', name: 'id'},
        ],
        filterGroups: [
          {
            name: 'Client',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Phone Number', name: 'phone', type: 'numeric'}
            ]
          }
        ],
      },
      municipalities: [],
      exportHtml: '',
      fabOptions: {
        bgColor: '#778899',
        position: 'bottom-right',
      },
      fabActions: [
        {
          name: 'addVaccination',
          icon: 'group_add',
          tooltip: "Add Vaccination"
        }
      ]
    }
  },
  created() {
    this.fetch()
  },
  methods: {
    fetch() {
      let municipality_url = window.location.protocol + '/api/municipality';
      axios.get(municipality_url)
          .then((response) => {
            this.municipalities = response.data;
          })
          .catch((error) => {
            console.error(error)
          })
          .finally(() => {
          })
    },

    gender(type) {
      switch (type) {
        case '1':
          return 'M';
        case '2':
          return 'F';
        default:
          return 'O';
      }
    },

    checkMunicipality: function (value) {
      if (value === 0 || value == null || value === '') {
        return ''
      } else {
        return this.municipalities.find(x => x.id == value).municipality_name;
      }
    },
    addVaccination(item){
      this.$dlg.modal(AddVaccinationModal, {
        title: 'Add Vaccination',
        width : 700,
        params: {
          item : item,
        },
      })
    },
  }
}
</script>
