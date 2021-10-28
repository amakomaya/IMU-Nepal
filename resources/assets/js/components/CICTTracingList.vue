<template>
  <div>
    <filterable v-bind="filterable">
      <thead slot="thead">
      <tr>
        <th>Case ID</th>
        <th>Name</th>
        <th>Age</th>
        <th title="Gender">G</th>
        <th>District</th>
        <th>Municipality</th>
        <th title="Initiated Date">Initiated Date</th>
        <th><i class="fa fa-mobile" aria-hidden="true"></i> / <i class="fa fa-desktop fa-sm" aria-hidden="true"></i></th>
        <th></th>
      </tr>
      </thead>
      <tr slot-scope="{item, removeItemOnSuccess}">
        <td>{{ item.case_id }}</td>
        <td>{{ item.name }}</td>
        <td>{{ item.age }}</td>
        <td>{{ sex(item.sex )}}</td>
        <td>{{ item.district.district_name }}</td>
        <td>{{ item.municipality.municipality_name }}</td>
        <td>
          <span v-if="item.cict_initiated_date">{{ item.cict_initiated_date }}</span>
          <span v-else class="label label-danger">Not Initiated</span>
        </td>
        <td>
          <span v-if="item.regdev === 'web'" title="Web" class="label label-info">W</span>
          <span v-else title="Mobile" class="label label-success">M</span>
        
        </td>

        <td>
          <span v-show="checkEditButton()">
            <button v-if="item.cict_initiated_date" v-on:click="cictUpdateData(item.case_id)" class="btn btn-primary btn-sm" title="CICT Edit">
              <i class="fa fa-edit" aria-hidden="true"> Edit</i>
            </button>
            <button v-else v-on:click="cictUpdateData(item.case_id)" class="btn btn-warning btn-sm" title="CICT Create">
              <i class="fa fa-plus" aria-hidden="true"> Create</i>
            </button>
          </span>
          <button v-on:click="contactList(item.case_id)" class="btn btn-secondary btn-sm" title="Contact List">
            <i class="fa fa-list" aria-hidden="true"> Contact List</i>
          </button>
          <button v-on:click="cictReport(item.case_id)" class="btn btn-success btn-sm" title="CICT Report">
            <i class="fa fa-file-pdf-o" aria-hidden="true"> Report</i>
          </button>
          <button v-if="permission == 1" v-on:click="deletePatientData(item, removeItemOnSuccess)" class="btn btn-danger btn-sm" title="Delete Data">
            <i class="fa fa-trash" aria-hidden="true"> Delete</i>
          </button>
        </td>
      </tr>
    </filterable>
  </div>
</template>

<script type="text/javascript">
import Filterable from './CICTFilterable.vue'
import axios from "axios";

export default {
  components: {Filterable},
  data() {
    return {
      permission: this.$permissionId,
      filterable: {
        url: '/data/api/cict-tracing',
        orderables: [
          {title: 'Name', name: 'name'},
          {title: 'Age', name: 'age'},
          {title: 'Created At', name: 'created_at'}
        ],
        filterGroups: [
          {
            name: 'Case',
            filters: [
              {title: 'Name', name: 'name', type: 'string'},
              {title: 'Age', name: 'age', type: 'numeric'},
              {title: 'Created At', name: 'created_at', type: 'datetime'}
            ]
          }
        ]
      }
    }
  },
  methods: {
    cictUpdateData : function (id){
      window.open(
          '/admin/cict-tracing/section-one?case_id=' + id,
          '_self'
      );
    },
    contactList : function (id){
      window.open(
          '/admin/cict-tracing/contact-list/' + id,
          '_self'
      );
    },
    cictReport : function (id){
      window.open(
          '/admin/cict-tracing/report/' + id,
          '_self'
      );
    },

    deletePatientData: function (item, removeItemOnSuccess) {
      this.$swal({
        title: "Are you sure?",
        text: "Your data will be deleted.",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel please!",
        closeOnConfirm: false,
        closeOnCancel: false
      }).then((result) => {
        if (result.value) {
          axios.post('/admin/cict-tracing/' + item.token, {_method: 'DELETE'})
              .then((response) => {
                if (response.data.message === 'success') {
                  removeItemOnSuccess(item);
                  this.$swal({
                    title: 'Record Deleted',
                    type: 'success',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                  })
                  this.healthpostSelected = null;
                  
                } else {
                  this.$swal({
                    title: 'Oops. No record found.',
                    type: 'error',
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000
                  })
                }
              })
        } else {
          this.$swal("Cancelled", "Data not deleted :)", "error");
        }
      })
    },
    sex(type) {
      switch (type) {
        case '1':
          return 'M';
        case '2':
          return 'F';
        default:
          return 'O';
      }
    },
    checkEditButton(){
      var arr = this.$userPermissions.split(',');
      return this.$userRole === 'healthworker';
    },
  }
}
</script>